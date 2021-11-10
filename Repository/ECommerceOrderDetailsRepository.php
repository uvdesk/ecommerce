<?php

namespace UVDesk\CommunityPackages\UVDesk\ECommerce\Repository;

use Doctrine\Persistence\ManagerRegistry;
use UVDesk\CommunityPackages\UVDesk\ECommerce\Entity\ECommerceOrderDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ECommerceOrderDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method ECommerceOrderDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method ECommerceOrderDetails[]    findAll()
 * @method ECommerceOrderDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ECommerceOrderDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ECommerceOrderDetails::class);
    }

    // /**
    //  * @return ECommerceOrderDetails[] Returns an array of ECommerceOrderDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ECommerceOrderDetails
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
