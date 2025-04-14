<?php

namespace App\Services\Queues\RabbitMQ;

use PhpAmqpLib\Message\AMQPMessage;

class Publisher
{
    private $connection;
    private $channel;
    private $config;

    public function __construct(ConnectRabbitMQ $connection, QueueConfig $config)
    {
        $this->connection = $connection;
        $this->channel = $connection->getChannel();
        $this->config = $config;

        $this->config->setupChannel($this->channel); // íntance
    }


    public function publish($message)
    {
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish(
            $msg,
            $this->config->getExchangeName(), // getter - setter
            $this->config->getRoutingKey()
        );

        echo "Send message to ".$this->config->getExchangeName()." / ".$this->config->getQueueName()."\n";
    }
}
