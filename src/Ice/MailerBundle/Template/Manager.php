<?php
namespace Ice\MailerBundle\Template;

use Symfony\Bundle\TwigBundle\TwigEngine;

class Manager
{
    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     * @return Manager
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
        return $this;
    }

    /**
     * @return \Symfony\Bundle\TwigBundle\TwigEngine
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @param string $templateName
     * @param array $vars
     * @return TemplateInterface
     */
    public function getMail($templateName, array $vars)
    {
        $templateClass = $this->loadTemplate($templateName);
        $templateClass->setVars($vars);
        return $templateClass;
    }

    /**
     * @param $templateName
     * @return TemplateInterface
     * @throws \RuntimeException
     */
    private function loadTemplate($templateName)
    {
        $className = '\Ice\MailerBundle\Template\\'.$templateName;
        if (class_exists($className, true)) {
            $template = new $className();
            if ($template instanceof AbstractTemplate) {
                $template->setManager($this);
                $template->setTemplateName($templateName);
                return $template;
            } else {
                throw new \RuntimeException("Templates must implement TemplateInterface");
            }
        }
        else {
            $template = new DefaultTemplate();
            $template->setManager($this);
            $template->setTemplateName($templateName);
            return $template;
        }
    }
}