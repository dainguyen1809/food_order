<?php

namespace Tests\Feature\Queues\RabbitMQ;

use App\Services\Queues\RabbitMQ\ProducerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class sendMessageTest extends TestCase
{
    public function testSendRabbitMQMessage()
    {
        $producer = new ProducerService();
        $producer->send('== Test Sending Messages ==');
        $producer->close();

        $this->assertTrue(true);
    }

}
