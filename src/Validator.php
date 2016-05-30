<?php

namespace Amneale\HtmlValidator;

use GuzzleHttp\Client;
use RuntimeException;
use function GuzzleHttp\json_decode;

class Validator
{
    const DEFAULT_URL = 'https://validator.nu/';

    const PARSER_XML = 'xml';
    const PARSER_XMLDTD = 'xmldtd';
    const PARSER_HTML = 'html';
    const PARSER_HTML5 = 'html5';
    const PARSER_HTML4 = 'html4';
    const PARSER_HTML4TR = 'html4tr';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $parser;

    public function __construct($validatorUrl = self::DEFAULT_URL, $parser = self::PARSER_HTML)
    {
        $this->setClient(new Client(['base_uri' => $validatorUrl]));
        $this->setParser($parser);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param string $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param $url
     * @return Message[]
     * @throws RuntimeException
     */
    public function validateUrl($url)
    {
        $response = $this->client->get(
            '',
            [
                'query' => [
                    'doc' => $url,
                    'parser' => $this->parser,
                    'out' => 'json',
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('Server responded with HTTP status ' . $response->getStatusCode());
        }

        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === false) {
            throw new RuntimeException('Server did not respond with the expected content-type (application/json)');
        }

        $messages = [];
        $result = json_decode($response->getBody(), true);

        if (!isset($result['messages'])) {
            return $messages;
        }

        foreach ($result['messages'] as $messageAttributes) {
            $messages[] = new Message($messageAttributes);
        }

        return $messages;
    }
}
