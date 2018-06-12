<?php

namespace UtenteDDDExample\Domain\Model\Utente;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Model\Utente\Password\HashedPasswordUtente;
use DDDStarterPack\Domain\Model\IdentifiableDomainObject;

class Utente implements IdentifiableDomainObject
{
    private $utenteId;
    private $email;
    private $password;
    private $ruolo;
    private $enabled = false; // Per il primo accesso
    private $locked = false;

    public function __construct(UtenteId $utenteId, EmailUtente $email, HashedPasswordUtente $password, Ruolo $ruolo)
    {
        $this->utenteId = $utenteId;
        $this->email = $email;
        $this->password = $password;
        $this->ruolo = $ruolo;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * REVIEW Usiamo un toggle?
     */
    public function lock(): void
    {
        $this->locked = true;
    }

    public function unlock(): void
    {
        $this->locked = false;
    }

    public function ruolo(): Ruolo
    {
        return $this->ruolo;
    }

    public function id(): UtenteId
    {
        return $this->utenteId;
    }

    public function isLock(): bool
    {
        return $this->locked;
    }

    public function password(): HashedPasswordUtente
    {
        return $this->password;
    }

    public function authenticate(AuthToken $token): void
    {

    }
}
