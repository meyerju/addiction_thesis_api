<?php

namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Entity\UploadedItem;
use App\Entity\PatientIncident;
use App\Entity\ActionType;
use App\Entity\FileDetail;
use App\Entity\File;
use App\Entity\Patient;

/**
 * Class FileService
 * @package Utils
 */
class FileService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /** @var BaseReader $reader */
    private $reader;

    /** @var Spreadsheet $spreadsheet */
    private $spreadsheet;

    private $mappingColumns = [
        "date" => 'A',
        "action_type" => 'B',
        "file_detail" => 'C',
        "latitude" => 'D',
        "longitude" => 'E',
    ];

    /**
     * FileService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    function load($uploadedFile, $patientId)
    {
        $patient = $this->em->getRepository(Patient::class)
            ->findOneBy([
                'id' => $patientId,
            ]);

        $this->guessReader($uploadedFile->getClientOriginalName());
        $this->reader->setReadDataOnly(true);
        $this->spreadsheet = $this->reader->load($uploadedFile->getRealPath());   
        $returnArray = [];
        $sheet = $this->spreadsheet->getAllSheets()[0];
        $returnArray[] = $this->savePatientIncidents($sheet, $uploadedFile, $patient);
        unset($sheet);        
      
        return $returnArray;
    }


    /**
     * @param Worksheet $worksheet
     */
    public function savePatientIncidents(Worksheet $worksheet, $uploadedFile, Patient $patient)
    {
        $array = $worksheet->toArray(null, true, true, true);
        $count = 0;

        $file = new File($uploadedFile->getClientOriginalName(), new \DateTime(), $patient);
        $this->em->persist($file);    

        
        foreach ($array as $key => $row)
        {
            if($key === 1){//first line
                continue;
            }

            $actionTypeName = $row[$this->mappingColumns['action_type']];
            $actionType = $this->em->getRepository(ActionType::class)
            ->findOneByName($actionTypeName);

            if (!$actionType instanceof ActionType)
            {
                $actionType = new ActionType($actionTypeName);
                $this->em->persist($actionType);
            }

            $fileDetailName = $row[$this->mappingColumns['file_detail']];
            $fileDetail = $this->em->getRepository(FileDetail::class)
            ->findOneBy([
                'name' => $fileDetailName,
                'actionType' => $actionType,
            ]);

            if (!$fileDetail instanceof FileDetail)
            {
                $fileDetail = new FileDetail($fileDetailName,$actionType, $file);
                $this->em->persist($fileDetail);
            }
            $date = new \DateTime();
            $date->setTimestamp($row[$this->mappingColumns['date']]);
            $latitude = $row[$this->mappingColumns['latitude']];
            $longitude = $row[$this->mappingColumns['longitude']];

            $patientIncident = new PatientIncident($date, $latitude, $longitude, $fileDetail);
            $this->em->persist($patientIncident);
            $count ++;

        }
        $this->em->flush();
        return ["{$count} patient incidents were saved."];
    }

    /**
     * @param $path
     * @throws \Exception
     */
    private function guessReader($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        switch ($ext)
        {
            case 'xlsx':
                $this->reader = new Xlsx();
                dump(1);
                break;

            case 'xls':
                $this->reader = new Xls();
                break;

            case 'csv':
                $this->reader = (new Csv())->setDelimiter(",");
                break;

            default:
                throw new \Exception("Cette extension ($ext) n'est pas prise en charge.");
        }

    }

    /**
     * Get all files of a patient
     *
     * @return array
     */
    public function findAll($patientId)
    {
        $patient = $this->em->getRepository(Patient::class)->findOneById($patientId);
        $files = $this->em->getRepository(File::class)->getAllOfPatient($patient);
        $this->em->flush();
        return $files;
    }

    public function deleteFile($fileId){
        $file = $this->em->getRepository(File::class)->findOneById($fileId);
        $file->setArchived(true);
        $this->em->merge($file);
        $this->em->flush();
    }
}
