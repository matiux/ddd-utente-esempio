<?php

namespace UtenteDDDExample\Domain\Model\Utente\Password;

interface PasswordHashing
{
    public function verify(NotHashedPasswordUtente $notHashedPasswordUtente, HashedPasswordUtente $hashedPassword): bool;

    public function hash(NotHashedPasswordUtente $notHashedPasswordUtente): HashedPasswordUtente;
}
