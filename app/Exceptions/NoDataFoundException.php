<?php

namespace App\Exceptions;

class NoDataFoundException extends ApiException
{
    protected $statusCode = 404;
}
