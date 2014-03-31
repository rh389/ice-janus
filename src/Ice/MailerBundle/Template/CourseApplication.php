<?php

namespace Ice\MailerBundle\Template;


class CourseApplication extends DefaultTemplate
{
    /**
     * @return array
     */
    public function getFrom()
    {
        return [
            'registration@ice.cam.ac.uk' => 'Institute of Continuing Education'
        ];
    }

    /**
     * Returns an array in the form:
     *
     * array(
     *      'john@doe.com' => 'John Doe'
     * )
     *
     * @return array
     */
    public function getBCC()
    {
        return [
            'ice.admissions@ice.cam.ac.uk'=>'ICE Admissions'
        ];
    }

    /**
     * @return string
     */
    protected function getBodyPlainTemplate()
    {
        $key = $this->getTemplateKey();
        return 'IceMailerBundle:'.$this->getTemplateName().':'.$key.'body.plain.twig';
    }

    /**
     * @return string
     */
    protected function getBodyHtmlTemplate()
    {
        $key = $this->getTemplateKey();
        return 'IceMailerBundle:'.$this->getTemplateName().':'.$key.'body.html.twig';
    }

    protected function getTemplateKey()
    {
        $vars = $this->getVars();
        if (isset($vars['templateKey'])) {
            return $vars['templateKey'] . '.';
        }
    }
}