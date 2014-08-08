<?php

namespace Ice\Features\Context;

use Behat\Behat\Context\SubcontextableContextInterface;

interface FixturesContext extends SubcontextableContextInterface
{
    public function loadFixtures(array $fixtures);
}
