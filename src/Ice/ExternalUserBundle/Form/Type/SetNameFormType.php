<?php

namespace Ice\ExternalUserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Form\AbstractType;

class SetNameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
