<?php
namespace Ice\MailerBundle\PreCompiler;

use Ice\MailerBundle\Event\TemplateEvent;
use Ice\MailerBundle\Template\BookingConfirmation;
use Ice\MailerBundle\Template\OrderConfirmation;
use Ice\MercuryClientBundle\Service\MercuryClient;

/**
 * Class OrderInformation
 * @package Ice\MailerBundle\Precompiler
 */
class OrderInformation
{
    /**
     * @var MercuryClient
     */
    private $mercuryClient;

    /**
     * @param \Ice\MercuryClientBundle\Service\MercuryClient $mercuryClient
     * @return OrderInformation
     */
    public function setMercuryClient($mercuryClient)
    {
        $this->mercuryClient = $mercuryClient;
        return $this;
    }

    /**
     * @return \Ice\MercuryClientBundle\Service\MercuryClient
     */
    public function getMercuryClient()
    {
        return $this->mercuryClient;
    }

    /**
     * Inject course information into the template
     *
     * @param TemplateEvent $event
     */
    public function onPreCompile(TemplateEvent $event)
    {
        $template = $event->getTemplate();
        if ($template instanceof OrderConfirmation) {
            $vars = $template->getVars();
            $orderReference = $vars['orderReference'];
            $order = $this->getMercuryClient()->findOrderByReference($orderReference);
            $vars['order'] = $order;
            $template->setVars($vars);
        }
    }
}