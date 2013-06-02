<?php

namespace Ice\ExternalUserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\Form\FormTypeInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ice\ExternalUserBundle\Entity\User,
    Ice\ExternalUserBundle\Entity\Attribute,
    Ice\ExternalUserBundle\Form\Type\CreateAttributeType,
    Ice\ExternalUserBundle\Form\Type\UpdateAttributeType;

/**
 * @Cache(
 *  expires="15 minutes",
 *  smaxage="900",
 *  maxage="900"
 * )
 */
class AttributesController extends FOSRestController
{
    /**
     * @param \Ice\ExternalUserBundle\Entity\User $user
     * @param $fieldName
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \FOS\RestBundle\View\View
     *
     * @Route("users/{username}/attributes/{fieldName}", name="post_attribute")
     * @Method("PUT")
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Update an Attribute for a User",
     *   input="Ice\ExternalUserBundle\Form\Type\UpdateAttributeType",
     *   output="Ice\ExternalUserBundle\Entity\Attribute",
     *   statusCodes={
     *     204="Returned on success",
     *     404="Returned when the User does not exist"
     *   }
     * )
     */
    public function putAttributesAction(User $user, $fieldName)
    {
        $attribute = $user->getAttributeByFieldName($fieldName);

        if (!$attribute) {
            //This amounts to using a PUT to create a new resource, which according to some is not restful. However, in the
            //context of attributes it's useful to be able to set a value without caring whether the attribute existed
            //previously. Doing this instead of returning a 404 doesn't seem like such a terrible thing.

            $attribute = new Attribute();
            $attribute->setFieldName($fieldName);
            $attribute->setUser($user);
        }

        return $this->processForm(new UpdateAttributeType(), $user, $attribute);
    }

    /**
     * @param \Ice\ExternalUserBundle\Entity\User $user
     * @return \FOS\RestBundle\View\View
     *
     * @Route("users/{username}/attributes", name="put_attribute")
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
    public function postAttributesAction(User $user)
    {
        return $this->processForm(new CreateAttributeType(), $user, new Attribute());
    }

    private function processForm(FormTypeInterface $form, User $user, Attribute $attribute)
    {
        $status = $this->getRequest()->isMethod('POST') ? 201 : 204;

        $form = $this->createForm($form, $attribute);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var $attribute Attribute */
            $attribute = $form->getData();

            if (!$em->contains($attribute)) {
                $user->addAttribute($attribute);
                $attribute->setUser($user);
            }

            $em->persist($attribute);
            $em->flush();

            return $this->view($attribute, $status);
        }

        return $this->view($form, 400);
    }

    /**
     * @param \Ice\ExternalUserBundle\Entity\User $user
     * @param $fieldName
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \FOS\RestBundle\View\View
     *
     * @Route("users/{username}/attributes/{fieldName}", name="get_attribute")
     * @Method("GET")
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Returns an Attribute for a User",
     *   output="Ice\ExternalUserBundle\Entity\Attribute",
     *   statusCodes={
     *     200="Returned on success",
     *     404="Returned when the Attribute does not exist"
     *   }
     * )
     */
    public function getAttributeAction(User $user, $fieldName)
    {
        $attribute = $user->getAttributeByFieldName($fieldName);

        if (!$attribute) {
            throw $this->createNotFoundException(sprintf("Attribute \"%s\" for user \"%s\" does not exist.", $fieldName, $user->getUsername()));
        }

        return $this->view($attribute);
    }
}