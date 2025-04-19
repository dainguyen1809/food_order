<?php

namespace Tests\Feature\Queues\RabbitMQ;

use App\Services\Queues\RabbitMQ\ConnectRabbitMQ;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Tests\TestCase;

class ProducerDLXTest extends TestCase
{

    public function testProducerDLX()
    {
        $connection = new ConnectRabbitMQ();
        $channel = $connection->getChannel();

        $notificationExchange = 'notificationExchange';
        $notificationQueue = 'notificationQueueProcess';
        $dlxExchange = 'dlxExchange';
        $dlxRoutingKey = 'dlxRoutingKey';

        $channel->exchange_declare(
            $notificationExchange,
            'direct',
            false,
            true,
            false
        );

        // set DLX options
        $args = new AMQPTable([
            'x-dead-letter-exchange' => $dlxExchange,
            'x-dead-letter-routing-key' => $dlxRoutingKey,
        ]);

        $queueInfo = $channel->queue_declare($notificationQueue, false, true, false, false, false, $args);

        $channel->queue_bind($queueInfo[0], $notificationExchange);

        $now = now()->format('H:i:s d/m/Y');
        $message = '== [ New Product: Razer abczzz ] ==';

        echo "[$now]\tMessage::: $message".PHP_EOL;

        $msg = new AMQPMessage($message, [
            'expiration' => '10000'
        ]);


        $channel->basic_publish($msg, '', $notificationQueue);

        $channel->close();
        $connection->close();

        $this->assertTrue(true);

    }
}
