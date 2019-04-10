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
        ]);

        if (!($therapist instanceof Therapist)) {
            throw new \Exception('Bad credentials (email: ' . $email . ')', Response::HTTP_BAD_REQUEST);
        }

        return $therapist;
    }

    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws \Exception
     */
    public function create(string $email, string $password)
    {
        $therapist = $this->em->getRepository(Therapist::class)->findOneByEmail($email);
        if ($therapist instanceof Therapist) {
            throw new \Exception("Email already used.", Response::HTTP_BAD_REQUEST);
        }
        $therapist = new Therapist();
        $therapist->setEmail($email)
            ->setPassword($password);
        $this->em->persist($therapist);

        $this->em->flush();
        return ["therapist_id" => $therapist->getId()];
    }
}
