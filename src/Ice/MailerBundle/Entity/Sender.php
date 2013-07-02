<?php
namespace Ice\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 */
class Sender extends Address
{
    /**
     * @var Mail
     *
     * Note: Although this is set up as a many-to-one association for consistency with other address types, in practice
     * there should only ever be one sender.
     *
     * @ORM\ManyToOne(targetEntity="Mail", inversedBy="senders")
     */
    private $mail;

    /**
     * @param \Ice\MailerBundle\Entity\Mail $mail
     * @return Sender
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