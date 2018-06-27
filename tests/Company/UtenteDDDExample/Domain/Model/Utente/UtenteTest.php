<?php

namespace Tests\Domain\Model\Utente;

use PHPUnit\Framework\TestCase;
use UtenteDDDExample\Domain\Model\Utente\Password\HashedPasswordUtente;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;

class UtenteTest extends TestCase
{
    /**
     * @test
     * @group utente
     */
    public function utente_can_be_created_with_competenze()
    {
        $utente = Utente::create(
            UtenteId::create(),
            'utente@email.it',
            new HashedPasswordUtente('$password'),
            'user',
            [
                'Raccogliere le foglie',
                'Pettinare le bambole'
            ]
        );

        $this->assertCount(2, $utente->competenze());
    }
}
