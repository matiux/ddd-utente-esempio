<?php

namespace UtenteDDDExample\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt;

use Symfony\Component\HttpFoundation\Request;
use UtenteDDDExample\Domain\Service\Utente\AuthToken\AuthTokenFinder\SpecificAuthTokenFinder;

class QueryStringAuthTokenFinder implements SpecificAuthTokenFinder
{
    private const AUTH_QUERY_STRING_KEY = 't';

    public function find($target): ?string
    {
        return $this->doFind($target);
    }

    private function doFind(Request $httpRequest): ?string
    {
        $token = trim($httpRequest->query->get(self::AUTH_QUERY_STRING_KEY));

        if (empty($token)) {
            return null;
        }

        return $token;
    }
}
