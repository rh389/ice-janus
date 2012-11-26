<?php

namespace Ice\UsernameGeneratorBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Ice\UsernameGeneratorBundle\Form\Type\RequestUsernameFormType,
    Ice\UsernameGeneratorBundle\Entity\Username;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UsernamesController extends FOSRestController
{
    /**
     * @Route("/api/usernames", name="generate_username")
     * @Method("POST")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Create a new Username",
     *  input="Ice\UsernameGeneratorBundle\Form\Type\RequestUsernameFormType"
     * )
     */
    public function postUsernamesAction()
    {
        $this->get('logger')->info($this->getRequest()->request->all());
        $form = $this->createForm(new RequestUsernameFormType());
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $data = $form->getData();
            $initials = $data['initials'];

            $usernameFormat = $this->container->getParameter('ice_username_generator.username_format');
            $username = new Username($usernameFormat);
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
                    $sequence = $this->container->getParameter('ice_username_generator.sequence_start');
                    $username->setSequence($sequence);
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

            $view = $this->view($username, 201);
        } else {
            $view = $this->view($form, 400);
        }

        return $this->handleView($view);
    }

    /**
     * @Route(
     *   "/api/usernames/{username}",
     *   name="get_username",
     *   requirements={
     *     "username": "[a-z]{2,}[0-9]+"
     *   }
     * )
     * @Method("GET")
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Returns a single Username",
     *   output="Ice\UsernameGeneratorBundle\Entity\Username"
     * )
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
