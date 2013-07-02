<?php
namespace Ice\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 */
class BccRecipient extends Address
{
    /**
     * @var Mail
     *
     * @ORM\ManyToOne(targetEntity="Mail", inversedBy="bccRecipients")
     */
    private $mail;

    /**
     * @param \Ice\MailerBundle\Entity\Mail $mail
     * @return BccRecipient
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return \Ice\MailerBundle\Entity\Mail
     */
    public function getMail()
    {
        return $this->mail;
    }
}