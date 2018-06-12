<?php

namespace UtenteDDDExample\Domain\Model\Utente\AuthToken;

interface AuthToken
{
    public function payload(): AuthTokenPayload;

    public function validate(): bool;

    public function verify(AuthTokenSigner $authTokenSigner): bool;
}
