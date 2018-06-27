<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Http\Controller\Utente;

use Symfony\Component\HttpFoundation\Request;
use UtenteDDDExample\Application\Service\Utente\SignUpUtenteRequest;

class UtentePubblicoController extends UtenteController
{
    public function postSignupUtente(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $email = $content['email'];
        $password = $content['password'];
        $competenze = $content['competenze'];

        $serviceRequest = new SignUpUtenteRequest($email, $password, $competenze);

        $service = $this->get('dddapp.transactional.sign_up_utente.service');

        return $this->executeService($service, $serviceRequest);

    }
}
