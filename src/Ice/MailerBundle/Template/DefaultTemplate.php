<?php
namespace Ice\MailerBundle\Template;

class DefaultTemplate extends AbstractTemplate implements TemplateInterface
{
    /**
     * @var string
     */
    protected $templateName;

    /**
     * @param string $templateName
     * @return DefaultTemplate
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

    /**
     * Get the string used for the plain text mail body
     *
     * @return string
     */
    public function getBodyPlain()
    {
        return $this->getManager()->getTemplating()->render(
            $this->getBodyPlainTemplate(),
            $this->getVars()
        );
    }

    /**
     * Get the string to be used for the mail subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->getManager()->getTemplating()->render(
            $this->getSubjectTemplate(),
            $this->getVars()
        );
    }

    /**
     * Returns a string with the mail's HTML body
     *
     * @return string
     */
    public function getBodyHtml()
    {
        return '';
    }

    /**
     * @return string
     */
    protected function getBodyPlainTemplate()
    {
        return 'IceMailerBundle:'.$this->getTemplateName().':body.plain.twig';
    }

    /**
     * @return string
     */
    protected function getSubjectTemplate()
    {
        return 'IceMailerBundle:'.$this->getTemplateName().':subject.plain.twig';
    }
}