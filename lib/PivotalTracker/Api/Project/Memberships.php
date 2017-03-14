<?php

namespace PivotalTracker\Api\Project;

use PivotalTracker\Api\AbstractApi;

class Memberships extends AbstractApi
{
    /**
     * List all of the memberships in a project
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#Project_Memberships
     *
     * @param int $project_id the ID of the project.
     * @return array
     */
    public function all($project_id)
    {
        return $this->get('/projects/'.rawurldecode($project_id).'/memberships');
    }
}
