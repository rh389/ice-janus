<?php

namespace Ice\Features\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;


class VeritasClientFixturesContext extends BehatContext implements KernelAwareInterface
{
    private $kernel;

    /**
     * @Given /^there are courses$/
     */
    public function thereAreCourses(TableNode $courses)
    {
        foreach ($courses->getHash() as $row) {
            $courseFixture = [
                'id' => $row['id'],
                'title' => $row['title']
            ];

            if (isset($row['level'])) {
                $levelParts = explode(':', $row['level']);
                $fixtures['\Ice\VeritasClientBundle\Entity\Level']['veritas_client_course_level:' . intval($levelParts[0])] = array(
                    'id' => intval($levelParts[0]),
                    'description' => $levelParts[1]
                );

                $courseFixture['level'] =
                    $this->getEntityManager()->getReference('Ice\VeritasClientBundle\Entity\Level', intval($levelParts[0]));
            }

            if (isset($row['programme'])) {
                $programmeParts = explode(':', $row['programme']);
                $fixtures['\Ice\VeritasClientBundle\Entity\Programme']['veritas_client_course_programme:' . intval($programmeParts[0])] = array(
                    'id' => intval($programmeParts[0]),
                    'title' => $programmeParts[1]
                );

                $courseFixture['programme'] =
                    $this->getEntityManager()->getReference('Ice\VeritasClientBundle\Entity\Programme', intval($programmeParts[0]));
            }

            $fixtures['\Ice\VeritasClientBundle\Entity\Course']['veritas_client_course:' . $row['id']] = $courseFixture;
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
