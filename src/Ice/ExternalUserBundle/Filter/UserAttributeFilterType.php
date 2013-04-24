<?php

namespace Ice\ExternalUserBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\FilterTypeSharedableInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\ORM\Expr;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserAttributeFilterType extends AbstractType implements FilterTypeSharedableInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fieldName', 'filter_text', array(
                'condition_pattern' => FilterOperands::STRING_BOTH,
            ))
            ->add('value', 'filter_text', array(
                'condition_pattern' => FilterOperands::STRING_BOTH,
            ))
        ;
    }

    public function getName()
    {
        return '';
    }

    public function addShared(FilterBuilderExecuterInterface $qbe)
    {
        $closure = function(QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
            $filterBuilder->leftJoin($alias . '.attributes', 'Attributes');
        };

        $qbe->addOnce($qbe->getAlias() . '.attributes', 'Attributes', $closure);

    }
}
