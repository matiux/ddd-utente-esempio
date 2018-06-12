<?php

namespace UtenteDDDExample\Application\Service\Utente;

use DDDStarterPack\Application\Service\ApplicationService;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteAutenticatoArrayDataTransformer;
use UtenteDDDExample\Domain\Model\Utente\UtenteAutenticato;
use UtenteDDDExample\Domain\Service\Utente\SignInUtente;

class LoginUtenteService implements ApplicationService
{
    private $signInUtente;
    private $dataTransformer;

    public function __construct
    (
        SignInUtente $signInUtente,
        UtenteAutenticatoArrayDataTransformer $utenteAutenticatoArrayDataTransformer
    )
    {
        $this->signInUtente = $signInUtente;
        $this->dataTransformer = $utenteAutenticatoArrayDataTransformer;
    }

    public function execute($request = null)
    {
        $loggedUtente = $this->doExecute($request);

        return $this->dataTransformer->write($loggedUtente)->read();
    }

    private function doExecute(LoginUtenteRequest $request): UtenteAutenticato
    {
        $email = $request->getEmail();
        $password = $request->getPassword();

        $utenteAutenticato = $this->signInUtente->login($email, $password);

        return $utenteAutenticato;
    }
}
