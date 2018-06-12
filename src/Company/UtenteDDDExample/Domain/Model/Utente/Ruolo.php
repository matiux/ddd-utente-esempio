<?php

namespace UtenteDDDExample\Domain\Model\Utente;

use UtenteDDDExample\Domain\Model\Utente\Exception\RuoloNotValidException;

class Ruolo
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    private $name;

    public function __construct(string $name)
    {
        $const = sprintf('ROLE_%s', strtoupper($name));

        if (!defined("self::$const")) {
            throw new RuoloNotValidException(sprintf('Ruolo non valido [%s]', $name));
        }

        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
