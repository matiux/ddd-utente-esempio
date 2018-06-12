<?php

namespace UtenteDDDExample\Domain\Model\Utente\Password;

class HashedPasswordUtente extends PasswordUtente
{
    protected function isPasswordValid(string $password): bool
    {
        /**
         * TODO - ?
         */

        return true;
    }
}
