<?php

namespace Doctors\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TokenUser
 *
 * @ORM\Table(name="token_user")
 * @ORM\Entity(repositoryClass="Doctors\AdminBundle\Repository\TokenUserRepository")
 */
class TokenUser
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
     * @ORM\Column(name="token", type="text", nullable=true)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="Doctors\AdminBundle\Entity\User")
     */
    private $user;

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
     * Set token
     *
     * @param string $token
     *
     * @return TokenUser
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set user
     *
     * @param \Doctors\AdminBundle\Entity\User $user
     *
     * @return TokenUser
     */
    public function setUser(\Doctors\AdminBundle\Entity\User $user = null)
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
}
