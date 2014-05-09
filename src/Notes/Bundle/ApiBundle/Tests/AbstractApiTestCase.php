<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\ApiBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiTestCase extends WebTestCase
{
    const AUTH_USER = '1_2bzyjhi21ny88c4ksg44wwk40c00ck4g800w4o8c0okkgks8ws';
    const AUTH_PASSWORD = '2kdceqmaah4wcgw00kc4s88cckso04csk8cok4k00kwgc00k4g';
    const JOHN_ACCESS_TOKEN = 'john';
    const FLORIAN_ACCESS_TOKEN = 'florian';
    const DAVID_ACCESS_TOKEN = 'david';

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    protected function assertJsonResponse(Response $response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode,
            $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    protected function jsonGetWithAccessToken($uri, $token, array $parameters = array())
    {
        $this->getClient();
        $this->jsonRequest('GET', $uri, $parameters, array(), array(
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)
        ));
        return $this->client;
    }

    protected function jsonGet($uri, array $parameters = array())
    {
        $this->getClient();
        $this->jsonRequest('GET', $uri, $parameters);
        return $this->client;
    }

    protected function jsonPostWithAccessToken($uri, $token, array $data = array())
    {
        $this->getClient();
        $this->jsonRequest('POST', $uri, array(), $data, array(
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)
        ));
        return $this->client;
    }

    protected function jsonPost($uri, array $data = array())
    {
        $this->getClient();
        $this->jsonRequest('POST', $uri, array(), $data);
        return $this->client;
    }

    protected function jsonPutWithAccessToken($uri, $token, array $data = array())
    {
        $this->getClient();
        $this->jsonRequest('PUT', $uri, array(), $data, array(
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)
        ));
        return $this->client;
    }

    protected function jsonPut($uri, array $data = array())
    {
        $this->getClient();
        $this->jsonRequest('PUT', $uri, array(), $data);
        return $this->client;
    }

    protected function deleteWithAccessToken($uri, $token)
    {
        $client = $this->getClient();
        $client->request('DELETE', $uri, array(), array(), array(
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)
        ));

        return $client;
    }

    protected function delete($uri)
    {
        $client = $this->getClient();
        $client->request('DELETE', $uri);

        return $client;
    }

    protected function getClient()
    {
        $client = parent::createClient();
        $this->client = $client;

        return $client;
    }

    protected function getAuthedClient()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => self::AUTH_USER,
            'PHP_AUTH_PW' => self::AUTH_PASSWORD,
        ));
        $this->client = $client;

        return $client;
    }

    protected function jsonRequest($verb, $uri, array $parameters = array(), array $data = array(), array $server = array())
    {
        $data = empty($data) ? null : json_encode($data);

        $this->client->request(
            $verb,
            $uri,
            $parameters,
            array(),
            array_merge(array(
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ), $server),
            $data
        );
    }
}
