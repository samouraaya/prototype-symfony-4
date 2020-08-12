<?php

namespace App\Repository\Config;

use App\Entity\Config\AccessInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccessInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessInterface[]    findAll()
 * @method AccessInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessInterfaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessInterface::class);
    }

    // /**
    //  * @return AccessInterface[] Returns an array of AccessInterface objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccessInterface
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
