<?php

namespace WPPayForm\App\Http\Controllers;

use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\Form;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Models\SubmissionActivity;
use WPPayForm\App\Models\Transaction;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\App\Models\Subscription;

class SubmissionController extends Controller
{
    public function index($formId = false)
    {
        return (new Submission())->index($formId, $this->request->all());
    }

    public static function reports($formId)
    {
        $paymentStatuses = GeneralSettings::getPaymentStatuses();
        $submission = new Submission();

        $searchString = Arr::get($_REQUEST, 'search_string');
        if(isset($searchString)){
            $searchString = sanitize_text_field($searchString);
        }
        
        $startDate = Arr::get($_REQUEST, 'start_date');
        $endDate = Arr::get($_REQUEST, 'end_date');

        if (isset($startDate)) {
            $startDate = sanitize_text_field($startDate);
        }

        if ((isset($endDate))) {
            $endDate = sanitize_text_field($endDate);
        }
        
        $reports = [];
        $reports['total'] = [
            'label' => 'All',
            'submission_count' => $submission->getTotalCount($formId, null, $searchString, $startDate, $endDate),
            //issue on that line
            'payment_total' => $submission->paymentTotal($formId, null, $searchString, $startDate, $endDate)
        ];

        foreach ($paymentStatuses as $status => $statusName) {
            $reports[$status] = [
                'label' => $statusName,
                'submission_count' => $submission->getTotalCount($formId, $status,  $searchString, $startDate, $endDate),
                'payment_total' => $submission->paymentTotal($formId, $status,  $searchString, $startDate, $endDate)
            ];
        }
        wp_send_json_success([
            'reports' => $reports,
            'currencySettings' => Form::getCurrencyAndLocale($formId),
            'is_payment_form' => Form::hasPaymentFields($formId)
        ], 200);
    }

    public function getSubmissionPrepared($formId, $submissionId = false)
    {
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId, array('transactions', 'order_items', 'tax_items', 'activities', 'refunds', 'discount'));
        if ($submission->status == 'new') {
            $submissionModel->where('form_id', $submission->form_id)
                ->where('id', $submission->id)
                ->update(['status' => 'read']);
            $submission->status = 'read';
        }

        $currencySetting = GeneralSettings::getGlobalCurrencySettings($formId);
        $currencySetting['currency_sign'] = GeneralSettings::getCurrencySymbol($submission->currency);
        $submission->currencySetting = $currencySetting;

        if ($submission->user_id) {
            $user = get_user_by('ID', $submission->user_id);
            if ($user) {
                $submission->user = [
                    'display_name' => $user->display_name,
                    'email' => $user->user_email,
                    'profile_url' => get_edit_user_link($user->ID)
                ];
            }
        }

        $submission = apply_filters('wppayform/form_entry_recurring_info', $submission);

        $parsedEntry = $submissionModel->getParsedSubmission($submission);

        $submission['widgets'] = apply_filters('wppayform_single_entry_widgets', [], array('submission' => $submission));

        return array(
            'submission' => $submission,
            'entry' => (object) $parsedEntry
        );
    }
    public function getSubmission($formId, $submissionId = false)
    {
        $submission = $this->getSubmissionPrepared($formId, $submissionId);
        wp_send_json_success($submission, 200);
    }

    public function addSubmissionNote($formId, $submissionId)
    {
        $content = esc_html($this->request->note);
        $userId = get_current_user_id();
        $user = get_user_by('ID', $userId);

        $note = array(
            'form_id' => $formId,
            'submission_id' => $submissionId,
            'type' => 'custom_note',
            'content' => $content,
            'created_by' => $user->display_name,
            'created_by_user_id' => $userId
        );

        $note = apply_filters('wppayform/add_note_by_user', $note, $formId, $submissionId);
        do_action('wppayform/before_create_note_by_user', $note);
        SubmissionActivity::createActivity($note);
        do_action('wppayform/after_create_note_by_user', $note);

        return array(
            'message' => __('Note successfully added', 'wp-payment-form'),
            'activities' => SubmissionActivity::getSubmissionActivity($submissionId)
        );
    }

    public function deleteNote($formId, $entryId, $noteId)
    {
        return SubmissionActivity::deleteActivity($formId, $entryId, $noteId);
        do_action('wppayform/after_delete_note_by_user', $entryId, $noteId);
    }

    public function changeEntryStatus($formId, $entryId)
    {
        $newStatus = sanitize_text_field($this->request->status);
        $submissionModel = new Submission();
        $newStatus = $submissionModel->changeEntryStatus($formId, $entryId, $newStatus);
        return array(
            'message' => __('Item has been marked as ', 'wp-payment-form') . $newStatus,
            'status' => $newStatus
        );
    }

    public function getNextPrevSubmission($formId = false, $currentSubmissionId = null)
    {
        $queryType = sanitize_text_field($this->request->type);

        $whereOperator = '<';
        $orderBy = 'DESC';
        // find the next / previous form id
        if ($queryType == 'next') {
            $whereOperator = '>';
            $orderBy = 'ASC';
        }

        $submissionQuery = Submission::orderBy('id', $orderBy)
            ->where('id', $whereOperator, $currentSubmissionId);

        if ($formId) {
            $submissionQuery->where('form_id', $formId);
        }

        $submission = $submissionQuery->first();

        if (!$submission) {
            wp_send_json_error(
                array(
                    'message' => __('Sorry, No Submission found', 'wp-payment-form')
                ),
                423
            );
        }
        $this->getSubmission($formId, $submission->id);
    }

    public function paymentStatus($submissionId)
    {
        $newStatus = sanitize_text_field($this->request->new_payment_status);
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);
        if ($submission->payment_status == $newStatus) {
            wp_send_json_error(
                array(
                    'message' => __('The submission have the same status', 'wp-payment-form')
                ),
                423
            );
        }

        do_action('wppayform/before_payment_status_change_manually', $submission, $newStatus, $submission->payment_status);

        $submissionModel->updateSubmission(
            $submissionId,
            array(
                'payment_status' => $newStatus
            )
        );

        Transaction::where('submission_id', $submissionId)
            ->where('transaction_type', '!=', 'subscription')
            ->update(
                array(
                    'status' => $newStatus,
                    'updated_at' => current_time('mysql')
                )
            );

        $transaction = Transaction::where('submission_id', $submissionId);

        do_action('wppayform/after_payment_status_change_manually', $submissionId, $newStatus, $submission->payment_status);

        do_action('wppayform/after_payment_status_change', $submissionId, $newStatus);

        $activityContent = 'Payment status changed from <b>' . $submission->payment_status . '</b> to <b>' . $newStatus . '</b>';

        if ('paid' == $newStatus) {
            do_action('wppayform/form_payment_success', $submission, $transaction, $submission->form_id, false);
        }

        if ($changeNote = $this->request->get('status_change_note', false)) {
            $note = wp_kses_post($changeNote);
            $activityContent .= '<br />Note: ' . $note;
        }

        $userId = get_current_user_id();
        $user = get_user_by('ID', $userId);
        SubmissionActivity::createActivity(
            array(
                'form_id' => $submission->form_id,
                'submission_id' => $submission->id,
                'type' => 'info',
                'created_by' => $user->display_name,
                'created_by_user_id' => $userId,
                'content' => $activityContent
            )
        );

        return array(
            'message' => __('Payment status successfully changed', 'wp-payment-form')
        );
    }

    public function remove()
    {
        $submissionId = $this->request->get('submission_id', []);
        $formId = $this->request->get('form_id', '');
        do_action('wppayform/before_delete_submission', $submissionId, $formId);
        $submissionModel = new Submission();
        $submissionModel->deleteSubmission($submissionId);
        do_action('wppayform/after_delete_submission', $submissionId, $formId);
        return array(
            'message' => __('Selected submission successfully deleted', 'wp-payment-form')
        );
    }

    public function syncSubscription($formId, $submissionId)
    {
        $submissionModel = new Submission();
        $entry = $submissionModel->getSubmission($submissionId);
        if (empty($entry->payment_method)) {
            return;
        }
        do_action('wppayform/subscription_settings_sync_' . $entry->payment_method, $formId, $submissionId);
    }

    public function syncOfflineSubscription($formId, $submissionId)
    {
        $subscriptions = $this->request->subscriptions;
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);

        if (!$submission) {
            wp_send_json_error(
                array(
                    'message' => __("Can't sync the subscription at this moment. Please try again", 'wp-payment-form'),
                ),
                423
            );
        }

        do_action('wppayform/offline_action_subcr_sync', $submission, $submissionId, $subscriptions, $formId);
    }

    public function changeOfflineSubscriptionStatus($formId, $submissionId)
    {
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);
        $subscription = $this->request->subscription;
        $newStatus = $this->request->newStatus;

        if ('offline' != $submission->payment_method) {
            wp_send_json_error(
                array(
                    'message' => __("Can't cancel the subscription at this moment. Please try again", 'wp-payment-form'),
                ),
                423
            );
        }

        $status = Arr::get($newStatus, 'status');

        $note = Arr::get($newStatus, 'note');

        do_action('wppayform/offline_action_subcr_status_change', $submission, $subscription, $status, $note);
    }

    public function changeOfflineSubscriptionPaymentStatus($formId, $submissionId)
    {
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);
        $subscription_payment = $this->request->subscription_payment;

        $newStatus = $this->request->newStatus;
        $statusTobeUpdated = Arr::get($newStatus, 'status');
        $note = Arr::get($newStatus, 'note');
        $transactionId = Arr::get($subscription_payment, 'id');


        if (Arr::get($subscription_payment, 'status') == $statusTobeUpdated) {
            wp_send_json_error(
                array(
                    'message' => __("Changing status is same as current status", 'wp-payment-form'),
                ),
                423
            );
        }

        do_action('wppayform/offline_action_subcr_payment_status_change', $submission, $transactionId, $statusTobeUpdated, $note);

    }

    public function cancelSubscription($formId, $submissionId)
    {
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);
        $subscription = $this->request->subscription;

        if (empty($submission->payment_method)) {
            wp_send_json_error(
                array(
                    'message' => __("Can't cancel the subscription at this moment. Please try again", 'wp-payment-form'),
                ),
                423
            );
        }

        do_action('wppayform/subscription_settings_cancel_' . $submission->payment_method, $formId, $submission, $subscription);

    }
}
