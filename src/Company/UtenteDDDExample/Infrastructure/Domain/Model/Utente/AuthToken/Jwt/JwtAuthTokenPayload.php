<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\AuthToken\Jwt;

use UtenteDDDExample\Domain\Model\Utente\AuthToken\AuthTokenPayload;
use Ramsey\Uuid\Uuid;
use ReflectionClass;

class JwtAuthTokenPayload implements AuthTokenPayload
{
    private $exp; // Expiration - Required - https://tools.ietf.org/html/rfc7519#section-4.1.4

    private $sub; // Subject - Optional - https://tools.ietf.org/html/rfc7519#section-4.1.2
//    private $iss; // Issuer - Optional - https://tools.ietf.org/html/rfc7519#section-4.1.1
//    private $aud; // Audience - Optional - https://tools.ietf.org/html/rfc7519#section-4.1.3
    private $jti; //jti - Optional - https://tools.ietf.org/html/rfc7519#section-4.1.7
    private $iat; // Issued at - Optional - https://tools.ietf.org/html/rfc7519#section-4.1.6
    private $nbf; // Not before - Optional - https://tools.ietf.org/html/rfc7519#section-4.1.5

    private $secureKey;

    public function __construct(int $exp, string $sub, string $securekey, ?int $nbf = null)
    {
        $this->exp = $exp;
        $this->sub = $sub;
        $this->nbf = $nbf;

        $this->jti = Uuid::uuid4()->toString();
        $this->iat = time();

        $this->secureKey = $securekey;
    }

    public function expiration(): int
    {
        return $this->exp;
    }

    public function subject(): string
    {
        return $this->sub;
    }

    public function JWTId(): string
    {
        return $this->jti;
    }

    public function issuedAt(): int
    {
        return $this->iat;
    }

    public function notBefore(): int
    {
        return $this->iat;
    }

    public function secureKey(): string
    {
        return $this->secureKey;
    }

    public function data(): array
    {
        return [
            'exp' => $this->exp,
            'sub' => $this->sub,
            'jti' => $this->jti,
            'iat' => $this->iat,
            'nbf' => $this->nbf
        ];
    }

    public static function fromToken(JwtAuthToken $token): self
    {
        $payload = new static(
            $token->expiration(),
            $token->subject(),
            '',
            $token->notBefore()
        );

        $class = new ReflectionClass(static::class);

        $reflectionProperty = $class->getProperty('jti');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($payload, $token->JWTId());

        $reflectionProperty = $class->getProperty('iat');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($payload, $token->issuedAt());

        return $payload;
    }
}
