<?php
 
namespace App\Exceptions;
 
use Exception;
 
class ApiCantFetchDataException extends Exception
{
    public function __construct($message)
    {
        $this->message = $message;
    }
}