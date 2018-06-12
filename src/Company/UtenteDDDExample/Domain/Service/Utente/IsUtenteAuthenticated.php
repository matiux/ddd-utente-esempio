<?php

namespace UtenteDDDExample\Domain\Service\Utente;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenSigner;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenStorage;
use UtenteDDDExample\Domain\Service\Utente\AuthToken\AuthTokenFinder\AuthTokenFinder;

class IsUtenteAuthenticated
{
    private $authTokenFinder;
    private $authTokenSigner;
    private $utenteFromAuthToken;
    private $authTokenStorage;

    public function __construct
    (
        AuthTokenFinder $authTokenFinder,
        AuthTokenSigner $authTokenSigner,
        UtenteFromAuthToken $utenteFromAuthToken,
        AuthTokenStorage $authTokenStorage
    )
    {
        $this->authTokenFinder = $authTokenFinder;
        $this->authTokenSigner = $authTokenSigner;
        $this->utenteFromAuthToken = $utenteFromAuthToken;
        $this->authTokenStorage = $authTokenStorage;
    }

    public function verifyAuthentication($target): bool
    {
        if (!$authToken = $this->authTokenFinder->find($target)) {
            return false;
        }

        $utenteIsValid = $this->isUtenteValid($authToken);
        $authTokenValid = $this->isAuthTokenValid($authToken);
        $authTokenVerified = $this->isAuthTokenVerified($authToken);

        if (!$utenteIsValid || !$authTokenValid || !$authTokenVerified) {
            return false;
        }

        $this->authTokenStorage->setToken($authToken);

        return true;
    }

    protected function isAuthTokenValid(AuthToken $authToken): bool
    {
        return $authToken->validate();
    }

    protected function isAuthTokenVerified(AuthToken $authToken): bool
    {
        return $authToken->verify($this->authTokenSigner);
    }

    private function isUtenteValid($authToken): bool
    {
        $utente = $this->utenteFromAuthToken->find($authToken);

        return $utente ? true : false;
    }
}
