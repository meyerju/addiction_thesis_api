<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 */
class Patient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"FullPatient"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"FullPatient"})
     */
    private $addiction_name;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Person", inversedBy="patient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"FullPatient"})
     */
    private $person;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="patient")
     */
    private $files;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Therapist", inversedBy="patients")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"FullPatient"})
     */
    private $therapist;

    /**
     * @var boolean $archived
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $archived;

    public function __construct($therapist)
    {
        $this->files = new ArrayCollection();
        $this->setArchived(false);
        $this->setTherapist($therapist);
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

    public function getTherapist(): ?Therapist
    {
        return $this->therapist;
    }

    public function setTherapist(?Therapist $therapist): self
    {
        $this->therapist = $therapist;

        return $this;
    }

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * @param bool $archived
     * @return User
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }
}
