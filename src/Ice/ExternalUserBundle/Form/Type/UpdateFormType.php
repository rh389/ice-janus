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
                'required' => true,
            ))
            ->add('title', 'text', array(
                'description' => 'Salutation.',
                'required' => true,
            ))
            ->add('firstNames', 'text', array(
                'description' => 'First name(s).',
                'required' => true,
            ))
            ->add('middleNames', 'text', array(
                'description' => 'Middle name(s).',
                'required' => false,
            ))
            ->add('lastNames', 'text', array(
                'description' => 'Last name.',
                'required' => true,
            ))
            ->add('dob', 'text', array(
                'widget' => 'single_text',
                'description' => 'Date of birth.',
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
