<?php

namespace Ice\ExternalUserBundle\Tests\Controller;

use Ice\ExternalUserBundle\Tests\Util\WebTestCase;

class UsersControllerTest extends WebTestCase
{
    public function setUp()
    {
        $classes = array(
            'Ice\ExternalUserBundle\DataFixtures\ORM\LoadUserData',
        );

        $this->loadFixtures($classes);
    }

    public function testGetUsersAction()
    {
        $client = $this->getJsonClient();
        $crawler = $client->request('GET', '/api/users', array(), array(), array());

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
    }

    public function testGetUserAction()
    {
        $client = $this->getJsonClient();
        $crawler = $client->request('GET', '/api/users/abc12');

        $response = $client->getResponse();
        $this->assertJsonResponse($response);

        // Check content decoded correctly
        $content = $response->getContent();
        json_decode($content);
        $this->assertEmpty(json_last_error());
    }

    public function testPostUsersAction()
    {
        $client = $this->getJsonClient();
        $crawler = $client->request('POST', '/api/users', array(), array(), array(), $this->createCompleteJsonRegisterBody());

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 201);
    }

    public function testGetUsersAuthenticateAction($username = 'abc123', $password = 'password')
    {
        $client = $this->getJsonClient();
        $client->setServerParameter('PHP_AUTH_USER', $username);
        $client->setServerParameter('PHP_AUTH_PW', $password);

        $crawler = $client->request('GET', '/api/users/authenticate');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 302);

        $client->followRedirect();
        $response = $client->getResponse();
        $this->assertJsonResponse($response);
    }

    public function testUpdateUser()
    {
        $client = $this->getJsonClient();

        $crawler = $client->request('PUT', '/api/users/abc12', array(), array(), array(), $this->createJsonUserUpdateBody());

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 204);
    }

    public function testUpdatePassword()
    {
        $client = $this->getJsonClient();

        $crawler = $client->request('PUT', '/api/users/abc12/password', array(), array(), array(), $this->createJsonUserUpdatePasswordBody());

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 204);

        $this->testGetUsersAuthenticateAction('abc12', 'newpassword');
    }

    protected function createCompleteJsonRegisterBody()
    {
        $body = array(
            "plainPassword" => "password",
            "email" => "test11222621@blah.com",
            "title" => "Mr",
            "firstNames" => "First Names",
            "middleNames" => "Middle Names",
            "lastNames" => "Lastname",
        );

        return json_encode($body);
    }

    protected function createIncompleteJsonRegisterBody()
    {
        $body = array(
            "plainPassword" => "password",
            "email" => "test@blah.com",
        );

        return json_encode($body);
    }

    protected function createJsonUserUpdateBody()
    {
        $body = array(
            "email" => "test2@blah.com",
            "title" => "Mrs",
            "firstNames" => "New User",
            "lastNames" => "Details",
        );

        return json_encode($body);
    }

    protected function createJsonUserUpdatePasswordBody()
    {
        $body = array(
            "plainPassword" => "newpassword",
        );

        return json_encode($body);
    }
}