<?php

namespace UtenteDDDExample\Application\Service\Utente;

class ShowUtenteRequest
{
    private $utenteId;

    public function __construct(string $utenteId)
    {
        $this->utenteId = $utenteId;
    }

    public function getUtenteId(): string
    {
        return $this->utenteId;
    }
}
