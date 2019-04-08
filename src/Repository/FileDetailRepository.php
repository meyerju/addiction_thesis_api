<?php

namespace App\Repository;

use App\Entity\FileDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FileDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileDetail[]    findAll()
 * @method FileDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileDetailRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FileDetail::class);
    }

    // /**
    //  * @return FileDetail[] Returns an array of FileDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FileDetail
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
