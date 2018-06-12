<?php

namespace UtenteDDDExample\Domain\Service\Utente;

use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Exception\EmailUtenteIsNotUniqueException;
use UtenteDDDExample\Domain\Model\Utente\Password\NotHashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;
use UtenteDDDExample\Domain\Model\Utente\Ruolo;
use UtenteDDDExample\Domain\Model\Utente\Specification\EmailUtenteIsUnique;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

class SignUpUtente
{
    private $utenteRepository;
    private $passwordHashing;

    public function __construct(UtenteRepository $utenteRepository, PasswordHashing $passwordHashing)
    {
        $this->utenteRepository = $utenteRepository;
        $this->passwordHashing = $passwordHashing;
    }

    public function registra(string $email, string $password, string $ruolo): Utente
    {
        $email = new EmailUtente($email);
        $password = new NotHashedPasswordUtente($password);
        $ruolo = new Ruolo($ruolo);

        /**
         * Specification pattern
         */
        $this->checkEmailIsUnique($email);

        $utenteId = $this->utenteRepository->nextIdentity();
        $hashedPassword = $this->passwordHashing->hash($password);

        $utente = new Utente($utenteId, $email, $hashedPassword, $ruolo);

        $this->utenteRepository->add($utente);

        return $utente;
    }

    /**
     * Check that an Email is unique
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
