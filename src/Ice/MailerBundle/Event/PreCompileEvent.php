<?php
namespace Ice\MailerBundle\Event;

/**
 * Class PreCompileEvent
 * @package Ice\MailerBundle\Event
 */
class PreCompileEvent extends MailEvent
{
    /**
     * @var string
     */
    private $templateName;

    /**
     * @param string $templateName
     * @return PreCompileEvent
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
}