<?php

namespace UtenteDDDExample\Symfony;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterServicesPass implements CompilerPassInterface
{
    private $registryId;
    private $serviceTag;
    private $adder;
    private $properties;

    public function __construct($registryId, $serviceTag, $adder = 'add', array $properties = null)
    {
        if (is_null($properties)) {
            $properties = [];
        }

        $this->registryId = $registryId;
        $this->serviceTag = $serviceTag;
        $this->adder = $adder;
        $this->properties = $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $container->getDefinition($this->registryId);
        $services = $container->findTaggedServiceIds($this->serviceTag);

        foreach ($services as $serviceId => $data) {
            $arguments = [new Reference($serviceId)];
            foreach ($this->properties as $property => $default) {
                $argument = $default;
                if (array_key_exists($property, $data[0])) {
                    $argument = $data[0][$property];
                };
                $arguments[] = $argument;
            }
            $registry->addMethodCall($this->adder, $arguments);
        }
    }
}
