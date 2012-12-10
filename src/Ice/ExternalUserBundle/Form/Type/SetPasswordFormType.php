<?php

namespace Ice\ExternalUserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Form\AbstractType;

class SetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', 'text', array(
                'description' => 'Plain text password. Encoded before User is persisted.',
                'required' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ice\ExternalUserBundle\Entity\User',
            'csrf_protection' => false,
            'validation_groups' => array('rest_set_password'),
        ));
    }

    public function getName()
    {
        return '';
    }
}
