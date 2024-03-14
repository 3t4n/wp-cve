<?php
/**
 * Sign-up Sheets Mail Class
 */

namespace FDSUS\Controller;

use FDSUS\Id;
use FDSUS\Model\Settings;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\Signup as SignupModel;
use WP_Error;

class Mail
{

    public $plain_blogname;

    public function __construct()
    {
        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $this->plain_blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    }

    /**
     * Send email when user signs up
     *
     * @param string         $to
     * @param SheetModel|int $sheet
     * @param object|int     $task   both task object and task id are accepted
     * @param object|int     $signup both signup object and signup id are accepted
     * @param string         $type   of email (signup, remove)
     * @param string         $message
     * @param string         $subject
     *
     * @return bool|WP_Error
     */
    public function send($to, $sheet, $task, $signup, $type = 'signup', $message = '', $subject = '')
    {
        // Set objects
        if (!is_object($signup)) {
            $signup = new SignupModel($signup);
        }
        if (!is_object($task)) {
            $task = new TaskModel($task);
        }
        if (empty($sheet)) {
            $sheet = new SheetModel($task->post_parent);
        } elseif (!is_a($sheet, '\FDSUS\Model\Sheet')) {
            $sheet = new SheetModel($sheet);
        }

        $headers = array();

        /**
         * Filter for mail headers
         *
         * @param array       $headers
         * @param SheetModel  $sheet
         * @param TaskModel   $task
         * @param SignupModel $signup
         * @param string      $type
         *
         * @return array
         * @since 2.2
         */
        $headers = apply_filters('fdsus_mail_headers', $headers, $sheet, $task, $signup, $type);

        /**
         * Filter for mail subject
         *
         * @param array       $headers
         * @param SheetModel  $sheet
         * @param TaskModel   $task
         * @param SignupModel $signup
         * @param string      $type
         *
         * @return string
         * @since 2.2
         */
        $subject = apply_filters('fdsus_mail_subject', $subject, $sheet, $task, $signup, $type);

        /**
         * Filter for mail message
         *
         * @param array       $headers
         * @param SheetModel  $sheet
         * @param TaskModel   $task
         * @param SignupModel $signup
         * @param string      $type
         *
         * @return string
         * @since 2.2
         */
        $message = apply_filters('fdsus_mail_message', $message, $sheet, $task, $signup, $type);

        // Set fallback default values
        if (empty($subject) && !empty(Settings::$defaultMailSubjects[$type])) {
            $subject = Settings::$defaultMailSubjects[$type];
        }
        if (empty($message) && !empty(Settings::$defaultMailMessages[$type])) {
            $message = Settings::$defaultMailMessages[$type];
        }

        // Add dynamic content
        $args = array(
            'sheet' => $sheet,
            'task' => $task,
            'signup' => $signup,
            'from' => $this->getConfirmationFrom(),
        );
        $message = $this->addDynamicContent($message, $args);

        // Send
        add_filter('wp_mail_from', array($this, 'getConfirmationFrom'));
        add_filter('wp_mail_from_name', array($this, 'getFromName'));
        $result = wp_mail($to, $subject, $message, $headers);
        remove_filter('wp_mail_from', array($this, 'getConfirmationFrom'));
        remove_filter('wp_mail_from_name', array($this, 'getFromName'));

        if (!$result) {
            global $ts_mail_errors;
            global $phpmailer;
            if (!isset($ts_mail_errors)) $ts_mail_errors = array();
            if (isset($phpmailer)) $ts_mail_errors[] = $phpmailer->ErrorInfo;
            return new WP_Error(
                'fdsus_mail_send',
                esc_html__('Error sending email.', 'fdsus')
                . (Settings::isDetailedErrors() ? '.. ' . implode(' --- ', $ts_mail_errors) : '')
            );
        }

        /**
         * Action after mail send is successful
         *
         * @param string     $type
         * @param SheetModel $sheet
         */
        do_action('fdsus_mail_send_successful', $type, $sheet);

        return $result;
    }

    /**
     * Add dynamic content
     *
     * @param string $message
     * @param array  $args
     *
     * @return string
     */
    public function addDynamicContent($message, $args)
    {
        // Set sign-up date
        $signupDate = null;
        if (!empty($args['sheet']->dlssus_date)) {
            $signupDate = $args['sheet']->dlssus_date;
        }

        /**
         * Filter $signupDate in the mail dynamic content
         *
         * @param string $signupDate
         * @param array  $args
         *
         * @return string
         * @since 2.2
         */
        $signupDate = apply_filters('fdsus_mail_dynamic_content_signup_date', $signupDate, $args);

        // Build initial signup details
        $signupDetails = (!empty($signupDate) ? esc_html__('Date', 'fdsus') . ': ' . date(get_option('date_format'), strtotime($signupDate)) . PHP_EOL : null)
            . esc_html__('Event', 'fdsus') . ': ' . wp_kses_post($args['sheet']->post_title) . PHP_EOL
            . esc_html(Settings::$text['task_title_label']['value'])
            . ': ' . wp_kses_post($args['task']->post_title);

        /**
         * Filter signup_details in the mail dynamic content
         *
         * @param string $signupDetails
         * @param array  $args
         *
         * @return string
         * @since 2.2
         */
        $signupDetails = apply_filters('fdsus_mail_dynamic_content_signup_details', $signupDetails, $args);

        // Replace
        $message = str_replace('{signup_details}', wp_kses_post($signupDetails), $message);
        $message = str_replace('{from_email}', sanitize_email($args['from']), $message);
        $message = str_replace('{site_name}', wp_kses_post($this->plain_blogname), $message);
        $message = str_replace('{site_url}', get_bloginfo('url'), $message);
        $message = str_replace('{signup_firstname}', esc_html($args['signup']->{Id::PREFIX . '_firstname'}), $message);
        $message = str_replace('{signup_lastname}', esc_html($args['signup']->{Id::PREFIX . '_lastname'}), $message);
        $message = str_replace('{signup_email}', sanitize_email($args['signup']->{Id::PREFIX . '_email'}), $message);

        /**
         * Filter the mail dynamic content
         *
         * @param string $message
         * @param array  $args
         *
         * @return string
         * @since 2.2
         */
        $message = apply_filters('fdsus_mail_dynamic_content_message', $message, $args);

        return $message;
    }

    /**
     * Get from email address for confirmation emails
     *
     * @return mixed|string|void
     */
    public function getConfirmationFrom()
    {
        $from = get_option('dls_sus_email_from');
        if (empty($from)) {
            $from = get_bloginfo('admin_email');
        }

        return sanitize_email($from);
    }

    /**
     * Get From Name for emails
     *
     * @return string
     */
    public function getFromName()
    {
        return $this->plain_blogname;
    }

    /**
     * Get From Name for emails
     *
     * @return string
     */
    public function getHtmlContentType()
    {
        return 'text/html';
    }
}
