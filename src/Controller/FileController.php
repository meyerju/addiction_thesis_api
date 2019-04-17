<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Utils\FileService;
use App\Entity\UploadedItem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileController
 * @package App\Controller
 */
class FileController extends Controller
{
    /**
     * @Route("/file/load", methods={"POST"})
     * 
     * @param Request $request
     * @return Response
     */
    function loadAction(Request $request, FileService $service)
    {
        $patientId = $request->request->get('patient');
        $uploadedFile = $request->files->get('file');
        if($uploadedFile){
            try
            {
                $changes = $service->load($uploadedFile, $patientId);
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

     /**
     * @Route("/files/{patientId}", methods={"GET"})
     * 
     * @param Request $request
     * @return Response
     */
    function getAllAction($patientId, FileService $service)
    {
        $files = $service->findAll($patientId);

        $files = $this->get('jms_serializer')
            ->serialize($files, 'json', SerializationContext::create()->setGroups(['FullFile'])->setSerializeNull(true));
        return new Response($files);
    }

      /**
     * @Route("/files/{fileId}", methods={"DELETE"})
     * 
     *  @param Request $request
     * @return JsonResponse|Response
     */

    public function removeAction($fileId, FileService $service)
    {
        try{
            $service->deleteFile($fileId);

        }catch (\Exception $exception){
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => 'data deleted','status'=>Response::HTTP_OK]);

    }
}