<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Law
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ModelBundle\Entity\LawRepository")
 */
class Law
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="applicationAuthority", type="string", length=255)
     */
    private $applicationAuthority;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Law
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Law
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set applicationAuthority
     *
     * @param string $applicationAuthority
     * @return Law
     */
    public function setApplicationAuthority($applicationAuthority)
    {
        $this->applicationAuthority = $applicationAuthority;

        return $this;
    }

    /**
     * Get applicationAuthority
     *
     * @return string 
     */
    public function getApplicationAuthority()
    {
        return $this->applicationAuthority;
    }
}
