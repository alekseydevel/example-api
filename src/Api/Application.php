<?php
namespace Api;

use Api\Infrastructure\Serializer\Serializable;
use Api\Middleware\MiddlewareCollection;
use Symfony\Component\HttpFoundation\Request;

class Application
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router, Serializable $serializer)
    {
        $this->serializer = $serializer;
        $this->router = $router;
        $this->middlewares = new MiddlewareCollection();
    }

    public function run(Request $request)
    {
        header('Content-Type: application/json;charset=UTF-8');

        try {
            // just calling of middleware instances without returning Response
            // if no exceptions (in this case - only Security check) - call controller.
            $this->middlewares->handle($request);
            $data = $this->router->matchRoute($request);
        }
        catch (\Throwable $e) {
            http_response_code($e->getCode());
            $data = ['error' => $e->getMessage(), 'code' => $e->getCode()];
        }

        echo $this->serializer->serialize($data);
    }
}
