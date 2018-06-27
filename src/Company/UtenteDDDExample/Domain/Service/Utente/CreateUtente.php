<?php

namespace UtenteDDDExample\Domain\Service\Utente;

use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Exception\EmailUtenteIsNotUniqueException;
use UtenteDDDExample\Domain\Model\Utente\Password\HashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\NotHashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;
use UtenteDDDExample\Domain\Model\Utente\Specification\EmailUtenteIsUnique;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

abstract class CreateUtente
{
    protected $utenteRepository;
    private $passwordHashing;

    /** @var HashedPasswordUtente */
    protected $hashedPassword;

    /** @var string */
    protected $email;

    /** @var array */
    protected $competenze;

    /** @var string */
    protected $ruolo;

    public function __construct(UtenteRepository $utenteRepository, PasswordHashing $passwordHashing)
    {
        $this->utenteRepository = $utenteRepository;
        $this->passwordHashing = $passwordHashing;
    }

    public function create(string $email, string $password, array $competenze = [], ?string $ruolo = null, ?bool $enabled = false): Utente
    {
        // Specification pattern
        $this->checkEmailIsUnique(new EmailUtente($email));

        $this->hashedPassword = $this->passwordHashing->hash(
            new NotHashedPasswordUtente($password)
        );

        $this->email = $email;
        $this->competenze = $competenze;
        $this->ruolo = $ruolo;

        $utente = $this->createUtente();

        !$enabled ?: $utente->enable();

        $this->utenteRepository->add($utente);

        return $utente;
    }

    protected abstract function createUtente(): Utente;


    /**
     * Controllo che la mail sia univoca
     *
     * @param EmailUtente $email
     * @throws EmailUtenteIsNotUniqueException
     * @return void
     */
    private function checkEmailIsUnique(EmailUtente $email): void
    {
        $specification = new EmailUtenteIsUnique($this->utenteRepository);

        if (!$specification->isSatisfiedBy($email)) {
            throw new EmailUtenteIsNotUniqueException(EmailUtenteIsNotUniqueException::MESSAGE . " $email");
        }
    }
}
