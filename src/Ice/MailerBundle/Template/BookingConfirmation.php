<?php
namespace Ice\MailerBundle\Template;

class BookingConfirmation extends DefaultTemplate
{
    /**
     * @return array
     */
    public function getFrom()
    {
        return [
            'ice.admissions@ice.cam.ac.uk' => 'ICE Admissions'
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
     * @param array $vars
     * @return AbstractTemplate
     */
    public function setVars(array $vars)
    {
        if (isset($vars['course'])) {
            $course = $vars['course'];

            $vars['showSectionStudyingAtICE'] = !$course['isMst'];
            $vars['showSectionCourseMaterials'] = !$course['isMst'];
        }
        return parent::setVars($vars);
    }
}