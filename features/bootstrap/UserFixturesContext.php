<?php

namespace Ice\Features\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;


class UserFixturesContext extends BehatContext implements KernelAwareInterface
{
    private $kernel;

    /**
     * @Given /^there are users$/
     */
    public function thereAreUsers(TableNode $courses)
    {
        $fixtures = array(
            '\Ice\ExternalUserBundle\Entity\User' => array(),
        );

        foreach ($courses->getHash() as $row) {
            //$id = $this->getRepositories()->getUnits()->getNextId();
            $fixtures['\Ice\ExternalUserBundle\Entity\User']['user' . $row['username']] = array(
                'username' => $row['username'],
                'password' => isset($row['password']) ? $row['password'] : 'password',
                'title' => isset($row['title']) ? $row['title'] : 'Mr',
                'firstNames' => isset($row['first_names']) ? $row['first_names'] : 'Rob',
                'lastNames' => isset($row['last_names']) ? $row['last_names'] : 'Hogan'
            );
        }
        $this->getMainContext()->getSubcontext('fixtures')->loadFixtures($fixtures);
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return EntityManagerInterface
     */
    private function getEntityManager()
    {
        return $this->kernel->getContainer()->get('doctrine.orm.default_entity_manager');
    }
}
