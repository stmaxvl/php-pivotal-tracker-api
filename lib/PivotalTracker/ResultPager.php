<?php

namespace PivotalTracker;

use PivotalTracker\Api\ApiInterface;
use PivotalTracker\HttpClient\Message\ResponseMediator;

/**
 * Pager class for supporting pagination in pivotal classes.
 *
 */
class ResultPager implements ResultPagerInterface
{
    /**
     * The Pivotal Tracker Client to use for pagination.
     *
     * @var \PivotalTracker\Client
     */
    protected $client;

    /**
     * Comes from pagination headers in Pivotal API results.
     *
     * @var array
     */
    protected $pagination;

    /**
     * The Pivotal client to use for pagination.
     *
     * This must be the same instance that you got the Api instance from.
     *
     * Example code:
     *
     * $client = new \PivotalTracker\Client();
     * $api = $client->api('someApi');
     * $pager = new \PivotalTracker\ResultPager($client);
     *
     * @param \PivotalTracker\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(ApiInterface $api, $method, array $parameters = array())
    {
        $result = $this->callApi($api, $method, $parameters);
        $this->postFetch();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll(ApiInterface $api, $method, array $parameters = array())
    {
        // get the limit from the api
        $limit = $api->getLimit();

        // set parameters limit to Pivotal Tracker max to minimize number of requests
        $api->setLimit(100);

        $result = $this->callApi($api, $method, $parameters);
        $this->postFetch();

        while ($this->hasNext()) {
            $next = $this->fetchNext($api, $method, $parameters);
            $result = array_merge($result, $next);
        }

        // restore the limit
        $api->setLimit($limit);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function postFetch()
    {
        $this->pagination = ResponseMediator::getPagination($this->client->getLastResponse());
    }

    /**
     * {@inheritdoc}
     */
    public function hasNext()
    {
        return ($this->pagination['offset'] + $this->pagination['limit']) < $this->pagination['total'];
    }

    /**
     * {@inheritdoc}
     */
    public function fetchNext(ApiInterface $api, $method, array $parameters = array())
    {
        $current = $this->pagination['offset'] + $this->pagination['limit'];
        $offset = $current > $this->pagination['total'] ? $this->pagination['total'] : $current;
        
        if(isset($parameters[1])) $parameters[1] = array_merge($parameters[1], ['offset' => $offset, 'limit' => $api->getLimit()]);
        $result = $this->callApi($api, $method, $parameters);
        $this->postFetch();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPrevious()
    {
        return ($this->pagination['offset'] - $this->pagination['limit']) >= 0;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPrevious(ApiInterface $api, $method, array $parameters = array())
    {
        $current = $this->pagination['offset'] - $this->pagination['limit'];
        $offset = $current >= 0 ? : 0;
        
        if(isset($parameters[1])) $parameters[1] = array_merge($parameters[1], ['offset' => $offset, 'limit' => $api->getLimit()]);
        $result = $this->callApi($api, $method, $parameters);
        $this->postFetch();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function get($key)
    {
        $result = $this->client->getHttpClient()->get($this->pagination['offset'] = $key);
        $this->postFetch();

        return ResponseMediator::getContent($result);
    }

    /**
     * @param ApiInterface $api
     * @param $method
     * @param array $parameters
     *
     * @return mixed
     */
    protected function callApi(ApiInterface $api, $method, array $parameters)
    {
        return call_user_func_array(array($api, $method), $parameters);
    }
}
