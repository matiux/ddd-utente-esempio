<?php

namespace Tests\Application\Service\Utente;

use DDDStarterPack\Application\Service\TransactionalApplicationService;
use Tests\Support\DoctrineSupportKernelTestCase;
use Tests\Support\Repository\Doctrine\Real\RealDoctrineRepository;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteArrayDataTransformer;
use UtenteDDDExample\Application\Service\Utente\CreateUtenteRequest;
use UtenteDDDExample\Application\Service\Utente\CreateUtenteService;
use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use UtenteDDDExample\Domain\Service\Utente\SignUpUtente;
use UtenteDDDExample\Infrastructure\Application\Persistence\Doctrine\DoctrineSession;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\BasicPasswordHashing;

class CreateUtenteServiceTest extends DoctrineSupportKernelTestCase
{
    use RealDoctrineRepository;

    /** @var CreateUtenteService */
    private $service;

    /** @var UtenteRepository */
    private $repository;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->realDoctrineUtenteRepository();

        $this->service = new TransactionalApplicationService(
            new CreateUtenteService(
                new SignUpUtente(
                    $this->repository,
                    new BasicPasswordHashing()
                ),
                new UtenteArrayDataTransformer()
            ),
            new DoctrineSession($this->em)
        );
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_register_new_user()
    {
        $email = 'user@dominio.it';
        $password = 'secure_psw';
        $role = 'user';

        $utenteRegistrato = $this->service->execute(new CreateUtenteRequest($email, $password, $role));

        $this->assertInternalType('array', $utenteRegistrato);
        $this->assertCount(5, $utenteRegistrato);

        $this->assertArrayHasKey('id', $utenteRegistrato);
        $this->assertArrayHasKey('email', $utenteRegistrato);
        $this->assertArrayHasKey('ruolo', $utenteRegistrato);
        $this->assertArrayHasKey('enabled', $utenteRegistrato);
        $this->assertArrayHasKey('locked', $utenteRegistrato);

        $utente = $this->repository->byEmail(new EmailUtente($email));

        $this->assertInstanceOf(Utente::class, $utente);
    }

    /**
     * @test
     * @group utente
     * @expectedException \UtenteDDDExample\Domain\Model\Utente\Exception\RuoloNotValidException
     * @expectedExceptionMessage Ruolo non valido [utente]
     */
    public function error_if_role_is_not_valid()
    {
        $email = 'user@dominio.it';
        $password = 'secure_psw';
        $role = 'utente';

        $this->service->execute(new CreateUtenteRequest($email, $password, $role));
    }

    /**
     * @test
     * @group utente
     * @expectedException \UtenteDDDExample\Domain\Model\Utente\Exception\EmailUtenteIsNotUniqueException
     * @expectedExceptionMessage Email già presente user@dominio.it
     */
    public function error_if_email_exists()
    {
        $email = 'user@dominio.it';
        $password = 'secure_psw';
        $role = 'user';

        $this->service->execute(new CreateUtenteRequest($email, $password, $role));

        $this->service->execute(new CreateUtenteRequest($email, $password, $role));
    }
}
