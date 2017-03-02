<?php

namespace PivotalTracker\Api;

use PivotalTracker\Api\Story\Attachment;

/**
 * Listing, editing and delete your story's comments.
 *
 * @link   https://www.pivotaltracker.com/help/api/rest/v5#Comments
 *
 */
class Comment extends AbstractApi
{
    /**
     * List story's comments.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#Stories
     *
     * @param string $project_id   the id project
     * @param string $story_id     the id story
     *
     * @return array the specified story's comments.
     */
    public function all($project_id, $story_id)
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($story_id).'/comments');
    }

    /**
     * Get extended information about an story by project id.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_get
     *
     * @param string $project_id   the ID of the project.
     * @param string $id         the epic id
     *
     * @return array information about the comment
     */
    public function show($project_id, $id)
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($id));
    }

    /**
     * Create a new comment for the given story.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_comments_post
     *
     * @param string $project_id   the ID of the project.
     * @param string $story_id     the id story
     * @param array  $params     the new story data
     *
     *
     * @return array information about the comment
     */
    public function create($project_id, $story_id, array $params)
    {
        return $this->post('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($story_id).'/comments', $params);
    }

    /**
     * Update existence epic by id.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_put
     *
     * @param string $project_id the id of the project
     * @param string $story_id   the id story
     * @param string $id         the comment id
     * @param array  $params     key=>value user attributes to update.
     *                           key can be text or etc
     *
     * @return array information about the comment
     */
    public function update($project_id, $story_id, $id, array $params)
    {
        return $this->put('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($story_id).'/comments/'.rawurlencode($id), $params);
    }

    /**
     * Delete a comment.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_stories_story_id_comments_comment_id_delete
     *
     * @param string $project_id the id of the project
     * @param string $story_id   the id story
     * @param string $id         the comment id
     *
     * @return mixed null on success, array on error with 'error'
     */
    public function remove($project_id, $story_id, $id)
    {
        return $this->delete('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($story_id).'/comments/'.rawurlencode($id));
    }

    /**
     * Operation with an attachment.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#Attachments
     *
     * @return Attachment
     */
    public function attachments()
    {
        return new Attachment($this->client);
    }
}
