<?php

namespace UtenteDDDExample\Infrastructure\Application\Persistence\Doctrine;

use DDDStarterPack\Application\Service\TransactionalSession;
use Doctrine\ORM\EntityManager;

class DoctrineSession implements TransactionalSession
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function executeAtomically(callable $operation)
    {
        return $this->entityManager->transactional($operation);
    }
}
