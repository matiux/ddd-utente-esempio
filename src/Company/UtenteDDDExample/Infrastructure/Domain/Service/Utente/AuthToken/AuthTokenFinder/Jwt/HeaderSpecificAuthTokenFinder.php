<?php

namespace UtenteDDDExample\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt;

use Symfony\Component\HttpFoundation\Request;
use UtenteDDDExample\Domain\Service\Utente\AuthToken\AuthTokenFinder\SpecificAuthTokenFinder;

class HeaderSpecificAuthTokenFinder implements SpecificAuthTokenFinder
{
    protected const AUTH_HEADER = 'authorization';
    protected const AUTH_HEADER_PREFIX = 'bearer';

    public function find($subject): ?string
    {
        if (!$token = $this->doFind($subject)) {
            return null;
        }

        return $token;
    }

    private function doFind(Request $httpRequest): ?string
    {
        $header = $httpRequest->headers->get(self::AUTH_HEADER) ?: $this->fromAlternativeHeaders($httpRequest);


        if ($header && stripos($header, self::AUTH_HEADER_PREFIX) === 0) {
            return trim(str_ireplace(self::AUTH_HEADER_PREFIX, '', $header));
        }

        return null;
    }

    private function fromAlternativeHeaders(Request $httpRequest): ?string
    {
        return $httpRequest->server->get('HTTP_AUTHORIZATION') ?: $httpRequest->server->get('REDIRECT_HTTP_AUTHORIZATION');
    }
}
