<?php

namespace UtenteDDDExample\Application\Service\Utente;

use DDDStarterPack\Application\Service\ApplicationService;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteArrayDataTransformer;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Service\Utente\RegisterUtente;

class RegisterUtenteService implements ApplicationService
{
    private $registerUtente;
    private $utenteArrayDataTransformer;

    public function __construct(RegisterUtente $registerUtente, UtenteArrayDataTransformer $utenteArrayDataTransformer)
    {
        $this->registerUtente = $registerUtente;
        $this->utenteArrayDataTransformer = $utenteArrayDataTransformer;
    }

    public function execute($request = null)
    {
        $utente = $this->doExecute($request);

        return $this->utenteArrayDataTransformer->write($utente)->read();
    }

    private function doExecute(RegisterUtenteRequest $request): Utente
    {
        $email = $request->getEmail();
        $password = $request->getPassword();
        $competenze = $request->getCompetenze();
        $ruolo = $request->getRuolo();
        $enabled = $request->getEnabled();

        $utente = $this->registerUtente->create($email, $password, $competenze, $ruolo, $enabled);

        return $utente;
    }
}
