<?php

namespace UtenteDDDExample\Application\Service\Utente;

use DDDStarterPack\Application\Service\ApplicationService;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteArrayDataTransformer;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Service\Utente\SignUpUtente;

class CreateUtenteService implements ApplicationService
{
    private $signUpUtente;
    private $utenteArrayDataTransformer;

    public function __construct(SignUpUtente $signUpUtente, UtenteArrayDataTransformer $utenteArrayDataTransformer)
    {
        $this->signUpUtente = $signUpUtente;
        $this->utenteArrayDataTransformer = $utenteArrayDataTransformer;
    }

    public function execute($request = null)
    {
        $utente = $this->doExecute($request);

        return $this->utenteArrayDataTransformer->write($utente)->read();
    }

    private function doExecute(CreateUtenteRequest $request): Utente
    {
        $email = $request->getEmail();
        $password = $request->getPassword();
        $ruolo = $request->getRuolo();
        $enabled = $request->isEnabled();

        $utente = $this->signUpUtente->registra($email, $password, $ruolo);

        !$enabled ?: $utente->enable();

        return $utente;
    }
}
