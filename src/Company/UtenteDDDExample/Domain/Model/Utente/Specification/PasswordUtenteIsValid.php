<?php

namespace UtenteDDDExample\Domain\Model\Utente\Specification;

use UtenteDDDExample\Domain\Model\Utente\Password\NotHashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Utente;

class PasswordUtenteIsValid extends PasswordUtenteSpecification
{
    public function isSatisfiedBy(PasswordUtente $passwordUtente, Utente $utente): bool
    {
        if (!$passwordUtente instanceof NotHashedPasswordUtente) {
            return false;
        }

        return $this->passwordHashing->verify($passwordUtente, $utente->password());
    }
}
