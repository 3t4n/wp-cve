<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\Models\Conversation;
use FluentSupport\App\Services\Includes\UploadService;

class FileUploadHandler
{
    public function init()
    {

    }

    private function updateFileInfo($file, $uploadInfo)
    {
        $file->file_path = $uploadInfo['file_path'] ?? $uploadInfo['path'] ?? null;
        $file->full_url = $uploadInfo['url'] ?? $uploadInfo['full_url'] ?? null;
        $file->title = $uploadInfo['name'] ?? $file->title;
        $file->driver = $uploadInfo['driver'] ?? 'local';
        $file->status = 'active';
        $file->save();

        return true;
    }

    public static function getFileUploadErrorNoteMessage($driver = 'local', $type = 'response')
    {
        $errorMessages = [
            'google_drive_settings' => 'Google Drive',
            'dropbox_settings'      => 'Dropbox',
            'local'                 => 'Local Server',
        ];

        if ('response' == $type) {
            $noteMessage = 'Attached file in this conversation is failed to upload to ' . $errorMessages[$driver] . ', Please check your';
        } else {
            $noteMessage = 'Attached file during creation this ticket is failed to upload to ' . $errorMessages[$driver] . ', Please check your';
        }

        if ('local' == $driver) {
            $noteMessage .= ' upload folder permission is writeable or not';
        } else {
            $noteMessage .= ' ' . $errorMessages[$driver] . ' settings';
        }

        return $noteMessage;
    }
}
