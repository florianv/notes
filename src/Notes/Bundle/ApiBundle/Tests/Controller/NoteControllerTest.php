<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\ApiBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Notes\Bundle\ApiBundle\Tests\AbstractApiTestCase;

class NoteControllerTest extends AbstractApiTestCase
{
    const NOTES_URI = '/api/notes';
    const SINGLE_NOTE_URI = '/api/note';

    public function testCannotGetNotesNoAccessToken()
    {
        $client = $this->jsonGet(self::NOTES_URI);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testGetNotesEmpty()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->jsonGetWithAccessToken(self::NOTES_URI, self::JOHN_ACCESS_TOKEN);

        $response = $client->getResponse();
        $contentArray = json_decode($response->getContent(), true);

        $this->assertJsonResponse($response);
        $this->assertCount(0, $contentArray);
    }

    public function testGetNotesWithSearch()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->jsonGetWithAccessToken(self::NOTES_URI, self::FLORIAN_ACCESS_TOKEN, array('search' => 'Note'));

        $response = $client->getResponse();
        $contentArray = json_decode($response->getContent(), true);

        $this->assertJsonResponse($response);
        $this->assertCount(2, $contentArray);

        // Note 2
        $this->assertCount(5, $contentArray[0]);
        $this->assertNotEquals(0, $contentArray[0]['id']);
        $this->assertEquals('Note 2', $contentArray[0]['title']);
        $this->assertNotEmpty($contentArray[0]['content']);
        $this->assertNotEmpty($contentArray[0]['created_date']);
        $this->assertNotEmpty($contentArray[0]['modified_date']);

        // Note 1
        $this->assertCount(5, $contentArray[1]);
        $this->assertNotEquals(0, $contentArray[1]['id']);
        $this->assertEquals('Note 1', $contentArray[1]['title']);
        $this->assertNotEmpty($contentArray[1]['content']);
        $this->assertNotEmpty($contentArray[1]['created_date']);
        $this->assertNotEmpty($contentArray[1]['modified_date']);
    }

    public function testGetNotesWithLimit()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->jsonGetWithAccessToken(self::NOTES_URI, self::DAVID_ACCESS_TOKEN, array('search' => 'Issue', 'limit' => 1));

        $response = $client->getResponse();
        $contentArray = json_decode($response->getContent(), true);

        $this->assertJsonResponse($response);
        $this->assertCount(1, $contentArray);

        $this->assertCount(5, $contentArray[0]);
        $this->assertNotEquals(0, $contentArray[0]['id']);
        $this->assertEquals('Issue 1', $contentArray[0]['title']);
        $this->assertNotEmpty($contentArray[0]['content']);
        $this->assertNotEmpty($contentArray[0]['created_date']);
        $this->assertNotEmpty($contentArray[0]['modified_date']);
    }

    public function testGetSingleNoteWrongAccessToken()
    {
        $client = $this->jsonGetWithAccessToken(self::SINGLE_NOTE_URI . '/2', 'wrong');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testGestSingleNoteOfOtherUser()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->jsonGetWithAccessToken(self::SINGLE_NOTE_URI . '/1', self::JOHN_ACCESS_TOKEN);
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testGetSingleNoteNotFound()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->jsonGetWithAccessToken(self::SINGLE_NOTE_URI . '/100', self::JOHN_ACCESS_TOKEN);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testGetSingleNote()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->jsonGetWithAccessToken(self::SINGLE_NOTE_URI . '/4', self::DAVID_ACCESS_TOKEN);
        $response = $client->getResponse();
        $contentArray = json_decode($response->getContent(), true);

        $this->assertJsonResponse($response);
        $this->assertCount(5, $contentArray);
        $this->assertNotEquals(0, $contentArray['id']);
        $this->assertEquals('Note 1', $contentArray['title']);
        $this->assertNotEmpty($contentArray['content']);
        $this->assertNotEmpty($contentArray['created_date']);
        $this->assertNotEmpty($contentArray['modified_date']);
    }

    public function testDeleteWrongAccessToken()
    {
        $client = $this->deleteWithAccessToken(self::SINGLE_NOTE_URI . '/4', 'wrong');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testDeleteNoteOfOtherUser()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->deleteWithAccessToken(self::SINGLE_NOTE_URI . '/1', self::JOHN_ACCESS_TOKEN);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());

        // Check it was not deleted
        $client = $this->jsonGetWithAccessToken(self::SINGLE_NOTE_URI . '/1', self::FLORIAN_ACCESS_TOKEN);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testDeleteNonExisting()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->deleteWithAccessToken(self::SINGLE_NOTE_URI . '/100', self::DAVID_ACCESS_TOKEN);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->deleteWithAccessToken(self::SINGLE_NOTE_URI . '/2', self::FLORIAN_ACCESS_TOKEN);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        // Check it was deleted
        $client = $this->jsonGetWithAccessToken(self::SINGLE_NOTE_URI . '/2', self::FLORIAN_ACCESS_TOKEN);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testCreateWrongAccessToken()
    {
        $client = $this->jsonPostWithAccessToken(self::SINGLE_NOTE_URI, 'wrong', array(
            'title' => 'hello',
            'content' => 'hello'
        ));

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testCreateInvalidData()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->jsonPostWithAccessToken(self::SINGLE_NOTE_URI, self::FLORIAN_ACCESS_TOKEN, array(
            'title' => '',
            'content' => 'hello'
        ));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());

        // Check it was note created
        $client = $this->jsonGetWithAccessToken(self::SINGLE_NOTE_URI . '/7', self::FLORIAN_ACCESS_TOKEN);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testCreate()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));
        $client = $this->jsonPostWithAccessToken(self::SINGLE_NOTE_URI, self::FLORIAN_ACCESS_TOKEN, array(
            'title' => 'hello',
            'content' => 'hello'
        ));

        $response = $client->getResponse();
        $contentArray = json_decode($response->getContent(), true);

        $this->assertJsonResponse($response, Response::HTTP_CREATED);
        $this->assertContains(self::SINGLE_NOTE_URI . '/7', $response->headers->get('Location'));
        $this->assertCount(5, $contentArray);
        $this->assertEquals(7, $contentArray['id']);
        $this->assertEquals("hello", $contentArray['title']);
        $this->assertEquals("hello", $contentArray['content']);
        $this->assertNotEmpty($contentArray['created_date']);
        $this->assertNotEmpty($contentArray['modified_date']);
        $this->assertEquals($contentArray['created_date'], $contentArray['modified_date']);

        // Check it was created
        $client = $this->jsonGetWithAccessToken(self::SINGLE_NOTE_URI . '/7', self::FLORIAN_ACCESS_TOKEN);
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $this->assertEquals($contentArray, json_decode($response->getContent(), true));
    }

    public function testUpdateWrongAccessToken()
    {
        $client = $this->jsonPutWithAccessToken(self::SINGLE_NOTE_URI . '/1', 'wrong');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testUpdateNotFound()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));

        $client = $this->jsonPutWithAccessToken(self::SINGLE_NOTE_URI . '/100', self::FLORIAN_ACCESS_TOKEN);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testUpdateNoteOfOtherUser()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));

        $client = $this->jsonPutWithAccessToken(self::SINGLE_NOTE_URI . '/2', self::DAVID_ACCESS_TOKEN);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testUpdate()
    {
        $this->loadFixtures(array(
            'Notes\Bundle\ApiBundle\Tests\Fixtures\NoteTestData',
            'Notes\Bundle\ApiBundle\Tests\Fixtures\TokenTestData'
        ));

        $client = $this->jsonPutWithAccessToken(self::SINGLE_NOTE_URI . '/2', self::FLORIAN_ACCESS_TOKEN, array(
            'title' => 'hello',
            'content' => 'hello'
        ));

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        // Check it was updated
        $client = $this->jsonGetWithAccessToken(self::SINGLE_NOTE_URI . '/2', self::FLORIAN_ACCESS_TOKEN);
        $response = $client->getResponse();
        $contentArray = json_decode($response->getContent(), true);

        $this->assertJsonResponse($client->getResponse());
        $this->assertCount(5, $contentArray);
        $this->assertEquals(2, $contentArray['id']);
        $this->assertEquals('hello', $contentArray['title']);
        $this->assertEquals('hello', $contentArray['content']);
        $this->assertNotEmpty($contentArray['created_date']);
        $this->assertNotEmpty($contentArray['modified_date']);
        $this->assertNotEquals($contentArray['created_date'], $contentArray['modified_date']);
    }
}
