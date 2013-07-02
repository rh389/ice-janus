<?php
namespace Ice\MailerBundle\Template;

use Ice\MailerBundle\Entity\Mail;

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

    /**
     * Returns an array in the form:
     *
     * array('john@doe.com' => 'John Doe')
     *
     * @return array
     */
    public function getFrom();

    /**
     * Returns an array in the form:
     *
     * array('john@doe.com' => 'John Doe')
     *
     * @return array
     */
    public function getTo();

    /**
     * Returns an array in the form:
     *
     * array('john@doe.com' => 'John Doe')
     *
     * @return array
     */
    public function getCC();

    /**
     * Returns an array in the form:
     *
     * array('john@doe.com' => 'John Doe')
     *
     * @return array
     */
    public function getBCC();

    /**
     * Returns the reference of this template
     *
     * @return string
     */
    public function getTemplateName();

    /**
     * Returns the vars passed to this template
     *
     * @return array
     */
    public function getVars();
}