<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Therapist;

/**
 * Class TherapistController
 * @package Api\Controller
 */
class TherapistController extends Controller
{

    /**
     * @Route("/login", methods={"POST"})
     *     *
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        try {
            $therapist = $this->container->get('therapist_service')->login($email, $password);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_FORBIDDEN);
        }
        
        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');
        
        $therapistJson = $serializer->serialize($therapist, 'json', SerializationContext::create()->setGroups(['FullTherapist'])->setSerializeNull(true));
        return new Response($therapistJson);
    }

    /**
     * @Route("/therapists", methods={"PUT"})
     *     
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $therapist = $this->get('therapist_service')->initialize($username, $password);
        return new Response($therapist);
    }
}