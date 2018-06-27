<?php

namespace UtenteDDDExample\Domain\Model\Utente;

class Competenza
{
    private $competenzaId;
    private $name;
    private $utenteId;

    public function __construct(CompetenzaId $competenzaId, string $name, UtenteId $utenteId)
    {
        $this->competenzaId = $competenzaId;
        $this->name = $name;
        $this->utenteId = $utenteId;
    }

    public function array(): array
    {
        return [
            'id' => $this->competenzaId->id(),
            'name' => $this->name
        ];
    }
}
