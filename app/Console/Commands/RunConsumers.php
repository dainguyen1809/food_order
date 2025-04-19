<?php

namespace App\Console\Commands;

use App\Services\Queues\RabbitMQ\ConsumerService;
use App\Services\Queues\RabbitMQ\ConsumerToQueueService;
use Illuminate\Console\Command;

class RunConsumers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:mq-consumer {type=normal}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run RabbitMQ consumers (normal or failed)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');

        if ($type === 'normal') {
            echo "Running normal consumer...\n";
            ConsumerToQueueService::consumerToQueue();
        } else if ($type === 'failed') {
            echo "Running failed (DLX) consumer...\n";
            ConsumerToQueueService::consumerToQueueFailed();
        } else {
            echo "❌ Unknown type. Use 'normal' or 'failed'.\n";
        }

    }
}
