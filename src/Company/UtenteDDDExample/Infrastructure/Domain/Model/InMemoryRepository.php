<?php

namespace UtenteDDDExample\Infrastructure\Domain\Model;

use DDDStarterPack\Domain\Model\EntityId;
use DDDStarterPack\Domain\Model\IdentifiableDomainObject;

abstract class InMemoryRepository
{
    protected $collection = [];

    public function __construct()
    {
        $this->collection = new \ArrayObject();
    }

    protected function inMemoryOfId(EntityId $entityId)
    {
        $f = function (IdentifiableDomainObject $entity, int $index) use ($entityId) {

            return $entity->id()->equals($entityId);
        };

        $entity = array_filter($this->collection->getArrayCopy(), $f, ARRAY_FILTER_USE_BOTH);

        return $entity ? reset($entity) : null;
    }

    protected function inMemoryAdd(IdentifiableDomainObject $entity): IdentifiableDomainObject
    {
        if (!($element = $this->inMemoryOfId($entity->id()))) {

            $this->collection->append($entity);

        } else {

            $element = $entity;
        }

        return $element ?: $entity;
    }

    protected function inMemoryRemove(IdentifiableDomainObject $entity)
    {
        $entityId = $entity->id();

        $f = function (IdentifiableDomainObject $entity) use ($entityId) {

            return !$entity->id()->equals($entityId);
        };

        $this->collection = array_filter($this->collection, $f);
    }
}
