<?php

namespace Tests\Feature\Queues\RabbitMQ;

use App\Services\Queues\RabbitMQ\ConnectRabbitMQ;
use App\Services\Queues\RabbitMQ\Publisher;
use App\Services\Queues\RabbitMQ\QueueConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Tests\TestCase;

class PublisherTest extends TestCase
{
    public function testPublishMessage()
    {
        $channel = Mockery::mock(AMQPChannel::class);
        $connection = Mockery::mock(ConnectRabbitMQ::class);
        $config = Mockery::mock(QueueConfig::class);

        $connection->shouldReceive('getChannel')->andReturn($channel);

        $config->shouldReceive('setupChannel')
            ->once()
            ->with($channel);

        $config->shouldReceive('getExchangeName')->andReturn('test_exchange');
        $config->shouldReceive('getQueueName')->andReturn('test_rabbitmq');
        $config->shouldReceive('getRoutingKey')->andReturn('test_key');

        $publisher = new Publisher($connection, $config);

        $channel->shouldReceive('basic_publish')->once();

        $publisher->publish('Publisher send message');

        $this->assertTrue(true);
    }
}
