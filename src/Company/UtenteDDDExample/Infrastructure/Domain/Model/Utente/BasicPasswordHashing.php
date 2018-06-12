<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente;

use UtenteDDDExample\Domain\Model\Utente\Password\HashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\NotHashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;

class BasicPasswordHashing implements PasswordHashing
{
    public function hash(string $password): HashedPasswordUtente
    {
        return new HashedPasswordUtente(password_hash($password, PASSWORD_DEFAULT));
    }

    public function verify(NotHashedPasswordUtente $plainPassword, HashedPasswordUtente $hashedPassword): bool
    {
        return password_verify(
            (string)$plainPassword,
            (string)$hashedPassword
        );
    }
}
