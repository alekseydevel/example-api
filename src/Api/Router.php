<?php
namespace Api;

use Api\Exception\MethodNotAllowedException;
use Api\Exception\NoHandlerForUriException;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    private $routes = [];

    public function add(string $endpointUrlPattern, string $method, \Closure $callable)
    {
        $this->routes['/^'.str_replace('/', '\/', $endpointUrlPattern).'$/'] = [
            'method' => $method,
            'callable' => $callable
        ];
    }

    public function matchRoute(Request $request)
    {
        // This method makes too much.
        // validation needs to be moved out

        foreach ($this->routes as $pattern => $callback) {
            if (preg_match($pattern, $request->getRequestUri(), $matches)) {
                if ($request->getMethod() != $callback['method']) {
                    throw new MethodNotAllowedException($request->getMethod());
                }

                $params = array_merge(
                    $matches,
                    $this->queryParamsArray($request->getRequestUri())
                );

                return $callback['callable']($params);
            }
        }
        throw new NoHandlerForUriException($request->getRequestUri());
    }

    private function queryParamsArray(string $uri): array
    {
        $params = [];
        $parsedUrl = parse_url($uri);

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $params);
        }

        return $params;
    }
}
