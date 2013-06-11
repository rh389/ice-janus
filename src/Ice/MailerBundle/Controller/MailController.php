<?php

namespace Ice\MailerBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Ice\MailerBundle\Event\MailerEvents;
use Ice\MailerBundle\Event\RequestEvent;
use Ice\MailerBundle\Form\Type\CreateMailRequestType;
use Symfony\Component\Form\FormTypeInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MailController extends FOSRestController
{
    /**
     * @return \FOS\RestBundle\View\View
     *
     * @Route("mail", name="post_mail")
     * @Method("POST")
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Create an Attribute for a User",
     *   input="Ice\ExternalUserBundle\Form\Type\UpdateAttributeType",
     *   output="Ice\ExternalUserBundle\Entity\Attribute",
     *   statusCodes={
     *     201="Returned on success",
     *     404="Returned when the User or Attribute does not exist"
     *   }
     * )
     */
    public function postMailAction()
    {
        $form = $this->createForm(new CreateMailRequestType(), null, [
            'em' => $this->getDoctrine()->getManager()
        ]);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $mailRequest = $form->getData();

            $this->getDoctrine()->getManager()->persist($mailRequest);
            $this->getDoctrine()->getManager()->flush();

            $this->get('event_dispatcher')->dispatch(MailerEvents::POST_CREATE_REQUEST,
                (new RequestEvent())->setRequest($mailRequest)
            );

            return $this->view($mailRequest);
        } else {
            return $this->view($form, 400);
        }
    }
}