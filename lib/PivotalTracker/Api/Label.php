<?php

namespace PivotalTracker\Api;

use PivotalTracker\Exception\MissingArgumentException;

/**
 * Listing, editing and delete your project's labels.
 *
 * @link   https://www.pivotaltracker.com/help/api/rest/v5#Labels
 *
 */
class Label extends AbstractApi
{
    /**
     * List of the project's labels.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_labels_get
     *
     * @param string $project_id   the id project
     * @param array  $params       the additional parameters like name
     *
     * @return array list of stories found
     */
    public function all($project_id, array $params = array())
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/labels', $params);
    }

    /**
     * Get extended information about a project's label.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_labels_label_id_get
     *
     * @param string $project_id   the ID of the project.
     * @param string $id         the label's id
     *
     * @return array information about the label
     */
    public function show($project_id, $id)
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/labels/'.rawurlencode($id));
    }

    /**
     * Creates a label on the project.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_labels_post
     *
     * @param string $project_id   the ID of the project.
     * @param array  $params     the new story data
     *
     * @throws MissingArgumentException
     *
     * @return array information about the labels
     */
    public function create($project_id, array $params)
    {
        if (!isset($params['name'])) {
            throw new MissingArgumentException(array('name'));
        }

        return $this->post('/projects/'.rawurlencode($project_id).'/labels', $params);
    }

    /**
     * Updates a project's label.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_labels_label_id_put
     *
     * @param string $project_id the id of the project
     * @param string $id         the label's id
     * @param array  $params     key=>value user attributes to update.
     *                           key can be name
     *
     * @return array information about the label
     */
    public function update($project_id, $id, array $params)
    {
        return $this->put('/projects/'.rawurlencode($project_id).'/labels/'.rawurlencode($id), $params);
    }

    /**
     * Delete a project's label.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_delete
     *
     * @param string $project_id the id of the project
     * @param string $id         the story id
     *
     * @return mixed null on success, array on error with 'error'
     */
    public function remove($project_id, $id)
    {
        return $this->delete('/projects/'.rawurlencode($project_id).'/srories/'.rawurlencode($id));
    }
}
