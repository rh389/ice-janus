<?php
namespace Ice\MailerBundle\PreCompiler;

use Ice\MailerBundle\Event\PreCompileEvent;
use Ice\MailerBundle\Template\BookingConfirmation;
use Ice\VeritasClientBundle\Service\VeritasClient;
use Doctrine\ORM\EntityManager;

/**
 * Class AccountInformation
 * @package Ice\MailerBundle\Precompiler
 */
class AccountInformation
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return AccountInformation
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Inject account information into the template
     *
     * @param PreCompileEvent $event
     */
    public function onPreCompile(PreCompileEvent $event)
    {
        if ($recipient = $event->getMail()->getRecipient()) {
            $vars = $event->getTemplate()->getVars();
            $vars['recipient'] = $recipient;
            $event->getTemplate()->setVars($vars);
        }
    }
}