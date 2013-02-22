<?php

namespace Ice\UsernameGeneratorBundle\Tests;

use Ice\UsernameGeneratorBundle\UsernameGenerator;

use Symfony\Component\Validator\Validation;

class UsernameGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UsernameGenerator
     */
    protected $generator;

    protected function setUp()
    {
        $registry = $this->getMock('Symfony\\Bridge\\Doctrine\\RegistryInterface');
        $em = $this->getMock('Doctrine\\ORM\\EntityManager', array(), array(), '', false);
        $repository = $this->getMock('Ice\\UsernameGeneratorBundle\\Entity\\UsernameRepository', array(), array(), '', false);

        $this->generator = new UsernameGenerator($registry, $em, $repository);
    }

    public function testGetUsernameWithNoInitialsFailsValidation()
    {
        $username = $this->generator->getUsernameForInitials('');
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $errors = $validator->validate($username);
        $this->assertContains('Initials cannot be blank', $errors->__toString(), $errors);
    }
}