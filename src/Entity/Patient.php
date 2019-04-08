<?php

namespace App\Entity;

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
}
