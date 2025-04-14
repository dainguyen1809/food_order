<?php

namespace App\Services\Queues\RabbitMQ;

class Consumer
{

    private $connection;
    private $channel;
    private $config;


    public function __construct(ConnectRabbitMQ $connection, QueueConfig $config)
    {
        $this->connection = $connection;
        $this->channel = $connection->getChannel();
        $this->config = $config;

        $this->config->setupChannel($this->channel);
    }

    public function consume()
    {
        $callback = function ($msg) {
            echo "[X] Received $msg->body \n";
        };

        $this->channel->basic_consume(
            $this->config->getQueueName(),
            '',
            false,
            true,
            false,
            false,
            $callback
        );

        echo 'Waiting for new message on '.$this->config->getQueueName()." \n";

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }
}
