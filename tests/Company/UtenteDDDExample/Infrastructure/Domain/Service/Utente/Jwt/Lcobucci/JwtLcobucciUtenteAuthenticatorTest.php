<?php

namespace Tests\Infrastructure\Domain\Service\Utente\Jwt\Lcobucci;

use Tests\Support\Builder\Doctrine\DoctrineUtenteBuilder;
use Tests\Support\DoctrineSupportKernelTestCase;
use Tests\Support\Repository\Doctrine\Dummy\DummyDoctrineRepository;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Service\Utente\UtenteAuthenticator;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\JwtAuthToken;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci\JwtLcobucciAuthToken;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci\JwtLcobucciSigner;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\BasicPasswordHashing;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\Jwt\Lcobucci\JwtLcobucciUtenteAuthenticator;

class JwtLcobucciUtenteAuthenticatorTest extends DoctrineSupportKernelTestCase
{
    use DummyDoctrineRepository;

    /** @var UtenteAuthenticator */
    private $authenticator;

    /** @var PasswordHashing */
    private $passwordHashing;

    protected function setUp()
    {
        parent::setUp();

        $expiration = self::$kernel->getContainer()->getParameter('auth_expiration');
        $secureString = self::$kernel->getContainer()->getParameter('secret');

        $this->passwordHashing = new BasicPasswordHashing();

        $signer = new JwtLcobucciSigner($secureString);

        $this->authenticator = new JwtLcobucciUtenteAuthenticator($expiration, $signer);
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_create_a_valid_token()
    {
        /** @var Utente $utente */
        $utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword($this->passwordHashing->hash('secure_psw'))
            ->withEmail('user@dominio.it')
            ->withEnabled(true)
            ->build();

        $this->dummyDoctrineUtenteRepository()->add($utente);

        $token = $this->authenticator->generateAuthToken($utente);

        $this->assertInstanceOf(AuthToken::class, $token);
        $this->assertInstanceOf(JwtAuthToken::class, $token);
        $this->assertInstanceOf(JwtLcobucciAuthToken::class, $token);

        $this->assertInternalType('string', (string)$token);
        $this->assertRegExp('/[A-Za-z0-9\-\._~\+\/]+=*/', (string)$token);

        $payload = $token->payload();

        $data = $payload->data();
        $this->assertCount(5, $data);
        $this->assertArrayHasKey('sub', $data);
        $this->assertArrayHasKey('exp', $data);
        $this->assertArrayHasKey('jti', $data);
        $this->assertArrayHasKey('iat', $data);
        $this->assertArrayHasKey('nbf', $data);

        $this->assertEquals($utente->id()->id(), $data['sub']);
        $this->assertGreaterThan(time(), $data['exp']);
    }
}
