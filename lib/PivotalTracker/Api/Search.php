<?php

namespace PivotalTracker\Api;

use PivotalTracker\Exception\MissingArgumentException;

/**
 * Search for stories and epics.
 *
 * @link https://www.pivotaltracker.com/help/api/rest/v5#Search
 *
 */
class Search extends AbstractApi
{

    /**
     * GET only; searches the project data and returns the stories and/or epics matching the query_string.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_search_get
     *
     * @param string $project_id   the id project
     * @param array $params  query=label:mnt AND includedone:true
     *
     * @throws MissingArgumentException
     *
     * @return array return the search_result_container resource.
     */
    public function show($project_id, array $params = array())
    {
        if (!isset($params['query'])) {
            throw new MissingArgumentException(array('query'));
        }
        return $this->get('/projects/'.rawurlencode($project_id).'/search', $params);
    }
}
