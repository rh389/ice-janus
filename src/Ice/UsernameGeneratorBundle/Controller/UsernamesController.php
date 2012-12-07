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
        $form = $this->createForm(new RequestUsernameFormType());
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $data = $form->getData();
            $initials = $data['initials'];

            /** @var $generator \Ice\UsernameGeneratorBundle\UsernameGenerator */
            $generator = $this->get('ice_username.generator');
            $username = $generator->getUsernameForInitials($initials);

            $view = $this->view($username, 201);
        } else {
            $view = $this->view($form, 400);
        }

        return $this->handleView($view);
    }

    /**
     * @Route(
     *   "/api/usernames/{generatedUsername}",
     *   name="get_username",
     *   requirements={
     *     "generatedUsername": "[a-z]{2,}[0-9]+"
     *   }
     * )
     *
     * @Method("GET")
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Returns a single Username",
     *   output="Ice\UsernameGeneratorBundle\Entity\Username"
     * )
     */
    public function getUsernameAction(Username $username)
    {
        $view = $this->view($username);
        return $this->handleView($view);
    }
}
