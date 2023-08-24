<?php

namespace App\Repository;

use App\Entity\Filtre;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    public function findSearch(Filtre $filtre, User $user): array
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

        // Requete filtre par organisateur
        if ($filtre->getOrganisateur() !== false) {
            $query = $query
                ->andWhere('p.organisateur = :user')
                ->setParameter('user', $user);
        }

        // Filtre pour les sorties auxquelles l'utilisateur est inscrit
        if ($filtre->getInscrit() !== false) {
            $query = $query
                ->leftJoin('p.participants', 'u', Join::WITH, 'u = :user')
                ->setParameter('user', $user)
                ->andWhere('u = :user');
        }

        return $query->getQuery()->getResult();
    }






//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
