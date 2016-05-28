<?php

namespace Amneale\HtmlValidator\Tests\Validator;

use Amneale\HtmlValidator\Result;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;

class ResultTest extends PHPUnit_Framework_TestCase
{
    public function testConstruction()
    {
        $result = new Result($this->getResponseMock());
        $this->assertInstanceOf(Result::class, $result);
    }

    /**
     * @expectedException \Amneale\HtmlValidator\Exception\ResponseException
     * @expectedExceptionMessage Response contains no messages
     */
    public function testConstructionWithNoMessages()
    {
        $emptyResponse = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();
        $emptyResponse
            ->expects($this->once())
            ->method('getBody')
            ->willReturn('{}');

        $result = new Result($emptyResponse);
    }

    /**
     * @dataProvider messagesProvider
     * @param array $messages
     * @param bool $hasWarning
     * @param bool $hasError
     */
    public function testWarningMessages(array $messages, $hasWarning, $hasError)
    {
        $result = new Result($this->getResponseMock($messages));

        $this->assertNotEmpty($result->getMessages());
        $this->assertEquals($hasWarning, $result->hasWarnings());
        $this->assertEquals($hasError, $result->hasErrors());
    }

    /**
     * @return array
     */
    public function messagesProvider()
    {
        return [
            [
                [$this->getMessage('info')], true, false
            ],
            [
                [$this->getMessage('warning')], false, true
            ],
            [
                [
                    $this->getMessage('info'),
                    $this->getMessage('warning'),
                ],
                true,
                true,
            ],
        ];
    }

    /**
     * @param array $messages
     * @return PHPUnit_Framework_MockObject_MockObject|ResponseInterface
     */
    private function getResponseMock(array $messages = [])
    {
        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn('{"messages": ' . json_encode($messages) . '}');

        return $response;
    }

    private function getMessage($type)
    {
        return [
            'type' => $type,
            'lastLine' => 1,
            'firstColumn' => 1,
            'lastColumn' => 1,
            'message' => 'test message',
            'extract' => '<p>test extract</p>',
            'hiliteStart' => 0,
            'hiliteLength' => 20,
            'subType' => 'test', // TODO only return subtype where applicable (warning/error?)
        ];
    }
}
