<?php

namespace Ice\Features\Context;
use Behat\Behat\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use WebDriver\Exception\WebTestAssertion;

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

    /**
     * @Given /^the response JSON should match "([^"]*)"$/
     */
    public function responseJsonShouldMatch($path)
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $path;

        if (!file_exists($filePath)) {
            throw new PendingException();
        }

        $content = file_get_contents($filePath);
        $pageContent = $this->getSession()->getPage()->getContent();

        if (!$this->isJsonEqual($content, $pageContent)) {
            throw new WebTestAssertion("Content is " . $pageContent);
        }
    }

    /**
     * @Given /^the response JSON should contain "([^"]*)"$/
     */
    public function responseJsonShouldContain($path)
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $path;

        if (!file_exists($filePath)) {
            throw new PendingException();
        }

        $content = file_get_contents($filePath);
        $pageContent = $this->getSession()->getPage()->getContent();

        $diff = $this->arrayDiff(json_decode($content, true), json_decode($pageContent, true));

        if (count($diff) != 0) {
            throw new WebTestAssertion("Response differs at " . json_encode($diff));
        }
    }

    protected function isJsonEqual($string1, $string2)
    {
        $json1 = json_decode($string1, true);
        $json2 = json_decode($string2, true);

        $difference = array_merge($this->arrayDiff($json1, $json2), $this->arrayDiff($json2, $json1));

        return count($difference) == 0;
    }

    protected function arrayDiff($array1, $array2)
    {
        $difference = array();
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key]) || !is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->arrayDiff($value, $array2[$key]);
                    if (!empty($new_diff))
                        $difference[$key] = $new_diff;
                }
            } else if (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
                $difference[$key] = $value;
            }
        }
        return $difference;
    }
}
