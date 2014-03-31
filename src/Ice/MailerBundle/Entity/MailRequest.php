<?php
namespace Ice\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MailRequest
 * @package Ice\MailerBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="ice_mailer_mailrequest")
 */
class MailRequest
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
     * @var Mail[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Mail", mappedBy="request", cascade="persist")
     */
    protected $mails;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Template name cannot be null")
     * @ORM\Column(name="template_name", type="string")
     */
    private $templateName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var array
     */
    private $vars = array();

    /**
     * @var string
     * @ORM\Column(name="serialized_vars", type="text")
     */
    private $serializedVars = "";

    /**
     * Initialise variables
     */
    function __construct()
    {
        $this->mails = new ArrayCollection();
        $this->created = new \DateTime();
    }

    /**
     * @param int $id
     * @return MailRequest
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Mail[]|ArrayCollection $mails
     * @return MailRequest
     */
    public function setMails(ArrayCollection $mails)
    {
        $this->mails = $mails;
        foreach ($this->mails as $mail) {
            $mail->setRequest($this);
        }
        return $this;
    }

    /**
     * @param Mail $mail
     * @return MailRequest
     */
    public function addMail(Mail $mail)
    {
        $mail->setRequest($this);
        $this->mails->add($mail);
        return $this;
    }

    /**
     * @param Mail $mail
     * @return $this
     */
    public function removeMail(Mail $mail)
    {
        $this->mails->removeElement($mail);
        return $this;
    }

    /**
     * @return Mail[]|ArrayCollection
     */
    public function getMails()
    {
        return $this->mails;
    }

    /**
     * @param string $templateName
     * @return MailRequest
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * @param \DateTime $created
     * @return MailRequest
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param array $vars
     * @return MailRequest
     */
    public function setVars($vars)
    {
        if (null === $vars) {
            $vars = [];
        }

        $this->vars = $vars;
        $this->serializedVars = serialize($vars);
        return $this;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @param string $serializedVars
     * @return MailRequest
     */
    public function setSerializedVars($serializedVars)
    {
        $this->serializedVars = $serializedVars;
        $this->vars = unserialize($serializedVars);
        return $this;
    }

    /**
     * @return string
     */
    public function getSerializedVars()
    {
        return $this->serializedVars;
    }
}
