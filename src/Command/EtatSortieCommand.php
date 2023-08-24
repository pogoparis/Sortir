<?php

namespace App\Command;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'EtatSortie',
    description: 'Modif des etats en fonction',
)]
class EtatSortieCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private SortieRepository $sortieRepository;
    private EtatRepository $etatRepository;


    public function __construct(EntityManagerInterface $entityManager, SortieRepository $sortieRepository, EtatRepository $etatRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->sortieRepository = $sortieRepository;
        $this->etatRepository = $etatRepository;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sortiesPassees = $this->sortieRepository->findPassedSorties();
        $sortiesCloturees = $this->sortieRepository->findCloturedSorties();
        $sortiesEnCours = $this->sortieRepository->findEncoursSorties();
        $etatPassee = $this->etatRepository->find(5);
        $etatEnCours = $this->etatRepository->find(4);
        $etatCloture = $this->etatRepository->find(3);

        foreach ($sortiesCloturees as $sortie) {
            $sortie->setEtat($etatCloture);
        }

        foreach ($sortiesEnCours as $sortie) {
            $sortie->setEtat($etatEnCours);
        }

        foreach ($sortiesPassees as $sortie) {
            $sortie->setEtat($etatPassee);
        }


        $this->entityManager->flush();

        $io->success('L\Etat des sorties est mis à jour');

        return Command::SUCCESS;
    }
}