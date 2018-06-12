<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenSigner;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtLcobucciSigner implements AuthTokenSigner
{
    private $applicationSecure;

    public function __construct(string $applicationSecure)
    {
        $this->applicationSecure = $applicationSecure;
    }

    public function signer()
    {
        return new Sha256();
    }

    public function secret(): string
    {
        return $this->applicationSecure;
    }
}
