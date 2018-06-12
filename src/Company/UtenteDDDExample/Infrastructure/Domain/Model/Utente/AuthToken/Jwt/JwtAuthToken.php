<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthToken;

abstract class JwtAuthToken implements AuthToken
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    abstract public function subject(): string;

    abstract public function __toString(): string;

    abstract public function expiration(): int;

    abstract public function notBefore(): ?int;

    abstract public function JWTId(): string;

    abstract public function issuedAt(): int;
}
