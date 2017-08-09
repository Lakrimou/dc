<?php

namespace Doctors\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Doctor
 *
 * @ORM\Table(name="doctor")
 * @ORM\Entity(repositoryClass="Doctors\AdminBundle\Repository\DoctorRepository")
 */
class Doctor
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
     * @ORM\Column(name="nameDoctor", type="string", nullable=true, length=255)
     */
    private $nameDoctor;

    /**
     * @var string
     *
     * @ORM\Column(name="nationality", type="string",  nullable=true, length=255)
     */
    private $nationality;

    /**
     * @var int
     *
     * @ORM\Column(name="phoneNumberDoctor", type="bigint", nullable=true, unique=true)
     */
    private $phoneNumberDoctor;

    /**
     * @var string
     *
     * @ORM\Column(name="countryDoctor", type="string", nullable=true, length=255)
     */
    private $countryDoctor;

    /**
     * @var string
     *
     * @ORM\Column(name="townDoctor", type="string", nullable=true, length=255)
     */
    private $townDoctor;

    /**
     * @var string
     *
     * @ORM\Column(name="cityDoctor", type="string", nullable=true, length=255)
     */
    private $cityDoctor;

    /**
     * @var string
     *
     * @ORM\Column(name="workplace", type="string", nullable=true, length=255)
     */
    private $workplace;

    /**
     * @var string
     *
     * @ORM\Column(name="workplaceName", type="string", nullable=true, length=255)
     */
    private $workplaceName;

    /**
     * @var string
     *
     * @ORM\Column(name="emailDoctor", type="string", length=255, nullable=true, unique=true)
     */
    private $emailDoctor;

    /**
     * @var string
     *
     * @ORM\Column(name="passwordDoctor", type="text", nullable=true)
     */
    private $passwordDoctor;

    /**
     * @var string
     *
     * @ORM\Column(name="photoDoctor", type="string", nullable=true, length=255)
     * @Assert\NotBlank(message="This file is not a valid image")
     * @Assert\File(mimeTypes={"image/jpeg", "image/png", "image/gif", "image/jpg"})
     */
    private $photoDoctor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAtDoctor", type="datetime")
     */
    private $createdAtDoctor;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActiveDoctor", type="boolean")
     */
    private $isActiveDoctor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastLoginDoctor", type="datetime", nullable=true)
     */
    private $lastLoginDoctor;

    /**
     * @var \bool
     *
     * @ORM\Column(name="isCompleted", type="boolean")
     */
    private $isCompleted;

    /**
     * @ORM\Column(name="longitude", nullable=true, type="text")
     */
    private $longitude;


    /**
     * @ORM\Column(name="lattitude", nullable=true, type="text")
     */
    private $lattitude;


    /**
     * @ORM\OneToMany(targetEntity="Doctors\AdminBundle\Entity\Evaluation", mappedBy="doctor")
     */
    private $evaluations;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Doctors\AdminBundle\Entity\Speciality")
     *
     */
    private $speciality;


    /**
     * @var time
     *
     * @ORM\Column(name="startTimeWork", type="time", nullable=true)
     */
    private $startTimeWork;

    /**
     * @var time
     *
     * @ORM\Column(name="endTimeWork", type="time", nullable=true)
     */
    private $endTimeWork;

    /**
     * @ORM\OneToMany(targetEntity="Doctors\AdminBundle\Entity\Appointment", mappedBy="doctor")
     */
    private $appointments;
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
     * Set nameDoctor
     *
     * @param string $nameDoctor
     *
     * @return Doctor
     */
    public function setNameDoctor($nameDoctor)
    {
        $this->nameDoctor = $nameDoctor;

        return $this;
    }

    /**
     * Get nameDoctor
     *
     * @return string
     */
    public function getNameDoctor()
    {
        return $this->nameDoctor;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     *
     * @return Doctor
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set phoneNumberDoctor
     *
     * @param integer $phoneNumberDoctor
     *
     * @return Doctor
     */
    public function setPhoneNumberDoctor($phoneNumberDoctor)
    {
        $this->phoneNumberDoctor = $phoneNumberDoctor;

        return $this;
    }

    /**
     * Get phoneNumberDoctor
     *
     * @return int
     */
    public function getPhoneNumberDoctor()
    {
        return $this->phoneNumberDoctor;
    }

    /**
     * Set countryDoctor
     *
     * @param string $countryDoctor
     *
     * @return Doctor
     */
    public function setCountryDoctor($countryDoctor)
    {
        $this->countryDoctor = $countryDoctor;

        return $this;
    }

    /**
     * Get countryDoctor
     *
     * @return string
     */
    public function getCountryDoctor()
    {
        return $this->countryDoctor;
    }

    /**
     * Set townDoctor
     *
     * @param string $townDoctor
     *
     * @return Doctor
     */
    public function setTownDoctor($townDoctor)
    {
        $this->townDoctor = $townDoctor;

        return $this;
    }

    /**
     * Get townDoctor
     *
     * @return string
     */
    public function getTownDoctor()
    {
        return $this->townDoctor;
    }

    /**
     * Set cityDoctor
     *
     * @param string $cityDoctor
     *
     * @return Doctor
     */
    public function setCityDoctor($cityDoctor)
    {
        $this->cityDoctor = $cityDoctor;

        return $this;
    }

    /**
     * Get cityDoctor
     *
     * @return string
     */
    public function getCityDoctor()
    {
        return $this->cityDoctor;
    }

    /**
     * Set workplace
     *
     * @param string $workplace
     *
     * @return Doctor
     */
    public function setWorkplace($workplace)
    {
        $this->workplace = $workplace;

        return $this;
    }

    /**
     * Get workplace
     *
     * @return string
     */
    public function getWorkplace()
    {
        return $this->workplace;
    }

    /**
     * Set workplaceName
     *
     * @param string $workplaceName
     *
     * @return Doctor
     */
    public function setWorkplaceName($workplaceName)
    {
        $this->workplaceName = $workplaceName;

        return $this;
    }

    /**
     * Get workplaceName
     *
     * @return string
     */
    public function getWorkplaceName()
    {
        return $this->workplaceName;
    }

    /**
     * Set emailDoctor
     *
     * @param string $emailDoctor
     *
     * @return Doctor
     */
    public function setEmailDoctor($emailDoctor)
    {
        $this->emailDoctor = $emailDoctor;

        return $this;
    }

    /**
     * Get emailDoctor
     *
     * @return string
     */
    public function getEmailDoctor()
    {
        return $this->emailDoctor;
    }

    /**
     * Set passwordDoctor
     *
     * @param string $passwordDoctor
     *
     * @return Doctor
     */
    public function setPasswordDoctor($passwordDoctor)
    {
        $this->passwordDoctor = $passwordDoctor;

        return $this;
    }

    /**
     * Get passwordDoctor
     *
     * @return string
     */
    public function getPasswordDoctor()
    {
        return $this->passwordDoctor;
    }

    /**
     * Set photoDoctor
     *
     * @param string $photoDoctor
     *
     * @return Doctor
     */
    public function setPhotoDoctor($photoDoctor)
    {
        $this->photoDoctor = $photoDoctor;

        return $this;
    }

    /**
     * Get photoDoctor
     *
     * @return string
     */
    public function getPhotoDoctor()
    {
        return $this->photoDoctor;
    }

    /**
     * Set createdAtDoctor
     *
     * @param \DateTime $createdAtDoctor
     *
     * @return Doctor
     */
    public function setCreatedAtDoctor($createdAtDoctor)
    {
        $this->createdAtDoctor = $createdAtDoctor;

        return $this;
    }

    /**
     * Get createdAtDoctor
     *
     * @return \DateTime
     */
    public function getCreatedAtDoctor()
    {
        return $this->createdAtDoctor;
    }

    /**
     * Set isActiveDoctor
     *
     * @param boolean $isActiveDoctor
     *
     * @return Doctor
     */
    public function setIsActiveDoctor($isActiveDoctor)
    {
        $this->isActiveDoctor = $isActiveDoctor;

        return $this;
    }

    /**
     * Get isActiveDoctor
     *
     * @return bool
     */
    public function getIsActiveDoctor()
    {
        return $this->isActiveDoctor;
    }

    /**
     * Set lastLoginDoctor
     *
     * @param \DateTime $lastLoginDoctor
     *
     * @return Doctor
     */
    public function setLastLoginDoctor($lastLoginDoctor)
    {
        $this->lastLoginDoctor = $lastLoginDoctor;

        return $this;
    }

    /**
     * Get lastLoginDoctor
     *
     * @return \DateTime
     */
    public function getLastLoginDoctor()
    {
        return $this->lastLoginDoctor;
    }

    /**
     * Set isCompleted
     *
     * @param boolean $isCompleted
     *
     * @return Doctor
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * Get isCompleted
     *
     * @return boolean
     */
    public function getIsCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->evaluations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add evaluation
     *
     * @param \Doctors\AdminBundle\Entity\Evaluation $evaluation
     *
     * @return Doctor
     */
    public function addEvaluation(\Doctors\AdminBundle\Entity\Evaluation $evaluation)
    {
        $this->evaluations[] = $evaluation;

        return $this;
    }

    /**
     * Remove evaluation
     *
     * @param \Doctors\AdminBundle\Entity\Evaluation $evaluation
     */
    public function removeEvaluation(\Doctors\AdminBundle\Entity\Evaluation $evaluation)
    {
        $this->evaluations->removeElement($evaluation);
    }

    /**
     * Get evaluations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvaluations()
    {
        return $this->evaluations;
    }

    /**
     * Set speciality
     *
     * @param \Doctors\AdminBundle\Entity\Speciality $speciality
     *
     * @return Doctor
     */
    public function setSpeciality(\Doctors\AdminBundle\Entity\Speciality $speciality = null)
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * Get speciality
     *
     * @return \Doctors\AdminBundle\Entity\Speciality
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set startTimeWork
     *
     * @param \DateTime $startTimeWork
     *
     * @return Doctor
     */
    public function setStartTimeWork($startTimeWork)
    {
        $this->startTimeWork = $startTimeWork;

        return $this;
    }

    /**
     * Get startTimeWork
     *
     * @return \DateTime
     */
    public function getStartTimeWork()
    {
        return $this->startTimeWork;
    }

    /**
     * Set endTimeWork
     *
     * @param \DateTime $endTimeWork
     *
     * @return Doctor
     */
    public function setEndTimeWork($endTimeWork)
    {
        $this->endTimeWork = $endTimeWork;

        return $this;
    }

    /**
     * Get endTimeWork
     *
     * @return \DateTime
     */
    public function getEndTimeWork()
    {
        return $this->endTimeWork;
    }

    /**
     * Add appointment
     *
     * @param \Doctors\AdminBundle\Entity\Appointment $appointment
     *
     * @return Doctor
     */
    public function addAppointment(\Doctors\AdminBundle\Entity\Appointment $appointment)
    {
        $this->appointments[] = $appointment;

        return $this;
    }

    /**
     * Remove appointment
     *
     * @param \Doctors\AdminBundle\Entity\Appointment $appointment
     */
    public function removeAppointment(\Doctors\AdminBundle\Entity\Appointment $appointment)
    {
        $this->appointments->removeElement($appointment);
    }

    /**
     * Get appointments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAppointments()
    {
        return $this->appointments;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Doctor
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set lattitude
     *
     * @param string $lattitude
     *
     * @return Doctor
     */
    public function setLattitude($lattitude)
    {
        $this->lattitude = $lattitude;

        return $this;
    }

    /**
     * Get lattitude
     *
     * @return string
     */
    public function getLattitude()
    {
        return $this->lattitude;
    }
}
