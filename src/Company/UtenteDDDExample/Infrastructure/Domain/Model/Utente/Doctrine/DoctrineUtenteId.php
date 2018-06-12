<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\Doctrine;

use DDDStarterPack\Infrastructure\Domain\DoctrineEntityId;

class DoctrineUtenteId extends DoctrineEntityId
{
    public function getName()
    {
        return 'UtenteId';
    }

    protected function getNamespace(): string
    {
        return 'UtenteDDDExample\Domain\Model\Utente';
    }
}
