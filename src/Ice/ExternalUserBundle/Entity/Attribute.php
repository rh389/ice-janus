<?php

namespace Ice\ExternalUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ice\ExternalUserBundle\Entity\Attribute
 *
 * @ORM\Table(name="ice_user_attribute")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Attribute
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $fieldName
     *
     * @ORM\Column(name="field_name", type="string", length=255)
     *
     * @Assert\NotBlank(groups={"rest_create", "rest_update"}, message="Field name must be specified")
     */
    private $fieldName;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var string $updatedBy
     *
     * @ORM\Column(name="updated_by", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(groups={"rest_update"}, message="Updated by must be specified")
     */
    private $updatedBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="attributes")
     */
    private $user;


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
     * Set fieldName
     *
     * @param string $fieldName
     * @return Attribute
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    
        return $this;
    }

    /**
     * Get fieldName
     *
     * @return string 
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Attribute
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Attribute
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Attribute
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set updatedBy
     *
     * @param string $updatedBy
     * @return Attribute
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    
        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return string 
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this->user;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * Before the entity is first persisted:
     * * Set created to now
     *
     * @ORM\PrePersist
     */
    public function doPrePersistLifecycleCallbacks()
    {
        $this->setCreated(new \DateTime());
    }

    /**
     * Before the entity is updated:
     * * Set updated to now
     *
     * @ORM\PreUpdate
     */
    public function doPreUpdateLifecycleCallbacks()
    {
        $this->setUpdated(new \DateTime());
    }
}
