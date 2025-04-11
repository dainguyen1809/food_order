<?php

namespace Tests\Feature\Queues\RabbitMQ;

use App\Services\Queues\RabbitMQ\ConsumerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class receiveMessageTest extends TestCase
{
    public function testReceiveRabbitMQMessage()
    {
        $received = false;

        $consumer = new ConsumerService();
        $consumer->consume(function ($msg) use (&$received) {
            echo "Received: ".$msg->body."\n";
            $received = true;
        }, 1); // only receiving one message and stop

        $this->assertTrue($received);
    }

}
