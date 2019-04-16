<?php

namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Entity\UploadedItem;

/**
 * Class PatientIncidentService
 * @package Utils
 */
class PatientIncidentService
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
        "date" => '0',
        "hour" => '1',
        "action_type" => '2',
        "file_detail" => '3',
        "latitude" => '4',
        "longitude" => '5',
    ];

    /**
     * PatientIncidentService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
        *
     * @param UploadedItem $uploadedItem
     * @param $filename
     * @return array
     * @throws \Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    function load(UploadedItem $uploadedItem, $filename)
    {
        dump($uploadedItem,$filename);
        $this->guessReader($filename);
        try
        {
            $this->reader->setReadDataOnly(true);
            $this->spreadsheet = $this->reader->load($uploadedItem->getFile()->getRealPath());
        }
        catch (\Exception $exception)
        {
            throw new \Exception("Ce fichier ({$uploadedItem->getCodifiedFileName()}) n'est pas lisible par l'application");
        }
        $sheet = $this->spreadsheet->getAllSheets()[0];
        $returnArray = [];
        $sheet->unfreezePane();
        $returnArray[] = $this->savePatientIncidents($sheet);
        unset($sheet);   
             
        return $returnArray;
    }


    /**
     * @param Worksheet $worksheet
     * @return array|string
     */
    public function savePatientIncidents(Worksheet $worksheet)
    {
        dump($worksheet);
        // $fileContent = $donneeBruteMapping->getFileContent();
        // unset($donneeBruteMapping);
        // $arrayFileContent = explode("\n", $fileContent);
        // unset($fileContent);
        // $codesDonneesBrutes = [];
        // foreach ($arrayFileContent as $row)
        // {
        //     $arrayRow = explode(",", $row);

        //     if (array_key_exists($this->mappingColumns['fileCode'], $arrayRow)
        //         && $codifiedFileName === $arrayRow[$this->mappingColumns['fileCode']])
        //     {
        //         if ('' === $arrayRow[$this->mappingColumns['feuille']]
        //             || intval($numeroSheet) === intval($arrayRow[$this->mappingColumns['feuille']]))
        //         {
        //             $codesDonneesBrutes[$arrayRow[$this->mappingColumns['code']]] = [];

        //             foreach ($this->mappingColumns as $name => $column)
        //             {
        //                 $codesDonneesBrutes[$arrayRow[$this->mappingColumns['code']]][$name] = $arrayRow[$column];
        //             }
        //         }
        //     }
        // }

        // $changes = [];
        // foreach ($incidents as $codifiedNameCode => $mapping)
        // {
        //     $code = $this->saveCode($mapping, $codifiedNameCode, $crudManager);
        //     $row = $mapping['ligne'];
        //     $columns = $mapping['colonnes'];
        //     // Récupération du centre de soin de la ligne
        //     try
        //     {
        //         $value = $this->calculValue($code, $worksheet, $columns, $row);
        //     }
        //     catch (\Exception $exception)
        //     {
        //         return "{$codifiedNameCode} : " . $exception->getMessage();
        //     }
        //     if (null === $value || "" === $value)
        //         $value = 0;
    
        //     $oldDonneeBrute = $this->em->getRepository(DonneeBrute::class)
        //         ->findOneBy([
        //             'code' => $code,
        //             'careCenter' => $careCenter,
        //             'periode' => $periode
        //         ]);
    
        //     if (!$oldDonneeBrute instanceof DonneeBrute)
        //     {
        //         $oldDonneeBrute = new DonneeBrute($code, $value, $periode, $type);
        //         $oldDonneeBrute->setCareCenter($careCenter);
        //     }
        //     else
        //     {
        //         $oldDonneeBrute->setValue($value);
        //     }
    
        //     $this->em->persist($oldDonneeBrute);
        //     $this->em->flush();
    
        //     $changes[] = "La donnée brute {$codifiedNameCode} a été lié à 1 centre de soin.";
        // }

        // return $changes;
        return[];
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
}
