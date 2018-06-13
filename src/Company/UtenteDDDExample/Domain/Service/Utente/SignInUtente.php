<?php

namespace UtenteDDDExample\Domain\Service\Utente;

use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Exception\PasswordInvalidException;
use UtenteDDDExample\Domain\Model\Utente\Exception\UtenteIsLockedException;
use UtenteDDDExample\Domain\Model\Utente\Exception\UtenteIsNotEnabledException;
use UtenteDDDExample\Domain\Model\Utente\Exception\UtenteNotFoundException;
use UtenteDDDExample\Domain\Model\Utente\Password\NotHashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;
use UtenteDDDExample\Domain\Model\Utente\Specification\EmailUtenteExists;
use UtenteDDDExample\Domain\Model\Utente\Specification\PasswordUtenteIsValid;
use UtenteDDDExample\Domain\Model\Utente\Specification\UtenteIsEnabled;
use UtenteDDDExample\Domain\Model\Utente\Specification\UtenteIsNotLocked;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteAutenticato;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

class SignInUtente
{
    private $utenteRepository;
    private $passwordHashing;
    private $utenteAuthenticator;

    public function __construct(UtenteRepository $utenteRepository, PasswordHashing $passwordHashing, UtenteAuthenticator $utenteAuthenticator)
    {
        $this->utenteRepository = $utenteRepository;
        $this->passwordHashing = $passwordHashing;
        $this->utenteAuthenticator = $utenteAuthenticator;
    }

    public function login(EmailUtente $email, NotHashedPasswordUtente $notHashedPasswordUtente): UtenteAutenticato
    {
        $this->checkUtenteExists($email);
        $utente = $this->utenteRepository->byEmail($email);
        $this->checkUtenteEnabled($utente);
        $this->checkUtenteIsNotLocked($utente);
        $this->checkPasswordIsValidFor($notHashedPasswordUtente, $utente);

        $token = $this->utenteAuthenticator->generateAuthToken($utente);

        $utenteAutenticato = new UtenteAutenticato($utente, $token);

        return $utenteAutenticato;
    }

    private function checkUtenteExists(EmailUtente $email): void
    {
        if (!(new EmailUtenteExists($this->utenteRepository))->isSatisfiedBy($email)) {
            throw new UtenteNotFoundException(sprintf('Email non valida [%s]', (string)$email));
        }
    }

    private function checkPasswordIsValidFor(NotHashedPasswordUtente $notHashedPasswordUtente, Utente $utente): void
    {
        if (!(new PasswordUtenteIsValid($this->passwordHashing))->isSatisfiedBy($notHashedPasswordUtente, $utente)) {
            throw new PasswordInvalidException();
        }
    }

    private function checkUtenteEnabled(Utente $utente): void
    {
        if (!(new UtenteIsEnabled($this->utenteRepository))->isSatisfiedBy($utente)) {
            throw new UtenteIsNotEnabledException();
        }
    }

    private function checkUtenteIsNotLocked(Utente $utente): void
    {
        if (!(new UtenteIsNotLocked($this->utenteRepository))->isSatisfiedBy($utente)) {
            throw new UtenteIsLockedException();
        }
    }
}
