<?php

namespace Amneale\HtmlValidator\Tests\Validator;

use Amneale\HtmlValidator\Message\Error;
use Amneale\HtmlValidator\Message\Factory;
use Amneale\HtmlValidator\Message\Warning;
use PHPUnit_Framework_TestCase;

class FactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new Factory();
    }

    /**
     * @expectedException \Amneale\HtmlValidator\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid message - message type not specified.
     */
    public function testNoTypeSet()
    {
        $this->factory->create([]);
    }

    /**
     * @param string $type
     * @param string $expectedClass
     * @dataProvider messageTypesProvider
     */
    public function testCreate($type, $expectedClass)
    {
        $message = $this->factory->create(['type' => $type]);
        $this->assertInstanceOf($expectedClass, $message);
    }

    /**
     * @return array
     */
    public function messageTypesProvider()
    {
        return [
            ['info', Warning::class],
            ['error', Error::class],
            ['non-document-error', Error::class],
        ];
    }
}
