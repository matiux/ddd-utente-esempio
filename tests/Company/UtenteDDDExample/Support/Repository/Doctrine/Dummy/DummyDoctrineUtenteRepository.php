<?php

namespace Tests\Support\Repository\Doctrine\Dummy;

use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\Doctrine\DoctrineUtenteRepository;

class DummyDoctrineUtenteRepository extends DoctrineUtenteRepository
{
    public function add(Utente $utente): void
    {
        parent::add($utente);

        $this->getEntityManager()->flush();
    }
}
