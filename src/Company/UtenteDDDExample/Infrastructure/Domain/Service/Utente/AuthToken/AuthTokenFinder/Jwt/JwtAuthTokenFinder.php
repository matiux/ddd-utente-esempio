<?php

namespace UtenteDDDExample\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt;

use Symfony\Component\HttpFoundation\Request;
use UtenteDDDExample\Domain\Service\Utente\AuthToken\AuthTokenFinder\AuthTokenFinder;

class JwtAuthTokenFinder extends AuthTokenFinder
{
    protected function isValidTarget($target): void
    {
        if (!$target instanceof Request) {
            throw new \InvalidArgumentException('Target deve essere un oggetto `Symfony\Component\HttpFoundation\Request`');
        }
    }
}
