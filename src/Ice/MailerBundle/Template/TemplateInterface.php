<?php
namespace Ice\MailerBundle\Template;

interface TemplateInterface
{
    /**
     * Get the string used for the plain text mail body
     *
     * @return string
     */
    public function getBodyPlain();

    /**
     * Get the string to be used for the mail subject
     *
     * @return string
     */
    public function getSubject();

    /**
     * Set the vars necessary for rendering the (twig, etc.) template
     *
     * @param array $vars
     * @return TemplateInterface
     */
    public function setVars(array $vars);

    /**
     * Returns a string with the mail's HTML body
     *
     * @return string
     */
    public function getBodyHtml();
}