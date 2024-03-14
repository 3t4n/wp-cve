<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Models\Attachment;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Services\EmailNotification\Settings;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Request\Request;
use FluentSupport\App\Services\Includes\UploadService;

/**
 * UploaderController class is responsible for uploading file
 * @package FluentSupport\App\Http\Controllers
 *
 * @version 1.0.0
 */
class UploaderController extends Controller
{
    /**
     * uploadTicketFiles method will upload all the attached file in a ticket
     * @param Request $request
     * @return array[]
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function uploadTicketFiles(Request $request)
    {
        $settings = (new Settings())->globalBusinessSettings();
        $maxFileSize = floatval($settings['max_file_size']);
        $mimeHeadings = Helper::getAcceptedMimeHeadings();
        $maxSizeBytes = $maxFileSize * 1024;

        $this->validateUploadedFiles($request->files(), $maxSizeBytes, $mimeHeadings, $maxFileSize);
        $ticketId = $this->resolveTicketId($request);
        $person = $this->resolvePerson($ticketId, $request);

        $this->checkPermissionToUploadFile($person);

        try {
            $uploadedFiles = UploadService::handleTempFileUpload($request->files());
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage(),
            ]);
        }

        if (is_wp_error($uploadedFiles)) {
            return $this->sendError([
                'message' => $uploadedFiles->get_error_message(),
            ]);
        }

        $attachmentHashes = $this->createAttachmentRecords($uploadedFiles, $ticketId, $person);

        return [
            'attachments' => $attachmentHashes,
        ];
    }

    private function validateUploadedFiles($files, $maxSizeBytes, $mimeHeadings, $maxFileSize)
    {
        $validationRules = [
            'file' => 'max:' . $maxSizeBytes . '|mimetypes:' . implode(',', Helper::ticketAcceptedFileMiles()),
        ];

        $validationMessages = [
            'file.mimetypes' => sprintf(__('Only %s files are allowed.', 'fluent-support'), implode(', ', $mimeHeadings)),
            'file.max'       => sprintf(__('The file cannot be more than %.01fMB. Please upload somewhere like Dropbox/Google Drive and paste the link in the response', 'fluent-support'), $maxFileSize),
        ];

        $this->validate($files, $validationRules, $validationMessages);
    }

    private function resolveTicketId($request)
    {
        $ticketId = $request->getSafe('ticket_id', 'intval');
        return $ticketId == 'undefined' ? null : $ticketId;
    }

    private function resolvePerson($ticketId, Request $request)
    {
        if ($request->get('is_agent') == 'yes') {
            return Helper::getCurrentAgent();
        }

        if ($ticketId && Helper::isPublicSignedTicketEnabled()) {
            $intendedTicketHash = $request->getSafe('intended_ticket_hash', 'sanitize_text_field');
            if ($intendedTicketHash && $intendedTicketHash != 'undefined') {
                $ticket = Ticket::with(['customer'])
                    ->where('hash', $intendedTicketHash)
                    ->find($ticketId);

                if ($ticket && $ticket->customer) {
                    return $ticket->customer;
                }
            }
        }

        return Helper::getCurrentPerson();
    }

    private function checkPermissionToUploadFile($person)
    {
        if (!$person) {
            return $this->sendError([
                'message' => __('You do not have permission to upload a file', 'fluent-support'),
            ]);
        }

        if ($person->person_type === 'customer') {
            $disabledFields = apply_filters('fluent_support/disabled_ticket_fields', []);
            if (in_array('file_upload', $disabledFields)) {
                return $this->sendError([
                    'message' => __('You do not have permission to upload a file', 'fluent-support'),
                ]);
            }
        }
    }

    private function createAttachmentRecords($uploadedFiles, $ticketId, $person)
    {
        $attachments = [];

        foreach ($uploadedFiles as $file) {
            if (empty($file['file_path'])) continue;

            $fileData = [
                'ticket_id' => intval($ticketId) ?: NULL,
                'person_id' => intval($person->id),
                'file_type' => $file['type'],
                'file_path' => $file['file_path'],
                'full_url'  => esc_url($file['url']),
                'title'     => sanitize_file_name($file['name']),
                'driver'    => 'local',
                'status'    => 'in-active',
                'settings'  => [
                    'local_temp_path' => $file['file_path'],
                ]
            ];

            try {
                $attachment = Attachment::create($fileData);
                $attachments[] = $attachment->file_hash;
                do_action('fluent_support/attachment_uploaded_as_temp', $attachment, $ticketId);
                $driver = Helper::getUploadDriverKey();

                do_action_ref_array('fluent_support/attachment_uploaded_as_temp_' . $driver, [&$attachment, $ticketId]);
            } catch (\Exception $exception) {
                continue;
            }
        }

        return $attachments;
    }
}
