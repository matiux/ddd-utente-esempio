<?php

namespace UtenteDDDExample\Domain\Model\Utente;

use DDDStarterPack\Domain\Model\IdentifiableDomainObject;
use Doctrine\Common\Collections\ArrayCollection;
use UtenteDDDExample\Domain\Model\Utente\Password\HashedPasswordUtente;

class Utente implements IdentifiableDomainObject
{
    private $utenteId;
    private $email;
    private $password;
    private $ruolo;
    private $enabled = false; // Per il primo accesso
    private $locked = false;

    private $competenze;

    protected function __construct(UtenteId $utenteId, EmailUtente $email, HashedPasswordUtente $password, Ruolo $ruolo)
    {
        $this->utenteId = $utenteId;
        $this->email = $email;
        $this->password = $password;
        $this->ruolo = $ruolo;

        $this->competenze = new ArrayCollection();
    }

    public static function create(UtenteId $utenteId, string $email, HashedPasswordUtente $password, string $ruolo, array $competenze = []): self
    {
        $utente = new self(
            $utenteId,
            new EmailUtente($email),
            $password,
            new Ruolo($ruolo)
        );

        foreach ($competenze as $competenza) {

            $utente->addCompetenza($competenza);
        }

        return $utente;
    }

    public function addCompetenza($name): void
    {
        $this->competenze->add(
            new Competenza(CompetenzaId::create(), $name, $this->utenteId)
        );
    }

    public function competenze(): \ArrayObject
    {
        if (!$this->competenze || $this->competenze->isEmpty()) {
            $competenze = [];
        } else {
            $competenze = $this->competenze->toArray();
        }

        return new \ArrayObject($competenze);
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

//    public function data(): array
//    {
//        return [
//            'email' => $this->email,
//            'locked' => $this->locked,
//            'enabled' => $this->enabled,
//        ];
//    }
}
