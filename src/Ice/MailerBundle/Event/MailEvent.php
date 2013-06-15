<?php
namespace Ice\MailerBundle\Event;

use Ice\MailerBundle\Entity\Mail;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class MailEvent
 * @package Ice\MailerBundle\Event
 */
class MailEvent extends Event
{
    /**
     * @var Mail
     */
    private $mail;

    /**
     * @param \Ice\MailerBundle\Entity\Mail $mail
     * @return MailEvent
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