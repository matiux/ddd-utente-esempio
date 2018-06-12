<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Http\Controller\Utente;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UtenteDDDExample\Application\Service\Utente\CreateUtenteRequest;
use UtenteDDDExample\Application\Service\Utente\ShowUtenteRequest;
use UtenteDDDExample\Domain\Model\Utente\Exception\UtenteNotFoundException;
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

    public function getShowUtente(string $utenteId)
    {
        $service = $this->get('dddapp.show_utente.service');

        try {

            $utente = $service->execute(new ShowUtenteRequest($utenteId));

            $response = new JsonResponse(json_encode($utente), 200, [], true);

            return $response;

        } catch (UtenteNotFoundException $e) {

            return new JsonResponse(json_encode(['message' => $e->getMessage()]), $e->getCode(), [], true);

        } catch (\Exception $exception) {

            throw $exception;
        }
    }
}
