<?php

namespace App\Repository;

use App\Entity\VilleLocalisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VilleLocalisation>
 *
 * @method VilleLocalisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method VilleLocalisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method VilleLocalisation[]    findAll()
 * @method VilleLocalisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleLocalisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VilleLocalisation::class);
    }

//    /**
//     * @return VilleLocalisation[] Returns an array of VilleLocalisation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VilleLocalisation
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
