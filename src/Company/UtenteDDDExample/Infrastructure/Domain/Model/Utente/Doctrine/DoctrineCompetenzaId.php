<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\Doctrine;

use DDDStarterPack\Infrastructure\Domain\DoctrineEntityId;

class DoctrineCompetenzaId extends DoctrineEntityId
{
    public function getName()
    {
        return 'CompetenzaId';
    }

    protected function getNamespace(): string
    {
        return 'UtenteDDDExample\Domain\Model\Utente';
    }
}
