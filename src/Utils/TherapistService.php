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
     * @param Therapist $therapist
     * @param array $therapistData
     * @return mixed
     * @throws \Exception
     */
    public function create(Therapist $therapist, array $therapistData)
    {
        $therapistSaved = $this->em->getRepository(therapist::class)->findOneByEmail($therapist->getEmail());
        if (!$therapistSaved instanceof Therapist) {
            throw new \Exception("The therapist with email " . $therapist->getEmail() . " has been not preconfigured. You need to ask 
            the salt for this email beforehand.");
        } elseif ($therapistSaved->getEnabled()) {
            throw new \Exception("The therapist with email " . $therapist->getEmail() . " has already been added");
        }

        $therapist->setId($therapistSaved->getId())
            ->setSalt($therapistData['salt'])
            ->setEmail($therapist->getEmail())
            ->setEnabled(1);

        $therapist->setPassword($therapistData['password']);

        $this->em->merge($therapist);
        $this->em->flush();
        return $therapist;
    }

    /**
     * @param string $email
     * @return array
     * @throws \Exception
     */
    public function initialize(string $email)
    {
        $therapist = $this->em->getRepository(Therapist::class)->findOneByEmail($email);
        if ($therapist instanceof Therapist) {
            throw new \Exception("Email already used.", Response::HTTP_BAD_REQUEST);
        }
        $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
        $therapist = new Therapist();
        $therapist->setEmail($email)
            ->setEnabled(0)
            ->setSalt($salt)
            ->setPassword("");

        $this->em->persist($therapist);

        $this->em->flush();
        return ["therapist_id" => $therapist->getId(), "salt" => $therapist->getSalt()];
    }

    /**
     * @param string $email
     * @return array
     * @throws \Exception
     */
    public function getSalt(string $email)
    {
        $therapist = $this->em->getRepository(Therapist::class)->findOneByEmail($email);

        if (!$therapist instanceof Therapist) {
            throw new \Exception("Bad credentials", Response::HTTP_BAD_REQUEST);
        }

        return ["therapist_id" => $therapist->getId(), "salt" => $therapist->getSalt()];
    }

    /**
     * @param arrray $content
     * @return mixed
     * @throws \Exception
     */
    public function login(array $content)
    {
        $repository = $this->em->getRepository(Therapist::class);
        $therapist = $repository->findOneBy([
            'email' => $content['email'],
            'password' => $content['saltedPassword'],
            'enabled' => 1
        ]);

        if (!($therapist instanceof Therapist)) {
            throw new \Exception('Bad credentials (email: ' .$content['email'] . ')', Response::HTTP_BAD_REQUEST);
        }

        return $therapist;
    }
}
