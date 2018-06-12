<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\Doctrine;

use UtenteDDDExample\Domain\Model\Utente\Ruolo;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DoctrineRuoloType extends Type
{
    const MYTYPE = 'Ruolo';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Ruolo($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string)$value;
    }

    public function getName()
    {
        return self::MYTYPE;
    }
}
