<?php

namespace App\Repository;

use App\Entity\Lease;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Lease|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lease|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lease[]    findAll()
 * @method Lease[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaseRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lease::class);
    }

    
    

    /*
    public function findOneBySomeField($value): ?Lease
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
