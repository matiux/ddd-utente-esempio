<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Console\Utente;

use DDDStarterPack\Application\Service\ApplicationService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use UtenteDDDExample\Application\Service\Utente\RegisterUtenteRequest;
use UtenteDDDExample\Application\Service\Utente\RegisterUtenteService;

class CreateUtenteCommand extends ContainerAwareCommand
{
    /** @var RegisterUtenteService */
    private $createUtenteService;

    public function __construct(ApplicationService $createUtenteService)
    {
        parent::__construct(null);

        $this->createUtenteService = $createUtenteService;
    }

    protected function configure()
    {
        $this
            ->setName('dddapp:create:utente')
            ->addArgument('email', InputArgument::REQUIRED, 'Login email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
            ->addOption('ruolo', 'r', InputOption::VALUE_OPTIONAL, 'Ruolo', 'admin')
            ->addOption('abilitato', 'a', InputOption::VALUE_OPTIONAL, 'Utente abilitato', 'true')
            ->addOption('competenze', 'c', InputOption::VALUE_OPTIONAL, 'Competenze', '')
            ->setDescription('Crea un nuovo utente');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $ruolo = $input->getOption('ruolo');

        $competenze = explode(',', $input->getOption('competenze'));
        $enabled = $input->getOption('abilitato') === 'true' ? true : false;

        $this->createUtenteService->execute(
            new RegisterUtenteRequest(
                $email,
                $password,
                $competenze,
                $ruolo,
                $enabled
            )
        );

        $output->write('Utente creato correttamente');
    }
}
