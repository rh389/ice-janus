<?php

namespace Ice\ExternalUserBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface,
    Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ManagerRegistry;

class UsernameConverter implements ParamConverterInterface
{
    protected $registry;

    public function __construct(ManagerRegistry $registry = null)
    {
        $this->registry = $registry;
    }

    function apply(Request $request, ConfigurationInterface $configuration)
    {

    }

    function supports(ConfigurationInterface $configuration)
    {
        // TODO: Implement supports() method.
    }
}