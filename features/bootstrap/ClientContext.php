<?php

namespace Ice\Features\Context;
use Behat\MinkExtension\Context\MinkContext;

class ClientContext extends MinkContext
{
    /**
     * @Given /^my client accepts content type "([^"]*)"$/
     */
    public function myClientAccepts($accepts)
    {
        $this->getSession()->setRequestHeader('Accept', $accepts);
    }

    /**
     * @Given /^I am connecting with HTTPS$/
     */
    public function iAmConnectingWithHttps()
    {
        $this->getSession()->setRequestHeader('X-Forwarded-Proto', 'https');
    }

    /**
     * @Given /^I use the username "([^"]*)" with password "([^"]*)"$/
     */
    public function iUseTheUsernameWithPassword($username, $password)
    {
        $this->getSession()->setBasicAuth($username, $password);
    }
}
