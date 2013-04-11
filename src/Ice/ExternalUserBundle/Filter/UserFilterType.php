<?php

namespace Ice\ExternalUserBundle\Filter;

use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstNames', 'filter_text', array(
                'condition_pattern' => FilterOperands::STRING_BOTH,
            ))
            ->add('middleNames', 'filter_text', array(
                'condition_pattern' => FilterOperands::STRING_BOTH,
            ))
            ->add('lastNames', 'filter_text', array(
                'condition_pattern' => FilterOperands::STRING_BOTH,
            ))
            ->add('email', 'filter_text', array(
                'condition_pattern' => FilterOperands::STRING_BOTH,
            ))
            ->add('attributes', new UserAttributeFilterType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return '';
    }
}
