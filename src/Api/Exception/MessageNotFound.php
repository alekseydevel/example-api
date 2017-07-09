<?php
namespace Api\Exception;

class MessageNotFound extends \Exception
{
    public function __construct()
    {
        parent::__construct("Message not found", 404);
    }
}