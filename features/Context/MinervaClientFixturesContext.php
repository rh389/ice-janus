<?php

namespace Ice\Features\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;


class MinervaClientFixturesContext extends BehatContext implements KernelAwareInterface
{
    private $kernel;

    /**
     * @Given /^there are bookings$/
     */
    public function thereAreBookings(TableNode $bookings)
    {
        $fixtures = [];
        $fixtures['\Ice\MinervaClientBundle\Entity\AcademicInformation'] = [];

        foreach ($bookings->getHash() as $row) {
            if (!isset(
                $fixtures['\Ice\MinervaClientBundle\Entity\AcademicInformation']
                    ['minerva_client_ai:' . $row['username'] . ':' . $row['course_id']]
            )) {
                $fixtures['\Ice\MinervaClientBundle\Entity\AcademicInformation']
                    ['minerva_client_ai:' . $row['username'] . ':' . $row['course_id']] =
                    [
                        'iceId' => $row['username'],
                        'courseId' => $row['course_id']
                    ]
                ;
            }

            $fixtures['\Ice\MinervaClientBundle\Entity\Booking']
                ['minerva_client_booking:' . $row['id']] = [
                    'id' => intval($row['id']),
                    'academicInformation' =>$this->getEntityManager()->
                        getReference('Ice\MinervaClientBundle\Entity\AcademicInformation',
                            ['iceId' => $row['username'], 'courseId' => $row['course_id']])
                ]
            ;
        }
        $this->getMainContext()->getSubcontext('fixtures')->loadFixtures($fixtures);
    }

    /**
     * @Given /^the bookings have items$/
     */
    public function theBookingsHaveItems(TableNode $bookingItems)
    {
        $fixtures = [];
        foreach ($bookingItems->getHash() as $row) {

            $itemFixture = [
                'code' => $row['code'],
                'booking' =>$this->getEntityManager()->
                    getReference('Ice\MinervaClientBundle\Entity\Booking', intval($row['booking_id']))
            ]
            ;

            //Category (mandatory)
            $categoryParts = explode(':', $row['category']);
            $fixtures['\Ice\MinervaClientBundle\Entity\Category']['minerva_client_category:' . intval($categoryParts[0])] = array(
                'id' => intval($categoryParts[0]),
                'description' => $categoryParts[1]
            );

            $itemFixture['category'] =
                $this->getEntityManager()->getReference('Ice\MinervaClientBundle\Entity\Category', intval($categoryParts[0]));

            $fixtures['\Ice\MinervaClientBundle\Entity\BookingItem']
            ['minerva_client_booking_item:' . $row['booking_id'] . ':' . $row['code']] = $itemFixture;
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
