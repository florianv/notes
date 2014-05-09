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

use OAuth2\OAuth2;

class OAuthIntegrationTest extends AbstractApiTestCase
{
    const TOKEN_URI = '/token';

    public function testToken()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\UserTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));

        $client = $this->getAuthedClient();

        // Ask an access token
        $client->request('POST', self::TOKEN_URI, array(), array(), array(
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ), json_encode(array(
            'grant_type' => OAuth2::GRANT_TYPE_USER_CREDENTIALS,
            'username' => 'florian',
            'password' => 'florian'
        )));

        $response = $client->getResponse();

        $this->assertJsonResponse($response);

        // Ask a refresh token
        $contentArray = json_decode($client->getResponse()->getContent(), true);
        $refreshToken = $contentArray['refresh_token'];

        $client->request('POST', self::TOKEN_URI, array(), array(), array(
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ), json_encode(array(
            'grant_type' => OAuth2::GRANT_TYPE_REFRESH_TOKEN,
            'refresh_token' => $refreshToken
        )));

        $response = $client->getResponse();
        $this->assertJsonResponse($response);
    }
}
