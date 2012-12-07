<?php

namespace Ice\ExternalUserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'description' => 'Email address. Must be unique.',
            ))
            ->add('title', 'text', array(
                'description' => 'Salutation.',
            ))
            ->add('firstNames', 'text', array(
                'description' => 'First name(s).',
            ))
            ->add('middleNames', 'text', array(
                'description' => 'Middle name(s).',
                'required' => false,
            ))
            ->add('lastNames', 'text', array(
                'description' => 'Last name.',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ice\ExternalUserBundle\Entity\User',
            'csrf_protection' => false,
            'validation_groups' => array('rest_update'),
        ));
    }

    public function getName()
    {
        return '';
    }
}
