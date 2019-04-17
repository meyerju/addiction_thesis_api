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
     * @ORM\Column(type="string", length=255)
     * @Groups({"FullTherapist"})
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"FullTherapist", "FullPatient"})
     */
    protected $email;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * The salt to use for hashing.
     *
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $salt;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Patient", mappedBy="therapist")
     */
    private $patients;

    public function __construct()
    {
        $this->enabled = false;
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

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Set enabled.
     *
     * @param $enabled
     * @return $this
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (bool) $boolean;
        return $this;
    }

    /**
     * Get enabled.
     *
     * @return $enabled
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled ;
    }

    /**
     * Set password.
     *
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email.
     *
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set salt.
     *
     * @param $salt
     * @return $this
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt.
     *
     * @return $salt
     */
    public function getSalt()
    {
        return $this->salt;
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
