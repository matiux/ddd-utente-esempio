<?php

namespace UtenteDDDExample\Infrastructure\Domain\Service\Utente\Jwt\Lcobucci;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Service\Utente\UtenteAuthenticator;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\JwtAuthTokenPayload;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\JwtAuthTokenPayloadBuilder;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci\JwtLcobucciAuthToken;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;

class JwtLcobucciUtenteAuthenticator extends UtenteAuthenticator
{
    public function generateAuthToken(Utente $utente): AuthToken
    {
        $authTokenPayload = $this->buildJwtAuthTokenPayload($utente);

        $builder = (new Builder())
            ->setId($authTokenPayload->JWTId())
            ->setIssuedAt($authTokenPayload->issuedAt())
            //->setNotBefore(time() + 60)
            ->setExpiration($authTokenPayload->expiration())
            //->set('name', $utente->email())// Configures a new claim, called "uid"
            ->setSubject($authTokenPayload->subject())
            ->sign($this->authTokenSigner->signer(), $authTokenPayload->secureKey());

        $token = $builder->getToken();

        return new JwtLcobucciAuthToken($token);
    }

    private function buildJwtAuthTokenPayload(Utente $utente): JwtAuthTokenPayload
    {
        $builder = JwtAuthTokenPayloadBuilder::aJwtAuthTokenPayload();
        $builder->withSubject($utente->id()->id())
            ->withExpiration($this->calculateAuthenticationExpireTime())
            ->withSecureKey($this->authTokenSigner->secret());

        $authTokenPayload = $builder->build();

        return $authTokenPayload;
    }

    public function authTokenFromString(string $autjToken): AuthToken
    {
        $token = (new Parser())->parse($autjToken);

        return new JwtLcobucciAuthToken($token);
    }
}
