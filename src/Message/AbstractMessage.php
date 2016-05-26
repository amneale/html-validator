<?php

namespace Amneale\HtmlValidator\Message;

abstract class AbstractMessage
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

    public function __construct(
        $type,
        $firstLine,
        $lastLine,
        $firstColumn,
        $lastColumn,
        $message,
        $extract,
        $hiliteStart,
        $hiliteEnd,
        $subType
    ) {
        $this->type = $type;
        $this->firstLine = $firstLine;
        $this->lastLine = $lastLine;
        $this->firstColumn = $firstColumn;
        $this->lastColumn = $lastColumn;
        $this->message = $message;
        $this->extract = $extract;
        $this->hiliteStart = $hiliteStart;
        $this->hiliteEnd = $hiliteEnd;
        $this->subType = $subType;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }
}
