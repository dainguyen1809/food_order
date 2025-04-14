<?php

namespace App\Services\Queues\RabbitMQ;

class QueueConfig
{
    protected $exchange;
    protected $queue;
    protected $routingKey;

    public function __construct()
    {
        $this->exchange = config('rabbitmq.exchange_name');
        $this->queue = config('rabbitmq.queue_name');
        $this->routingKey = config('rabbitmq.routing_key');
    }

    public function getExchangeName()
    {
        return $this->exchange;
    }

    public function getQueueName()
    {
        return $this->queue;
    }

    public function getRoutingKey()
    {
        return $this->routingKey;
    }


    public function setupChannel($channel)
    {
        $channel->exchange_declare($this->exchange, 'direct', false, false, false);
        $channel->queue_declare($this->queue, false, false, false, false);
        $channel->queue_bind($this->queue, $this->exchange, $this->routingKey);
    }
}
