<?php

namespace PivotalTracker\Api;

use PivotalTracker\Exception\MissingArgumentException;

/**
 * Upload file in the project.
 *
 * @link   https://www.pivotaltracker.com/help/api/rest/v5#Epics
 *
 */
class Upload extends AbstractApi
{

    /**
     * Response to this get will either be the content of the file or a redirect to a non-Tracker storage server
     * that will provide the attachment (i.e., S3).
     *
     * @link https://www.pivotaltracker.com/file_attachments/{file_attachment_id}/download
     *
     * @param string $id     the attachment's id
     *
     * @return array download the original file content of an attachment.
     */
    public function show($id)
    {
        return $this->get('https://www.pivotaltracker.com/file_attachments/'.rawurlencode($id).'/download');
    }

    /**
     * Upload a new file to the Tracker server
     *
     * @link https://www.pivotaltracker.com/help/api/rest/v5#projects_project_id_uploads
     *
     * @param string $project_id   the ID of the project.
     * @param object|string  $multipartStream   the multipart stream as body for request
     * @param string $boundary the boundary for upload file
     *
     * @throws MissingArgumentException
     *
     * @return array information about the file attachment
     */
    public function create($project_id, $multipartStream, $boundary)
    {
        if (!isset($multipartStream)) {
            throw new MissingArgumentException(array('multipartStream'));
        }

        return $this->postRaw('/projects/'.rawurlencode($project_id).'/uploads', $multipartStream, ['Content-Type' => 'multipart/form-data; boundary="'.$boundary.'"']);
    }
}
