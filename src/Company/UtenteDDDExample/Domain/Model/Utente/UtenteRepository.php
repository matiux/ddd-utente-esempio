<?php

namespace UtenteDDDExample\Domain\Model\Utente;

interface UtenteRepository
{
    public function nextIdentity(): UtenteId;

    public function add(Utente $utente): void;

    public function byEmail(EmailUtente $email): ?Utente;

    public function ofId(UtenteId $utenteId): ?Utente;
}
