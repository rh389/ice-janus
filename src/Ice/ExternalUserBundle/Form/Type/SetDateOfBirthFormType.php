<?php

namespace Ice\ExternalUserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Form\AbstractType;

class SetDateOfBirthFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dob', 'birthday', array(
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
