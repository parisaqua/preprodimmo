<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    
    /**
     * Liste des individus actifs par ordre alphabetique
     * 
     * @return User[]
     */
    public function findActive(): array
    {
        return $this->findActiveQuery()
            // ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Fonction des individus actifs par ordre alphabetique
     *
     * @return QueryBuilder
     */
    public function findActiveQuery(): QueryBuilder {
        return $this->createQueryBuilder('u')
        ->andWhere('u.isActive = true')
        ->orderBy('u.lastName', 'ASC')
        ;
    }

    //  /**
    //  * @return User[]
    //  */
    // public function findAllNameAlphabetical()
    // {
    //     return $this->createQueryBuilder('u')
    //         ->orderBy('u.lastName', 'ASC')
    //         ->getQuery()
    //         ->execute()
    //     ;
    // }

    // /**
    //  * @return User[]
    //  */
    // public function findByCreator($user)
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.creator = :val')
    //         ->setParameter('val', $user)
    //         ->orderBy('u.lastName', 'ASC')
    //         ->getQuery()
    //         ->execute();
    // }

    
    /**
     * @return User[]
     */
    public function findOwnerAlphabetical()
    {
        return $this->findActiveQuery('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%'."ROLE_PROPERTYOWNER".'%')
            ->orderBy('u.lastName', 'ASC')  
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @return User[]
     */
    public function findTenantAlphabetical()
    {
        return $this->findActiveQuery('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%'."ROLE_PROPERTYTENANT".'%')
            ->orderBy('u.lastName', 'ASC')  
            ->getQuery()
            ->execute()
        ;
    }


           
    

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value) 
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
