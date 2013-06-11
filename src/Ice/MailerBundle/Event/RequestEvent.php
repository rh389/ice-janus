<?php
namespace Ice\MailerBundle\Event;

use Ice\MailerBundle\Entity\MailRequest;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class RequestEvent
 * @package Ice\MailerBundle\Event
 */
class RequestEvent extends Event
{
    /**
     * @var MailRequest;
     */
    private $request;

    /**
     * @param \Ice\MailerBundle\Entity\MailRequest $request
     * @return RequestEvent
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return \Ice\MailerBundle\Entity\MailRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
}