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

    public function getBarData($fileId)
    {
        $qb = $this->createQueryBuilder("pi");
        $q = $qb->select("CONCAT( year(pi.date),'-',CONCAT('0', MONTH(pi.date)),'-',CONCAT('0', day(pi.date)) ) as date, COUNT(pi) as value", "fd.name as name")
            ->leftJoin('pi.fileDetail', 'fd')
            ->leftJoin('fd.file', 'f')
            ->groupBy('name, date')
            ->andWhere("f.id = :fileId")
            ->setParameter('fileId', $fileId);

        return $q->getQuery()->getResult();
    }
}
