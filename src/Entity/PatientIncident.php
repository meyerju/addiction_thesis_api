<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientIncidentRepository")
 */
class PatientIncident
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * -1, 0 or 1
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $progress;

    /**
     * 0, 1, 2 or 3
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $scaleValue;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FileDetail", inversedBy="patientIncident")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fileDetail;

    function __construct($date, $latitude, $longitude, $fileDetail, $progress, $scaleValue)
    {
        $this->setDate($date);
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
        $this->setFileDetail($fileDetail);
        $this->setProgress($progress);
        $this->setScaleValue($scaleValue);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): ?self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): ?self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getProgress(): ?float
    {
        return $this->progress;
    }

    public function setProgress(?float $progress): ?self
    {
        $this->progress = $progress;

        return $this;
    }

    public function getScaleValue(): ?float
    {
        return $this->scaleValue;
    }

    public function setScaleValue(?float $scaleValue): ?self
    {
        $this->scaleValue = $scaleValue;

        return $this;
    }

    public function getFileDetail(): ?FileDetail
    {
        return $this->fileDetail;
    }

    public function setFileDetail(?FileDetail $fileDetail): self
    {
        $this->fileDetail = $fileDetail;

        return $this;
    }
}
