<?php

namespace App\Repository;

use App\Entity\RecipeReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RecipeReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeReview[]    findAll()
 * @method RecipeReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeReview::class);
    }

    // /**
    //  * @return RecipeReview[] Returns an array of RecipeReview objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecipeReview
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
