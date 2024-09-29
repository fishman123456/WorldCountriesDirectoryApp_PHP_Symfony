<?php

namespace App\Model\Exceptions;

use Throwable;
use Exception;

// AirportNotFoundException - исключение не найденного аэропорта
class CountryNotFoundException extends Exception
{

    // переопределение конструктора исключения
    public function __construct($notFoundCode, Throwable $previous = null)
    {
        $exceptionMessage = "страна с кодом '" . $notFoundCode . "' не найдена";
        // вызов конструктора базового класса исключения
        parent::__construct(
            message: $exceptionMessage,
            code: ErrorCodes::NOT_FOUND_ERROR,
            previous: $previous,
        );
    }
}
