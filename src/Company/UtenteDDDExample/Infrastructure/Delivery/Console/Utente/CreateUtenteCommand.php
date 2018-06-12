<?php

namespace UtenteDDDExample\Infrastructure\Delivery\Console\Utente;

use DDDStarterPack\Application\Service\ApplicationService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use UtenteDDDExample\Application\Service\Utente\CreateUtenteRequest;
use UtenteDDDExample\Application\Service\Utente\CreateUtenteService;

class CreateUtenteCommand extends ContainerAwareCommand
{
    /** @var CreateUtenteService */
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
            ->setDescription('Crea un nuovo utente');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $ruolo = $input->getOption('ruolo');

        $this->createUtenteService->execute(new CreateUtenteRequest($email, $password, $ruolo, true));

        $output->write('Utente creato correttamente');
    }
}
