<?php

namespace Tests\Feature\Queues\RabbitMQ;

use App\Services\Queues\RabbitMQ\QueueConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Mockery;
use PhpAmqpLib\Channel\AMQPChannel;
use Tests\TestCase;

class ConfigRabbitMQTest extends TestCase
{
    public function testSetupRabbitMQConfig()
    {
        $exchange = 'test_exchange';
        $queue = 'test_queue';
        $routingKey = 'test_key';

        Config::set('rabbitmq.exchange_name', $exchange);
        Config::set('rabbitmq.queue_name', $queue);
        Config::set('rabbitmq.routing_key', $routingKey);

        $channel = Mockery::mock(AMQPChannel::class);

        $channel->shouldReceive('exchange_declare')
            ->with($exchange, 'direct', false, false, false)
            ->once();

        $channel->shouldReceive('queue_declare')
            ->with($queue, false, false, false, false)
            ->once();

        $channel->shouldReceive('queue_bind')
            ->with($queue, $exchange, $routingKey)
            ->once();

        $queueConfig = new QueueConfig();
        $queueConfig->setupChannel($channel);

        $this->assertTrue(true);
    }
}
