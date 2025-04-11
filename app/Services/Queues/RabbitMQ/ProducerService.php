<?php

namespace App\Services\Queues\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ProducerService
{
    protected $connection;
    protected $channel;
    protected $queue;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD')
        );

        $this->channel = $this->connection->channel();
        $this->queue = env('RABBITMQ_QUEUE');
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    public function send(string $message): void
    {
        $msg = new AMQPMessage($message, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        $this->channel->basic_publish($msg, '', $this->queue);
    }

    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}
