<?php

namespace Ice\ExternalUserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Ice\ExternalUserBundle\Entity\User;

use Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UsersController extends FOSRestController
{
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('IceExternalUserBundle:User')->findAll();

        $view = $this->view($users, 200);

        return $this->handleView($view);
    }

    /**
     * @ParamConverter("user", class="IceExternalUserBundle:User")
     *
     * @param \Ice\ExternalUserBundle\Entity\User $user
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getUserAction(User $user)
    {
        if (!$user) {
            return new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("User with ID %s does not exist.", $id);
        }

        $view = $this->view($user, 200);
        return $this->handleView($view);
    }

    public function postUsersAction()
    {
        return $this->processForm(new User());
    }

    private function processForm(User $user)
    {
        $statusCode = $this->getRequest()->isMethod('POST') ? 201 : 204;

        $form = $this->createForm($this->container->get('ice_external_user.registration.form.type'), $user);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $user
                ->setUsername(uniqid())
                ->setEnabled(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new RedirectResponse($this->generateUrl('get_user', array('user' => $user->getId()), true), $statusCode);
        }

        $view = $this->view($form);

        return $this->handleView($view, 400);
    }

    public function getUsersAuthenticateAction()
    {
        $username = $this->getUser()->getUsername();

        $user = $this->getDoctrine()->getRepository('IceExternalUserBundle:User')->findOneBy(array('username' => $username));

        return new RedirectResponse($this->generateUrl('get_user', array('user' => $user->getId()), true));
    }

}
