<?php
namespace tests\functional;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class EndpointTest extends TestCase
{
    const URL = 'http://example-api';
    const AUTH_TOKEN = '12345';

    private $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client(['base_uri' => self::URL, 'exceptions' => false]);
    }

    public function setUp()
    {
        parent::setUp();

        // hack for reloading db instead of proper prepare of fixtures` db
        echo "\n";
        echo shell_exec('docker exec -it exampleapi_php_1 ./console/command app:db:init');
        echo "\n";
    }

    /**
     * @test
     */
    public function checkNoAuthIndexPage()
    {
        $response = $this->client->request('GET', '/');

        $this->assertEquals($response->getStatusCode(), Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function checkAuthIndexPage()
    {
        $response = $this->client->request(
            'GET',
            '/',
            $this->authHeaderArray()
        );

        $contents = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals($response->getStatusCode(), Response::HTTP_OK);
        $this->assertArrayHasKey('results', $contents);
        $this->assertInternalType('array', $contents['results']);
        $this->assertNotEmpty($contents['results']);
        $this->assertArrayHasKey('pagination', $contents);
        $this->assertArrayHasKey('prev', $contents['pagination']);
        $this->assertArrayHasKey('next', $contents['pagination']);
        $this->assertArrayHasKey('totalPages', $contents['pagination']);
        $this->assertTrue((int) $contents['pagination']['next'] > (int) $contents['pagination']['prev']);
    }

    /**
     * @test
     */
    public function checkWrongMethodStateChangeEndpoint()
    {
        $response = $this->client->request(
            'GET',
            '/23/archive',
            $this->authHeaderArray()
        );

        $this->assertEquals($response->getStatusCode(), Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @test
     */
    public function checkViewPage()
    {
        $response = $this->client->request(
            'GET',
            '/23',
            $this->authHeaderArray()
        );

        $expectedJson = "{\"id\":23,\"text\":\"The story is about an obedient midwife and a graceful scuba diver who is in debt to a fence. It takes place in a magical part of our universe. The story ends with a funeral.\",\"sender\":\"Virgina Woolf\",\"subject\":\"debt\",\"state\":\"NOT_READ\"}";

        $this->assertContains($response->getHeader('Content-type')[0], 'application/json;charset=UTF-8');
        $this->assertEquals($response->getStatusCode(), Response::HTTP_OK);
        $this->assertEquals($response->getBody()->getContents(), $expectedJson);
    }

    /**
     * @test
     */
    public function checkArchivePage()
    {
        $response = $this->client->request(
            'GET',
            '/23',
            $this->authHeaderArray()
        );
        $messageJson = $response->getBody()->getContents();
        $message = json_decode($messageJson, true);

        $this->assertEquals($message['state'], 'NOT_READ');

        $response = $this->client->request(
            'PATCH',
            '/23/archive',
            $this->authHeaderArray()
        );

        $this->assertEquals($response->getStatusCode(), Response::HTTP_OK);

        $response = $this->client->request(
            'GET',
            '/23',
            $this->authHeaderArray()
        );
        $messageJson = $response->getBody()->getContents();
        $message = json_decode($messageJson, true);

        $this->assertEquals($message['state'], 'ARCHIVED');
    }

    private function authHeaderArray()
    {
        return ['headers' => [
            'Authorization' => 'Bearer: ' . self::AUTH_TOKEN
        ]];
    }
}
