<?php

namespace Tests\Feature\Queues\RabbitMQ;

use App\Services\Queues\RabbitMQ\ConnectRabbitMQ;
use App\Services\Queues\RabbitMQ\Consumer;
use App\Services\Queues\RabbitMQ\QueueConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PhpAmqpLib\Channel\AMQPChannel;
use Tests\TestCase;

class ConsumerTest extends TestCase
{
    public function testConsumerMessage()
    {
        $channel = Mockery::mock(AMQPChannel::class);
        $connection = Mockery::mock(ConnectRabbitMQ::class);
        $config = Mockery::mock(QueueConfig::class);

        $connection->shouldReceive('getChannel')->andReturn($channel);

        $config->shouldReceive('setupChannel')->once()->with($channel);
        $config->shouldReceive('getQueueName')->andReturn('test_queue');

        $channel->shouldReceive('basic_consume')
            ->once()
            ->with(
                'test_queue',
                '',
                false,
                true,
                false,
                false,
                Mockery::type('callable')
            );

        $channel->shouldReceive('is_consuming')
            ->andReturn(true, false); // simulate 1 loop only

        $channel->shouldReceive('wait')->once();

        $consumer = new Consumer($connection, $config);

        ob_start(); // capture echo
        $consumer->consume();
        $output = ob_get_clean();

        $this->assertStringContainsString('Waiting for new message on test_queue', $output);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
