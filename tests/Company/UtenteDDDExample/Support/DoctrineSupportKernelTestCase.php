<?php

namespace Tests\Support;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use UtenteDDDExample\Infrastructure\Application\Persistence\Doctrine\DoctrineSession;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class DoctrineSupportKernelTestCase extends KernelTestCase
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $em;

    /** @var DoctrineSession */
    protected $doctrineTransactionalSession;

    protected function setUp()
    {
        parent::setUp();

        self::$kernel = self::bootKernel();

        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();

        (new ORMPurger($this->em))->purge();

        $this->doctrineTransactionalSession = new DoctrineSession($this->em);
    }
}
