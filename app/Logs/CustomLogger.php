<?php

namespace App\Logs;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

class CustomLogger
{
    public function __invoke($logger)
    {
        $timezone = date_default_timezone_get();
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message%\n", // Custom format
                'Y-m-d H:i:s', // Format datetime
                false, // Allow inline line breaks
                true // Ignore empty context and extra
            ));
        }
    }
}
