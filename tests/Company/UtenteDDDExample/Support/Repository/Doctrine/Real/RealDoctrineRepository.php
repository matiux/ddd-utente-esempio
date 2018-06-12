<?php

namespace Tests\Support\Repository\Doctrine\Real;

use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

trait RealDoctrineRepository
{
    protected function realDoctrineUtenteRepository(): UtenteRepository
    {
        $utenteRepository = self::$kernel->getContainer()->get('dddapp.utente.repository');

        return $utenteRepository;
    }
}
