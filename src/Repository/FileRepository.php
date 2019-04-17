<?php

namespace App\Repository;

use App\Entity\File;
use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method File|null find($id, $lockMode = null, $lockVersion = null)
 * @method File|null findOneBy(array $criteria, array $orderBy = null)
 * @method File[]    findAll()
 * @method File[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, File::class);
    }
    
    public function getAllOfPatient(Patient $patient)
    {
        $qb = $this->createQueryBuilder("f");
        $q = $qb->where("f.patient = :patient")
            ->andWhere("f.archived = :archived")
            ->orderBy('f.upload_date', 'DESC')
            ->setParameter("patient", $patient)
            ->setParameter("archived", false);

        return $q->getQuery()->getResult();
    }
}
