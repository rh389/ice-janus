<?php
namespace Ice\MailerBundle\Template;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Ice\MailerBundle\Entity\Mail;

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
     * @param \Ice\MailerBundle\Entity\Mail $mail
     * @return TemplateInterface
     */
    public function getTemplateByMail(Mail $mail)
    {
        $templateClass = $this->loadTemplate($mail->getRequest()->getTemplateName());
        $templateClass->setMail($mail);
        $templateClass->setVars($mail->getRequest()->getVars());
        return $templateClass;
    }

    /**
     * @param $templateName
     * @return TemplateInterface|AbstractTemplate
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