<?php

namespace Ice\ExternalUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Entity\User as BaseUser;

/**
 * Ice\ExternalUserBundle\Entity\User
 *
 * @ORM\Table(name="ice_user")
 * @ORM\Entity(repositoryClass="Ice\ExternalUserBundle\Entity\UserRepository")
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


    public function __construct()
    {
        parent::__construct();
    }
}
