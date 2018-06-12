<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\Doctrine;

use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use Doctrine\ORM\EntityRepository;

class DoctrineUtenteRepository extends EntityRepository implements UtenteRepository
{
    public function nextIdentity(): UtenteId
    {
        return UtenteId::create();
    }

    public function add(Utente $utente): void
    {
        $this->getEntityManager()->persist($utente);
    }

    public function byEmail(EmailUtente $email): ?Utente
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.email = :email')->setParameter('email', $email);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    public function ofId(UtenteId $utenteId): ?Utente
    {
        $utente = $this->find($utenteId);

        return $utente;
    }
}
