<?php
namespace Api\Exception;

class NoHandlerForUriException extends \Exception
{
    public function __construct($uri)
    {
        parent::__construct("No handler for uri {$uri}", 404);
    }
}