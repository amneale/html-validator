<?php

namespace Amneale\HtmlValidator;

use Amneale\HtmlValidator\Exception\ResponseException;
use Amneale\HtmlValidator\Message\AbstractMessage;
use Amneale\HtmlValidator\Message\Error;
use Amneale\HtmlValidator\Message\Factory;
use Amneale\HtmlValidator\Message\Warning;
use Psr\Http\Message\ResponseInterface;

class Result
{
    /**
     * @var Error[]
     */
    private $errors = [];

    /**
     * @var Warning[]
     */
    private $warnings = [];

    /**
     * @var AbstractMessage[]
     */
    private $messages = [];

    /**
     * @param ResponseInterface $response
     * @throws \Exception
     */
    public function __construct(ResponseInterface $response)
    {
        $result = json_decode($response->getBody(), true);

        if (!isset($result['messages'])) {
            // TODO do we get messages if validation passes?
            throw new ResponseException('Response contains no messages');
        }

        $factory = new Factory();

        foreach ($result['messages'] as $messageAttributes) {
            $message = $factory->create($messageAttributes);

            if ($message instanceof Error) {
                $this->errors[] = $message;
            }

            if ($message instanceof Warning) {
                $this->warnings[] = $message;
            }

            $this->messages[] = $message;
        }
    }

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * @return Warning[]
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * @return bool
     */
    public function hasWarnings()
    {
        return !empty($this->warnings);
    }

    /**
     * @return AbstractMessage[]
     */
    public function getMessages()
    {
        return $this->messages;
    }
}