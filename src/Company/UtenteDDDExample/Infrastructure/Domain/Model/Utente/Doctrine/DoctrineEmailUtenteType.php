<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model\Utente\Doctrine;

use UtenteDDDExample\Domain\Model\Utente\EmailUtente;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DoctrineEmailUtenteType extends Type
{
    const MYTYPE = 'EmailUtente';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new EmailUtente($value);
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
