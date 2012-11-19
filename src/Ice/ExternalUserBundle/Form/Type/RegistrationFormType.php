<?php

namespace Ice\ExternalUserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        parent::buildForm($builder, $options);

        $builder
            ->add('plainPassword', 'password', array(
                'description' => 'Plain text password. Encoded before new user is persisted.',
            ))
            ->add('email', 'email', array(
                'description' => 'Email address. Must be unique.',
            ))
            ->add('title', 'text', array(
                'description' => 'Salutation.',
            ))
            ->add('firstNames', 'text', array(
                'description' => 'First name(s).',
            ))
            ->add('lastName', 'text', array(
                'description' => 'Last name.',
            ))
            ->add('dob', 'date', array(
                'widget' => 'single_text',
                'description' => 'Date of birth.',
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ice\ExternalUserBundle\Entity\User',
            'csrf_protection' => false,
            'validation_groups' => array('rest_register'),
        ));
    }

    public function getName()
    {
        return 'register';
    }
}
