<?php
namespace Ice\MailerBundle\Entity;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Mapping as ORM;
use Ice\ExternalUserBundle\Entity\User;
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Mail
 * @package Ice\MailerBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="ice_mailer_mail")
 */
class Mail
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Ice\ExternalUserBundle\Entity\User")
     */
    private $recipient;

    /**
     * @var MailRequest
     *
     * @ORM\ManyToOne(targetEntity="MailRequest", inversedBy="mails")
     */
    private $request;

    /**
     * @var string
     *
     * @ORM\Column(nullable = true, name="compiled_body_plain", type="text")
     */
    private $compiledBodyPlain;

    /**
     * @var string
     *
     * @ORM\Column(nullable = true, name="compiled_body_html", type="text")
     */
    private $compiledBodyHtml;

    /**
     * @var string
     *
     * @ORM\Column(nullable = true, name="compiled_subject", type="text")
     */
    private $compiledSubject;

    /**
     * @var string
     *
     * @ORM\Column(nullable = true, name="from_address", type="string")
     */
    private $fromAddress;

    /**
     * @var string
     *
     * @ORM\Column(nullable = true, name="from_name", type="string")
     */
    private $fromName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(nullable=true, type="datetime")
     */
    private $compiled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(nullable=true, type="datetime")
     */
    private $sent;

    /**
     * @var ToRecipient[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ToRecipient", mappedBy="mail", cascade="persist")
     */
    protected $toRecipients;

    /**
     * @var CcRecipient[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CcRecipient", mappedBy="mail", cascade="persist")
     */
    protected $ccRecipients;

    /**
     * @var BccRecipient[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BccRecipient", mappedBy="mail", cascade="persist")
     */
    protected $bccRecipients;

    /**
     * Initialise variables
     */
    function __construct()
    {
        $this->toRecipients = new ArrayCollection();
        $this->ccRecipients = new ArrayCollection();
        $this->bccRecipients = new ArrayCollection();
    }

    /**
     * @param int $id
     * @return Mail
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
     * @param \Ice\ExternalUserBundle\Entity\User $recipient
     * @return Mail
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return \Ice\ExternalUserBundle\Entity\User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param \Ice\MailerBundle\Entity\MailRequest $request
     * @return Mail
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return \Ice\MailerBundle\Entity\MailRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $compiledBodyPlain
     * @return Mail
     */
    public function setCompiledBodyPlain($compiledBodyPlain)
    {
        $this->compiledBodyPlain = $compiledBodyPlain;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompiledBodyPlain()
    {
        return $this->compiledBodyPlain;
    }

    /**
     * @param string $compiledBodyHtml
     * @return Mail
     */
    public function setCompiledBodyHtml($compiledBodyHtml)
    {
        $this->compiledBodyHtml = $compiledBodyHtml;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompiledBodyHtml()
    {
        return $this->compiledBodyHtml;
    }

    /**
     * @param string $compiledSubject
     * @return Mail
     */
    public function setCompiledSubject($compiledSubject)
    {
        $this->compiledSubject = $compiledSubject;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompiledSubject()
    {
        return $this->compiledSubject;
    }

    /**
     * @param \DateTime $compiled
     * @return Mail
     */
    public function setCompiled($compiled)
    {
        $this->compiled = $compiled;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCompiled()
    {
        return $this->compiled;
    }

    /**
     * @param \DateTime $sent
     * @return Mail
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return array_merge(
            [
                'recipient'=>$this->getRecipient()
            ],
            $this->getRequest()->getVars()
        );
    }

    /**
     * @param string $fromAddress
     * @return Mail
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * @param string $fromName
     * @return Mail
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Return an array of the form [ 'john.doe@example.com' => 'John Doe' ]
     *
     * @return array
     */
    public function getFromArray()
    {
        return [
            $this->getFromAddress() => $this->getFromName()
        ];
    }

    /**
     * @param array $fromArray
     * @return Mail
     */
    public function setFromArray(array $fromArray)
    {
        $this->setFromAddress(key($fromArray));
        $this->setFromName(current($fromArray));
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Ice\MailerBundle\Entity\BccRecipient[] $bccRecipients
     * @return Mail
     */
    public function setBccRecipients(ArrayCollection $bccRecipients)
    {
        $this->bccRecipients = $bccRecipients;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Ice\MailerBundle\Entity\BccRecipient[]
     */
    public function getBccRecipients()
    {
        return $this->bccRecipients;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Ice\MailerBundle\Entity\CcRecipient[] $ccRecipients
     * @return Mail
     */
    public function setCcRecipients(ArrayCollection $ccRecipients)
    {
        $this->ccRecipients = $ccRecipients;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Ice\MailerBundle\Entity\CcRecipient[]
     */
    public function getCcRecipients()
    {
        return $this->ccRecipients;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Ice\MailerBundle\Entity\ToRecipient[] $toRecipients
     * @return Mail
     */
    public function setToRecipients(ArrayCollection $toRecipients)
    {
        $this->toRecipients = $toRecipients;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Ice\MailerBundle\Entity\ToRecipient[]
     */
    public function getToRecipients()
    {
        return $this->toRecipients;
    }

    /**
     * Accepts an array in the form:
     *
     * array('john@doe.com' => 'John Doe')
     *
     * @param array $recipients
     * @return Mail
     */
    public function setToRecipientsByArray(array $recipients = array())
    {
        $collection = new ArrayCollection();
        foreach ($recipients as $address=>$name) {
            $collection->add(
                (new ToRecipient())
                    ->setMail($this)
                    ->setAddress($address)
                    ->setName($name)
            );
        }
        $this->setToRecipients($collection);
        return $this;
    }

    /**
     * Accepts an array in the form:
     *
     * array('john@doe.com' => 'John Doe')
     *
     * @param array $recipients
     * @return Mail
     */
    public function setCcRecipientsByArray(array $recipients = array())
    {
        $collection = new ArrayCollection();
        foreach ($recipients as $address=>$name) {
            $collection->add(
                (new CcRecipient())
                    ->setMail($this)
                    ->setAddress($address)
                    ->setName($name)
            );
        }
        $this->setCcRecipients($collection);
        return $this;
    }

    /**
     * Accepts an array in the form:
     *
     * array('john@doe.com' => 'John Doe')
     *
     * @param array $recipients
     * @return Mail
     */
    public function setBccRecipientsByArray(array $recipients = array())
    {
        $collection = new ArrayCollection();
        foreach ($recipients as $address=>$name) {
            $collection->add(
                (new BccRecipient())
                    ->setMail($this)
                    ->setAddress($address)
                    ->setName($name)
            );
        }
        $this->setBccRecipients($collection);
        return $this;
    }
}