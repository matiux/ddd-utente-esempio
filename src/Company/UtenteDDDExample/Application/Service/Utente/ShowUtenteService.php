<?php

namespace UtenteDDDExample\Application\Service\Utente;

use DDDStarterPack\Application\Service\ApplicationService;
use UtenteDDDExample\Application\DataTransformer\Utente\UtenteArrayDataTransformer;
use UtenteDDDExample\Domain\Model\Utente\Exception\UtenteNotFoundException;
use UtenteDDDExample\Domain\Model\Utente\Utente;
use UtenteDDDExample\Domain\Model\Utente\UtenteId;
use UtenteDDDExample\Domain\Model\Utente\UtenteRepository;

class ShowUtenteService implements ApplicationService
{
    private $utenteRepository;
    private $utenteArrayDataTransformer;

    public function __construct(UtenteRepository $utenteRepository, UtenteArrayDataTransformer $utenteArrayDataTransformer)
    {
        $this->utenteRepository = $utenteRepository;
        $this->utenteArrayDataTransformer = $utenteArrayDataTransformer;
    }

    public function execute($request = null)
    {
        $utente = $this->doExecute($request);

        $utente = $this->utenteArrayDataTransformer->write($utente)->read();

        return $utente;
    }

    private function doExecute(ShowUtenteRequest $request): Utente
    {
        $utenteId = $request->getUtenteId();

        $utente = $this->utenteRepository->ofId(UtenteId::create($utenteId));

        if (!$utente) {
            throw new UtenteNotFoundException(sprintf('Utente non trovato [%s]', $utenteId));
        }

        return $utente;
    }
}
