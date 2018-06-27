<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Http\Controller\Utente;

use UtenteDDDExample\Application\Service\Utente\EnableUtenteByIdRequest;
use UtenteDDDExample\Application\Service\Utente\ShowUtenteRequest;
use UtenteDDDExample\Infrastructure\Delivery\Http\Controller\TokenAuthenticatedController;

class UtentePrivatoController extends UtenteController implements TokenAuthenticatedController
{
    public function getShowUtente(string $utenteId)
    {
        $service = $this->get('dddapp.show_utente.service');

        $request = new ShowUtenteRequest($utenteId);

        return $this->executeService($service, $request);
    }

    public function patchEnableUtente($utenteId)
    {
        $service = $this->get('dddapp.transactional.enable_utente.service');

        $request = new EnableUtenteByIdRequest($utenteId);

        return $this->executeService($service, $request);
    }
}
