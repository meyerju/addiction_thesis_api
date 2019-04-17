<?php

namespace App\Utils;

use App\Entity\Patient;
use App\Entity\Therapist;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class PatientService
 * @package Utils
 */
class PatientService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * PatientService constructor.
     * @param EntityManagerInterface $entityManager
     * @param Serializer $serializer
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Get all patients
     *
     * @return array
     */
    public function findAll($therapistId)
    {
        $therapist = $this->em->getRepository(Therapist::class)->findOneById($therapistId);
        $patients = $this->em->getRepository(Patient::class)->getAllOfTherapist($therapist);
        $this->em->flush();
        return $patients;
    }
}
