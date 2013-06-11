<?php
namespace Ice\MailerBundle\Template;

abstract class AbstractTemplate implements TemplateInterface
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var array
     */
    protected $vars;

    /**
     * @param \Ice\MailerBundle\Template\Manager $manager
     * @return AbstractTemplate
     */
    public function setManager(Manager $manager)
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * @return \Ice\MailerBundle\Template\Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param array $vars
     * @return AbstractTemplate
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }
}