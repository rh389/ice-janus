<?php

namespace Ice\ExternalUserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations As Rest;

class UsersController extends FOSRestController
{
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('IceExternalUserBundle:User')->findAll();

        $view = $this->view($users, 200);

        return $this->handleView($view);
    }
}
