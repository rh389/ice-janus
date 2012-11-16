<?php

namespace Ice\ExternalUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Entity\User as BaseUser;

/**
 * Ice\ExternalUserBundle\Entity\User
 *
 * @ORM\Table(name="ice_user")
 * @ORM\Entity(repositoryClass="Ice\ExternalUserBundle\Entity\UserRepository")
 * @ORM\AttributeOverrides({
 *  @ORM\AttributeOverride(name="email",
 *      column=@ORM\Column(
 *          nullable = true,
 *          unique = true
 *      )
 *  ),
 * @ORM\AttributeOverride(name="emailCanonical",
 *      column=@ORM\Column(
 *          name = "email_canonical",
 *          nullable = true,
 *          unique = true
 *      )
 *  ),
 * })
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_names", type="string")
     */
    protected $firstNames;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string")
     */
    protected $lastName;

    public function __construct()
    {
        parent::__construct();
    }

    public function setFirstNames($firstNames)
    {
        $this->firstNames = $firstNames;
        return $this;
    }

    public function getFirstNames()
    {
        return $this->firstNames;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }
}
