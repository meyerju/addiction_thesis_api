<?php

namespace App\Repository;

use App\Entity\Patient;
use App\Entity\Therapist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findAll()
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function getAllOfTherapist(Therapist $therapist)
    {
        $qb = $this->createQueryBuilder("p");
        $q = $qb->where("p.therapist = :therapist")
            ->setParameter("therapist", $therapist);

        return $q->getQuery()->getResult();
    }
}