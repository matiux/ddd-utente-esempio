<?php

namespace UtenteDDDExample\Infrastructure\Application\DataTransformer\Jwt;

use UtenteDDDExample\Application\DataTransformer\Utente\UtenteAutenticatoArrayDataTransformer;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\JwtAuthToken;

class JwtUtenteAutenticatoArrayDataTransformer extends UtenteAutenticatoArrayDataTransformer
{
    public function providerRead(): array
    {
        /** @var JwtAuthToken $token */
        $token = $this->utenteAutenticato->token();

        return [
            'token_expire' => $token->expiration(),
        ];
    }
}
