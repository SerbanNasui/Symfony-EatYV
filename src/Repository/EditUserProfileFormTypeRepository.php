<?php

namespace App\Repository;

use App\Entity\EditUserProfileFormType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EditUserProfileFormType|null find($id, $lockMode = null, $lockVersion = null)
 * @method EditUserProfileFormType|null findOneBy(array $criteria, array $orderBy = null)
 * @method EditUserProfileFormType[]    findAll()
 * @method EditUserProfileFormType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EditUserProfileFormTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EditUserProfileFormType::class);
    }

    // /**
    //  * @return EditUserProfileFormType[] Returns an array of EditUserProfileFormType objects
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
    public function findOneBySomeField($value): ?EditUserProfileFormType
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
