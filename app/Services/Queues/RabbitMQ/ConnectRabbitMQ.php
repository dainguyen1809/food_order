<?php

namespace App\Services\Queues\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConnectRabbitMQ
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        try {
            $this->connection = new AMQPStreamConnection(
                env('RABBITMQ_HOST'),
                env('RABBITMQ_PORT'),
                env('RABBITMQ_USER'),
                env('RABBITMQ_PASSWORD'),
            );

            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            \Log::channel('rabbitmq_logs')->error($e->getMessage());
        }
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function __destruct()
    {
        $this->close();
    }
}
