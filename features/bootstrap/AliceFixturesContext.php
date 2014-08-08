<?php

namespace Ice\Features\Context;

use Behat\Behat\Context\BehatContext;

use Behat\Behat\Event\SuiteEvent;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Event\ScenarioEvent;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;
use Nelmio\Alice\LoaderInterface;
use Nelmio\Alice\ORMInterface;
use Nelmio\Alice\Loader\Base as Loader;
use Nelmio\Alice\ORM\Doctrine as Persister;


class AliceFixturesContext extends BehatContext implements FixturesContext, KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var ORMInterface
     */
    private $persister;

    public function __construct()
    {
        $this->useContext('userFixtures', new UserFixturesContext());
    }

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function loadPersister()
    {
        $this->loader = new Loader();
        $this->persister = new Persister($this->getEntityManager());
        $this->loader->setORM($this->persister);
    }

    public function getEntityManager()
    {
        return $this->kernel->getContainer()->get('doctrine')->getManager();
    }

    public function loadFixtures(array $fixtures)
    {
        $objects = $this->loader->load($fixtures);

        $this->persister->persist($objects);
    }

    /**
     * @BeforeScenario
     */
    public function buildSchema()
    {
        foreach ($this->getEntityManagers() as $entityManager) {
            $metadata = $this->getMetadata($entityManager);

            if (!empty($metadata)) {
                $tool = new SchemaTool($entityManager);
                $tool->dropSchema($metadata);
                $tool->createSchema($metadata);
            }
        }
    }

    /**
     * @AfterScenario
     */
    public function closeDBALConnections()
    {
        foreach ($this->getEntityManagers() as $entityManager) {
            $entityManager->clear();
        }

        foreach ($this->getConnections() as $connection) {
            $connection->close();
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     *
     * @return array
     */
    protected function getMetadata($entityManager)
    {
        return $entityManager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @return EntityManagerInterface[]
     */
    protected function getEntityManagers()
    {
        return $this->kernel->getContainer()->get('doctrine')->getManagers();
    }

    /**
     * @return Connection[]
     */
    protected function getConnections()
    {
        return $this->kernel->getContainer()->get('doctrine')->getConnections();
    }
}
