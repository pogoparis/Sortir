<?php

namespace App\Repository;

use App\Entity\Filtre;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{

    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }


    public function findSearch(Filtre $filtre): array
    {
        $query = $this
            ->createQueryBuilder('p');


        if (!empty($filtre->getNom())) {
            $query = $query
                ->andWhere('p.nom LIKE :nom')
                ->setParameter('nom', "%{$filtre->getNom()}%");
        }

        if ($filtre->getDateMin() !== null) {

            $query = $query
                ->andWhere('p.dateHeureDebut >= :dateMin')
                ->setParameter('dateMin', $filtre->getDateMin());
        }

        if ($filtre->getDateMax() !== null) {
            $query = $query
                ->andWhere('p.dateHeureDebut <= :dateMax')
                ->setParameter('dateMax', $filtre->getDateMax());
        }

        // Filtrer par site
        if ($filtre->getSite() !== null) {
            $query = $query
                ->andWhere('p.site = :site ')
                ->setParameter('site', $filtre->getSite());
        }

        // Requete filtre par organisateur
        if ($filtre->getOrganisateur() !== false) {
            $user = $this->security->getUser();
            $query = $query
                ->andWhere('p.organisateur = :user')
                ->setParameter('user', $user);
        }

        //Filtre sorties passées
        if ($filtre->getSortiesPassees() !== false) {
            $query = $query
                ->andWhere('p.dateHeureFin < :now')
                ->setParameter('now', new \DateTime());
        }


        // Filtre pour les sorties auxquelles l'utilisateur est inscrit
        if ($filtre->getInscrit() !== false) {
            $user = $this->security->getUser();
            $query = $query
                ->leftJoin('p.participants', 'u', Join::WITH, 'u = :user')
                ->setParameter('user', $user)
                ->andWhere('u = :user');
        }

            // Tri par date
            $query = $query
                ->orderBy('p.dateHeureDebut', 'ASC');
        return $query->getQuery()->getResult();
    }


    public function findPassedSorties()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('s')
            ->where('s.dateHeureFin < :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    public function findCloturedSorties()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('s')
            ->where('s.dateLimiteInscription < :now')
            ->setParameter('now',$now)
            ->getQuery()
            ->getResult();
    }

    public function findEncoursSorties()
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('s')
            ->where('s.dateHeureDebut <= :now'  )
            ->andWhere('s.dateHeureFin >:now')
            ->setParameter('now',$now)
            ->getQuery()
            ->getResult();
    }


    public function findSortiesOlderThan(\DateTimeInterface $date): array
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.dateHeureFin < :date')
            ->setParameter('date', $date);

        return $qb->getQuery()->getResult();
    }

    public function countParticipants(Sortie $sortie): int
    {
        return count($sortie->getParticipants());
    }

    public function findAllPerso(): Paginator
    {
        $query = $this->createQueryBuilder('s')
            ->where('s.etat != :etatId')
            ->setParameter('etatId', 5)
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setMaxResults(50)
            ->getQuery();

        return new Paginator($query);
    }

    public function findSearchNonLog(Filtre $filtre)
    {

    }

}
