<?php

namespace WPPayForm\app\Services;

use WPPayForm\App\Models\ScheduledActions;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Models\Form;

class AsyncRequest
{
    /**
     * $prefix The prefix for the identifier
     * @var string
     */
    protected $table = 'wpf_scheduled_actions';

    /**
     * $prefix The prefix for the identifier
     * @var string
     */
    protected $action = 'wppayform_background_process';

    public function queueFeeds($feeds)
    {
        return ScheduledActions::insert($feeds);
    }

    public function dispatchAjax($data = [])
    {
        $args = array(
            'timeout' => 0.1,
            'blocking' => false,
            'body' => $data,
            'cookies' => map_deep($_COOKIE, 'wp_kses_post'),
            'sslverify' => apply_filters('wppayform_https_local_ssl_verify', false),
        );

        $queryArgs = array(
            'action' => $this->action,
            'nonce' => wp_create_nonce($this->action),
        );

        $url = add_query_arg($queryArgs, admin_url('admin-ajax.php'));
        wp_remote_post(esc_url_raw($url), $args);
    }

    public function handleBackgroundCall()
    {
        $originId = false;
        if (isset($_REQUEST['origin_id'])) {
            $originId = intval($_REQUEST['origin_id']);
        }

        $this->processActions($originId);
        echo 'success';
        die();
    }

    public function processActions($originId = false)
    {
        $actionFeedQuery = ScheduledActions::where('status', 'pending');
        if ($originId) {
            $actionFeedQuery = $actionFeedQuery->where('origin_id', $originId);
        }

        $actionFeeds = $actionFeedQuery->get();

        if (!$actionFeeds) {
            return;
        }

        $formCache = [];
        $submissionCache = [];
        $entryCache = [];

        foreach ($actionFeeds as $actionFeed) {
            $action = $actionFeed->action;
            $feed = maybe_unserialize($actionFeed->data);
            $feed['scheduled_action_id'] = $actionFeed->id;
            if (isset($submissionCache[$actionFeed->origin_id])) {
                $submission = $submissionCache[$actionFeed->origin_id];
            } else {
                $submission = Submission::find($actionFeed->origin_id);
                $submissionCache[$submission->id] = $submission;
            }
            if (isset($formCache[$submission->form_id])) {
                $form = $formCache[$submission->form_id];
            } else {
                $form = Form::where('post_type', 'wp_payform')->find($submission->form_id);
                $formCache[$form->id] = $form;
            }

            if (isset($entryCache[$submission->id])) {
                $entry = $entryCache[$submission->id];
            } else {
                $entry = $this->getEntry($submission->id, $form->ID);
                $entryCache[$submission->id] = $entry;
            }

            $formData = json_decode($submission->response, true);

            ScheduledActions::where('id', $actionFeed->id)
                ->update([
                    'status' => 'processing',
                    'retry_count' => $actionFeed->retry_count + 1,
                    'updated_at' => current_time('mysql')
                ]);
            do_action($action, $feed, $formData, $entry, $form->ID);
        }

        if ($originId && !empty($form) && !empty($submission)) {
            do_action('wppayform_global_notify_completed', $submission->id, $form->ID);
        }
    }

    private function getEntry($submissionId, $formId)
    {
        return (new Submission())->getSubmission($submissionId);
    }
}
