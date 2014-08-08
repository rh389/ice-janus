<?php

namespace Ice\Features\Context;

use Behat\Behat\Context\BehatContext;

use Behat\Behat\Event\SuiteEvent;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Event\ScenarioEvent;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;


class FeatureContext extends BehatContext
{
    public function __construct()
    {
        $this->useContext('server', new BuiltInServerContext());
        $this->useContext('fixtures', new AliceFixturesContext());
        $this->useContext('client', new ClientContext());
    }

    /**
     * @return FixturesContext
     */
    public function getFixtures()
    {
        return $this->getMainContext()->getSubcontext('fixtures');
    }
}
