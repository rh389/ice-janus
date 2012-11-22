<?php

namespace Ice\UsernameGeneratorBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Form\AbstractType,
    Symfony\Component\Validator\Constraints\Collection,
    Symfony\Component\Validator\Constraints\Regex,
    Symfony\Component\Validator\Constraints\NotBlank;

class RequestUsernameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('initials', 'text', array(
                'description' => 'User\'s initials',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $constraints = new Collection(array(
            'initials' => array(
                new NotBlank(),
                new Regex(array("pattern" => "/^[a-z]{2,}$/", "message" => "Initials can only be letters and must have at least two characters")),
            ),
        ));

        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'constraints' => $constraints,
        ));
    }

    public function getName()
    {
        return '';
    }
}
