<?php

namespace UtenteDDDExample\Domain\Model\Utente;

use UtenteDDDExample\Domain\Model\Utente\Exception\EmailNotValidException;

class EmailUtente
{
    private $email;

    public function __construct(string $email)
    {
        $this->setEmail($email);
    }

    private function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new EmailNotValidException(sprintf('Email non vadida [%s]', $email));
        }

//        if (!checkdnsrr($domain, 'MX')) {
//            // domain is not valid
//        }

        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email();
    }

    public function email(): string
    {
        return $this->email;
    }
}
