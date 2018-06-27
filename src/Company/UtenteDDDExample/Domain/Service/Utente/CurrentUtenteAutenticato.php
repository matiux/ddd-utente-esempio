<?php

namespace UtenteDDDExample\Domain\Service\Utente;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenStorage;
use UtenteDDDExample\Domain\Model\Utente\UtenteAutenticato;

class CurrentUtenteAutenticato
{
    private $utenteFromAuthToken;
    private $authTokenStorage;

    public function __construct(UtenteFromAuthToken $utenteFromAuthToken, AuthTokenStorage $authTokenStorage)
    {
        $this->utenteFromAuthToken = $utenteFromAuthToken;
        $this->authTokenStorage = $authTokenStorage;
    }

    public function get(): ?UtenteAutenticato
    {
        $token = $this->authTokenStorage->getToken();

        $utente = $this->utenteFromAuthToken->find($token);

        if (!$utente) {
            return null;
        }

        $utenteAutenticato = new UtenteAutenticato($utente, $token);

        return $utenteAutenticato;
    }
}
