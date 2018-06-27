<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Http\Controller\Utente;

use DDDStarterPack\Application\Exception\ApplicationException;
use DDDStarterPack\Application\Service\ApplicationService;
use DDDStarterPack\Domain\Model\Exception\DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class UtenteController extends Controller
{
    protected function executeService(ApplicationService $service, $request): JsonResponse
    {
        try {

            $serviceResponse = $service->execute($request);

            $response = $this->prepareResponse($serviceResponse);

            $response = new JsonResponse($response, 200, [], true);

            return $response;

        } catch (DomainException | ApplicationException $e) {

            return new JsonResponse(json_encode(['message' => $e->getMessage()]), $e->getCode(), [], true);

        } catch (\Exception $exception) {

            throw $exception;
        }
    }

    private function prepareResponse($serviceResponse): string
    {
        if (is_array($serviceResponse)) {
            $response = $serviceResponse;
        }

        if (is_bool($serviceResponse)) {
            $response = ['status' => $serviceResponse];
        }

        return json_encode($response);
    }
}
