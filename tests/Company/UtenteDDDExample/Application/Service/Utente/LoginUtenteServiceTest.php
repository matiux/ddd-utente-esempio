<?php

namespace Tests\Application\Service\Utente;

use Tests\Support\Builder\Doctrine\DoctrineUtenteBuilder;
use Tests\Support\DoctrineSupportKernelTestCase;
use Tests\Support\Repository\Doctrine\Dummy\DummyDoctrineRepository;
use UtenteDDDExample\Application\Service\Utente\LoginUtenteRequest;
use UtenteDDDExample\Application\Service\Utente\LoginUtenteService;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use UtenteDDDExample\Domain\Service\Utente\SignInUtente;
use UtenteDDDExample\Infrastructure\Application\DataTransformer\Jwt\JwtUtenteAutenticatoArrayDataTransformer;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci\JwtLcobucciSigner;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\BasicPasswordHashing;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\Jwt\Lcobucci\JwtLcobucciUtenteAuthenticator;

class LoginUtenteServiceTest extends DoctrineSupportKernelTestCase
{
    use DummyDoctrineRepository;

    /** @var UtenteRepository */
    private $utenteRepository;

    /** @var PasswordHashing */
    private $passwordHashing;

    /** @var Utente */
    private $utente;

    protected function setUp()
    {
        parent::setUp();

        $this->utenteRepository = $this->dummyDoctrineUtenteRepository();
        $this->passwordHashing = new BasicPasswordHashing();

        $this->utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword($this->passwordHashing->hash('secure_psw'))
            ->withEmail('user@dominio.it')
            ->withEnabled(true)
            ->build();

        $this->utenteRepository->add($this->utente);
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_login_utente_with_real_authenticator()
    {
        $expiration = self::$kernel->getContainer()->getParameter('auth_expiration');
        $secureString = self::$kernel->getContainer()->getParameter('secret');

        $signer = new JwtLcobucciSigner($secureString);
        $authenticator = new JwtLcobucciUtenteAuthenticator($expiration, $signer);

        $service = new LoginUtenteService(
            new SignInUtente(
                $this->utenteRepository,
                $this->passwordHashing,
                $authenticator
            ),
            new JwtUtenteAutenticatoArrayDataTransformer()
        );

        $utenteLoggato = $service->execute(new LoginUtenteRequest('user@dominio.it', 'secure_psw'));

        $this->assertInternalType('array', $utenteLoggato);
        $this->assertCount(3, $utenteLoggato);
        $this->assertArrayHasKey('utente', $utenteLoggato);
        $this->assertArrayHasKey('token', $utenteLoggato);
        $this->assertArrayHasKey('token_expire', $utenteLoggato);

        $this->assertInternalType('array', $utenteLoggato['utente']);
        $this->assertCount(5, $utenteLoggato['utente']);

        $this->assertArrayHasKey('id', $utenteLoggato['utente']);
        $this->assertArrayHasKey('email', $utenteLoggato['utente']);
        $this->assertArrayHasKey('ruolo', $utenteLoggato['utente']);
        $this->assertArrayHasKey('enabled', $utenteLoggato['utente']);
        $this->assertArrayHasKey('locked', $utenteLoggato['utente']);
    }
}
