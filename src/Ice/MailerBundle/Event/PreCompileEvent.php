<?php
namespace Ice\MailerBundle\Event;

use Ice\MailerBundle\Template\TemplateInterface;
use Symfony\Component\EventDispatcher\Event;
use Ice\MailerBundle\Entity\Mail;

/**
 * Class PreCompileEvent
 * @package Ice\MailerBundle\Event
 */
class PreCompileEvent extends Event
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
     * @return PreCompileEvent
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
     * @return PreCompileEvent
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