<?php

namespace WPPayForm\App\Models;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Manage Submission
 * @since 1.0.0
 */
class SubmissionActivity extends Model
{
    protected $table = 'wpf_submission_activities';

    public static function getSubmissionActivity($submissionId)
    {
        $activities = static::where('submission_id', $submissionId)
            ->orderBy('id', 'DESC')
            ->get();
        foreach ($activities as $activitiy) {
            if ($activitiy->created_by_user_id) {
                $activitiy->user_profile_url = get_edit_user_link($activitiy->created_by_user_id);
            }
        }

        return apply_filters('wppayform/entry_activities', $activities, $submissionId);
    }

    public static function createActivity($data)
    {
        $data['created_at'] = current_time('mysql');
        $data['updated_at'] = current_time('mysql');

        return static::insert($data);
    }

    public static function deleteActivity($formId, $entryId, $noteId)
    {
        $query = SubmissionActivity::where('submission_id', $entryId)
        ->where('id', $noteId)
        ->where('form_id', $formId)
        ->delete();

        if ($query === 0) {
            wp_send_json_error(array(
                'success' => false,
                'message' => 'Something went wrong.'
            ));
        }

        return array(
            'success' => true,
            'message' => 'Log deleted successfully!',
            'activities' => self::getSubmissionActivity($entryId)
        );
    }
}
