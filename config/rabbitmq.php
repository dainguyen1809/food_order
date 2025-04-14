<?php

return [
    'exchange_name' => env('RABBITMQ_EXCHANGE_NAME', 'default_exchange'),
    'queue_name' => env('RABBITMQ_QUEUE', 'default_queue'),
    'routing_key' => env('RABBITMQ_ROUTING_KEY', 'default_key'),
];
