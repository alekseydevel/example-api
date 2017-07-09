<?php
namespace Api\Exception;

use Symfony\Component\HttpFoundation\Response;

class MethodNotAllowedException extends \Exception
{
    public function __construct($methodName)
    {
        parent::__construct("Method {$methodName} is not allowed", Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
