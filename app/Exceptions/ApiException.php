<?php

namespace App\Exceptions;

use Exception;

abstract class ApiException extends Exception
{
    protected $statusCode = 400;

    public function render($request)
    {
        $message = $this->getMessage();

        if ($this->statusCode == 400) {
            $message = 'Los datos proporcionados son inválidos o incompletos: '.$message;
        }

        return response()->json([
            'error' => $message,
        ], $this->statusCode);
    }
}
