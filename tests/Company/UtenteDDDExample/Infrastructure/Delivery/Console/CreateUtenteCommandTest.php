<?php

namespace Tests\Infrastructure\Delivery\Console;

use Symfony\Component\Console\Tester\CommandTester;
use Tests\Support\DoctrineSupportKernelTestCase;
use Tests\Support\Repository\Doctrine\Real\RealDoctrineRepository;
use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Ruolo;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class CreateUtenteCommandTest extends DoctrineSupportKernelTestCase
{
    use RealDoctrineRepository;

    /** @var UtenteRepository */
    private $utenteRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->utenteRepository = $this->realDoctrineUtenteRepository();
    }

    /**
     *
     * @test
     * @group integration
     * @group utente
     */
    public function it()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('dddapp:create:utente');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'email' => 'utente@dominio.it',
            'password' => 'psw'
        ]);

        $output = $commandTester->getDisplay();

        $this->assertEquals('Utente creato correttamente', $output);

        $utente = $this->utenteRepository->byEmail(new EmailUtente('utente@dominio.it'));

        $this->assertInstanceOf(Utente::class, $utente);
        $this->assertTrue($utente->isEnabled());
        $this->assertEquals(Ruolo::ROLE_ADMIN, (string)$utente->ruolo());
    }
}
