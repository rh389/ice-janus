<?php
namespace Ice\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 */
class CcRecipient extends Address
{
    /**
     * @var Mail
     *
     * @ORM\ManyToOne(targetEntity="Mail", inversedBy="ccRecipients")
     */
    private $mail;

    /**
     * @param \Ice\MailerBundle\Entity\Mail $mail
     * @return CcRecipient
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