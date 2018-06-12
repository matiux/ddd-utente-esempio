<?php

namespace UtenteDDDExample\Domain\Model\Utente\Password;

interface PasswordHashing
{
    public function verify(NotHashedPasswordUtente $plainPassword, HashedPasswordUtente $hashedPassword): bool;

    public function hash(string $password): HashedPasswordUtente;
}
