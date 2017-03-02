<?php

namespace PivotalTracker;

use PivotalTracker\Api\ApiInterface;

/**
 * Pager interface.
 *
 */
interface ResultPagerInterface
{
    /**
     * @return null|array pagination result of last request
     */
    public function getPagination();

    /**
     * Fetch a single result (page) from an api call.
     *
     * @param ApiInterface $api        the Api instance
     * @param string       $method     the method name to call on the Api instance
     * @param array        $parameters the method parameters in an array
     *
     * @return array returns the result of the Api::$method() call
     */
    public function fetch(ApiInterface $api, $method, array $parameters = array());

    /**
     * Fetch all results (pages) from an api call.
     *
     * Use with care - there is no maximum.
     *
     * @param ApiInterface $api        the Api instance
     * @param string       $method     the method name to call on the Api instance
     * @param array        $parameters the method parameters in an array
     *
     * @return array returns a merge of the results of the Api::$method() call
     */
    public function fetchAll(ApiInterface $api, $method, array $parameters = array());

    /**
     * Method that performs the actual work to refresh the pagination property.
     */
    public function postFetch();

    /**
     * Check to determine the availability of a next page.
     *
     * @return bool
     */
    public function hasNext();

    /**
     * Check to determine the availability of a previous page.
     *
     * @return bool
     */
    public function hasPrevious();

    /**
     * Fetch the next page.
     *
     * @param ApiInterface $api        the Api instance
     * @param string       $method     the method name to call on the Api instance
     * @param array        $parameters the method parameters in an array
     *
     * @return array
     */
    public function fetchNext(ApiInterface $api, $method, array $parameters = array());

    /**
     * Fetch the previous page.
     *
     * @param ApiInterface $api        the Api instance
     * @param string       $method     the method name to call on the Api instance
     * @param array        $parameters the method parameters in an array
     *
     * @return array
     */
    public function fetchPrevious(ApiInterface $api, $method, array $parameters = array()   );
}
