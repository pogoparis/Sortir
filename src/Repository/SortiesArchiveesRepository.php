<?php

namespace App\Repository;

use App\Entity\SortiesArchivees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SortiesArchivees>
 *
 * @method SortiesArchivees|null find($id, $lockMode = null, $lockVersion = null)
 * @method SortiesArchivees|null findOneBy(array $criteria, array $orderBy = null)
 * @method SortiesArchivees[]    findAll()
 * @method SortiesArchivees[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortiesArchiveesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SortiesArchivees::class);
    }


}
