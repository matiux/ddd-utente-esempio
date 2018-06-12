<?php

namespace Tests\Domain\Model\Utente\Specification;

use PHPUnit\Framework\TestCase;
use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use UtenteDDDExample\Domain\Model\Utente\Specification\EmailUtenteIsUnique;
use UtenteDDDExample\Domain\Model\Utente\Specification\EmailUtenteSpecification;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

class EmailUtenteIsUniqueTest extends TestCase
{
    /** @var UtenteRepository */
    private $utenteRepository;

    /** @var EmailUtenteSpecification */
    private $spec;

    protected function setUp()
    {
        parent::setUp();

        $this->utenteRepository = \Mockery::mock(UtenteRepository::class);
        $this->spec = new EmailUtenteIsUnique($this->utenteRepository);
    }

    /**
     * @test
     * @group utente
     */
    public function should_return_true_when_unique()
    {
        $this->utenteRepository->shouldReceive('byEmail')->andReturn(null);

        $this->assertTrue($this->spec->isSatisfiedBy(new EmailUtente('user@dominio.it')));
    }

    /**
     * @test
     * @group utente
     */
    public function should_return_false_when_not_unique()
    {
        $user = \Mockery::mock(Utente::class);

        $this->utenteRepository->shouldReceive('byEmail')->andReturn($user);
        $this->assertFalse($this->spec->isSatisfiedBy(new EmailUtente('user@dominio.it')));
    }
}
