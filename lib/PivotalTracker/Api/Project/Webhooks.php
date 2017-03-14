<?php

namespace PivotalTracker\Api\Project;

use PivotalTracker\Api\AbstractApi;

class Webhooks extends AbstractApi
{
    /**
     * List all of the project's webhooks.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_webhooks_get
     *
     * @param int $project_id the ID of the project.
     * @return array
     */
    public function all($project_id)
    {
        return $this->get('/projects/'.rawurldecode($project_id).'/webhooks');
    }
}
