<?php


namespace App\Controller;

use JMS\Serializer\SerializationContext;
use App\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\PatientService;

/**
 * Class PatientController
 * @package App\Controller
 */
class PatientController extends Controller
{

    /**
     * @Route("/patients/{therapistId}", methods={"GET"})
     *
     * @return Response
     */
    public function getAllAction($therapistId, PatientService $service)
    {
        $patients = $service->findAll($therapistId);

        $patients = $this->get('jms_serializer')
            ->serialize($patients, 'json', SerializationContext::create()->setGroups(['FullPatient'])->setSerializeNull(true));
        return new Response($patients);
    }
}
