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
            'admissions@ice.cam.ac.uk' => 'ICE Admissions'
        ];
    }

    public function preCompile()
    {

    }
}