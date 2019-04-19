<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionTypeRepository")
 */
class ActionType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"FullFile"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FileDetail", mappedBy="actionType")
     */
    private $fileDetails;

    public function __construct($name)
    {
        $this->setName($name);
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

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $fileDetail->setActionType($this);
        }

        return $this;
    }

    public function removeFileDetail(FileDetail $fileDetail): self
    {
        if ($this->fileDetails->contains($fileDetail)) {
            $this->fileDetails->removeElement($fileDetail);
            // set the owning side to null (unless already changed)
            if ($fileDetail->getActionType() === $this) {
                $fileDetail->setActionType(null);
            }
        }

        return $this;
    }
}
