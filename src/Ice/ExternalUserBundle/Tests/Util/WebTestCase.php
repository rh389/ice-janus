<?php

namespace Ice\ExternalUserBundle\Tests\Util;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase AS BaseWebTestCase,
    Symfony\Component\HttpFoundation\Response;

class WebTestCase extends BaseWebTestCase
{
    protected function assertJsonResponse(Response $response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );

        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json')
        );
    }

    protected function getJsonClient()
    {
        $client = static::createClient();
        $client->setServerParameter('CONTENT_TYPE','application/json');
        $client->setServerParameter('HTTP_ACCEPT', 'application/json');
        return $client;
    }
}