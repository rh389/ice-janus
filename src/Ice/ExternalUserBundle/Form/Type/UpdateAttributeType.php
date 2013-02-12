<?php

namespace Ice\ExternalUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', 'text', array(
                'description' => 'Value of attribute',
                'required' => true,
            ))
            ->add('updatedBy', 'text', array(
                'description' => 'Username of User who initiated the update',
                'required' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ice\ExternalUserBundle\Entity\Attribute',
            'csrf_protection' => false,
            'validation_groups' => array('rest_update'),
        ));
    }

    public function getName()
    {
        return '';
    }
}
