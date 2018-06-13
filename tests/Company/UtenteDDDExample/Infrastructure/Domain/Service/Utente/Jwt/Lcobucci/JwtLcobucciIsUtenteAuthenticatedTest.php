<?php

namespace Tests\Infrastructure\Domain\Service\Utente\Jwt\Lcobucci;

use Lcobucci\JWT\Builder;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Tests\Support\Builder\Doctrine\DoctrineUtenteBuilder;
use Tests\Support\DoctrineSupportKernelTestCase;
use Tests\Support\Repository\Doctrine\Dummy\DummyDoctrineRepository;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenSigner;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenStorage;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Service\Utente\IsUtenteAuthenticated;
use UtenteDDDExample\Domain\Service\Utente\UtenteAuthenticator;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\AuthTokenStorage\JwtInMemoryAuthTokenStorage;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci\JwtLcobucciSigner;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt\HeaderSpecificAuthTokenFinder;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt\JwtAuthTokenFinder;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\AuthToken\AuthTokenFinder\Jwt\QueryStringAuthTokenFinder;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\Jwt\JwtUtenteFromAuthToken;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\Jwt\Lcobucci\JwtLcobucciUtenteAuthenticator;

class JwtLcobucciIsUtenteAuthenticatedTest extends DoctrineSupportKernelTestCase
{
    use DummyDoctrineRepository;

    /** @var IsUtenteAuthenticated */
    private $service;

    /** @var Utente */
    private $utente;

    /** @var UtenteAuthenticator */
    private $authenticator;

    /** @var AuthTokenSigner */
    private $signer;

    /** @var AuthTokenStorage */
    private $authTokenStorage;

    protected function setUp()
    {
        parent::setUp();

        $this->signer = new JwtLcobucciSigner(self::$kernel->getContainer()->getParameter('secret'));

        $this->authenticator = new JwtLcobucciUtenteAuthenticator(self::$kernel->getContainer()->getParameter('auth_expiration'), $this->signer);

        $authTokenFinder = new JwtAuthTokenFinder($this->authenticator);
        $authTokenFinder->addSpecificAuthTokenFinder(new HeaderSpecificAuthTokenFinder());
        $authTokenFinder->addSpecificAuthTokenFinder(new QueryStringAuthTokenFinder());

        $utenteFromAuthToken = new JwtUtenteFromAuthToken($this->dummyDoctrineUtenteRepository());

        $this->authTokenStorage = new JwtInMemoryAuthTokenStorage();

        $this->service = new IsUtenteAuthenticated($authTokenFinder, $this->signer, $utenteFromAuthToken, $this->authTokenStorage);

        $this->utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword('password')
            ->withEmail('utente@dominio.it')
            ->withEnabled(true)
            ->build();

        $this->dummyDoctrineUtenteRepository()->add($this->utente);
    }


    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_return_true_if_user_is_authenticated()
    {
        $token = (string)$this->authenticator->generateAuthToken($this->utente);

        $target = Request::create('/foo', 'GET');
        $target->headers->set('Authorization', "Bearer {$token}");

        $isAuthenticated = $this->service->verifyAuthentication($target);

        $this->assertTrue($isAuthenticated);
        $this->assertEquals((string)$this->authTokenStorage->getToken(), $token);
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_return_false_if_token_is_expired()
    {
        $builder = (new Builder())
            ->setId(Uuid::uuid4()->toString())
            ->setIssuedAt(time())
            ->setExpiration(time() + 1)
            ->setSubject($this->utente->id()->id())
            ->sign($this->signer->signer(), $this->signer->secret());

        sleep(2);

        $token = (string)$builder->getToken();

        $target = Request::create('/foo', 'GET');
        $target->headers->set('Authorization', "Bearer {$token}");

        $isAuthenticated = $this->service->verifyAuthentication($target);

        $this->assertFalse($isAuthenticated);
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function it_should_return_false_if_token_is_not_verified()
    {
        $builder = (new Builder())
            ->setId(Uuid::uuid4()->toString())
            ->setIssuedAt(time())
            ->setExpiration(time() + 120)
            ->setSubject($this->utente->id()->id())
            ->sign($this->signer->signer(), 'wrong_secret');

        $token = (string)$builder->getToken();

        $target = Request::create('/foo', 'GET');
        $target->headers->set('Authorization', "Bearer {$token}");

        $isAuthenticated = $this->service->verifyAuthentication($target);

        $this->assertFalse($isAuthenticated);
    }
}
