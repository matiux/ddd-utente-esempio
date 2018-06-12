<?php

namespace Tests\Infrastructure\Delivery\Http\Controller\Utente;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;
use Tests\Support\Repository\Doctrine\Real\RealDoctrineRepository;
use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Ruolo;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

class UtenteControllerTest extends WebTestCase
{
    use RealDoctrineRepository;

    /** @var Client */
    private $webClient;

    /** @var UtenteRepository */
    private $utenteRepository;

    protected function setUp()
    {
        parent::setUp();

        self::$kernel = self::bootKernel();

        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        (new ORMPurger($em))->purge();

        $this->webClient = static::createClient();
        $this->utenteRepository = $this->realDoctrineUtenteRepository();
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
        ];

        $this->webClient->request('POST', '/v1/signup', [], [], ['content_type' => 'application/json'], json_encode($post));

        $this->assertEquals(200, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertInternalType('array', $content);
        $this->assertCount(5, $content);

        $this->assertArrayHasKey('email', $content);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('ruolo', $content);
        $this->assertArrayHasKey('enabled', $content);
        $this->assertArrayHasKey('locked', $content);

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
        ];

        $this->webClient->request('POST', '/v1/signup', [], [], ['content_type' => 'application / json'], json_encode($post));

        $this->assertEquals(412, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertCount(1, $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertEquals('Email non vadida [utente@dominio]', $content['message']);
    }

    public function post_crea_nuovo_utente_admin()
    {
        $post = [
            'email' => 'utenteadmin@dominio.it',
            'password' => 'psw',
            'ruolo' => 'admin',
            'enabled' => true,
        ];

        $this->webClient->request('POST', '/v1/signup', [], [], ['content_type' => 'application/json'], json_encode($post));

        $this->assertEquals(200, $this->webClient->getResponse()->getStatusCode());

        $content = json_decode($this->webClient->getResponse()->getContent(), true);

        $this->assertInternalType('array', $content);
        $this->assertCount(5, $content);

        $this->assertArrayHasKey('email', $content);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('ruolo', $content);
        $this->assertArrayHasKey('enabled', $content);
        $this->assertArrayHasKey('locked', $content);

        $this->assertFalse($content['locked']);
        $this->assertTrue($content['enabled']);
        $this->assertEquals(Ruolo::ROLE_ADMIN, $content['ruolo']);
        $this->assertEquals('utenteadmin@dominio.it', $content['email']);

        $utente = $this->utenteRepository->byEmail(new EmailUtente('utenteadmin@dominio.it'));
        $this->assertInstanceOf(Utente::class, $utente);
    }
}
