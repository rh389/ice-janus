<?php
namespace Ice\MailerBundle\Event;

use Ice\MailerBundle\Template\TemplateInterface;
use Symfony\Component\EventDispatcher\Event;

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
}