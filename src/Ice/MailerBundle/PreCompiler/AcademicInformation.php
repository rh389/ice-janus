<?php
namespace Ice\MailerBundle\PreCompiler;

use Ice\MailerBundle\Event\TemplateEvent;
use Ice\MailerBundle\Template\BookingConfirmation;
use Ice\MinervaClientBundle\Service\MinervaClient;

/**
 * Class AcademicInformation
 * @package Ice\MailerBundle\Precompiler
 */
class AcademicInformation
{
    /**
     * @var MinervaClient
     */
    private $minervaClient;

    /**
     * @param \Ice\MinervaClientBundle\Service\MinervaClient $minervaClient
     * @return AcademicInformation
     */
    public function setMinervaClient($minervaClient)
    {
        $this->minervaClient = $minervaClient;
        return $this;
    }

    /**
     * @return \Ice\MinervaClientBundle\Service\MinervaClient
     */
    public function getMinervaClient()
    {
        return $this->minervaClient;
    }

    /**
     * Inject academic / booking information into the template
     *
     * @param TemplateEvent $event
     */
    public function onPreCompile(TemplateEvent $event)
    {
        $template = $event->getTemplate();
        if ($template instanceof BookingConfirmation) {
            $vars = $template->getVars();
            $iceId = $vars['academicInformation']['iceId'];
            $courseId = $vars['academicInformation']['courseId'];
            $academicInformation = $this->getMinervaClient()->getAcademicInformation($iceId, $courseId);
            $vars['academicInformation'] = [];

            foreach ($academicInformation->getActiveBooking()->getBookingItems() as $bookingItem) {
                if($bookingItem->isCourseAccommodation()) {
                    $vars['accommodationChoiceDescription'] = $bookingItem->getDescription();
                    break;
                }
            }

            $template->setVars($vars);
        }
    }
}