<?php

namespace UtenteDDDExample\Domain\Model\Utente\Exception;

use DDDStarterPack\Domain\Model\Exception\DomainAuthException;

class UtenteNotAuthorizedException extends DomainAuthException
{
    const MESSAGE = "Utente cannot perform this action";
}
