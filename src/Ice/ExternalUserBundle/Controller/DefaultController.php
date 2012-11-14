<?php

namespace Ice\ExternalUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction($name)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setEmail(null)
            ->setEmailCanonical(null)
            ->setPlainPassword('password')
            ->setUsername($name)
            ->setEnabled(true);
        var_dump($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        var_dump($user);

        return array('name' => $name);
    }
}
