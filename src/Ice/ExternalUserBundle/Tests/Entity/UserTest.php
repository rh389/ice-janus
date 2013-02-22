<?php

namespace Ice\ExternalUserBundle\Tests\Entity;

use Ice\ExternalUserBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInitialsForAccentedName()
    {
        $user = new User();
        $user
            ->setFirstNames('First')
            ->setMiddleNames('Middle')
            ->setLastNames('Èast');

        $this->assertEquals('fmè', $user->getInitials());
    }
}