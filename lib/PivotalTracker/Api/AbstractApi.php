<?php

namespace PivotalTracker\Api;

use PivotalTracker\Client;
use PivotalTracker\HttpClient\Message\ResponseMediator;

/**
 * Abstract class for Api classes.
 *
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * The client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Number of items per page - limit (Pivotal Tracker pagination).
     *
     * @var null|int
     */
    protected $limit;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function configure()
    {
    }

    /**
     * @return null|int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param null|int $perPage
     */
    public function setLimit($limit)
    {
        $this->limit = (null === $limit ? $limit : (int) $limit);

        return $this;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     GET parameters.
     * @param array  $requestHeaders Request Headers.
     *
     * @return array|string
     */
    protected function get($path, array $parameters = array(), array $requestHeaders = array())
    {
        if (null !== $this->limit && !isset($parameters['limit'])) {
            $parameters['limit'] = $this->limit;
        }

        if (count($parameters) > 0) {
            $path .= '?'.http_build_query($parameters);
        }

        $response = $this->client->getHttpClient()->get($path, $this->addContentTypeToHeader($requestHeaders));

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a HEAD request with query parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     HEAD parameters.
     * @param array  $requestHeaders Request headers.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function head($path, array $parameters = array(), array $requestHeaders = array())
    {
        if (array_key_exists('ref', $parameters) && is_null($parameters['ref'])) {
            unset($parameters['ref']);
        }

        $response = $this->client->getHttpClient()->head($path.'?'.http_build_query($parameters), $requestHeaders);

        return $response;
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be JSON encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return array|string
     */
    protected function post($path, array $parameters = array(), array $requestHeaders = array())
    {
        $requestHeaders = array_merge($requestHeaders,  ['Content-Type' => 'application/json']);
        return $this->postRaw(
            $path,
            $this->createJsonBody($parameters),
            $requestHeaders
        );
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string $path           Request path.
     * @param string $body           Request body.
     * @param array  $requestHeaders Request headers.
     *
     * @return array|string
     */
    protected function postRaw($path, $body, array $requestHeaders = array())
    {
        $response = $this->client->getHttpClient()->post(
            $path,
            $requestHeaders,
            $body
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be JSON encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return array|string
     */
    protected function patch($path, array $parameters = array(), array $requestHeaders = array())
    {
        $response = $this->client->getHttpClient()->patch(
            $path,
            $this->addContentTypeToHeader($requestHeaders),
            $this->createJsonBody($parameters)
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be JSON encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return array|string
     */
    protected function put($path, array $parameters = array(), array $requestHeaders = array())
    {
        $requestHeaders = array_merge($requestHeaders,  ['Content-Type' => 'application/json']);
        $response = $this->client->getHttpClient()->put(
            $path,
            $requestHeaders,
            $this->createJsonBody($parameters)
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be JSON encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return array|string
     */
    protected function delete($path, array $parameters = array(), array $requestHeaders = array())
    {
        $response = $this->client->getHttpClient()->delete(
            $path,
            $this->addContentTypeToHeader($requestHeaders),
            $this->createJsonBody($parameters)
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $parameters Request parameters
     *
     * @return null|string
     */
    protected function createJsonBody(array $parameters)
    {
        return (count($parameters) === 0) ? null : json_encode($parameters, empty($parameters) ? JSON_FORCE_OBJECT : 0);
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $requestHeaders array of headers
     *
     * @return array
     */
    protected function addContentTypeToHeader(array $requestHeaders){
        return  array_merge($requestHeaders,  ['Content-Type' => 'application/json']);
    }
}