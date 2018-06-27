<?php

namespace Tests\Application\Service\Utente;

use DDDStarterPack\Application\Service\TransactionalApplicationService;
use Tests\Support\DoctrineSupportKernelTestCase;
use Tests\Support\Repository\Doctrine\Real\RealDoctrineRepository;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteArrayDataTransformer;
use UtenteDDDExample\Application\Service\Utente\SignUpUtenteRequest;
use UtenteDDDExample\Application\Service\Utente\SignUpUtenteService;
use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use UtenteDDDExample\Domain\Service\Utente\SignUpUtente;
use UtenteDDDExample\Infrastructure\Application\Persistence\Doctrine\DoctrineSession;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\BasicPasswordHashing;

class CreateUtenteServiceTest extends DoctrineSupportKernelTestCase
{
    use RealDoctrineRepository;

    /** @var SignUpUtenteService */
    private $service;

    /** @var UtenteRepository */
    private $repository;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->realDoctrineUtenteRepository();

        $this->service = new TransactionalApplicationService(
            new SignUpUtenteService(
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
    public function user_can_sign_up()
    {
        $email = 'user@dominio.it';
        $password = 'secure_psw';

        $utenteRegistrato = $this->service->execute(
            new SignUpUtenteRequest($email, $password, ['Raccogliere le foglie', 'Pettinare le bambole'])
        );

        $this->assertInternalType('array', $utenteRegistrato);
        $this->assertCount(6, $utenteRegistrato);

        $this->assertArrayHasKey('id', $utenteRegistrato);
        $this->assertArrayHasKey('email', $utenteRegistrato);
        $this->assertArrayHasKey('ruolo', $utenteRegistrato);
        $this->assertArrayHasKey('enabled', $utenteRegistrato);
        $this->assertArrayHasKey('locked', $utenteRegistrato);
        $this->assertArrayHasKey('competenze', $utenteRegistrato);
        $this->assertCount(2, $utenteRegistrato['competenze']);
        $this->assertEquals('Raccogliere le foglie', $utenteRegistrato['competenze'][0]['name']);
        $this->assertEquals('Pettinare le bambole', $utenteRegistrato['competenze'][1]['name']);

        $utente = $this->repository->byEmail(new EmailUtente($email));

        $this->assertInstanceOf(Utente::class, $utente);
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

        $this->service->execute(
            new SignUpUtenteRequest($email, $password, ['Raccogliere le foglie', 'Pettinare le bambole'])
        );

        $this->service->execute(
            new SignUpUtenteRequest($email, $password, ['Raccogliere le foglie', 'Pettinare le bambole'])
        );
    }
}
