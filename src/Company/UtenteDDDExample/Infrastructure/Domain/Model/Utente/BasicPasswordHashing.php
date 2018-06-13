<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente;

use UtenteDDDExample\Domain\Model\Utente\Password\HashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\NotHashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;

class BasicPasswordHashing implements PasswordHashing
{
    public function hash(NotHashedPasswordUtente $notHashedPasswordUtente): HashedPasswordUtente
    {
        $hashedPassword = password_hash((string)$notHashedPasswordUtente, PASSWORD_DEFAULT);

        return new HashedPasswordUtente($hashedPassword);
    }

    public function verify(NotHashedPasswordUtente $notHashedPasswordUtente, HashedPasswordUtente $hashedPassword): bool
    {
        return password_verify(
            (string)$notHashedPasswordUtente,
            (string)$hashedPassword
        );
    }
}
