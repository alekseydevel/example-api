<?php
namespace Api\Middleware;

use Symfony\Component\HttpFoundation\Request;

interface Middleware
{
    public function handle(Request $request);
}