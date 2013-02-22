<?php

namespace Ice\ExternalUserBundle\Tests\Util;

use Ice\ExternalUserBundle\Util\String;

class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testNameWithNonAlphaCharactersReturnsValidInitials()
    {
        $name = strtolower("First Middle (Nickname) Last Names");
        $this->assertEquals('fmnln', String::getInitials($name));
    }

    public function testNameWithAccentsReturnsValidInitials()
    {
        $name = mb_strtolower('First Middle Èast', 'UTF-8');
        $this->assertEquals('fmè', String::getInitials($name));
    }

    public function testNameWithMultipleSpacesDoesNotResultInInitialsWithSpaces()
    {
        $name = strtolower('First  Middle  Last');
        $this->assertEquals('fml', String::getInitials($name));
    }
}