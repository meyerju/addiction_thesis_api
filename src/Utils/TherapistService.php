<?php

namespace App\Utils;

use App\Entity\Therapist;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * TherapistService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
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
     * @param array $content
     * @return array
     * @throws \Exception
     */
    public function create(array $content)
    {
        $therapist = $this->em->getRepository(Therapist::class)->findOneByEmail($content['email']);
        if ($therapist instanceof Therapist) {
            throw new \Exception("Email already used.", Response::HTTP_BAD_REQUEST);
        }
        $therapist = new Therapist();
        $therapist->setEmail($content['email'])
            ->setPassword($content['password']);
        $this->em->persist($therapist);

        $this->em->flush();
        return ["therapist_id" => $therapist->getId()];
    }
}
