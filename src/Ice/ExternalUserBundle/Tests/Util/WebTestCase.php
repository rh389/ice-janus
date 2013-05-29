<?php

namespace Ice\ExternalUserBundle\Tests\Util;

use Liip\FunctionalTestBundle\Test\WebTestCase AS BaseWebTestCase;

use Symfony\Component\HttpFoundation\Response;

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

        $client->setServerParameters(array(
            'CONTENT_TYPE'  => 'application/json',
            'HTTP_ACCEPT'   => 'application/json',
            'HTTPS'         => true,
            'PHP_AUTH_USER' => 'janus',
            'PHP_AUTH_PW'   => 'AmjtP*GHfBKqG5bv^#uCTkaHp7WPEC',
        ));
        return $client;
    }
}