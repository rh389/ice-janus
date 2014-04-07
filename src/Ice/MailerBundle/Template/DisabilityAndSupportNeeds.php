<?php

namespace Ice\MailerBundle\Template;


class DisabilityAndSupportNeeds extends DefaultTemplate
{
    /**
     * @return array
     */
    public function getFrom()
    {
        return [
            'da@ice.cam.ac.uk' => 'Disability advisor'
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

}
