<?php
require __DIR__.'/vendor/autoload.php';

use Api\Application;
use Api\ExampleRouterFactory;
use Api\Infrastructure\Serializer\JsonSerializer;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$serializer = new JsonSerializer();

$app = new Application(ExampleRouterFactory::create(), $serializer);
$app->run($request);
