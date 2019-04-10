<?php

namespace App\Utils;

use App\Entity\Therapist;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TherapistService
 * @package Utils
 */
class TherapistService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /** @var Serializer $serializer */
    private $serializer;

    /**
     * TherapistService constructor.
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
     * @param string $email
     * @param string $password
     * @return mixed
     * @throws \Exception
     */
    public function login(string $email, string $password)
    {
        $repository = $this->em->getRepository('Therapist');

        $therapist = $repository->findOneBy([
            'email' => $email,
            'password' => $password,
            'enabled' => 1
        ]);

        if (!($therapist instanceof Therapist)) {
            throw new \Exception('Bad credentials (email: ' . $email . ')', Response::HTTP_BAD_REQUEST);
        }

        return $therapist;
    }
}
