<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Http\Controller\Utente;

use Symfony\Component\HttpFoundation\JsonResponse;
use UtenteDDDExample\Application\Service\Utente\CreateUtenteRequest;
use UtenteDDDExample\Domain\Model\Utente\Exception\EmailNotValidException;
use UtenteDDDExample\Domain\Model\Utente\Exception\EmailUtenteIsNotUniqueException;

trait CreateUtente
{
    protected function createUtente(CreateUtenteRequest $createUtenteRequest): JsonResponse
    {
        $service = $this->get('dddapp.transactional.create_utente.service');

        try {

            $utente = $service->execute($createUtenteRequest);

            $response = new JsonResponse(json_encode($utente), 200, [], true);

            return $response;

        } catch (EmailUtenteIsNotUniqueException|EmailNotValidException $e) {

            return new JsonResponse(json_encode(['message' => $e->getMessage()]), $e->getCode(), [], true);

        } catch (\Exception $exception) {

            throw $exception;
        }
    }
}
