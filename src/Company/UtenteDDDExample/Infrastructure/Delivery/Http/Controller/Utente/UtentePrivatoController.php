<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Http\Controller\Utente;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use UtenteDDDExample\Application\Service\Utente\ShowUtenteRequest;
use UtenteDDDExample\Domain\Model\Utente\Exception\UtenteNotFoundException;
use UtenteDDDExample\Infrastructure\Delivery\Http\Controller\TokenAuthenticatedController;

class UtentePrivatoController extends Controller implements TokenAuthenticatedController
{
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
