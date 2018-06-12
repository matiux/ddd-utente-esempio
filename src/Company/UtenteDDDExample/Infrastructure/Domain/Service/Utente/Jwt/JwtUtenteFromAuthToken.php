<?php

namespace UtenteDDDExample\Infrastructure\Domain\Service\Utente\Jwt;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;
use UtenteDDDExample\Domain\Service\Utente\UtenteFromAuthToken;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\JwtAuthToken;

class JwtUtenteFromAuthToken extends UtenteFromAuthToken
{
    protected function getUtenteIdFromAuthToken(AuthToken $authToken): ?UtenteId
    {
        if (!$authToken instanceof JwtAuthToken) {
            throw new \InvalidArgumentException('Il token deve essere JWT');
        }

        $utenteId = UtenteId::create($authToken->subject());

        return $utenteId;
    }
}
