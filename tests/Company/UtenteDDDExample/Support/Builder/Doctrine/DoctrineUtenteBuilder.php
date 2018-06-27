<?php

namespace Tests\Support\Builder\Doctrine;

use Tests\Support\Builder\UtenteBuilder;
use UtenteDDDExample\Domain\Model\Utente\Utente;

class DoctrineUtenteBuilder extends UtenteBuilder
{
    public function build(): Utente
    {
        $utente = Utente::create(
            $this->utenteId,
            $this->email,
            $this->password,
            $this->ruolo,
            $this->competenze
        );

        if ($this->enabled) {
            $utente->enable();
        }

        if ($this->locked) {
            $utente->lock();
        }

        return $utente;
    }
}
