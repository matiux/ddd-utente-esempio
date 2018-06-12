<?php

namespace UtenteDDDExample\Domain\Model\Utente\Specification;

use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Utente;

abstract class PasswordUtenteSpecification
{
    protected $passwordHashing;

    public function __construct(PasswordHashing $passwordHashing)
    {
        $this->passwordHashing = $passwordHashing;
    }

    abstract public function isSatisfiedBy(PasswordUtente $passwordUtente, Utente $utente): bool;
}
