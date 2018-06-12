<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\AuthTokenStorage;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenStorage;

class JwtSessionAuthTokenStorage implements AuthTokenStorage
{
    private $sessionStarted = false;

    /**
     * The namespace used to store values in the session.
     *
     * @var string
     */
    const SESSION_NAMESPACE = '_auth_token';

    private $namespace;

    public function __construct($namespace = self::SESSION_NAMESPACE)
    {
        $this->namespace = $namespace;
    }

    public function getToken(): AuthToken
    {
        if (!$this->sessionStarted) {
            $this->startSession();
        }

        if (!isset($_SESSION[$this->namespace])) {
            throw new \LogicException('Token is not stored in session');
        }

        return $_SESSION[$this->namespace];
    }

    public function setToken(AuthToken $authToken): void
    {
        if (!$this->sessionStarted) {
            $this->startSession();
        }

        $_SESSION[$this->namespace] = $authToken;
    }


    private function startSession(): void
    {
        if (PHP_SESSION_NONE === session_status()) {
            session_start();
        }

        $this->sessionStarted = true;
    }
}
