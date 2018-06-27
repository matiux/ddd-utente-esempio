<?php

namespace UtenteDDDExample\Application\Service\Utente;

abstract class CreateUtenteRequest
{
    protected $email;
    protected $password;
    protected $competenze;

    public function __construct(string $email, string $password, array $competenze)
    {
        $this->email = $email;
        $this->password = $password;
        $this->competenze = $competenze;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCompetenze(): array
    {
        return $this->competenze;
    }
}
