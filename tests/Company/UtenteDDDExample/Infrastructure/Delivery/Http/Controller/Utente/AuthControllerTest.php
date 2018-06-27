<?php

namespace Tests\Infrastructure\Delivery\Http\Controller\Utente;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;
use Tests\Support\Builder\Doctrine\DoctrineUtenteBuilder;
use Tests\Support\Repository\Doctrine\Dummy\DummyDoctrineUtenteRepository;
use UtenteDDDExample\Domain\Model\Utente\Password\PasswordHashing;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\BasicPasswordHashing;


class AuthControllerTest extends WebTestCase
{
    /** @var Client */
    private $webClient;

    /** @var UtenteRepository */
    private $utenteRepository;

    /** @var EntityManager */
    private $em;

    /** @var PasswordHashing */
    private $passwordHashing;

    protected function setUp()
    {
        parent::setUp();

        self::$kernel = self::bootKernel();

        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();

        (new ORMPurger($this->em))->purge();

        $this->webClient = static::createClient();
        $this->utenteRepository = new DummyDoctrineUtenteRepository($this->em, $this->em->getClassMetadata(Utente::class));

        $this->passwordHashing = new BasicPasswordHashing();
    }

    /**
     * @test
     * @group integration
     * @group utente
     */
    public function post_login_not_existing_email()
    {
        $post = [
            'email' => 'utente@dominio',
            'password' => 'psw',
        ];

        $this->webClient->request('POST', '/v1/login', [], [], ['content_type' => 'application / json'], json_encode($post));

        $this->assertEquals(412, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertInternalType('array', $content);
        $this->assertCount(1, $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertEquals('Email non vadida [utente@dominio]', $content['message']);

    }

    /**
     * @test
     * @group integration
     * @group utente
     */
    public function post_login_invalid_password()
    {
        $utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword('secure_psw')
            ->withEmail('user@dominio.it')
            ->withEnabled(true)
            ->build();

        $this->utenteRepository->add($utente);

        $post = [
            'email' => 'user@dominio.it',
            'password' => 'password_sbagliata',
        ];

        $this->webClient->request('POST', '/v1/login', [], [], ['content_type' => 'application / json'], json_encode($post));

        $this->assertEquals(401, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertInternalType('array', $content);
        $this->assertCount(1, $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertEquals('Password non valida', $content['message']);
    }

    /**
     * @test
     * @group integration
     * @group utente
     */
    public function post_login_not_enabled_utente()
    {
        $utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword('secure_psw')
            ->withEmail('user@dominio.it')
            ->withEnabled(false)
            ->build();

        $this->utenteRepository->add($utente);

        $post = [
            'email' => 'user@dominio.it',
            'password' => 'secure_psw',
        ];

        $this->webClient->request('POST', '/v1/login', [], [], ['content_type' => 'application / json'], json_encode($post));

        $this->assertEquals(401, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertInternalType('array', $content);
        $this->assertCount(1, $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertEquals('Utente non abilitato', $content['message']);
    }

    /**
     * @test
     * @group integration
     * @group utente
     */
    public function post_login_locked_utente()
    {
        $utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword('secure_psw')
            ->withEmail('user@dominio.it')
            ->withEnabled(true)
            ->withLocked(true)
            ->build();

        $this->utenteRepository->add($utente);

        $post = [
            'email' => 'user@dominio.it',
            'password' => 'secure_psw',
        ];

        $this->webClient->request('POST', '/v1/login', [], [], ['content_type' => 'application / json'], json_encode($post));

        $this->assertEquals(401, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertInternalType('array', $content);
        $this->assertCount(1, $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertEquals('Utente bloccato', $content['message']);
    }

    /**
     * @test
     * @group integration
     * @group utente
     */
    public function post_login()
    {
        $utente = DoctrineUtenteBuilder::anUtente()
            ->withPassword('secure_psw')
            ->withEmail('user@dominio.it')
            ->build();

        $this->utenteRepository->add($utente);

        $post = [
            'email' => 'user@dominio.it',
            'password' => 'secure_psw',
        ];

        $this->webClient->request('POST', '/v1/login', [], [], ['content_type' => 'application / json'], json_encode($post));

        $this->assertEquals(200, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertInternalType('array', $content);
        $this->assertCount(3, $content);
        $this->assertArrayHasKey('utente', $content);
        $this->assertArrayHasKey('token', $content);
        $this->assertArrayHasKey('token_expire', $content);

        $this->assertInternalType('array', $content['utente']);
        $this->assertCount(6, $content['utente']);
        $this->assertArrayHasKey('ruolo', $content['utente']);

    }
}
