<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * Therapist
 *
 * @ORM\Table(name="therapist")
 * @ORM\Entity(repositoryClass="App\Repository\TherapistRepository")
 */
class Therapist
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"FullTherapist"})
     */
    protected $id;
    
    /**
     * @var string
     * @Groups({"FullTherapist"})
     */
    protected $password;

    /**
     * @var string
     * @Groups({"FullTherapist"})
     */
    protected $email;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Patient", mappedBy="therapist")
     */
    private $patients;

    public function __construct()
    {
        parent::__construct();
        $this->patients = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->addTherapist($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->contains($patient)) {
            $this->patients->removeElement($patient);
            $patient->removeTherapist($this);
        }

        return $this;
    }
}
