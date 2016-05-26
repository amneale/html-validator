<?php

namespace Amneale\HtmlValidator\Tests\Validator;

use Amneale\HtmlValidator\Result;
use Amneale\HtmlValidator\Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
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
     * @expectedException Amneale\HtmlValidator\Exception\ResponseException
     * @expectedExceptionMessage Server responded with HTTP status 500
     */
    public function testValidateUrlWithInvalidStatusCode()
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->willReturn(new Response(500));

        $this->validator->setClient($client);
        $this->validator->validateUrl(self::TEST_URL);
    }

    /**
     * @expectedException Amneale\HtmlValidator\Exception\ResponseException
     * @expectedExceptionMessage Server did not respond with the expected content-type (application/json)
     */
    public function testValidateUrlWithInvalidContentType()
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->willReturn(new Response(
                200,
                ['Content-Type' => 'invalid/type']
            ));

        $this->validator->setClient($client);
        $this->validator->validateUrl(self::TEST_URL);
    }

    public function testValidateUrl()
    {
        $body = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->getMock();

        $body
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('{"messages": []}');

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->willReturn(new Response(
                200,
                ['Content-Type' => 'application/json'],
                $body
            ));

        $this->validator->setClient($client);
        $result = $this->validator->validateUrl(self::TEST_URL);

        $this->assertInstanceOf(Result::class, $result);
    }
}
