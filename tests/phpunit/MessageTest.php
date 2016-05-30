<?php

namespace Amneale\HtmlValidator\Tests\Validator;

use Amneale\HtmlValidator\Message;
use PHPUnit_Framework_TestCase;

class MessageTest extends PHPUnit_Framework_TestCase
{
    public function testConstruction()
    {
        $message = new Message(['type' => 'info']);
        $this->assertInstanceOf(Message::class, $message);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid message: message type not specified
     */
    public function testWithoutMessageType()
    {
        $message = new Message([]);
    }
}
