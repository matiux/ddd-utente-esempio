<?php

namespace UtenteDDDExample\Application\Service\Utente;

class RegisterUtenteRequest extends CreateUtenteRequest
{
    private $ruolo;
    private $enabled;

    public function __construct(string $email, string $password, array $competenze, string $ruolo, bool $enabled)
    {
        parent::__construct($email, $password, $competenze);

        $this->ruolo = $ruolo;
        $this->enabled = $enabled;
    }

    public function getRuolo(): string
    {
        return $this->ruolo;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }
}
