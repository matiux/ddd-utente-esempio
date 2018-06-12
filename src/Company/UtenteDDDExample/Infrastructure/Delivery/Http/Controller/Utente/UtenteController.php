<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Http\Controller\Utente;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UtenteDDDExample\Application\Service\Utente\CreateUtenteRequest;
use UtenteDDDExample\Infrastructure\Delivery\Http\Controller\TokenAuthenticatedController;

class UtenteController extends Controller implements TokenAuthenticatedController
{
    use CreateUtente;

    public function postCreaUtente(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $email = $content['email'];
        $password = $content['password'];
        $ruolo = $content['ruolo'];
        $enabled = $content['enabled'];

        $serviceRequest = new CreateUtenteRequest($email, $password, $ruolo, $enabled);

        return $this->createUtente($serviceRequest);
    }
}
