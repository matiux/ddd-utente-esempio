<?php

namespace UtenteDDDExample;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use UtenteDDDExample\Symfony\RegisterServicesPass;

class UtenteDDDExample extends Bundle
{
    public function build(ContainerBuilder $containerBuilder)
    {
        $this->manageAuthTokenFinder($containerBuilder);
    }

    private function manageAuthTokenFinder(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addCompilerPass(
            new RegisterServicesPass(
                'dddapp.auth_token_finder',
                'dddapp.specific_auth_token_finder',
                'addSpecificAuthTokenFinder'
            )
        );
    }
}
