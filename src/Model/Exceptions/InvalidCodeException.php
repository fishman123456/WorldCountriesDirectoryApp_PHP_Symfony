<?php

namespace App\Model\Exceptions;

use Throwable;
use Exception;

// InvalidCodeException - исключение невалидного кода аэропорта
class InvalidCodeException extends Exception
{

    // переопределение конструктора исключения
    public function __construct($invalidCode, $message, Throwable $previous = null)
    {
        $exceptionMessage = "коды страны '" . $invalidCode . "' не коректный: " . $message;
        // вызов конструктора базового класса исключения
        parent::__construct(
            message: $exceptionMessage,
            code: ErrorCodes::INVALID_CODE_ERROR,
            previous: $previous,
        );
    }
}
