<?php

namespace UtenteDDDExample\Application\Service\Utente;

use DDDStarterPack\Application\Service\ApplicationService;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteArrayDataTransformer;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Service\Utente\SignUpUtente;

class SignUpUtenteService implements ApplicationService
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

    private function doExecute(SignUpUtenteRequest $request): Utente
    {
        $email = $request->getEmail();
        $password = $request->getPassword();
        $competenze = $request->getCompetenze();

        /**
         * Qui si potrebbe fare una validazione preliminare sulla richiesta
         */

        $utente = $this->signUpUtente->create($email, $password, $competenze);

        return $utente;
    }
}
