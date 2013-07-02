<?php
namespace Ice\MailerBundle\Event;

use Ice\MailerBundle\Template\TemplateInterface;
use Symfony\Component\EventDispatcher\Event;
use Ice\MailerBundle\Entity\Mail;

/**
 * Class TemplateEvent
 * @package Ice\MailerBundle\Event
 */
class TemplateEvent extends Event
{
    /**
     * @var TemplateInterface
     */
    private $template;

    /**
     * @var Mail
     */
    private $mail;

    /**
     * @param \Ice\MailerBundle\Template\TemplateInterface $template
     * @return TemplateEvent
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return \Ice\MailerBundle\Template\TemplateInterface
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param \Ice\MailerBundle\Entity\Mail $mail
     * @return TemplateEvent
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