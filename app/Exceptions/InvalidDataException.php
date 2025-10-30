<?php

namespace App\Exceptions;

class InvalidDataException extends ApiException
{
    protected $statusCode = 400;
}
