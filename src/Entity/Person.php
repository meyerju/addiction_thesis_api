<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"FullPatient"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"FullPatient"})
     */
    private $surname;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"FullPatient"})
     */
    private $birth_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"FullPatient"})
     */
    private $gender;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Patient", mappedBy="person", cascade={"persist", "remove"})
     */
    private $patient;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(Patient $patient): self
    {
        $this->patient = $patient;

        // set the owning side of the relation if necessary
        if ($this !== $patient->getPerson()) {
            $patient->setPerson($this);
        }

        return $this;
    }
}
