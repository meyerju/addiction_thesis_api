<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\TherapistService;
use App\Entity\Therapist;

/**
 * Class TherapistController
 * @package Api\Controller
 */
class TherapistController extends Controller
{
    /**
     * Log a therapist with its email and salted password. Create a new one if not in the db (remove this part for prod env)
     * @Route("/login", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request, TherapistService $service)
    {
        $jsonContent = $request->getContent();
        $content = json_decode($jsonContent,true);

        try {
            $therapist = $service->login($content);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_FORBIDDEN);
        }
        
        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');
        
        $therapistJson = $serializer->serialize($therapist, 'json', SerializationContext::create()->setGroups(['FullTherapist'])->setSerializeNull(true));
        return new Response($therapistJson);
    }

    /**
     * Get therapist's salt
     * @Route("/salt/{email}", methods={"GET"})
     * 
     * @param $email
     * @return Response
     */
    public function getSaltAction($email, TherapistService $service)
    {
        try {
            $salt = $service->getSalt($email);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), $exception->getCode()>=Response::HTTP_BAD_REQUEST ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($salt);
    }

    /**
     * @Route("/initialize/{email}", methods={"GET"})
     * 
     * @param $email
     * @return Response
     */
    public function initializeAction($email, TherapistService $service)
    {
        try {
            $salt = $service->initialize($email);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), $exception->getCode()>=Response::HTTP_BAD_REQUEST ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($salt);
    }

    /**
     * Create a new Therapist. You must have called getSalt before use this one
     * @Route("/therapists", methods={"PUT"})
     * 
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request, TherapistService $service)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        $jsonContent = $request->getContent();
        $therapist = json_decode($jsonContent,true);
        $therapistata = $therapist;

        try {
            $therapist = $serializer->deserialize($request->getContent(), Therapist::class, 'json');
            $return = $service->create($therapist, $therapistData);

            if (!$therapist instanceof Therapist) {
                return new JsonResponse($therapist);
            }

            $utherapistJson = $serializer->serialize(
                $return,
                'json',
                SerializationContext::create()->setGroups(['FullTherapist'])->setSerializeNull(true)
            );
            return new Response($therapistJson);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), 500);
        }
    }
}