<?php

if (!defined('ABSPATH')) exit;

class gdmaq_admin_ajax {
    public function __construct() {
        add_action('wp_ajax_gdmaq_tools_emailtest', array($this, 'emailtest'));
        add_action('wp_ajax_gdmaq_tools_queuetest', array($this, 'queuetest'));

        add_action('wp_ajax_gdmaq_log_entry_preview', array($this, 'log_entry_preview'));
        add_action('wp_ajax_gdmaq_log_entry_html', array($this, 'log_entry_html'));
    }

    private function _check_nonce($base, $id) {
        $action = $base.'-'.$id;

        $nonce = wp_verify_nonce($_REQUEST['_ajax_nonce'], $action);

        if ($nonce === false) {
            wp_die(-1);
        }
    }

    public function emailtest() {
        check_ajax_referer('gd-mail-queue-tools-options');

        $timestamp = date('Y-m-d H:i:s');

        $post = $_POST['gdmaqtools']['mailtest'];
        $email = sanitize_email($post['email']);
        $subject = sanitize_text_field($post['subject']);

        $message = __("This is the message sent from your website to test email delivery.", "gd-mail-queue").D4P_EOL;
        $message.= __("This message was sent at:", "gd-mail-queue")." ".$timestamp;

        gdmaq_mailer()->set_current_type('gdmaq_email_test');
        gdmaq_mailer()->pause_intercept();

        wp_mail($email, $subject, $message);

        global $phpmailer;

        $props = array('CharSet', 'ContentType', 'Encoding', 'From', 'FromName', 'Sender', 'Mailer', 'Host', 'Port', 'SMTPSecure', 'SMTPAuth', 'Timeout');
        $version = gdmaq_mailer()->phpmailer_version === false ? $phpmailer->Version : gdmaq_mailer()->phpmailer_version;

        $test_settings = '<hr/><h4>'.__("Setup", "gd-mail-queue").'</h4>';
        $test_settings.= __("PHPMailer Version", "gd-mail-queue").': <strong>'.$version.'</strong><br/>';

        foreach ($props as $property) {
            $test_settings.= $property.': <strong>'.$phpmailer->$property.'</strong><br/>';
        }

        $test_class = 'gdmaq-result-ok';
        $test_message = __("Everything looks OK, email is successfully sent.", "gd-mail-queue");

        if ($phpmailer->ErrorInfo != '') {
            $test_class = 'gdmaq-result-error';
            $test_message = __("An error occurred", "gd-mail-queue").':<br/><strong>'.$phpmailer->ErrorInfo.'</strong>';
        }

        $message = '<div class="'.$test_class.'"><h4>'.
            __("Results", "gd-mail-queue").'</h4>'.$test_message.$test_settings.'<hr/><h4>'.
            __("MIME Message", "gd-mail-queue").'</h4><pre>'.esc_html($phpmailer->getSentMIMEMessage()).
            '</pre></div>';

        die($message);
    }

    public function queuetest() {
        check_ajax_referer('gd-mail-queue-tools-options');

        $timestamp = date('Y-m-d H:i:s');

        $post = $_POST['gdmaqtools']['queuetest'];
        $email = sanitize_email($post['email']);
        $subject = sanitize_text_field($post['subject']);

        $message = __("This is the message sent from your website through email queue implemented by GD Mail Queue plugin.", "gd-mail-queue").D4P_EOL;
        $message.= __("This message added to queue at:", "gd-mail-queue")." ".$timestamp;

        $args = array(
            'to' => $email,
            'subject' => $subject,
            'plain' => $message,
            'html' => gdmaq_htmlfy()->htmlfy_content($message, $subject),
            'type' => 'gdmaq_queue_test',
            'extras' => array('CharSet' => 'UTF-8')
        );

        if (gdmaq_mailer()->from) {
            $args['extras']['From'] = gdmaq_mailer()->from_email;
            $args['extras']['FromName'] = gdmaq_mailer()->from_name;
        } else {
            $from = gdmaq_default_from();
            $args['extras']['From'] = $from['email'];
            $args['extras']['FromName'] = $from['name'];
        }

        if (gdmaq_mailer()->reply) {
            $args['extras']['ReplyTo'] = array(
                gdmaq_mailer()->reply_email => array(gdmaq_mailer()->reply_email, gdmaq_mailer()->reply_name)
            );
        }

        gdmaq_mail_to_queue($args);

        $test_message = __("Everything looks OK, email is added to the queue.", "gd-mail-queue");

        $message = '<div class="gdmaq-result-ok"><h4>'.
            __("Results", "gd-mail-queue").'</h4>'.$test_message.'<hr/><h4>'.
            '</pre></div>';

        die($message);
    }

    public function log_entry_preview() {
        $id = absint($_REQUEST['id']);

        $this->_check_nonce('gdrts-log-view', $id);

        $email = gdmaq_db()->email_log_get_entry($id);

        ob_start();

        include(GDMAQ_PATH.'forms/log/entry.php');

        $render = ob_get_contents();
        ob_end_clean();

        die($render);
    }

    public function log_entry_html() {
        $id = absint($_GET['id']);

        $this->_check_nonce('gdrts-log-html', $id);

        $html = gdmaq_db()->email_log_get_html($id);

        if ($html === false) {
            die(__("HTML for this email not found.", "gd-mail-queue"));
        } else {
	        $html = wp_kses($html, gdmaq_allowed_tags_iframe_display());

	        if (gdmaq_settings()->get('preview_html_disable_links', 'log')) {
                $html = preg_replace('/(<a.+href=["|\'])(.+?)(["|\'])/i', '$1#$3', $html);
            }

            die($html);
        }
    }
}

new gdmaq_admin_ajax();
