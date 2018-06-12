<?php

namespace Tests\Support\Builder;

use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\HashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Ruolo;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;

abstract class UtenteBuilder implements EntityBuilder
{
    protected $utenteId;
    protected $email;
    protected $password;
    protected $ruolo;
    protected $enabled = true;
    protected $locked = false;

    public function __construct()
    {
        $this->utenteId = UtenteId::create();
        $this->email = new EmailUtente('user@dominio.it');
        $this->password = new HashedPasswordUtente('$2y$10$jaY.eUrLO5gfKCTBr6MH.uk6OL8bofDONdJ.JjhCgUl.vksuS43L.'); //in chiaro: password
        $this->ruolo = new Ruolo('user');
    }

    public function withPassword(HashedPasswordUtente $password): self
    {
        $this->password = $password;

        return $this;
    }

    public static function anUtente(): self
    {
        return new static();
    }

    public function withEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function withEmail($emailUtente)
    {
        $this->email = new EmailUtente($emailUtente);

        return $this;
    }

    public function withLocked(bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }
}
