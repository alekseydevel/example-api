<?php
namespace Api\Middleware;

use Api\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class SecurityTokenMiddleware implements Middleware
{
    const EXAMPLE_HARD_CODE_TOKEN = 'Bearer: 12345';

    public function handle(Request $request)
    {
        if (!$this->validateUserToken((string) $request->headers->get('Authorization'))) {
            throw new AccessDeniedException();
        }

        return true;
    }

    private function validateUserToken(string $token): bool
    {
        return self::EXAMPLE_HARD_CODE_TOKEN === $token;
    }
}
