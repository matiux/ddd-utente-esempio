<?php

namespace UtenteDDDExample\Domain\Model\Utente\AuthToken;

interface AuthTokenSigner
{
    public function signer();

    public function secret(): string;
}
