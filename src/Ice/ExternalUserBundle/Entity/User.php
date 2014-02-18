<?php

namespace Ice\ExternalUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Entity\User as BaseUser;

use Symfony\Component\Validator\Constraints as Assert,
    Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use JMS\Serializer\Annotation\ExclusionPolicy,
    JMS\Serializer\Annotation\Expose,
    JMS\Serializer\Annotation\SerializedName;

use Ice\ExternalUserBundle\Util\String as StringUtil;

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
 *
 * @UniqueEntity(fields="emailCanonical", message="This email address is already associated with another account", groups={"rest_register", "rest_update", "rest_set_email"})
 *
 * @ExclusionPolicy("all")
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
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please provide your title", groups={"rest_register", "rest_update"})
     *
     * @Expose()
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="first_names", type="string")
     *
     * @Assert\NotBlank(message="Please provide your first name(s)", groups={"rest_register", "rest_update"})
     * @Assert\Regex(pattern="/\p{L}+/u", message="First name(s) must include at least one letter", groups={"rest_register", "rest_update"})
     *
     * @Expose()
     * @SerializedName("firstNames")
     */
    protected $firstNames;

    /**
     * @var string
     *
     * @ORM\Column(name="middle_names", type="string", nullable=true)
     *
     * @Expose()
     * @SerializedName("middleNames")
     */
    protected $middleNames;

    /**
     * @var string
     *
     * @ORM\Column(name="last_names", type="string")
     *
     * @Assert\NotBlank(message="Please provide your last name(s)", groups={"rest_register", "rest_update"})
     * @Assert\Regex(pattern="/\p{L}+/u", message="Last name(s) must include at least one letter", groups={"rest_register", "rest_update"})
     *
     * @Expose()
     * @SerializedName("lastNames")
     */
    protected $lastNames;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="date", nullable=true)
     *
     * @Assert\Date
     *
     * @Expose
     */
    protected $dob;

    /**
     * @var Attribute[]
     *
     * @ORM\OneToMany(targetEntity="Attribute", mappedBy="user")
     *
     * @Expose
     */
    protected $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();

        parent::__construct();
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setFirstNames($firstName)
    {
        $this->firstNames = $firstName;
        return $this;
    }

    public function getFirstNames()
    {
        return $this->firstNames;
    }

    public function setMiddleNames($middleName)
    {
        $this->middleNames = $middleName;
        return $this;
    }

    public function getMiddleNames()
    {
        return $this->middleNames;
    }

    public function setLastNames($lastNames)
    {
        $this->lastNames = $lastNames;
        return $this;
    }

    public function getLastNames()
    {
        return $this->lastNames;
    }

    public function getInitials()
    {
        $names = array(
            $this->getFirstNames(),
            $this->getMiddleNames(),
            $this->getLastNames(),
        );

        $name = implode(" ", $names);
        $name = mb_strtolower($name, 'UTF-8');
        return StringUtil::getInitials($name);
    }

    public function getFullName()
    {
        $names = array(
            $this->getTitle(),
            $this->getFirstNames(),
            $this->getMiddleNames(),
            $this->getLastNames()
        );

        $names = array_filter($names);

        return implode(" ", $names);
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param $fieldName
     * @return Attribute
     */
    public function getAttributeByFieldName($fieldName)
    {
        return $this->getAttributes()->filter(function($attribute) use ($fieldName) {
            return $attribute->getFieldName() === $fieldName;
        })->first(); // There will only be one matching element
    }

    /**
     * @param Attribute $attribute
     * @return User
     */
    public function addAttribute(Attribute $attribute)
    {
        $this->getAttributes()->add($attribute);
        return $this;
    }

    /**
     * @param \DateTime $dob
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }
}
