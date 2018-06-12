<?php

namespace Tests\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt;

use Symfony\Component\HttpFoundation\Request;
use Tests\Support\Builder\Doctrine\DoctrineUtenteBuilder;
use Tests\Support\DoctrineSupportKernelTestCase;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Service\Utente\AuthToken\AuthTokenFinder\AuthTokenFinder;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\JwtAuthToken;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci\JwtLcobucciAuthToken;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci\JwtLcobucciSigner;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\BasicPasswordHashing;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt\HeaderSpecificAuthTokenFinder;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt\JwtAuthTokenFinder;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt\QueryStringAuthTokenFinder;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\Jwt\Lcobucci\JwtLcobucciUtenteAuthenticator;

class JwtAuthTokenFinderTest extends DoctrineSupportKernelTestCase
{
    /** @var AuthTokenFinder */
    private $service;

    private $token;

    protected function setUp()
    {
        parent::setUp();

        $expiration = self::$kernel->getContainer()->getParameter('auth_expiration');
        $secureString = self::$kernel->getContainer()->getParameter('secret');

        $signer = new JwtLcobucciSigner($secureString);

        $authenticator = new JwtLcobucciUtenteAuthenticator($expiration, $signer);

        $this->service = new JwtAuthTokenFinder($authenticator);

        $utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword((new BasicPasswordHashing())->hash('password'))
            ->withEmail('utente@dominio.it')
            ->withEnabled(true)
            ->build();

        $this->token = (string)$authenticator->generateAuthToken($utente);
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_find_token_from_headers()
    {
        $this->service->addSpecificAuthTokenFinder(new HeaderSpecificAuthTokenFinder());

        $target = Request::create('/foo', 'GET');
        $target->headers->set('Authorization', "Bearer {$this->token}");

        $token = $this->service->find($target);

        $this->assertInstanceOf(AuthToken::class, $token);
        $this->assertInstanceOf(JwtAuthToken::class, $token);
        $this->assertInstanceOf(JwtLcobucciAuthToken::class, $token);
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_find_token_from_query_string()
    {
        $this->service->addSpecificAuthTokenFinder(new QueryStringAuthTokenFinder());

        $target = Request::create("/foo?t={$this->token}", 'GET');

        $token = $this->service->find($target);

        $this->assertInstanceOf(AuthToken::class, $token);
        $this->assertInstanceOf(JwtAuthToken::class, $token);
        $this->assertInstanceOf(JwtLcobucciAuthToken::class, $token);
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_return_null_if_token_is_not_found()
    {
        $this->service->addSpecificAuthTokenFinder(new HeaderSpecificAuthTokenFinder());
        $this->service->addSpecificAuthTokenFinder(new QueryStringAuthTokenFinder());

        $target = Request::create("/foo", 'GET');

        $token = $this->service->find($target);

        $this->assertNull($token);
    }
}
