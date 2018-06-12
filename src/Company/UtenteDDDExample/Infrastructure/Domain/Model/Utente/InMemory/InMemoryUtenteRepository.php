<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\InMemory;

use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use UtenteDDDExample\Infrastructure\Domain\Model\InMemoryRepository;

class InMemoryUtenteRepository extends InMemoryRepository implements UtenteRepository
{
    public function nextIdentity(): UtenteId
    {

    }

    public function add(Utente $utente): void
    {

    }

    public function byEmail(EmailUtente $email): ?Utente
    {

    }

    public function ofId(UtenteId $utenteId): ?Utente
    {

    }
}
