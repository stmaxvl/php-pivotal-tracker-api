<?php

namespace PivotalTracker\Api;

use PivotalTracker\Api\Story\Task;
use PivotalTracker\Api\Story\Comment;
use PivotalTracker\Exception\MissingArgumentException;

/**
 * Listing stories, editing and delete your projects stories.
 *
 * @link   https://www.pivotaltracker.com/help/api/rest/v5#Stories
 *
 */
class Story extends AbstractApi
{
    /**
     * List project stories.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#Stories
     *
     * @param string $project_id   the id project
     * @param array  $params       the additional parameters like with_label, with_story_type, with_state, after_story_id, ..., filter
     *
     * @return array list of stories found
     */
    public function all($project_id, array $params = array())
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/stories', $params);
    }

    /**
     * Get extended information about an story by project id.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_get
     *
     * @param string $project_id   the ID of the project.
     * @param string $id         the epic id
     *
     * @return array information about the story
     */
    public function show($project_id, $id)
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($id));
    }

    /**
     * Create a new story for the given project.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_post
     *
     * @param string $project_id   the ID of the project.
     * @param array  $params     the new story data
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

        return $this->post('/projects/'.rawurlencode($project_id).'/stories', $params);
    }

    /**
     * Update existence epic by id.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_put
     *
     * @param string $project_id the id of the project
     * @param string $id         the epic id
     * @param array  $params     key=>value user attributes to update.
     *                           key can be name or body
     *
     * @return array information about the epic
     */
    public function update($project_id, $id, array $params)
    {
        return $this->put('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($id), $params);
    }

    /**
     * Delete a story.
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

    /**
     * List an issue tasks.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#Story_Tasks
     *
     * @return Task
     */
    public function tasks()
    {
        return new Task($this->client);
    }

    /**
     * List an issue comments.
     *
     * @link   https://www.pivotaltracker.com/help/api/rest/v5#Comments
     *
     * @return Comment
     */
    public function comments()
    {
        return new Comment($this->client);
    }
}
