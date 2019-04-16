<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;

/**
 * UploadedItem
 */
class UploadedItem 
{
    /**
     * Excel|csv file uploaded
     */
    private $file;

    /**
     * @var string
     */
    private $codifiedFileName;

    /**
     * @param File $file
     */
    public function __construct($file = null)
    {
        if( ! empty($file)){
            $this->setFile($file);
        }
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getCodifiedFileName()
    {
        return $this->codifiedFileName;
    }

    /**
     * @param mixed $codifiedFileName
     */
    public function setCodifiedFileName($codifiedFileName)
    {
        $this->codifiedFileName = $codifiedFileName;
    }
}
