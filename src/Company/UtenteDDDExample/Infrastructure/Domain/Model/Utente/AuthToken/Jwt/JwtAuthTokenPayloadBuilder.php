<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt;

class JwtAuthTokenPayloadBuilder
{
    private $exp = null;
    private $sub = null;
    private $nbf = null;
    private $secureKey = null;

    public static function aJwtAuthTokenPayload(): self
    {
        return new static();
    }

    public function withSubject($subject): self
    {
        $this->sub = $subject;

        return $this;
    }

    public function withExpiration(int $expiration): self
    {
        $this->exp = $expiration;

        return $this;
    }

    public function withSecureKey(string $secureKey): self
    {
        $this->secureKey = $secureKey;

        return $this;
    }

    public function build(): JwtAuthTokenPayload
    {
        return new JwtAuthTokenPayload($this->exp, $this->sub, $this->secureKey, $this->nbf);
    }
}
