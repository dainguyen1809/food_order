<?php

namespace Tests\Feature\Queues\RabbitMQ;

use App\Services\Queues\RabbitMQ\ConnectRabbitMQ;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConnectToRabbitMQTest extends TestCase
{
    public function testConnectionToRabbitMQ()
    {
        $connection = new ConnectRabbitMQ();
        $channel = $connection->getChannel();

        $this->assertNotNull($channel);
        $this->assertTrue($channel->is_open());
    }
}
