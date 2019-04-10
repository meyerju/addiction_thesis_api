<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 */
class Patient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addiction_name;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Person", inversedBy="patient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="patient")
     */
    private $files;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Therapist", inversedBy="patients")
     */
    private $therapists;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->therapist = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddictionName(): ?string
    {
        return $this->addiction_name;
    }

    public function setAddictionName(?string $addiction_name): self
    {
        $this->addiction_name = $addiction_name;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setPatient($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getPatient() === $this) {
                $file->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Therapist[]
     */
    public function getTherapists(): Collection
    {
        return $this->therapists;
    }

    public function addTherapist(Therapist $therapist): self
    {
        if (!$this->therapists->contains($therapist)) {
            $this->therapists[] = $therapist;
            $therapist->addTherapist($this);
        }

        return $this;
    }

    public function removeTherapist(Therapist $therapist): self
    {
        if ($this->therapists->contains($therapist)) {
            $this->therapists->removeElement($therapist);
            $therapist->removeTherapist($this);
        }

        return $this;
    }
}
