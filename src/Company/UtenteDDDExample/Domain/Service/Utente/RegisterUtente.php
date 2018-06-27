<?php

namespace UtenteDDDExample\Domain\Service\Utente;

use UtenteDDDExample\Domain\Model\Utente\Utente;

class RegisterUtente extends CreateUtente
{
    protected function createUtente(): Utente
    {
        $utente = Utente::create(
            $this->utenteRepository->nextIdentity(),
            $this->email,
            $this->hashedPassword,
            $this->ruolo,
            $this->competenze
        );

        return $utente;
    }
}
