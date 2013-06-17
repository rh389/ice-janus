<?php

namespace Ice\MailerBundle\Service;

use Ice\MailerBundle\Entity\Mail;
use Ice\MailerBundle\Entity\MailRequest;
use Ice\MailerBundle\Event\MailerEvents;
use Ice\MailerBundle\Event\RequestEvent;
use Ice\MailerBundle\Event\TemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Ice\MailerBundle\Template\Manager;
use Swift_Mailer;

class MailerService implements EventSubscriberInterface
{
    /**
     * @var Registry;
     */
    private $doctrine;

    /**
     * @var Manager;
     */
    private $templateManager;

    /**
     * @var Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @return MailerService
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
        return $this;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param \Swift_Mailer $swiftMailer
     * @return MailerService
     */
    public function setSwiftMailer($swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
        return $this;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getSwiftMailer()
    {
        return $this->swiftMailer;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            MailerEvents::POST_CREATE_REQUEST => 'onPostCreateRequest'
        ];
    }

    /**
     * @param RequestEvent $event
     */
    public function onPostCreateRequest(RequestEvent $event)
    {
        try {
            $mailRequest = $event->getRequest();

            $this->compileMailRequest($mailRequest);

            foreach ($mailRequest->getMails() as $mail) {
                $this->sendCompiledMail($mail);
            }

            $this->getDoctrine()->getManager()->persist($mailRequest);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $e) {
            //Swallow all exceptions since this event is passive - we don't want to disrupt the recording of the request
        }
    }

    /**
     * Attempt to compile each Mail in a MailRequest and set the compiled datetime
     *
     * @param MailRequest $mailRequest
     */
    public function compileMailRequest(MailRequest $mailRequest)
    {
        $manager = $this->getTemplateManager();

        foreach ($mailRequest->getMails() as $mail) {

            $template = $manager->getMail($mailRequest->getTemplateName(), $mail->getVars());

            $this->getEventDispatcher()->dispatch(
                MailerEvents::PRE_COMPILE_MAIL,
                (new TemplateEvent())->setTemplate($template)
            );

            $mail->setCompiledBodyPlain($template->getBodyPlain());
            $mail->setCompiledBodyHtml($template->getBodyHtml());
            $mail->setCompiledSubject($template->getSubject());
            $mail->setFromArray($template->getFrom());
            $mail->setCompiled(new \DateTime());
        }
    }

    /**
     * @param Mail $mail
     */
    public function sendCompiledMail(Mail $mail)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($mail->getCompiledSubject())
            ->setFrom($mail->getFromArray())
            ->setTo($mail->getRecipient()->getEmail())
            ->setBody(
                $mail->getCompiledBodyPlain()
            )
            ->setBody(
                $mail->getCompiledBodyHtml(),
                'text/html'
            )
        ;
        $this->getSwiftMailer()->send($message);
    }

    /**
     * @param \Ice\MailerBundle\Template\Manager $templateManager
     * @return MailerService
     */
    public function setTemplateManager($templateManager)
    {
        $this->templateManager = $templateManager;
        return $this;
    }

    /**
     * @return \Ice\MailerBundle\Template\Manager
     */
    public function getTemplateManager()
    {
        return $this->templateManager;
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @return MailerService
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}