<?php

namespace PivotalTracker\Api;

use PivotalTracker\Progect\Api\Memberships;

/**
 * Get all of a user's active projects, fetch the content of the specified project
 * Create, update, delete the specified project.
 *
 * @link   https://www.pivotaltracker.com/help/api/rest/v5#Project
 */
class Project extends AbstractApi
{
    /**
     * List all projects.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_get
     *
     * @return array list of projects found
     */
    public function all()
    {
        return $this->get('/projects');
    }

    /**
     * Get a project by id
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_get
     *
     * @param string $id     the projects's id
     *
     * @return array project info
     */
    public function show($id)
    {
        return $this->get('/projects/'.rawurlencode($id));
    }

    /**
     * Create project.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_post
     *
     * @param string      $name         name of the project
     * @param string      $description  description of the project's content.
     * @param string      $project_type Valid enumeration values: demo, private, public, shared
     *
     * @return array returns repository data
     */
    public function create(
        $name,
        $description = '',
        $public = 'private'
    ) {

        $parameters = array(
            'name'          => $name,
            'description'   => $description,
            'private'       => !$public
        );

        return $this->post('/projects/', $parameters);
    }

    /**
     * Update the specified project.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_put
     *
     * @param string $project_id the name of the repository
     * @param array  $values     the key => value pairs to post
     *
     * @return array information about the project
     */
    public function update($project_id, array $values)
    {
        return $this->patch('/projects/'.rawurlencode($project_id), $values);
    }

    /**
     * Manage the project Memberships
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#Project_Memberships
     *
     * @return Memberships
     */
    public function memberships()
    {
        return new Memberships($this->client);
    }
}
