<?php
 
namespace App\Exceptions;
 
use Exception;
 
class InvalidBodyResponseException extends Exception
{
    public function __construct($message = "")
    {
        $this->message = $message;
    }
}