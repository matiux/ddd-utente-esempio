<?php

namespace UtenteDDDExample\Domain\Service\Utente;

use UtenteDDDExample\Domain\Model\Utente\Exception\UtenteNotAuthorizedException;
use UtenteDDDExample\Domain\Model\Utente\Exception\UtenteNotFoundException;
use UtenteDDDExample\Domain\Model\Utente\Specification\OnlyAdminCanPerfomSomeActions;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

class EnableUtente
{
    private $utenteRepository;
    private $currentUtenteAutenticato;

    public function __construct(UtenteRepository $utenteRepository, CurrentUtenteAutenticato $currentUtenteAutenticato)
    {
        $this->utenteRepository = $utenteRepository;
        $this->currentUtenteAutenticato = $currentUtenteAutenticato;
    }

    public function enable(UtenteId $utenteId): Utente
    {
        $this->onlyAdminCanEnableUser($utenteId);

        $utente = $this->utenteById($utenteId);

        if (!$utente->isEnabled()) {
            $utente->enable();
        }

        return $utente;
    }

    private function utenteById(UtenteId $utenteId): Utente
    {
        $utente = $this->utenteRepository->ofId($utenteId);

        if (!$utente) {

            throw new UtenteNotFoundException(sprintf('Utente non trovato [%s]', $utenteId));
        }

        return $utente;
    }

    private function onlyAdminCanEnableUser(UtenteId $utenteId)
    {
        if (!(new OnlyAdminCanPerfomSomeActions($this->currentUtenteAutenticato))->isSatisfiedBy($utenteId)) {
            throw new UtenteNotAuthorizedException('Non puoi compiere questa azione');
        }
    }
}
