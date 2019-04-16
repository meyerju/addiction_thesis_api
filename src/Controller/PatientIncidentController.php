<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Utils\PatientIncidentService;
use App\Entity\UploadedItem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PatientIncidentController
 * @package App\Controller
 */
class PatientIncidentController extends Controller
{
    /**
     * @Route("/file/load", methods={"POST"})
     * 
     * @param Request $request
     * @return Response
     */
    function loadAction(Request $request, PatientIncidentService $service)
    {
        dump($request);
        $uploadedFile = $request->files->get('file');
        dump($uploadedFile);
        if($uploadedFile){
            $uploadedItem = new UploadedItem($uploadedFile);

            try
            {
                $changes = $service->load($uploadedItem,"ghjk-xlsx");
            }
            catch (\Exception $exception)
            {
                return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }    

            return new Response(json_encode($changes));
        }else{       
            return new Response("No file", Response::HTTP_BAD_REQUEST);
        }
    }

}