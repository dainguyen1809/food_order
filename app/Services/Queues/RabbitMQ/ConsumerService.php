<?php

namespace App\Services\Queues\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumerService
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

    public function consume(callable $callback, int $messageLimit = 1)
    {
        $channel = $this->connection->channel();

        $channel->queue_declare('test-rabbitmq', false, true, false, false);

        $receivedCount = 0;

        $channel->basic_consume(
            'test-rabbitmq',
            '',
            false,
            true,
            false,
            false,

            function ($msg) use ($callback, &$receivedCount, $channel, $messageLimit) {
                $callback($msg);
                $receivedCount++;

                if ($receivedCount >= $messageLimit) {
                    $channel->close(); // stop after receiving enough messages
                }
            });

        while ($channel->is_consuming()) {
            $channel->wait(); // will be pending until have a message
        }

        $this->connection->close();
    }

    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}
