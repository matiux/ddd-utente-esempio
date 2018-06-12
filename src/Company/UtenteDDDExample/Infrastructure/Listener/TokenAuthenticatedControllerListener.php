<?php

namespace UtenteDDDExample\Infrastructure\Listener;

use UtenteDDDExample\Domain\Service\Utente\IsUtenteAuthenticated;
use UtenteDDDExample\Infrastructure\Delivery\Http\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticatedControllerListener
{
    private $isUtenteAuthenticated;

    public function __construct(IsUtenteAuthenticated $isUtenteAuthenticated)
    {
        $this->isUtenteAuthenticated = $isUtenteAuthenticated;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedController) {

            $request = $event->getRequest();

            $isAuth = $this->isUtenteAuthenticated->verifyAuthentication($request);

            if (!$isAuth) {

                throw new UnauthorizedHttpException(
                    'unauthorized',
                    'Not authorized',
                    null,
                    Response::HTTP_UNAUTHORIZED
                );
            }
        }
    }
}
