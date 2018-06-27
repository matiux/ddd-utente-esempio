<?php

namespace UtenteDDDExample\Application\Service\Utente;

class EnableUtenteByIdRequest
{
    private $utenteId;

    public function __construct(string $utenteId)
    {
        $this->utenteId = $utenteId;
    }

    public function getUtenteId()
    {
        return $this->utenteId;
    }
}
