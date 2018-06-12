<?php

namespace UtenteDDDExample\Domain\Service\Utente\AuthToken\AuthTokenFinder;

interface SpecificAuthTokenFinder
{
    public function find($target): ?string;
}
