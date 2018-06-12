<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\AuthTokenStorage;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenStorage;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\JwtAuthToken;

class JwtInMemoryAuthTokenStorage implements AuthTokenStorage
{
    /**
     * @var JwtAuthToken
     */
    private $authToken;

    public function getToken(): AuthToken
    {
        return $this->authToken;
    }

    public function setToken(AuthToken $authToken): void
    {
        $this->authToken = $authToken;
    }
}
