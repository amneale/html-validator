<?php

namespace Amneale\HtmlValidator\Tests\Validator;

use Amneale\HtmlValidator\Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    const TEST_URL = 'https://adam-neale.co.uk/';

    /**
     * @var Validator
     */
    private $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testConstructionWithDefaultArguments()
    {
        $this->assertInstanceOf(Validator::class, $this->validator);

        $this->assertInstanceOf(Client::class, $this->validator->getClient());
        $this->assertEquals(new Client(['base_uri' => Validator::DEFAULT_URL]), $this->validator->getClient());

        $this->assertEquals(Validator::PARSER_HTML, $this->validator->getParser());
    }

    public function testConstructionWithNonDefaultArguments()
    {
        $validator = new Validator(self::TEST_URL, Validator::PARSER_XML);

        $this->assertInstanceOf(Validator::class, $validator);

        $this->assertInstanceOf(Client::class, $validator->getClient());
        $this->assertEquals(new Client(['base_uri' => self::TEST_URL]), $validator->getClient());

        $this->assertEquals(Validator::PARSER_XML, $validator->getParser());
    }

    public function testGetAndSetClient()
    {
        $client = new Client(['base_uri' => self::TEST_URL]);

        $this->validator->setClient($client);
        $this->assertEquals($client, $this->validator->getClient());
    }

    public function testGetAndSetParser()
    {
        $this->validator->setParser(Validator::PARSER_XML);
        $this->assertEquals(Validator::PARSER_XML, $this->validator->getParser());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Server responded with HTTP status 500
     */
    public function testValidateUrlWithInvalidStatusCode()
    {
        $this->validator->setClient($this->getClient(new Response(500)));
        $this->validator->validateUrl(self::TEST_URL);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Server did not respond with the expected content-type (application/json)
     */
    public function testValidateUrlWithInvalidContentType()
    {
        $this->validator->setClient($this->getClient(new Response(200, ['Content-Type' => 'invalid/type'])));
        $this->validator->validateUrl(self::TEST_URL);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage json_decode error: unexpected character
     */
    public function testValidateUrlWithInvalidJson()
    {
        $this->validator->setClient($this->getClient($this->getJsonResponse('invalidJson')));
        $messages = $this->validator->validateUrl(self::TEST_URL);

        $this->assertInternalType('array', $messages);
    }

    public function testValidateUrl()
    {
        $this->validator->setClient($this->getClient($this->getJsonResponse('{"messages": []}')));
        $messages = $this->validator->validateUrl(self::TEST_URL);

        $this->assertInternalType('array', $messages);
    }

    /**
     * @param Response $response
     * @return Client|PHPUnit_Framework_MockObject_MockObject
     */
    private function getClient(Response $response)
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('get'))
            ->willReturn($response);

        return $client;
    }

    /**
     * @param string $json
     * @return Response
     */
    private function getJsonResponse($json)
    {
        $body = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->getMock();

        $body
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($json);

        return new Response(200,  ['Content-Type' => 'application/json'], $body);
    }
}
