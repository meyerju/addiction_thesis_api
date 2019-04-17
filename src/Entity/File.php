<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"FullFile"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"FullFile"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"FullFile"})
     */
    private $upload_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FileDetail", cascade={"persist"}, mappedBy="file")
     */
    private $fileDetails;

    /**
     * @var boolean $archived
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $archived;

    public function __construct($name, $upload_date, $patient)
    {
        $this->setName($name);
        $this->setUploadDate($upload_date);
        $this->setPatient($patient);
        $this->setArchived(false);
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
