<?php

namespace Ice\ExternalUserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;

use Ice\ExternalUserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setUsername('abc12')
            ->setTitle('Mr')
            ->setFirstNames('First Names')
            ->setLastName('Lastname')
            ->setEmail('test@example.org')
            ->setPlainPassword('password')
            ->setEnabled(true);

        $user2 = clone $user;
        $user2
            ->setEmail('test2@example.com')
            ->setUsername('abc123');

        $manager->persist($user);
        $manager->persist($user2);
        $manager->flush();
    }
}