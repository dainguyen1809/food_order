<?php

namespace App\Services\Queues\RabbitMQ;

class RabbitMQService
{
    private $publisher;
    private $consumer;

    public function __construct()
    {
        $connection = new ConnectRabbitMQ();
        $this->publisher = new Publisher($connection);
        $this->consumer = new Consumer($connection);
    }

    public function publish($message)
    {
        $this->publisher->publish($message);
    }

    public function consume()
    {
        $this->consumer->consume();
    }
}
