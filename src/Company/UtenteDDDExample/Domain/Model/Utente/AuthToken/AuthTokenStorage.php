<?php

namespace UtenteDDDExample\Domain\Model\Utente\AuthToken;

interface AuthTokenStorage
{
    public function getToken(): AuthToken;

    public function setToken(AuthToken $authToken): void;
}
