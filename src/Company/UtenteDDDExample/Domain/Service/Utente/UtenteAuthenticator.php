<?php

namespace UtenteDDDExample\Domain\Service\Utente;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenSigner;
use UtenteDDDExample\Domain\Model\Utente\Utente;

abstract class UtenteAuthenticator
{
    protected $defaultExpTime;
    protected $authTokenSigner;

    public function __construct(int $defaultExpTime, AuthTokenSigner $authTokenSigner)
    {
        $this->defaultExpTime = $defaultExpTime;

        $this->authTokenSigner = $authTokenSigner;
    }

    protected function calculateAuthenticationExpireTime(): int
    {
        return time() + $this->defaultExpTime;
    }

    abstract public function generateAuthToken(Utente $utente): AuthToken;

    abstract public function authTokenFromString(string $autjToken): AuthToken;
}
