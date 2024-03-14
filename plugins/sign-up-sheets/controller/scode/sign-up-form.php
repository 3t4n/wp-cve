<?php
/**
 * [sign_up_form] Shortcode Controller
 */

namespace FDSUS\Controller\Scode;

use FDSUS\Id;
use FDSUS\Controller\Base;
use FDSUS\Controller\Mail as Mail;
use FDSUS\Model\Data;
use FDSUS\Model\Settings;
use FDSUS\Model\Signup;
use FDSUS\Model\SignupFormInitialValues;
use FDSUS\Model\States as StatesModel;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Lib\Dls\Notice;
use FDSUS\Lib\Exception;
use WP_Error;

class SignUpForm extends Base
{

    private $data;
    private $mail;

    public function __construct()
    {
        parent::__construct();

        $this->data = new Data();
        $this->mail = new Mail();

        add_shortcode('sign_up_form', array(&$this, 'shortcode'));

        add_action('init', array(&$this, 'maybeProcessSignupForm'), 9);
    }

    /**
     * Enqueue sign-up form CSS and JS files
     */
    public function enqueueScriptsStylesOnSignup()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_style(Id::PREFIX . '-style');
        if (Settings::isEmailValidationEnabled()) {
            wp_enqueue_script(Id::PREFIX . '-mailcheck');
        }
        wp_enqueue_script('dlssus-js');

        do_action('fdsus_enqueue_scripts_styles_on_signup');
    }

    /**
     * Display signup form
     *
     * @param array $atts
     *
     * @return string
     */
    public function shortcode($atts)
    {
        $this->enqueueScriptsStylesOnSignup();

        ob_start();

        /** @var int|string $task_ids comma separated list of task ids */
        extract(
            shortcode_atts(
                array(
                    'task_ids' => 0, // int or array of task ids
                ), $atts
            )
        );

        $task_ids = explode(',', $task_ids);
        /** @var array $task_ids */
        $task_ids = array_map('intval', $task_ids); // convert all to int
        $task_id = current($task_ids);

        if (empty($task_id)) {
            echo '<p>' . esc_html__('Task not found.', 'fdsus') . '</p>';
            return ob_get_clean();
        }

        $task = new TaskModel($task_id);
        if (empty($task) || empty($task->post_parent)) {
            echo '<p>' . esc_html__('No Sign-up Form Found.', 'fdsus') . '</p>';
            return ob_get_clean();
        }

        $sheet = $task->getSheet();
        if (empty($sheet)) {
            echo '<p>' . esc_html__('No Sign-up Sheet Found.', 'fdsus') . '</p>';
            return ob_get_clean();
        }

        $_POST = $this->data->stripslashes_full($_POST);

        $multi_tag = '';
        $date_display = null;
        $signup_titles = array();
        if (isset($_POST['signupbox_multi'])) {
            if (is_array($_POST['signupbox_multi'])) {
                $tasks = $_POST['signupbox_multi'];

                $tasks_str = '';
                foreach ($tasks as $t) {
                    if ($tasks_str != '') {
                        $tasks_str .= ',';
                    }
                    $tasks_str .= $t;
                    $task = new TaskModel($t);
                    $date_display = null;
                    if ($date = $task->getDate()) {
                        $date_display = ' ' . esc_html__('on', 'fdsus')
                            . sprintf(' <em class="dls-sus-task-date">%s</em>',
                                date(get_option('date_format'), strtotime($date))
                            );
                    }
                    $signup_titles[] = $task->post_title . $date_display;
                }
                $multi_tag = '<input type="hidden" id="signupbox_multi_str"  name="signupbox_multi_str"  value="' . esc_attr($tasks_str) . '" />';
            }
        } elseif ( isset( $_POST['signupbox_multi_str'] ) ) {
            $multi_tag = '<input type="hidden" id="signupbox_multi_str"  name="signupbox_multi_str"  value="' . esc_attr($_POST['signupbox_multi_str']) . '" />';
            $st = explode(",", $_POST['signupbox_multi_str']);
            foreach ($st as $s) {
                $task = new TaskModel($s);
                $date_display = null;
                if ($date = $task->getDate()) {
                    $date_display = ' ' . esc_html__('on', 'fdsus')
                        . sprintf(
                            ' <em class="dls-sus-task-date">%s</em>',
                            date(get_option('date_format'), strtotime($date))
                        );
                }
                $signup_titles[] = $task->post_title . $date_display;
            }
        } else {
            if ($date = $task->getDate()) {
                $date_display = ' ' . esc_html__('on', 'fdsus')
                    . sprintf(
                        ' <em class="dls-sus-task-date">%s</em>',
                        date(get_option('date_format'), strtotime($date))
                    );
            }
            $signup_titles[] = $task->post_title . $date_display;
        }

        // Build signup title display string
        $last_element = array_pop($signup_titles);
        $signup_titles_str = $last_element;
        if (count($signup_titles) > 0) {
            $signup_titles_str = implode(', ', $signup_titles);
            $signup_titles_str .= ' and ' . $last_element;
        }

        fdsus_the_signup_form_response();

        if (Notice::isContentHidden()) {
            return ob_get_clean();
        }

        /**
         * Filter for sign-up form shortcode signup object
         *
         * @param SignupModel|WP_Error|null $signup
         * @param SheetModel                $sheet
         * @param TaskModel                 $task
         *
         * @return SignupModel|WP_Error|null
         */
        $signup = apply_filters('fdsus_signup_form_shortcode_signup_object', null, $sheet, $task);
        if (is_wp_error($signup)) {
            Notice::resetDisplayCount();
            Notice::add('info', esc_html($signup->get_error_message()), true);
            echo apply_filters('dlssus_notices', null);
            return ob_get_clean();
        }

        $initial = new SignupFormInitialValues($sheet, $task, $signup, $_POST);
        $states = new StatesModel;

        $submitButtonText = __('Sign me up!', 'fdsus');
        /**
         * Filter for submit button text on sign-up form
         *
         * @param string     $submitButtonText
         * @param SheetModel $sheet
         * @param TaskModel  $task
         *
         * @return string
         */
        $submitButtonText = apply_filters('fdsus_signup_form_submit_button_text', $submitButtonText, $sheet, $task);

        $goBackUrl = fdsus_back_to_sheet_url($task_id);
        /**
         * Filter for go back url on sign-up form
         *
         * @param string     $goBackUrl
         * @param SheetModel $sheet
         * @param TaskModel  $task
         *
         * @return string
         */
        $goBackUrl = apply_filters('fdsus_signup_form_go_back_url', $goBackUrl, $sheet, $task);

        $args = array(
            'sheet'               => $sheet,
            'task_id'             => $task_id,
            'signup_titles_str'   => $signup_titles_str,
            'initial'             => $initial->get(),
            'multi_tag'           => $multi_tag,
            'states'              => $states->get(),
            'submit_button_text'  => $submitButtonText,
            'go_back_url'         => $goBackUrl,
        );
        dlssus_get_template_part('fdsus/sign-up-form', null, true, $args);
        return ob_get_clean();
    }

    /**
     * Process signup form if it needs to be
     *
     * @return bool
     */
    public function maybeProcessSignupForm()
    {
        $taskId = isset($_POST['dlssus_submitted']) ? absint($_POST['dlssus_submitted']) : 0;
        if (empty($taskId) || wp_doing_ajax()) {
            return false;
        }
        if (!isset($_POST['signup_nonce'])
            || !wp_verify_nonce($_POST['signup_nonce'], 'fdsus_signup_submit')
        ) {
            Notice::add('error', esc_html__('Sign-up nonce not valid', 'fdsus'), false, Id::PREFIX . '-signup-nonce-invalid');
            return false;
        }

        // Set objects/data and validate
        $objects = array(
            'signup' => null,
            'task'   => null,
            'sheet'  => null,
        );
        /**
         * Filter to set the sheet, task, signup objects prior to signup form processing
         *
         * @param array $objects
         * @param int   $taskId
         *
         * @return array|WP_Error
         *
         * @api
         * @since 2.2.11
         */
        $objects = apply_filters('fdsus_signup_form_objects', $objects, $taskId);
        if (is_wp_error($objects)) {
            return false;
        }

        if (empty($objects['task'])) {
            $objects['task'] = new TaskModel($taskId);
        }

        if (!$objects['task']->isValid()) {
            Notice::add(
                'error', esc_html__('Hmm... we could not find the task for this sign-up.', 'fdsus'),
                true, 'fdsus-task-invalid'
            );
            return false;
        }

        $objects['sheet'] = $objects['task']->getSheet();

        if (!$objects['sheet']->isValid()) {
            Notice::add(
                'error', esc_html__('Hmm... we could not find the sheet for this sign-up.', 'fdsus'),
                true, 'fdsus-sheet-invalid'
            );
            return false;
        }

        if ($objects['task']->isExpired() || $objects['sheet']->isExpired()) {
            Notice::add(
                'error', esc_html__('This sign-up can no longer be edited.', 'fdsus'),
                true, 'fdsus-task-sheet-expired'
            );
            return false;
        }

        if (!$objects['sheet']->dlssus_is_active) {
            Notice::add(
                'error', esc_html__('Sign-ups are no longer being accepted for this sheet.', 'fdsus'),
                true, 'fdsus-signup-nonce-invalid'
            );
            return false;
        }

        if (!$objects['task']->dlssus_is_active) {
            Notice::add(
                'error', esc_html__('Sign-ups are no longer being accepted for this task.', 'fdsus'),
                true, 'fdsus-signup-nonce-invalid'
            );
            return false;
        }

        // Form error handling
        if (is_array($missingFieldNames = SignupModel::validateRequiredFields($_POST, $objects['sheet']))) {
            Notice::add(
                'warn', sprintf(
                    /* translators: %s is replaced with a comma separated list of all missing required fields */
                    esc_html__('Please complete the following required fields: %s', 'fdsus'),
                    implode(', ', $missingFieldNames)
                ), false, 'fdsus-missing-fields'
            );
            return false;
        }

        if (Settings::isEmailValidationEnabled() && (!filter_var($_POST['signup_email'], FILTER_VALIDATE_EMAIL))) {
            Notice::add(
                'warn', esc_html__('Please check that your email address is properly formatted', 'fdsus'),
                false, 'fdsus-invalid-email'
            );
            return false;
        }

        if (Settings::isEmailValidationEnabled()
            && !checkdnsrr(
                substr($_POST['signup_email'], strpos($_POST['signup_email'], '@') + 1), 'MX'
            )
        ) {
            Notice::add(
                'warn', esc_html__('Whoops, it looks like your email domain may not be valid.', 'fdsus'),
                false, 'fdsus-email-checkdnsrr'
            );
            return false;
        }

        if ($this->data->is_honeypot_enabled() && !empty($_POST['website'])) {
            Notice::add(
                'warn', esc_html__('Sorry, your submission has been blocked.', 'fdsus'),
                false, 'fdsus-signup-form-honeypot'
            );
            return false;
        }

        /**
         * Filter to set the sheet, task, signup objects prior to signup form processing
         *
         * @param bool  $override
         * @param array $objects
         *
         * @return bool
         *
         * @api
         * @since 2.2.11
         */
        $override = apply_filters('fdsus_override_process_signup_form', false, $objects);
        if ($override) {
            return true;
        }

        // Process
        $this->_processSignupForm($objects['task'], $objects['sheet']);
        return true;
    }

    /**
     * Process signup form
     *
     * @param TaskModel  $task
     * @param SheetModel $sheet
     *
     * @return bool
     */
    protected function _processSignupForm($task, $sheet)
    {
        // Pre-process actions
        do_action('fdsus_signup_form_pre_process', $task);
        do_action("fdsus_signup_form_pre_process_{$sheet->ID}", $task);

        $err = array();
        $successTaskIds = array();
        $successSignupIds = array();
        $signupId = null;

        // Add Signup
        if (!$err) {
            try {

                if (isset($_POST['signupbox_multi_str'])) {
                    $taskIds = explode(',', $_POST['signupbox_multi_str']);
                } else {
                    $taskIds = array($_GET['task_id']);
                }
                $taskIndex = 0;
                foreach ($taskIds as $taskId) {
                    $errorMsg = '';
                    /**
                     * Filter the error check that runs when processing the signup form before the new signup is added
                     * to the DB
                     *
                     * @param string|WP_Error $errorMsg
                     * @param int             $taskId
                     * @param int             $taskIndex
                     *
                     * @return string|WP_Error
                     *
                     * @api
                     * @since 2.2
                     */
                    $errorMsg = apply_filters('fdsus_error_before_add_signup', $errorMsg, $taskId, $taskIndex);
                    if (is_wp_error($errorMsg)) {
                        throw new Exception($errorMsg->get_error_message());
                    }

                    $signup = new SignupModel();
                    $signupId = $signup->add($_POST, $taskId);

                    $task = new TaskModel($taskId);
                    $successTaskIds[] = (int)$taskId;
                    $successSignupIds[] = (int)$signupId;

                    $sendSignupConfirmationEmail = !empty($_POST['signup_email']);
                    /**
                     * Filter the flag to send the confirmation email on sign-up
                     *
                     * @param bool        $sendSignupConfirmationEmail
                     * @param SheetModel  $sheet
                     * @param TaskModel   $task
                     * @param SignupModel $signup
                     *
                     * @return bool
                     *
                     * @since 2.2.7
                     */
                    $sendSignupConfirmationEmail = apply_filters(
                        'fdsus_send_signup_confirmation_email',
                        $sendSignupConfirmationEmail, $sheet, $task, $signup
                    );

                    if ($sendSignupConfirmationEmail) {
                        $this->mail->send($_POST['signup_email'], $sheet, $task, $signupId, 'signup');
                    }
                }
            } catch (Exception $e) {
                $err[] = $e->getMessage();
            }
        }

        // Set error messages (success are set on sheet object after redirect)
        if (!empty($err)) {
            foreach ($err as $e) {
                Notice::add('warn', $e, false, Id::PREFIX . '-signup-form-err');
            }
        }

        // Post-process actions
        do_action("fdsus_signup_form_post_process", $task, $signupId);
        do_action("fdsus_signup_form_post_process_{$sheet->ID}", $task, $signupId);

        // If successful, redirect to sheet page
        if (empty($err) && !empty($successTaskIds)) {
            $currentUrl = remove_query_arg(array('task_id', 'action', 'signups', 'remove_spot_task_id', '_susnonce'));
            $currentUrl = add_query_arg(
                array('action' => 'signup', 'status' => 'success', 'tasks' => implode(',', $successTaskIds), 'signups' => implode(',', $successSignupIds)),
                wp_nonce_url($currentUrl, 'signup-success-' . implode(',', $successSignupIds) .'-tasks-' . implode(',', $successTaskIds), '_susnonce')
            );
            wp_redirect(urldecode($currentUrl));
            exit;
        }
    }

}
