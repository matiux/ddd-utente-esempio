<?php

namespace UtenteDDDExample\Application\Service\Utente;

use DDDStarterPack\Application\Service\ApplicationService;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteArrayDataTransformer;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;
use UtenteDDDExample\Domain\Service\Utente\EnableUtente;

class EnableUtenteByIdService implements ApplicationService
{
    private $enableUtente;
    private $utenteArrayDataTransformer;

    public function __construct(EnableUtente $enableUtente, UtenteArrayDataTransformer $utenteArrayDataTransformer)
    {
        $this->enableUtente = $enableUtente;
        $this->utenteArrayDataTransformer = $utenteArrayDataTransformer;
    }

    public function execute($request = null)
    {
        $utente = $this->doExecute($request);

        return $this->utenteArrayDataTransformer->write($utente)->read();
    }

    private function doExecute(EnableUtenteByIdRequest $request): Utente
    {
        $utenteId = $request->getUtenteId();

        $utente = $this->enableUtente->enable(UtenteId::create($utenteId));

        /**
         * TODO Inviare la mail al cliente?
         */

        return $utente;
    }
}
