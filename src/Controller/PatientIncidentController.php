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
}