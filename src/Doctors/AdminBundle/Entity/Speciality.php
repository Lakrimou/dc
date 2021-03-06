<?php

namespace Doctors\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Speciality
 *
 * @ORM\Table(name="speciality")
 * @ORM\Entity(repositoryClass="Doctors\AdminBundle\Repository\SpecialityRepository")
 */
class Speciality
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
     * @ORM\Column(name="nameSpeciality", type="string", length=255)
     */
    private $nameSpeciality;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActiveSpeciality", type="boolean")
     */
    private $isActiveSpeciality;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAtSpeciality", type="datetime")
     */
    private $createdAtSpeciality;


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
     * Set nameSpeciality
     *
     * @param string $nameSpeciality
     *
     * @return Speciality
     */
    public function setNameSpeciality($nameSpeciality)
    {
        $this->nameSpeciality = $nameSpeciality;

        return $this;
    }

    /**
     * Get nameSpeciality
     *
     * @return string
     */
    public function getNameSpeciality()
    {
        return $this->nameSpeciality;
    }

    /**
     * Set isActiveSpeciality
     *
     * @param boolean $isActiveSpeciality
     *
     * @return Speciality
     */
    public function setIsActiveSpeciality($isActiveSpeciality)
    {
        $this->isActiveSpeciality = $isActiveSpeciality;

        return $this;
    }

    /**
     * Get isActiveSpeciality
     *
     * @return bool
     */
    public function getIsActiveSpeciality()
    {
        return $this->isActiveSpeciality;
    }

    /**
     * Set createdAtSpeciality
     *
     * @param \DateTime $createdAtSpeciality
     *
     * @return Speciality
     */
    public function setCreatedAtSpeciality($createdAtSpeciality)
    {
        $this->createdAtSpeciality = $createdAtSpeciality;

        return $this;
    }

    /**
     * Get createdAtSpeciality
     *
     * @return \DateTime
     */
    public function getCreatedAtSpeciality()
    {
        return $this->createdAtSpeciality;
    }
}
