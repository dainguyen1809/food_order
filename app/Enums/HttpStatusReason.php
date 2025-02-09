<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HttpStatusReason extends Enum
{
    const OK = 'OK';
    const CREATED = 'Created';
    const ACCEPTED = 'Accepted';
    const NO_CONTENT = 'No Content';
    const BAD_REQUEST = 'Bad Request';
    const UNAUTHORIZED = 'Unauthorized';
    const FORBIDDEN = 'Forbidden';
    const NOT_FOUND = 'Not Found';
    const METHOD_NOT_ALLOWED = 'Method Not Allowed';
    const CONFLICT = 'Conflict';
    const INTERNAL_SERVER_ERROR = 'Internal Server Error';
    const SERVICE_UNAVAILABLE = 'Service Unavailable';
}
