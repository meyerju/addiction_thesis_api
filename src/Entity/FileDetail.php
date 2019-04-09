<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileDetailRepository")
 */
class FileDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\File", inversedBy="fileDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ActionType", inversedBy="fileDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $actionType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PatientIncident", mappedBy="fileDetail")
     */
    private $patientIncident;

    public function __construct()
    {
        $this->patientIncident = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getActionType(): ?ActionType
    {
        return $this->actionType;
    }

    public function setActionType(?ActionType $actionType): self
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * @return Collection|PatientIncident[]
     */
    public function getPatientIncident(): Collection
    {
        return $this->patientIncident;
    }

    public function addPatientIncident(PatientIncident $patientIncident): self
    {
        if (!$this->patientIncident->contains($patientIncident)) {
            $this->patientIncident[] = $patientIncident;
            $patientIncident->setFileDetail($this);
        }

        return $this;
    }

    public function removePatientIncident(PatientIncident $patientIncident): self
    {
        if ($this->patientIncident->contains($patientIncident)) {
            $this->patientIncident->removeElement($patientIncident);
            // set the owning side to null (unless already changed)
            if ($patientIncident->getFileDetail() === $this) {
                $patientIncident->setFileDetail(null);
            }
        }

        return $this;
    }
}
