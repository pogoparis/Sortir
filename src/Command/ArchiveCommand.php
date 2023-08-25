<?php

namespace App\Command;

use App\Entity\SortiesArchivees;
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
    name: 'archive',
    description: 'Archive sorties de plus d\'un mois',
)]
class ArchiveCommand extends Command
{

    private EntityManagerInterface $entityManager;
    private SortieRepository $sortieRepository;

    public function __construct(EntityManagerInterface $entityManager, SortieRepository $sortieRepository)
    {

        parent::__construct();
        $this->entityManager = $entityManager;
        $this->sortieRepository = $sortieRepository;
    }
    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $oneMonthAgo = new \DateTime('-1 month');
        $oldSorties = $this->sortieRepository->findSortiesOlderThan($oneMonthAgo);

        foreach ($oldSorties as $sortie) {
            $archivedSortie = new SortiesArchivees();
            $archivedSortie->setNom($sortie->getNom());
            $archivedSortie->setDateHeureDebut($sortie->getDateHeureDebut());
            $archivedSortie->setDateHeureFin($sortie->getDateHeureFin());
            $archivedSortie->setNbInscriptionsMax($sortie->getNbInscriptionsMax());
            $archivedSortie->setDateLimiteInscription($sortie->getDateLimiteInscription());
            $archivedSortie->setInfosSortie($sortie->getInfosSortie());
            $archivedSortie->setOrganisateurId($sortie->getOrganisateur());
            $archivedSortie->setSiteId($sortie->getSite());
            $archivedSortie->setLieuId($sortie->getLieu());
            $archivedSortie->setEtatId($sortie->getEtat());

            $particpantSortieCollection = $sortie->getParticipants();
                foreach ($particpantSortieCollection as $participant){
                    $archivedSortie->addUserId($participant);
                }


            $this->entityManager->persist($archivedSortie);
            $this->entityManager->remove($sortie);
        }

        $this->entityManager->flush();

        $io->success('Sorties Archivées avec succès');


        return Command::SUCCESS;
    }



}
