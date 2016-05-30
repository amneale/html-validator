<?php

namespace Amneale\HtmlValidator;

use InvalidArgumentException;

class Message
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $firstLine;

    /**
     * @var int
     */
    protected $lastLine;

    /**
     * @var int
     */
    protected $firstColumn;

    /**
     * @var int
     */
    protected $lastColumn;

    /**
     * @var string
     */
    protected $subType;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $extract;

    /**
     * @var int
     */
    protected $hiliteStart;

    /**
     * @var int
     */
    protected $hiliteEnd;

    /**
     * @var array
     */
    protected $defaults = [
        'lastLine'     => 0,
        'firstColumn'  => 0,
        'lastColumn'   => 0,
        'hiliteStart'  => 0,
        'hiliteLength' => 0,
        'message'      => '',
        'extract'      => '',
        'subType'      => null,
    ];

    public function __construct(array $attributes)
    {
        $attributes = array_merge($this->defaults, $attributes);

        if (!isset($attributes['type'])) {
            throw new InvalidArgumentException('Invalid message: message type not specified.');
        }

        $this->type =  $attributes['type'];
        $this->firstLine = isset($attributes['firstLine']) ? $attributes['firstLine'] : $attributes['lastLine'];
        $this->lastLine = $attributes['lastLine'];
        $this->firstColumn = $attributes['firstColumn'];
        $this->lastColumn = $attributes['lastColumn'];
        $this->message = $attributes['message'];
        $this->extract = $attributes['extract'];
        $this->hiliteStart = $attributes['hiliteStart'];
        $this->hiliteEnd = $attributes['hiliteLength'];
        $this->subType = $attributes['subType'];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getFirstLine()
    {
        return $this->firstLine;
    }

    /**
     * @return int
     */
    public function getLastLine()
    {
        return $this->lastLine;
    }

    /**
     * @return int
     */
    public function getFirstColumn()
    {
        return $this->firstColumn;
    }

    /**
     * @return int
     */
    public function getLastColumn()
    {
        return $this->lastColumn;
    }

    /**
     * @return string
     */
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getExtract()
    {
        return $this->extract;
    }

    /**
     * @return int
     */
    public function getHiliteStart()
    {
        return $this->hiliteStart;
    }

    /**
     * @return int
     */
    public function getHiliteEnd()
    {
        return $this->hiliteEnd;
    }
}
