<?php

namespace UtenteDDDExample\Domain\Service\Utente\AuthToken\AuthTokenFinder;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Service\Utente\UtenteAuthenticator;

abstract class AuthTokenFinder
{
    private $utenteAuthenticator;

    /** @var SpecificAuthTokenFinder[] */
    private $finders = [];

    public function __construct(UtenteAuthenticator $utenteAuthenticator)
    {
        $this->utenteAuthenticator = $utenteAuthenticator;
    }

    public function find($target): ?AuthToken
    {
        $this->isValidTarget($target);

        $token = null;

        foreach ($this->finders as $finder) {
            if (null !== ($token = $finder->find($target))) {
                break;
            }
        }

        if (!$token) {
            return null;
        }

        $token = $this->utenteAuthenticator->authTokenFromString($token);

        return $token;
    }

    public function addSpecificAuthTokenFinder(SpecificAuthTokenFinder $authTokenFinder): void
    {
        $this->finders[spl_object_hash($authTokenFinder)] = $authTokenFinder;
    }

    abstract protected function isValidTarget($target): void;
}
