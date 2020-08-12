<?php

namespace App\Repository\Config;

use App\Entity\Config\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository {

    const ITEMS_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, User::class);
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

    public function findAllPaginator(int $page = 1,$nbPerPage): Paginator {
        $query       = $this->createQueryBuilder('u')
                ->leftJoin('u.role', 'r')
                ->addSelect('r')
                ->orderBy('u.id')
                ->getQuery()
                ->setFirstResult(($page - 1) * $nbPerPage)
                ->setMaxResults($nbPerPage);
        return new Paginator($query, true);
    }

}
