<?php

namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Entity\UploadedItem;
use App\Entity\PatientIncident;
use App\Entity\ActionType;
use App\Entity\FileDetail;
use App\Entity\File;
use App\Entity\Patient;
use App\OutputFormatter\OutputFormatterBar;
use App\OutputFormatter\OutputFormatterLine;
use App\OutputFormatter\OutputFormatterReversedLine;
use App\OutputFormatter\OutputFormatterTable;
use App\OutputFormatter\OutputFormatterHoursBar;
use App\OutputFormatter\OutputFormatterPeriodeBar;
use App\OutputFormatter\OutputFormatterTimePie;
use App\OutputFormatter\OutputFormatterPieMap;
use App\OutputFormatter\OutputFormatterMap;
use App\OutputFormatter\OutputFormatterProgress;
use App\OutputFormatter\OutputFormatterHeatMap;

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
        "progress" => 'F',
        "scaleValue" => 'G',
        "start_date" => 'B',
        "end_date" => 'B',
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
        $startDate = array_values($array)[0][$this->mappingColumns['start_date']];
        $endDate = array_values($array)[1][$this->mappingColumns['end_date']];
        dump(Date::excelToDateTimeObject($startDate));
        dump(Date::excelToDateTimeObject($endDate));
        $file = new File($uploadedFile->getClientOriginalName(), new \DateTime(), $patient, Date::excelToDateTimeObject($startDate), Date::excelToDateTimeObject($endDate));
        $this->em->persist($file);    

        $this->em->flush();
        
        foreach ($array as $key => $row)
        {
            if(($key === 1)||($key === 2)||($key === 3)){//first lines for start and end dates
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
                'file' => $file
            ]);

            if (!$fileDetail instanceof FileDetail)
            {
                $fileDetail = new FileDetail($fileDetailName,$actionType, $file);
                $this->em->persist($fileDetail);
            }
            $date = $worksheet->getCell("A".strval($key))->getValue();
            $date = Date::excelToDateTimeObject($date); 
            $latitude = $row[$this->mappingColumns['latitude']];
            $longitude = $row[$this->mappingColumns['longitude']];
            $progress = $row[$this->mappingColumns['progress']];
            $scaleValue = $row[$this->mappingColumns['scaleValue']];
            dump($row[$this->mappingColumns['scaleValue']]);
            $patientIncident = new PatientIncident($date, $latitude, $longitude, $fileDetail, $progress, $scaleValue);
            $this->em->persist($patientIncident);
            $count ++;

            $this->em->flush();
        }
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

    public function getData($fileId){
        $barData = $this->em->getRepository(PatientIncident::class)->getBarData($fileId);
        $barFormatter = new OutputFormatterBar();
        // $lineData = $this->em->getRepository(PatientIncident::class)->getLineData($fileId);
        // $lineFormatter = new OutputFormatterLine();
        $tableData = $this->em->getRepository(PatientIncident::class)->getTableData($fileId);
        $tableFormatter = new OutputFormatterHoursBar();
        $timePieFormatter = new OutputFormatterTimePie();
        $periodeBarFormatter = new OutputFormatterPeriodeBar();
        $heatMapData = $this->em->getRepository(PatientIncident::class)->getHeatMapData($fileId);
        $heatMapFormatter = new OutputFormatterHeatMap();
        $bubbleData = $this->em->getRepository(PatientIncident::class)->getBubbleData($fileId);
        $bubbleFormatter = new OutputFormatterReversedLine();
        // $mapData = $this->em->getRepository(PatientIncident::class)->getMapData($fileId);
        // $pieMapFormatter = new OutputFormatterPieMap();
        // $mapFormatter = new OutputFormatterMap();
        // $progressData = $this->em->getRepository(PatientIncident::class)->getProgressData($fileId);
        $periodeFile = $this->em->getRepository(File::class)->getPeriode($fileId);
        // $progressFormatter = new OutputFormatterProgress();
        return [
            // "step"=>$progressFormatter->format($progressData, $periodeFile), 
            // "map"=> $mapFormatter->format($mapData), 
            // "mapPie" => $pieMapFormatter->format($mapData), 
            "bar" => $barFormatter->format($barData), 
            "table" => $tableFormatter->format($tableData),  
            "periodeBar" => $periodeBarFormatter->format($tableData),
            "timePie" => $timePieFormatter->format($tableData),
            "heatMap" => $heatMapFormatter->format($heatMapData, $periodeFile),
            "bubble" => $bubbleFormatter->format($bubbleData, $periodeFile),
        ];
    }
}
