<?php
namespace Ice\MailerBundle\Template;

class OrderConfirmation extends DefaultTemplate
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
}