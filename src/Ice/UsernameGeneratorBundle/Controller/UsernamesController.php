<?php

namespace Ice\UsernameGeneratorBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Ice\UsernameGeneratorBundle\Form\Type\RequestUsernameFormType,
    Ice\UsernameGeneratorBundle\Entity\Username;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UsernamesController extends FOSRestController
{
    /**
     * @Route("/api/usernames", name="generate_username")
     * @Method("POST")
     */
    public function postUsernamesAction()
    {
        $form = $this->createForm(new RequestUsernameFormType());
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $data = $form->getData();
            $initials = $data['initials'];

            $username = new Username();
            $username->setInitials($initials);

            $em = $this->getDoctrine()->getManager();

            /** @var $repository \Ice\UsernameGeneratorBundle\Entity\UsernameRepository */
            $repository = $this->getDoctrine()->getRepository('IceUsernameGeneratorBundle:Username');

            // There is potential for a race condition here, whereby two clients could request a
            // username for the same initials before the first clients request had been persisted, causing
            // an integrity constraint violation.
            //
            // This part of the controller keeps trying to persist a new username until it is successful.
            $usernameSuccessfullyGenerated = false;
            do {
                $result = $repository->findCurrentSequenceForInitials($initials);
                $sequence = $result[1];

                if ($sequence) {
                    $username->setSequence($sequence + 1);
                } else {
                    $username->setSequence(1);
                }

                $em->persist($username);

                try {
                    $em->flush();
                    $usernameSuccessfullyGenerated = true;
                } catch(\Doctrine\DBAL\DBALException $e) {
                    // Get the previous exception, which will be a PDOException if there was an integrity constraint
                    // violation
                    $previousException = $e->getPrevious();

                    // Integrity constraint violation as a result of a race condition.
                    // The loop will continue to retry until it is successful
                    if ("PDOException" === get_class($previousException) && 23000 == $previousException->getCode()) {
                        // EntityManager will closed due to the exception.
                        // Reset so it can be used during the next iteration of the loop.
                        $this->getDoctrine()->resetManager();
                        $em = $this->getDoctrine()->getManager();
                    }

                    throw $e;
                }
            } while($usernameSuccessfullyGenerated !== true);

            return new RedirectResponse($this->generateUrl("get_username", array("username" => $username->getGeneratedUsername())), 201);
        }

        $view = $this->view($form, 400);
        return $this->handleView($view);
    }

    /**
     * @Route("/api/usernames/{username}", name="get_username")
     * @Method("GET")
     */
    public function getUsernameAction($username)
    {
        $foundUsername = $this->getDoctrine()->getRepository('IceUsernameGeneratorBundle:Username')->findOneBy(array('generatedUsername' => $username));

        if (!$foundUsername) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(sprintf("Username with username '%s' could not be found", $username));
        }

        $view = $this->view($foundUsername);
        return $this->handleView($view);
    }
}
