<?php

namespace Doctors\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evaluation
 *
 * @ORM\Table(name="evaluation")
 * @ORM\Entity(repositoryClass="Doctors\AdminBundle\Repository\EvaluationRepository")
 */
class Evaluation
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
     * @ORM\Column(name="evaluation", type="string", length=255)
     */
    private $evaluation;

    /**
     * @var string
     *
     * @ORM\Column(name="feedback", type="text")
     */
    private $feedback;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAtEvaluation", type="datetime")
     */
    private $createdAtEvaluation;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActiveEvaluation", type="boolean")
     */
    private $isActiveEvaluation;

    /**
     * @var bool
     *
     * @ORM\Column(name="statusEvaluation", type="boolean")
     */
    private $statusEvaluation;

    /**
     * @ORM\ManyToOne(targetEntity="Doctors\AdminBundle\Entity\Doctor", inversedBy="evaluations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $doctor;

    /**
     * @ORM\ManyToOne(targetEntity="Doctors\AdminBundle\Entity\User", inversedBy="evaluations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Doctors\AdminBundle\Entity\Appointment", cascade={"persist", "remove"})
     */
    private $appointment;



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
     * Set evaluation
     *
     * @param string $evaluation
     *
     * @return Evaluation
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    /**
     * Get evaluation
     *
     * @return string
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }
    
    /**
     * Set createdAtEvaluation
     *
     * @param \DateTime $createdAtEvaluation
     *
     * @return Evaluation
     */
    public function setCreatedAtEvaluation($createdAtEvaluation)
    {
        $this->createdAtEvaluation = $createdAtEvaluation;

        return $this;
    }

    /**
     * Get createdAtEvaluation
     *
     * @return \DateTime
     */
    public function getCreatedAtEvaluation()
    {
        return $this->createdAtEvaluation;
    }

    /**
     * Set isActiveEvaluation
     *
     * @param boolean $isActiveEvaluation
     *
     * @return Evaluation
     */
    public function setIsActiveEvaluation($isActiveEvaluation)
    {
        $this->isActiveEvaluation = $isActiveEvaluation;

        return $this;
    }

    /**
     * Get isActiveEvaluation
     *
     * @return bool
     */
    public function getIsActiveEvaluation()
    {
        return $this->isActiveEvaluation;
    }

    /**
     * Set doctor
     *
     * @param \Doctors\AdminBundle\Entity\Doctor $doctor
     *
     * @return Evaluation
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
     * Set user
     *
     * @param \Doctors\AdminBundle\Entity\User $user
     *
     * @return Evaluation
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
     * Set statusEvaluation
     *
     * @param boolean $statusEvaluation
     *
     * @return Evaluation
     */
    public function setStatusEvaluation($statusEvaluation)
    {
        $this->statusEvaluation = $statusEvaluation;

        return $this;
    }

    /**
     * Get statusEvaluation
     *
     * @return boolean
     */
    public function getStatusEvaluation()
    {
        return $this->statusEvaluation;
    }

    /**
     * Set appointment
     *
     * @param \Doctors\AdminBundle\Entity\Appointment $appointment
     *
     * @return Evaluation
     */
    public function setAppointment(\Doctors\AdminBundle\Entity\Appointment $appointment = null)
    {
        $this->appointment = $appointment;

        return $this;
    }

    /**
     * Get appointment
     *
     * @return \Doctors\AdminBundle\Entity\Appointment
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * Set feedback
     *
     * @param string $feedback
     *
     * @return Evaluation
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * Get feedback
     *
     * @return string
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
}
