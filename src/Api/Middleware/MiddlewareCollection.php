<?php
namespace Api\Middleware;

use Symfony\Component\HttpFoundation\Request;

class MiddlewareCollection
{
    /** @var Middleware[] */
    private $closures = [];

    public function __construct()
    {
        $this->closures[] = SecurityTokenMiddleware::class;
    }

    public function handle(Request $request)
    {
        // For simplicity it`s just an iterator through middleware instances
        // In general the response can be catch or following middleware executed
        foreach ($this->closures as $closure) {
            (new $closure)->handle($request);
        }
    }
}