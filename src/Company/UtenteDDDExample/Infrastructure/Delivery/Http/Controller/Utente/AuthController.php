<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Http\Controller\Utente;

use DDDStarterPack\Domain\Model\Exception\DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UtenteDDDExample\Application\Service\Utente\CreateUtenteRequest;
use UtenteDDDExample\Application\Service\Utente\LoginUtenteRequest;

class AuthController extends Controller
{
    use CreateUtente;

    public function postSignupUtente(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $email = $content['email'];
        $password = $content['password'];

        $serviceRequest = new CreateUtenteRequest($email, $password);

        return $this->createUtente($serviceRequest);
    }

    public function postLoginUtente(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $email = $content['email'];
        $password = $content['password'];

        $service = $this->get('dddapp.login_utente.service');

        try {
            $utenteAutenticato = $service->execute(new LoginUtenteRequest($email, $password));

            $response = new JsonResponse(json_encode($utenteAutenticato), 200, [], true);

            return $response;

        } catch (DomainException  $e) {

            return new JsonResponse(json_encode(['message' => $e->getMessage()]), $e->getCode(), [], true);

        } catch (\Exception $e) {

            return new JsonResponse(json_encode(['message' => $e->getMessage()]), 500, [], true);
        }
    }
}
