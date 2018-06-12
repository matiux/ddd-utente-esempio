<?php

namespace UtenteDDDExample\Domain\Service\Utente;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

abstract class UtenteFromAuthToken
{
    private $utenteRepository;

    public function __construct(UtenteRepository $utenteRepository)
    {
        $this->utenteRepository = $utenteRepository;
    }

    public function find(AuthToken $authToken): ?Utente
    {
        $utenteId = $this->getUtenteIdFromAuthToken($authToken);

        if (!$utenteId) {
            return null;
        }

        $utente = $this->utenteRepository->ofId($utenteId);

        return $utente;
    }

    abstract protected function getUtenteIdFromAuthToken(AuthToken $authToken): ?UtenteId;
}
