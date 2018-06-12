<?php

namespace Tests\Support\Repository\Doctrine\Dummy;

use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

trait DummyDoctrineRepository
{
    protected function dummyDoctrineUtenteRepository(): UtenteRepository
    {
        $utenteRepository = new DummyDoctrineUtenteRepository($this->em, $this->em->getClassMetadata(Utente::class));

        return $utenteRepository;
    }
}
