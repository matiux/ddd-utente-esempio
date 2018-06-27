<?php

namespace Tests\Infrastructure\Delivery\Http\Controller\Utente;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;
use Tests\Support\Builder\Doctrine\DoctrineUtenteBuilder;
use Tests\Support\Repository\Doctrine\Dummy\DummyDoctrineUtenteRepository;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci\JwtLcobucciSigner;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\Jwt\Lcobucci\JwtLcobucciUtenteAuthenticator;

class UtentePrivatoControllerTest extends WebTestCase
{
    /** @var Client */
    private $webClient;

    /** @var UtenteRepository */
    private $utenteRepository;

    /** @var EntityManager */
    protected $em;

    protected function setUp()
    {
        parent::setUp();

        self::$kernel = self::bootKernel();

        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();

        (new ORMPurger($this->em))->purge();

        $this->webClient = static::createClient();
        $this->utenteRepository = new DummyDoctrineUtenteRepository($this->em, $this->em->getClassMetadata(Utente::class));
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function get_show_utente()
    {
        $utente = DoctrineUtenteBuilder::anUtente()
            ->withEnabled(true)
            ->withEmail('email@dominio.it')
            ->withPassword('password')
            ->withLocked(false)
            ->build();

        $this->utenteRepository->add($utente);

        $authenticator = new JwtLcobucciUtenteAuthenticator(
            self::$kernel->getContainer()->getParameter('auth_expiration'),
            new JwtLcobucciSigner(
                self::$kernel->getContainer()->getParameter('secret')
            )
        );

        $token = (string)$authenticator->generateAuthToken($utente);

        $utenteId = $utente->id()->id();

        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer $token",
            'content_type' => 'application/json',
        ];

        $this->webClient->request('GET', "/v1/utente/{$utenteId}", [], [], $headers);

        $this->assertEquals(200, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertInternalType('array', $content);
        $this->assertCount(6, $content);

        $this->assertArrayHasKey('email', $content);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('ruolo', $content);
        $this->assertArrayHasKey('enabled', $content);
        $this->assertArrayHasKey('locked', $content);
        $this->assertArrayHasKey('competenze', $content);

    }
}
