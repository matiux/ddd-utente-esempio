<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\Lcobucci;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenSigner;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\JwtAuthToken;
use UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt\JwtAuthTokenPayload;
use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenPayload;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

class JwtLcobucciAuthToken extends JwtAuthToken
{
    public function __construct(Token $token)
    {
        parent::__construct($token);

        $this->token = $token;
    }

    public function subject(): string
    {
        return $this->token->getClaim('sub');
    }

    public function __toString(): string
    {
        return (string)$this->token;
    }

    public function payload(): AuthTokenPayload
    {
        $payload = JwtAuthTokenPayload::fromToken($this);

        return $payload;
    }

    public function expiration(): int
    {
        return $this->token->getClaim('exp');
    }

    public function notBefore(): ?int
    {
        if ($this->token->hasClaim('nbf')) {
            return $this->token->getClaim('nbf');
        }

        return null;
    }

    public function JWTId(): string
    {
        return $this->token->getClaim('jti');
    }

    public function issuedAt(): int
    {
        return $this->token->getClaim('iat');
    }


    public function validate(): bool
    {
        $data = new ValidationData();

        $valid = $this->token->validate($data);

        return $valid;
    }

    public function verify(AuthTokenSigner $authTokenSigner): bool
    {
        $verified = $this->token->verify(
            $authTokenSigner->signer(),
            $authTokenSigner->secret()
        );

        return $verified;
    }
}
