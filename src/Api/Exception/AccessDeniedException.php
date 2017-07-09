<?php
namespace Api\Exception;

use Symfony\Component\HttpFoundation\Response;

class AccessDeniedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Access denied", Response::HTTP_FORBIDDEN);
    }
}