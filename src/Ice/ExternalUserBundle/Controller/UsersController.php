<?php

namespace Ice\ExternalUserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Ice\ExternalUserBundle\Entity\User;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UsersController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/users", name="get_users", defaults={"_format"="json"})
     * @Method("GET")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of User"
     * )
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('IceExternalUserBundle:User')->findAll();

        $view = $this->view($users, 200);

        return $this->handleView($view);
    }


    /**
     * @param \Ice\ExternalUserBundle\Entity\User $user
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/users/{username}", requirements={"username"="[a-z]{2,}[0-9]+"}, defaults={"_format"="json"}, name="get_user")
     * @Method("GET")
     * @ParamConverter("user", class="IceExternalUserBundle:User")
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Returns a single User",
     *   return="Ice\ExternalUserBundle\Entity\User"
     * )
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/users", name="register_user")
     * @Method("POST")
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Create a new User",
     *   input="Ice\ExternalUserBundle\Form\Type\RegistrationFormType",
     *   statusCodes={
     *      201="Returned when User successfully created",
     *      400="Returned when there is a validation error"
     *   }
     * )
     */
    public function postUsersAction()
    {
        $formName = $this->container->get('ice_external_user.registration.form.type');
        return $this->processForm($formName, new User());
    }

    /**
     * @param \Ice\ExternalUserBundle\Entity\User $user
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/users/{username}", requirements={"username"="[a-z]{2,}[0-9]+"}, name="update_user")
     * @Method("PUT")
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Update an existing User",
     *   input="Ice\ExternalUserBundle\Form\Type\UpdateFormType",
     *   statusCodes={
     *     204="Returned when User successfully updated",
     *     400="Returned when there is a validation error"
     *   }
     * )
     */
    public function putUsersAction(User $user)
    {
        $formName = $this->container->get('ice_external_user.update.form.type');
        return $this->processForm($formName, $user);
    }

    private function processForm($formName, User $user)
    {
        $statusCode = $this->getRequest()->isMethod('POST') ? 201 : 204;

        $form = $this->createForm($formName, $user);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            // Only generate a username on the original registration
            if ($this->getRequest()->isMethod('POST')) {
                /** @var $usernameClient \Guzzle\Service\Client */
                $usernameClient = $this->get('janus_username.client');
                $command = $usernameClient->getCommand('RegisterUsername', array('initials' => $user->getInitials()));
                $command->prepare();
                $command->getRequest()->setHeader('Content-Type', 'application/json');
                $usernameClient->execute($command);
                $response = $command->getResult();
                $username = $response['username'];

                $user
                    ->setUsername($username)
                    ->setEnabled(true); // User won't need to be enabled on update
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $view = $this->view($user, $statusCode);
        } else {
            $view = $this->view($form, 400);
        }

        return $this->handleView($view);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/users/authenticate", name="authenticate_user")
     * @Method("GET")
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Authenticate an existing User using HTTP basic authentication",
     *   statusCodes={
     *      302="Returned when authentication is successful",
     *      401="Returned when authentication is not successful"
     *   }
     * )
     */
    public function getUsersAuthenticateAction()
    {
        $username = $this->getUser()->getUsername();

        /** @var $user User */
        $user = $this->getDoctrine()->getRepository('IceExternalUserBundle:User')->findOneBy(array('username' => $username));
        $user->setLastLogin(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new RedirectResponse($this->generateUrl('get_user', array('username' => $user->getUsername()), true));
    }

}
