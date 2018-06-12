<?php

namespace UtenteDDDExample\Domain\Model\Utente\Specification;

use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

abstract class EmailUtenteSpecification
{
    protected $utenteRepository;

    public function __construct(UtenteRepository $utenteRepository)
    {
        $this->utenteRepository = $utenteRepository;
    }

    abstract public function isSatisfiedBy(EmailUtente $emailUtente): bool;
}
