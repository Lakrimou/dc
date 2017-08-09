<?php

namespace Doctors\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TokenDoctor
 *
 * @ORM\Table(name="token_doctor")
 * @ORM\Entity(repositoryClass="Doctors\AdminBundle\Repository\TokenDoctorRepository")
 */
class TokenDoctor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tokenDoctor", type="text", nullable=true)
     */
    private $tokenDoctor;

    /**
     * @ORM\ManyToOne(targetEntity="Doctors\AdminBundle\Entity\Doctor")
     */
    private $doctor;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tokenDoctor
     *
     * @param string $tokenDoctor
     *
     * @return TokenDoctor
     */
    public function setTokenDoctor($tokenDoctor)
    {
        $this->tokenDoctor = $tokenDoctor;

        return $this;
    }

    /**
     * Get tokenDoctor
     *
     * @return string
     */
    public function getTokenDoctor()
    {
        return $this->tokenDoctor;
    }

    /**
     * Set doctor
     *
     * @param \Doctors\AdminBundle\Entity\Doctor $doctor
     *
     * @return TokenDoctor
     */
    public function setDoctor(\Doctors\AdminBundle\Entity\Doctor $doctor = null)
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * Get doctor
     *
     * @return \Doctors\AdminBundle\Entity\Doctor
     */
    public function getDoctor()
    {
        return $this->doctor;
    }
}
