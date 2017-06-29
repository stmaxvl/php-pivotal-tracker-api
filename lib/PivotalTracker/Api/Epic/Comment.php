<?php

namespace PivotalTracker\Api\Epic;

use PivotalTracker\Api\AbstractApi;

/**
 * Listing, editing and delete your epic's comments.
 *
 * @link   https://www.pivotaltracker.com/help/api/rest/v5#Comments
 *
 */
class Comment extends AbstractApi
{
    /**
     * List epics comments.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#Epics
     *
     * @param string $project_id  the project id
     * @param string $epic_id     the epic id
     * @param array $params     the additional parameters like file_attachments, ..., filter
     *
     * @return array the specified epics comments.
     */
    public function all($project_id, $epic_id, array $params = array())
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/epics/'.rawurlencode($epic_id).'/comments' , $params);
    }

    /**
     * Get extended information about an comment by project and epic id.
     *
     * https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_comments_comment_id_get
     *
     * @param string $project_id   the ID of the project.
     * @param string $epic_id      the epic id
     * @param string $id           the comment id
     *
     * @return array information about the comment
     */
    public function show($project_id, $epic_id, $id)
    {
        return $this->get('/projects/'.rawurlencode($project_id).'/epics/'.rawurlencode($epic_id).'/comments/'.rawurlencode($id));
    }

    /**
     * Create a new comment for the given epic.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_comments_post
     *
     * @param string $project_id   the ID of the project.
     * @param string $epic_id      the epic id
     * @param array  $params       the new comment data
     *
     *
     * @return array information about the comment
     */
    public function create($project_id, $epic_id, array $params)
    {
        return $this->post('/projects/'.rawurlencode($project_id).'/epics/'.rawurlencode($epic_id).'/comments', $params);
    }

    /**
     * Update existence comment by id.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_comments_comment_id_put
     *
     * @param string $project_id the id of the project
     * @param string $epic_id    the epic id
     * @param string $id         the comment id
     * @param array  $params     key=>value user attributes to update.
     *                           key can be text or etc
     *
     * @return array information about the comment
     */
    public function update($project_id, $epic_id, $id, array $params)
    {
        return $this->put('/projects/'.rawurlencode($project_id).'/epics/'.rawurlencode($epic_id).'/comments/'.rawurlencode($id), $params);
    }

    /**
     * Delete a comment.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_comments_comment_id_delete
     *
     * @param string $project_id the id of the project
     * @param string $epic_id   the id epic
     * @param string $id         the comment id
     *
     * @return mixed null on success, array on error with 'error'
     */
    public function remove($project_id, $epic_id, $id)
    {
        return $this->delete('/projects/'.rawurlencode($project_id).'/epics/'.rawurlencode($epic_id).'/comments/'.rawurlencode($id));
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
