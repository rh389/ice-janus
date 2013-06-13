<?php
namespace Ice\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ice\ExternalUserBundle\Entity\User;
use JMS\Serializer\Annotation as JMS;

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
     * @ORM\ManyToOne(targetEntity="MailRequest", inversedBy="mail")
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
     * Initialise variables
     */
    function __construct()
    {

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
}