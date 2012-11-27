<?php

namespace Ice\ExternalUserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin,
    Sonata\AdminBundle\Datagrid\ListMapper,
    Sonata\AdminBundle\Datagrid\DatagridMapper,
    Sonata\AdminBundle\Validator\ErrorElement,
    Sonata\AdminBundle\Form\FormMapper,
    Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends Admin
{
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('title')
            ->add('firstNames')
            ->add('lastName')
            ->add('email')
            ->add('dob', null, array('label' => 'Date of birth'))
            ->add('enabled')
            ->add('lastLogin')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('firstNames')
            ->add('lastName')
            ->add('email')
            ->add('dob', 'date', array(
                'widget' => 'choice',
                'years' => range(1900, date('Y') - 15),
                'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                'required' => false,
                'label' => 'Date of birth',
            ))
            ->add('enabled', null, array(
                'required' => false,
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('firstNames')
            ->add('lastName')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('lastLogin')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                )
            ))
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
    }
}