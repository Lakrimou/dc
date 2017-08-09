<?php

namespace Doctors\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appointment
 *
 * @ORM\Table(name="appointment")
 * @ORM\Entity(repositoryClass="Doctors\AdminBundle\Repository\AppointmentRepository")
 */
class Appointment
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
     * @var datetime
     * @ORM\Column(name="appointment", type="datetime")
     */
    private $appointment;


    /**
     * @var string
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @var \DateTime
     * @ORM\Column(name="createdAtAppointment", type="datetime")
     */
    private $createdAtAppointment;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActiveAppointment", type="boolean")
     */
    private $isActiveAppointment;

    /**
     * @var bool
     *
     * @ORM\Column(name="eval", type="boolean")
     */
    private $eval;

    /**
     * @var text
     *
     * @ORM\Column(name="reason", type="text", nullable=true)
     */
    private $reason;

    /**
     * @ORM\ManyToOne(targetEntity="Doctors\AdminBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Doctors\AdminBundle\Entity\Doctor", inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
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
     * Set status
     *
     * @param string $status
     *
     * @return Appointment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAtAppointment
     *
     * @param \DateTime $createdAtAppointment
     *
     * @return Appointment
     */
    public function setCreatedAtAppointment($createdAtAppointment)
    {
        $this->createdAtAppointment = $createdAtAppointment;

        return $this;
    }

    /**
     * Get createdAtAppointment
     *
     * @return \DateTime
     */
    public function getCreatedAtAppointment()
    {
        return $this->createdAtAppointment;
    }

    /**
     * Set isActiveAppointment
     *
     * @param boolean $isActiveAppointment
     *
     * @return Appointment
     */
    public function setIsActiveAppointment($isActiveAppointment)
    {
        $this->isActiveAppointment = $isActiveAppointment;

        return $this;
    }

    /**
     * Get isActiveAppointment
     *
     * @return boolean
     */
    public function getIsActiveAppointment()
    {
        return $this->isActiveAppointment;
    }

    /**
     * Set user
     *
     * @param \Doctors\AdminBundle\Entity\User $user
     *
     * @return Appointment
     */
    public function setUser(\Doctors\AdminBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Doctors\AdminBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    

    /**
     * Set appointment
     *
     * @param \DateTime $appointment
     *
     * @return Appointment
     */
    public function setAppointment($appointment)
    {
        $this->appointment = $appointment;

        return $this;
    }

    /**
     * Get appointment
     *
     * @return \DateTime
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * Set doctor
     *
     * @param \Doctors\AdminBundle\Entity\Doctor $doctor
     *
     * @return Appointment
     */
    public function setDoctor(\Doctors\AdminBundle\Entity\Doctor $doctor)
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

    /**
     * Set eval
     *
     * @param boolean $eval
     *
     * @return Appointment
     */
    public function setEval($eval)
    {
        $this->eval = $eval;

        return $this;
    }

    /**
     * Get eval
     *
     * @return boolean
     */
    public function getEval()
    {
        return $this->eval;
    }

    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return Appointment
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }
}
