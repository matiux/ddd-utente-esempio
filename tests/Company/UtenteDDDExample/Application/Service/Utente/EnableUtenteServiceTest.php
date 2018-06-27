<?php

namespace Tests\Application\Service\Utente;


use DDDStarterPack\Application\Service\TransactionalApplicationService;
use Tests\Support\Builder\Doctrine\DoctrineUtenteBuilder;
use Tests\Support\DoctrineSupportKernelTestCase;
use Tests\Support\Repository\Doctrine\Dummy\DummyDoctrineUtenteRepository;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteArrayDataTransformer;
use UtenteDDDExample\Application\Service\Utente\EnableUtenteByIdRequest;
use UtenteDDDExample\Application\Service\Utente\EnableUtenteByIdService;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;
use UtenteDDDExample\Domain\Service\Utente\CurrentUtenteAutenticato;
use UtenteDDDExample\Domain\Service\Utente\EnableUtente;
use UtenteDDDExample\Infrastructure\Application\Persistence\Doctrine\DoctrineSession;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\AuthTokenStorage\JwtInMemoryAuthTokenStorage;
use UtenteDDDExample\Infrastructure\Domain\Service\Utente\Jwt\JwtUtenteFromAuthToken;

class EnableUtenteServiceTest extends DoctrineSupportKernelTestCase
{
    /** @var UtenteRepository */
    private $utenteRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->utenteRepository = new DummyDoctrineUtenteRepository($this->em, $this->em->getClassMetadata(Utente::class));
    }

    /**
     * @test
     * @group utente
     * @group integration
     */
    public function admin_can_enable_an_utente()
    {
        $utenteAdmin = DoctrineUtenteBuilder::anUtente()->withRuolo('admin')->build();
        $this->utenteRepository->add($utenteAdmin);

        $utenteUser = DoctrineUtenteBuilder::anUtente()->withEnabled(false)->build();
        $this->utenteRepository->add($utenteUser);
        $this->assertFalse($utenteUser->isEnabled());

        $token = self::$kernel->getContainer()->get('dddapp.utente_authenticator.service')->generateAuthToken($utenteAdmin);

        $tokenStorage = new JwtInMemoryAuthTokenStorage();
        $tokenStorage->setToken($token);

        $service = new TransactionalApplicationService(
            new EnableUtenteByIdService(
                new EnableUtente(
                    $this->utenteRepository,
                    new CurrentUtenteAutenticato(
                        new JwtUtenteFromAuthToken($this->utenteRepository),
                        $tokenStorage
                    )
                ),
                new UtenteArrayDataTransformer()
            ),
            new DoctrineSession($this->em)
        );

        $service->execute(new EnableUtenteByIdRequest($utenteUser->id()->id()));

        $utenteEnabled = $this->utenteRepository->ofId($utenteUser->id());

        $this->assertTrue($utenteEnabled->isEnabled());
    }
}

