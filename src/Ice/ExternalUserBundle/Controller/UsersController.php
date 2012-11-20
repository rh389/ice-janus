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
     * @Route("/users/{user}", requirements={"user"="\d+"}, defaults={"_format"="json"}, name="get_user")
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
     *      201="Returned when user successfully created",
     *      400="Returned when there is a validation error"
     *   }
     * )
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

        return new RedirectResponse($this->generateUrl('get_user', array('user' => $user->getId()), true));
    }

}
