<?php

namespace Amneale\HtmlValidator\Message;

use Amneale\HtmlValidator\Exception\InvalidArgumentException;

class Factory
{
    protected $defaults = array(
        'lastLine'     => 0,
        'firstColumn'  => 0,
        'lastColumn'   => 0,
        'hiliteStart'  => 0,
        'hiliteLength' => 0,
        'message'      => '',
        'extract'      => '',
        'subType'      => null,
    );

    public function create(array $attributes)
    {
        if (!isset($attributes['type'])) {
            throw new InvalidArgumentException('Invalid message - message type not specified.');
        }

        $attributes = array_merge($this->defaults, $attributes);
        $class = $attributes['type'] === 'info' ? Warning::class : Error::class;

        $message = new $class(
            $attributes['type'],
            isset($attributes['firstLine']) ? $attributes['firstLine'] : $attributes['lastLine'],
            $attributes['lastLine'],
            $attributes['firstColumn'],
            $attributes['lastColumn'],
            $attributes['message'],
            $attributes['extract'],
            $attributes['hiliteStart'],
            $attributes['hiliteLength'],
            $attributes['subType']
        );

        return $message;
    }
}