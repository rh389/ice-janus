<?php

namespace Ice\MailerBundle\Form\Type;

use Ice\MailerBundle\Transformer\Model\MailCollectionToUsernamesTransformer;
use Ice\MailerBundle\Transformer\View\VarsToSomethingTransformer;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateMailRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                $builder
                    ->create('to', 'text', ['property_path'=>'mails'])
                    ->addModelTransformer(new MailCollectionToUsernamesTransformer($options['em'])),
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Mail must be to at least one username',
                        ])
                    ]
                ]
            )
            ->add('template_name', 'text', ['constraints' => [
                new NotBlank([
                    'message' => 'Template name must be specified',
                ])
            ]])
            ->add('vars')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));

        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'cascade_validation' => true,
            'data_class' => 'Ice\MailerBundle\Entity\MailRequest',
            'validation_groups' => array('Default', 'rest_create'),
        ));
    }



    public function getName()
    {
        return '';
    }
}
