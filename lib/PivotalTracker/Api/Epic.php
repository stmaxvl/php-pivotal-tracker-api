<?php

namespace PivotalTracker\Api;

use PivotalTracker\Api\Epic\Comment;
use PivotalTracker\Exception\MissingArgumentException;

/**
 * Listing epics in the project.
 *
 * @link   https://www.pivotaltracker.com/help/api/rest/v5#Epics
 *
 */
class Epic extends AbstractApi
{
    /**
     * List project's epics.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_get
     *
     * @param string $project_id   the id project
     * @param array  $params       the additional parameters like filter
     *
     * @return array list of epics found
     */
    public function all($project_id, array $params = array())
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/epics', $params);
    }

    /**
     * Get extended information about a epic by project id.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_get
     *
     * @param string $project_id   the ID of the project.
     * @param string $id         the epic id
     *
     * @return array information about the epic
     */
    public function show($project_id, $id)
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/epics/'.rawurlencode($id));
    }

    /**
     * Create a new epic for the given project.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_post
     *
     * @param string $project_id   the ID of the project.
     * @param array  $params     the new epic data
     *
     * @throws MissingArgumentException
     *
     * @return array information about the epic
     */
    public function create($project_id, array $params)
    {
        if (!isset($params['name'])) {
            throw new MissingArgumentException(array('name'));
        }

        return $this->post('/projects/'.rawurlencode($project_id).'/epics', $params);
    }

    /**
     * Update existence epic by id.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_put
     *
     * @param string $project_id the id of the project
     * @param string $id         the story id
     * @param array  $params     key=>value user attributes to update.
     *                           key can be name or body
     *
     * @return array information about the story
     */
    public function update($project_id, $id, array $params)
    {
        return $this->put('/projects/'.rawurlencode($project_id).'/epics/'.rawurlencode($id), $params);
    }

    /**
     * Delete an epic.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_delete
     *
     * @param string $project_id the id of the project
     * @param string $id         the epic id
     *
     * @return mixed null on success, array on error with 'error'
     */
    public function remove($project_id, $id)
    {
        return $this->delete('/projects/'.rawurlencode($project_id).'/epics/'.rawurlencode($id));
    }

    /**
     * List an epics comments.
     *
     * @link  https://www.pivotaltracker.com/help/api/rest/v5#Comments
     *
     * @return Comment
     */
    public function comments()
    {
        return new Comment($this->client);
    }
}
