<?php

namespace Tests\Infrastructure\Delivery\Http\Controller\Utente;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;
use Tests\Support\Repository\Doctrine\Dummy\DummyDoctrineUtenteRepository;
use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Ruolo;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

class UtentePubblicoControllerTest extends WebTestCase
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
     * @group integration
     * @group utente
     */
    public function post_signup_utente()
    {
        $post = [
            'email' => 'utente@dominio.it',
            'password' => 'psw',
            'competenze' => [
                'Contare le zampe ai millepiedi'
            ],
        ];

        $this->webClient->request('POST', '/v1/signup', [], [], ['content_type' => 'application/json'], json_encode($post));

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

        $this->assertFalse($content['locked']);
        $this->assertFalse($content['enabled']);
        $this->assertEquals(Ruolo::ROLE_USER, $content['ruolo']);
        $this->assertEquals('utente@dominio.it', $content['email']);

        $utente = $this->utenteRepository->byEmail(new EmailUtente('utente@dominio.it'));
        $this->assertInstanceOf(Utente::class, $utente);
    }

    /**
     * @test
     * @group integration
     * @group utente
     */
    public function post_signup_with_existing_email()
    {
        $post = [
            'email' => 'utente@dominio.it',
            'password' => 'psw',
            'competenze' => []
        ];

        $this->webClient->request('POST', '/v1/signup', [], [], ['content_type' => 'application / json'], json_encode($post));

        $utente = $this->utenteRepository->byEmail(new EmailUtente('utente@dominio.it'));
        $this->assertInstanceOf(Utente::class, $utente);

        $this->webClient->request('POST', '/v1/signup', [], [], ['content_type' => 'application/json'], json_encode($post));

        $this->assertEquals(409, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertCount(1, $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertEquals('Email già presente utente@dominio.it', $content['message']);
    }

    /**
     * @test
     * @group integration
     * @group utente
     */
    public function post_signup_utente_with_invalid_email()
    {
        $post = [
            'email' => 'utente@dominio',
            'password' => 'psw',
            'competenze' => []
        ];

        $this->webClient->request('POST', '/v1/signup', [], [], ['content_type' => 'application / json'], json_encode($post));

        $this->assertEquals(412, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertCount(1, $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertEquals('Email non vadida [utente@dominio]', $content['message']);
    }
}
