<?php

namespace UtenteDDDExample\Domain\Model\Utente\Password;

abstract class PasswordUtente
{
    protected $password;

    public function __construct(string $password)
    {
        if (!$this->isPasswordValid($password)) {
            throw new \InvalidArgumentException('Password non valida');
        }


        $this->password = $password;
    }

    public function __toString(): string
    {
        return $this->password();
    }

    public function password(): string
    {
        return $this->password;
    }

    abstract protected function isPasswordValid(string $password): bool;
}
