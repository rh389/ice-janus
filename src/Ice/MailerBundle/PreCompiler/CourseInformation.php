<?php
namespace Ice\MailerBundle\PreCompiler;

use Ice\MailerBundle\Event\TemplateEvent;
use Ice\MailerBundle\Template\BookingConfirmation;
use Ice\VeritasClientBundle\Service\VeritasClient;

/**
 * Class CourseInformation
 * @package Ice\MailerBundle\Precompiler
 */
class CourseInformation
{
    /**
     * @var VeritasClient
     */
    private $veritasClient;

    /**
     * @param \Ice\VeritasClientBundle\Service\VeritasClient $veritasClient
     * @return CourseInformation
     */
    public function setVeritasClient($veritasClient)
    {
        $this->veritasClient = $veritasClient;
        return $this;
    }

    /**
     * @return \Ice\VeritasClientBundle\Service\VeritasClient
     */
    public function getVeritasClient()
    {
        return $this->veritasClient;
    }

    /**
     * Inject course information into the template
     *
     * @param TemplateEvent $event
     */
    public function onPreCompile(TemplateEvent $event)
    {
        $template = $event->getTemplate();
        if ($template instanceof BookingConfirmation) {
            $vars = $template->getVars();
            $courseId = $vars['academicInformation']['courseId'];
            $course = $this->getVeritasClient()->getCourse($courseId);
            $vars['course'] = [
                'title' => $course->getTitle(),
                'startDate' => $course->getStartDate(),
                'code' => $course->getCode(),
                'endDate' => $course->getEndDate()
            ];
            $template->setVars($vars);
        }
    }
}