<?php

namespace Ice\ExternalUserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Ice\ExternalUserBundle\Entity\User;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UsersController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Get a list of all users"
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('IceExternalUserBundle:User')->findAll();

        $view = $this->view($users, 200);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Get a single user",
     *   return="Ice\ExternalUserBundle\Entity\User"
     * )
     *
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

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Create a new user",
     *   input="Ice\ExternalUserBundle\Form\Type\RegistrationFormType",
     *   statusCodes={
     *      201="Returned when user successfully created",
     *      400="Returned when there is a validation error"
     *   }
     * )
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Authenticate an existing user using HTTP basic",
     *   statusCodes={
     *      302="Returned when authentication is successful",
     *      401="Returned when authentication is not successful"
     *   }
     * )
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getUsersAuthenticateAction()
    {
        $username = $this->getUser()->getUsername();

        $user = $this->getDoctrine()->getRepository('IceExternalUserBundle:User')->findOneBy(array('username' => $username));

        return new RedirectResponse($this->generateUrl('get_user', array('user' => $user->getId()), true));
    }

}
