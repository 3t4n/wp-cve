<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\Models\Activity;
use FluentSupport\App\Models\Attachment;
use FluentSupport\App\Models\Meta;
use FluentSupport\App\Services\EmailNotification\Settings;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Services\Includes\FileSystem;
use FluentSupport\App\Services\Integrations\Maintenance;

class CleanupHandler
{
    public function initHourlyTasks()
    {
        $this->cleanLiveActivities();

        $this->maybeDeleteOldTempFiles();
    }

    public function initDailyTasks()
    {
        $this->cleanActivityLogs();
    }

    protected function cleanLiveActivities()
    {
        // Delete All Live Activity older than 24 hours
        $oldDateTime = date('Y-m-d H:i:s', current_time('timestamp') - 86400);

        Meta::where('key', '_live_activity')
            ->where('object_type', 'ticket_meta')
            ->where('updated_at', '<', $oldDateTime)
            ->delete();
    }

    protected function cleanActivityLogs()
    {
        $settings = Helper::getOption('_activity_settings', []);

        if (!$settings && empty($settings['delete_days'])) {
            $settings['delete_days'] = 14;
        }

        $oldDateTime = date('Y-m-d H:i:s', current_time('timestamp') - ($settings['delete_days'] * 86400));

        Activity::where('created_at', '<', $oldDateTime)->delete();
    }

    public function maybeDeleteAttachmentsOnClose($ticket)
    {
        $settings = (new Settings())->globalBusinessSettings();
        if ($settings['del_files_on_close'] == 'yes') {
            $this->deleteTicketAttachments($ticket);
        }
    }

    public function deleteTicketAttachments($ticket)
    {
        $uploadDir = wp_upload_dir();
        $dir = $uploadDir['basedir'] . FLUENT_SUPPORT_UPLOAD_DIR;

        $attachments = Attachment::where('ticket_id', $ticket->id)->get();

        if (!$attachments->isEmpty()) {
            $ticketDir = $dir . '/ticket_' . $ticket->id;
            if (is_dir($ticketDir)) {
                $this->deleteDir($ticketDir);
            }

            foreach ($attachments as $attachment) {
                if ($attachment->driver != 'local') {
                    do_action('fluent_support/delete_remote_attachment_' . $attachment->driver, $attachment, $ticket->id);
                } else if (file_exists($attachment->file_path)) {
                    @unlink($attachment->file_path);
                }
            }

            Attachment::where('ticket_id', $ticket->id)->delete();
        }

    }

    private function deleteDir($dir)
    {
        if (!class_exists('\WP_Filesystem_Direct')) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
            require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');
        }

        $fileSystemDirect = new \WP_Filesystem_Direct(false);
        $fileSystemDirect->rmdir($dir, true);
    }

    public function maybeMaintanceTask()
    {
        (new Maintenance())->maybeProcessData();
    }

    private function maybeDeleteOldTempFiles()
    {
        $dir = FileSystem::getDir();

        // loop through files in directory
        foreach (glob($dir . '/temp_files/*') as $filename) {
            // check if file was created before last 2 hours
            if (time() - filectime($filename) >= 7200) { // 2 hours
                @unlink($filename); // delete file
            }
        }
    }
}
