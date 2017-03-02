<?php

namespace PivotalTracker\Api\Story;

use PivotalTracker\Api\AbstractApi;
use PivotalTracker\Exception\MissingArgumentException;

/**
 * Listing tasks in the story.
 *
 * @link https://www.pivotaltracker.com/help/api/rest/v5#Story_Tasks
 */
class Task extends AbstractApi
{
    /**
     * Get all tasks for an story.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_tasks_get
     *
     * @param string $project_id   the id project
     * @param string $story_id   the id story
     *
     * @return array list of stories found
     */
    public function all($project_id, $id)
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($id).'/tasks');
    }


    /**
     * Get extended information about specified task on a specified story
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_tasks_task_id_get
     *
     * @param string $project_id   the ID of the project.
     * @param string $story_id   the ID of the story.
     * @param string $id         the task id
     *
     * @return array information about the task
     */
    public function show($project_id, $story_id, $id)
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($story_id).'/tasks/'.rawurlencode($id));
    }

    /**
     * Create a new task for the given story.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_tasks_post
     *
     * @param string $project_id   the ID of the project.
     * @param string $story_id   the ID of the story.
     * @param array  $params     the new task data
     *
     * @throws MissingArgumentException
     *
     * @return array the task that was created.
     */
    public function create($project_id, $story_id, array $params)
    {
        if (!isset($params['description'])) {
            throw new MissingArgumentException(array('description'));
        }

        return $this->post('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($story_id).'/tasks/', $params);
    }

    /**
     * Update existence task by id.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_tasks_task_id_put
     *
     * @param string $project_id the id of the project
     * @param string $story_id   the ID of the story.
     * @param string $id         the task id
     * @param array  $params     key=>value user attributes to update.
     *                           key can be description
     *
     * @return array information about the task
     */
    public function update($project_id, $story_id, $id, array $params)
    {
        return $this->put('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($story_id).'/tasks/'.rawurlencode($id), $params);
    }

    /**
     * Delete an task.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_tasks_task_id_delete
     *
     * @param string $project_id the id of the project
     * @param string $story_id   the ID of the story.
     * @param string $id         the task id
     *
     * @return mixed null on success, array on error with 'error'
     */
    public function remove($project_id, $story_id, $id)
    {
        return $this->delete('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($story_id).'/tasks/'.rawurlencode($id));
    }
}
