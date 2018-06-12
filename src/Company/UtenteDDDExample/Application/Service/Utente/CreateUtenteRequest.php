<?php

namespace UtenteDDDExample\Application\Service\Utente;

class CreateUtenteRequest
{
    private $email;
    private $password;
    private $ruolo;
    private $enabled;

    public function __construct(string $email, string $password, string $ruolo = 'user', bool $enabled = false)
    {
        $this->email = $email;
        $this->password = $password;
        $this->ruolo = $ruolo;
        $this->enabled = $enabled;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRuolo(): string
    {
        return $this->ruolo;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
