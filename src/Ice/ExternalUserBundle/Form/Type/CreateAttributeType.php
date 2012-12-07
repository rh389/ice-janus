<?php

namespace Ice\ExternalUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreateAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fieldName', 'text', array(
                'description' => 'Field name',
                'required' => true,
            ))
            ->add('value', 'text', array(
                'description' => 'Value of attribute',
                'required' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ice\ExternalUserBundle\Entity\Attribute',
            'csrf_protection' => false,
            'validation_groups' => array('rest_create'),
        ));
    }

    public function getName()
    {
        return '';
    }
}
