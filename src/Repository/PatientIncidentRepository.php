<?php

namespace App\Repository;

use App\Entity\PatientIncident;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PatientIncident|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientIncident|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientIncident[]    findAll()
 * @method PatientIncident[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientIncidentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PatientIncident::class);
    }

    // /**
    //  * @return PatientIncident[] Returns an array of PatientIncident objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PatientIncident
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
