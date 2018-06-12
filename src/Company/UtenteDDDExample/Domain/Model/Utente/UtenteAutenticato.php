<?php

namespace UtenteDDDExample\Domain\Model\Utente;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;

class UtenteAutenticato
{
    private $utente;
    private $authToken;

    public function __construct(Utente $utente, AuthToken $authToken)
    {
        $this->utente = $utente;
        $this->authToken = $authToken;
    }

    public function utente(): Utente
    {
        return $this->utente;
    }

    public function token(): AuthToken
    {
        return $this->authToken;
    }
}
