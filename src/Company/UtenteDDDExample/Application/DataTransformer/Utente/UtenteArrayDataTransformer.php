<?php

namespace UtenteDDDExample\Application\DataTransformer\Utente;

use UtenteDDDExample\Domain\Model\Utente\Utente;

class UtenteArrayDataTransformer
{
    /** @var Utente */
    private $utente;

    public function write(Utente $utente): UtenteArrayDataTransformer
    {
        $this->utente = $utente;

        return $this;
    }

    public function read(): array
    {
        return [
            'email' => $this->utente->email(),
            'id' => $this->utente->id()->id(),
            'ruolo' => (string)$this->utente->ruolo(),
            'enabled' => $this->utente->isEnabled(),
            'locked' => $this->utente->isLock(),
        ];
    }
}
