<?php

namespace Tests\Domain\Service\Utente;

use PHPUnit\Framework\TestCase;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use UtenteDDDExample\Domain\Service\Utente\SignUpUtente;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\BasicPasswordHashing;

class SignUpUtenteTest extends TestCase
{
    /** @var UtenteRepository */
    private $utenteRepository;

    /** @var SignUpUtente */
    private $service;

    protected function setUp()
    {
        parent::setUp();

        $this->utenteRepository = \Mockery::mock(UtenteRepository::class);

        $this->service = new SignUpUtente($this->utenteRepository, new BasicPasswordHashing());
    }

    /**
     * @test
     * @group utente
     * @expectedException \UtenteDDDExample\Domain\Model\Utente\Exception\EmailUtenteIsNotUniqueException
     * @expectedExceptionMessage Email già presente user@dominio.it
     */
    public function should_throw_exception_if_email_is_not_unique()
    {
        $utente = \Mockery::mock(Utente::class);

        $this->utenteRepository->shouldReceive('byEmail')->andReturn($utente);

        $this->service->create(
            'user@dominio.it',
            'secure_psw',
            []
        );
    }

    /**
     * @test
     * @group utente
     */
    public function should_register_new_user()
    {
        $this->utenteRepository->shouldReceive('byEmail')->andReturn(null);
        $this->utenteRepository->shouldReceive('nextIdentity')->andReturn(UtenteId::create());
        $this->utenteRepository->shouldReceive('add');

        $utente = $this->service->create(
            'user@dominio.it',
            'secure_psw',
            []
        );

        $this->assertInstanceOf(Utente::class, $utente);
    }
}
