<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $upload_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FileDetail", mappedBy="file")
     */
    private $fileDetails;

    public function __construct()
    {
        $this->fileDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUploadDate(): ?\DateTimeInterface
    {
        return $this->upload_date;
    }

    public function setUploadDate(\DateTimeInterface $upload_date): self
    {
        $this->upload_date = $upload_date;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * @return Collection|FileDetail[]
     */
    public function getFileDetails(): Collection
    {
        return $this->fileDetails;
    }

    public function addFileDetail(FileDetail $fileDetail): self
    {
        if (!$this->fileDetails->contains($fileDetail)) {
            $this->fileDetails[] = $fileDetail;
            $fileDetail->setFile($this);
        }

        return $this;
    }

    public function removeFileDetail(FileDetail $fileDetail): self
    {
        if ($this->fileDetails->contains($fileDetail)) {
            $this->fileDetails->removeElement($fileDetail);
            // set the owning side to null (unless already changed)
            if ($fileDetail->getFile() === $this) {
                $fileDetail->setFile(null);
            }
        }

        return $this;
    }
}
