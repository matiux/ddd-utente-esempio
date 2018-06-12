<?php

namespace UtenteDDDExample\Domain\Model\Utente\Specification;

use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

abstract class UtenteSpecification
{
    protected $utenteRepository;

    public function __construct(UtenteRepository $utenteRepository)
    {
        $this->utenteRepository = $utenteRepository;
    }

    abstract public function isSatisfiedBy(Utente $utente): bool;
}
