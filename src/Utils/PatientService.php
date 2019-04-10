<?php

namespace App\Utils;

use App\Entity\Patient;
use App\Entity\Therapist;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;

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

    /** @var Serializer $serializer */
    private $serializer;

    /**
     * PatientService constructor.
     * @param EntityManagerInterface $entityManager
     * @param Serializer $serializer
     */
    public function __construct(EntityManagerInterface $entityManager, Serializer $serializer)
    {
        $this->em = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * Get all patients
     *
     * @param Therapist $therapist
     * @return array
     */
    public function findAll(Therapist $therapist)
    {
        $patients = $this->em->getRepository(Patient::class)->getAllOfTherapist($therapist);
        $this->em->flush();
        return $patients;
    }
}
