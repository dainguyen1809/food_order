<?php

namespace App\Services\Queues\RabbitMQ;

use ErrorException;
use PhpAmqpLib\Wire\AMQPTable;

class ConsumerToQueueService
{
    public static function consumerToQueue()
    {
        try {
            $connection = new ConnectRabbitMQ();
            $channel = $connection->getChannel();

            $notificationQueue = 'notificationQueueProcess';

            /**
             * The case simulates TTL of the message
             */

            // $delay = 15; // seconds
            // echo "⏳ Waiting $delay seconds before consuming...\n";
            // sleep($delay);

            // $channel->basic_consume($notificationQueue, '', false, false, false, false, function ($msg) use ($channel) {
            //     $now = now()->format('H:i:s d/m/Y');
            //     echo "[$now] Processed: ".$msg->body."\n";
            //     $channel->basic_ack($msg->delivery_info['delivery_tag']);
            // });

            /**
             * The cas simulates the process where sending the messages fails
             */

            $channel->basic_consume($notificationQueue, '', false, false, false, false, function ($msg) use ($channel) {
                try {
                    $random = rand(0, 10);
                    if ($random < 7) {
                        echo "number: $random";
                        echo "\nThis message: $msg->body fails in the process of sending \n";
                        return false;
                    }

                    echo "\nSend notification to the queue successfully: $msg->body\n";
                    $channel->basic_ack($msg->delivery_info['delivery_tag']);

                } catch (\Exception $e) {
                    echo "\nSend message to the queue fails: $msg->body. Please hot fix !!!\n";
                    $channel->basic_nack($msg->delivery_info['delivery_tag'], false, false);
                    \Log::channel('rabbitmq_logs')->error($e->getMessage());
                }
            });


            while ($channel->is_consuming()) {
                $channel->wait();
            }

        } catch (\Exception $e) {
            echo "Send notification error ==> ".$e->getMessage();
            \Log::channel('rabbitmq_logs')->error($e->getMessage());
        }
    }

    public static function consumerToQueueFailed()
    {
        try {
            $connection = new ConnectRabbitMQ();
            $channel = $connection->getChannel();

            $dlxExchange = 'dlxExchange';
            $dlxRoutingKey = 'dlxRoutingKey';
            $queueHotFix = 'notiQueueHotFix';

            $channel->exchange_declare(
                $dlxExchange,
                'direct',
                false,
                true,
                false
            );

            $queueInfo = $channel->queue_declare($queueHotFix, false, true, false, false);

            $channel->queue_bind($queueInfo[0], $dlxExchange, $dlxRoutingKey);

            $channel->basic_consume($queueHotFix, '', false, true, false, false, function ($msg) use ($channel) {
                $now = now()->format('H:i:s d/m/Y');
                echo "[$now] Processed: ".$msg->body."\n";
                // $channel->basic_ack($msg->delivery_info['delivery_tag']);
            });

            while ($channel->is_consuming()) {
                $channel->wait();
            }

        } catch (\Exception $e) {
            \Log::channel('rabbitmq_logs')->error("This message failed:: ".$e->getMessage().":: Please hot fix");
            throw new \Exception($e->getMessage());
        }
    }
}
