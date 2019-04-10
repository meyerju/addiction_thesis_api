<?php


namespace App\Controller;

use JMS\Serializer\SerializationContext;
use App\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PatientController
 * @package ProjectBundle\Controller
 */
class PatientController extends Controller
{

    /**
     * @Route("/patients", methods={"GET"})
     *
     * @return Response
     */
    public function getAllAction()
    {
        $therapist = $this->getTherapist();
        $patients = $this->get('patient_service')->findAll($therapist);

        $patients = $this->get('jms_serializer')
            ->serialize($patients, 'json', SerializationContext::create()->setGroups(['FullPatient'])->setSerializeNull(true));
        return new Response($patients);
    }
}
