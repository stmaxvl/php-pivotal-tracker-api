<?php

namespace PivotalTracker\Api\Epic;

use PivotalTracker\Api\AbstractApi;

/**
 * Operations on an individual file attachment.
 *
 * @link https://www.pivotaltracker.com/help/api/rest/v5#Attachments
 */
class Attachment extends AbstractApi
{
    /**
     * Delete an attachment.
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_epics_epic_id_comments_comment_id_file_attachments_file_attachment_id_delete
     *
     * @param string $project_id the id of the project
     * @param string $epic_id   the ID of the epic.
     * @param string $comment_id   the ID of the story.
     * @param string $id         the task id
     *
     * @return mixed null on success, array on error with 'error'
     */
    public function remove($project_id, $epic_id, $comment_id, $id)
    {
        return $this->delete('/projects/'.rawurlencode($project_id).'/stories/'.rawurlencode($epic_id).'/comments/'.rawurlencode($comment_id).'/file_attachments/'.rawurlencode($id));
    }
}
