<?php

namespace UtenteDDDExample\Application\DataTransformer\Utente;

use UtenteDDDExample\Domain\Model\Utente\UtenteAutenticato;

abstract class UtenteAutenticatoArrayDataTransformer
{
    /** @var UtenteAutenticato */
    protected $utenteAutenticato;

    public function write(UtenteAutenticato $utenteAutenticato): UtenteAutenticatoArrayDataTransformer
    {
        $this->utenteAutenticato = $utenteAutenticato;

        return $this;
    }

    public function read(): array
    {
        $data = [
            'utente' => (new UtenteArrayDataTransformer())->write($this->utenteAutenticato->utente())->read(),
            'token' => (string)$this->utenteAutenticato->token(),
        ];

        $providerData = $this->providerRead();

        return array_merge($data, $providerData);
    }

    abstract public function providerRead(): array;
}
