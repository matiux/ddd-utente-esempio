<?php

namespace Tests\Application\Service\Utente;

use Tests\Support\Builder\Doctrine\DoctrineUtenteBuilder;
use Tests\Support\DoctrineSupportKernelTestCase;
use Tests\Support\Repository\Doctrine\Dummy\DummyDoctrineRepository;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteArrayDataTransformer;
use UtenteDDDExample\Application\Service\Utente\ShowUtenteRequest;
use UtenteDDDExample\Application\Service\Utente\ShowUtenteService;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

class ShowUtenteServiceTest extends DoctrineSupportKernelTestCase
{
    use DummyDoctrineRepository;

    /** @var ShowUtenteService */
    private $service;

    /** @var Utente */
    private $utente;

    /** @var UtenteRepository */
    private $utenteRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->utenteRepository = $this->dummyDoctrineUtenteRepository();

        $this->service = new ShowUtenteService(
            $this->utenteRepository,
            new UtenteArrayDataTransformer()
        );

        $this->utente = DoctrineUtenteBuilder::anUtente()
            ->withEnabled(true)
            ->withEmail('email@dominio.it')
            ->withPassword('password')
            ->withLocked(false)
            ->build();

        $this->utenteRepository->add($this->utente);
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_show_an_utente()
    {
        $utente = $this->service->execute(new ShowUtenteRequest($this->utente->id()->id()));

        $this->assertInternalType('array', $utente);
        $this->assertNotEmpty($utente);
        $this->assertCount(6, $utente);
        $this->assertArrayHasKey('ruolo', $utente);
        $this->assertArrayHasKey('id', $utente);
        $this->assertArrayHasKey('email', $utente);
        $this->assertArrayHasKey('enabled', $utente);
        $this->assertArrayHasKey('locked', $utente);
        $this->assertArrayHasKey('competenze', $utente);
    }

    /**
     * @test
     * @group utente
     * @group integration
     * @expectedException \UtenteDDDExample\Domain\Model\Utente\Exception\UtenteNotFoundException
     * @expectedExceptionMessage Utente non trovato [wrong_id]
     */
    public function it_should_thrown_an_exception_if_user_doesnt_exists()
    {
        $this->service->execute(new ShowUtenteRequest('wrong_id'));
    }
}
