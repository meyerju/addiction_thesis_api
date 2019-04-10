<?php


namespace App\Controller;

use JMS\Serializer\SerializationContext;
use ProjectBundle\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class PatientController
 * @package ProjectBundle\Controller
 */
class PatientController extends Controller
{

    /**
     * @Rest\Get("/patients", name="get_all_patient")
     *
     * @SWG\Tag(name="Patients")
     *
     * @SWG\Response(
     *     response=200,
     *     description="All Patients",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Patient::class))
     *     )
     * )
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
