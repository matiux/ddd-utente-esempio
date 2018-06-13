<?php

namespace Tests\Domain\Service\Utente;

use PHPUnit\Framework\TestCase;
use Tests\Support\Builder\Doctrine\DoctrineUtenteBuilder;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\NotHashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;
use UtenteDDDExample\Domain\Model\Utente\UtenteAutenticato;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use UtenteDDDExample\Domain\Service\Utente\SignInUtente;
use UtenteDDDExample\Domain\Service\Utente\UtenteAuthenticator;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\BasicPasswordHashing;

class SignInUtenteTest extends TestCase
{
    /** @var UtenteRepository */
    private $utenteRepository;

    /** @var SignInUtente */
    private $service;

    /** @var PasswordHashing */
    private $passwordHashing;

    /** @var UtenteAuthenticator */
    private $authenticator;

    protected function setUp()
    {
        parent::setUp();

        $this->utenteRepository = \Mockery::mock(UtenteRepository::class);
        $this->authenticator = \Mockery::mock(UtenteAuthenticator::class);

        $this->passwordHashing = new BasicPasswordHashing();

        $this->service = new SignInUtente(
            $this->utenteRepository,
            $this->passwordHashing,
            $this->authenticator
        );
    }

    /**
     * @test
     * @group utente
     * @expectedException \UtenteDDDExample\Domain\Model\Utente\Exception\UtenteNotFoundException
     * @expectedExceptionMessage Email non valida [user@dominio.it]
     */
    public function should_throw_exception_if_email_is_invalid()
    {
        $this->utenteRepository->shouldReceive('byEmail')->andReturn(null);

        $this->service->login(
            new EmailUtente('user@dominio.it'),
            new NotHashedPasswordUtente('secure_psw')
        );
    }

    /**
     * @test
     * @group utente
     * @expectedException \UtenteDDDExample\Domain\Model\Utente\Exception\PasswordInvalidException
     * @expectedExceptionMessage Password non valida
     */
    public function should_throw_exception_if_password_is_invalid()
    {
        $utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword('password')
            ->build();

        $this->utenteRepository->shouldReceive('byEmail')->andReturn($utente);

        $this->service->login(
            new EmailUtente('user@dominio.it'),
            new NotHashedPasswordUtente('secure_psw')
        );
    }

    /**
     * @test
     * @group utente
     */
    public function it_should_sign_in_user()
    {
        $utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword('password')
            ->build();

        $this->utenteRepository->shouldReceive('byEmail')->andReturn($utente);

        $this->authenticator->shouldReceive('generateAuthToken')->andReturn(\Mockery::mock(AuthToken::class));

        $utenteAutenticato = $this->service->login(
            new EmailUtente('user@dominio.it'),
            new NotHashedPasswordUtente('password')
        );

        $this->assertInstanceOf(UtenteAutenticato::class, $utenteAutenticato);
        $this->assertSame($utenteAutenticato->utente(), $utente);
        $this->assertInstanceOf(AuthToken::class, $utenteAutenticato->token());
    }
}
