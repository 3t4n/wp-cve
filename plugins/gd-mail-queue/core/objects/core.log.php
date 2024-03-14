<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_core_log {
    public $active = false;
    public $mail = true;
    public $mail_if_not_queue = false;
    public $queue = true;
    public $store_smtp_password = false;

    private $_last_log_id;

    public function __construct() {
        foreach (array('active', 'mail', 'mail_if_not_queue', 'queue', 'store_smtp_password') as $key) {
            $this->$key = gdmaq_settings()->get($key, 'log');
        }

        add_action('gdmaq_plugin_init', array($this, 'init'), 20);
    }

    public function is_mail_active() {
        return $this->active && $this->mail;
    }

    public function is_queue_active() {
        return $this->active && $this->queue;
    }

    /** @return gdmaq_core_log */
    public static function instance() {
        static $_gdmaq_log = false;

        if (!$_gdmaq_log) {
            $_gdmaq_log = new gdmaq_core_log();
        }

        return $_gdmaq_log;
    }

    public function init() {
        if ($this->is_mail_active()) {
            add_action('gdmaq_mailer_phpmailer_to_log', array($this, 'mail_log'), 10, 2);
        }

        if ($this->is_queue_active()) {
            add_action('qdmaq_queue_phpmailer_email_send', array($this, 'queue_phpmailer_log'), 10, 3);
        }
    }

    /** @param PHPMailer\PHPMailer\PHPMailer $phpmailer
      * @param string $status */
    public function mail_log($phpmailer, $status) {
        $this->_last_log_id = 0;

        remove_action('wp_mail_failed', array($this, 'mail_failed'), 10);

        $log = $this->is_mail_active();

        if ($status == 'queue' && $this->mail_if_not_queue) {
            $log = false;
        }

        if ($log) {
            $copy = gdmaq_mailer()->copy();

            if ($copy === false) {
                $copy = new gdmaq_mirror_phpmailer($phpmailer, gdmaq_mailer()->get_current_type());
            }

            $_status = $status == 'mail' ? 'ok' : $status;

            $this->_add_to_log($copy, 'mail', 'phpmailer', $_status);

            if ($status == 'mail') {
                add_action('wp_mail_failed', array($this, 'mail_failed'), 10);
            }
        }
    }

    /** @param string $status
      * @param stdClass $item
      * @param PHPMailer\PHPMailer\PHPMailer $phpmailer */
    public function queue_phpmailer_log($status, $item, $phpmailer) {
        $this->_last_log_id = 0;

        $copy = new gdmaq_mirror_phpmailer($phpmailer, $item->type);

        $_status = $status['result'] ? 'ok' : 'fail';
        $_message = $status['message'];

        $this->_add_to_log($copy, 'queue', 'phpmailer', $_status, $_message);
    }

    /** @param WP_Error $error */
    public function mail_failed($error) {
        if ($this->_last_log_id > 0) {
            gdmaq_db()->email_log_update_status($this->_last_log_id, 'fail', $error->get_error_message());
        }
    }

    /** @param gdmaq_mirror_phpmailer $copy
      * @param string $operation
      * @param string $engine
      * @param string $status
      * @param string $message */
    private function _add_to_log($copy, $operation, $engine, $status, $message = '') {
        $emails = $copy->get_emails_by_type();
        $ids = array();

        foreach ($emails as $type => $list) {
            foreach ($list as $e) {
                $item = gdmaq_normalize_email($e);
                $item['rel'] = $type;
                $item['id'] = gdmaq_db()->email_get_id($item['email'], $item['name']);

                $ids[] = $item;
            }
        }

        $_extras = $copy->extras;
        $_mailer = $copy->mailer;

        foreach (array('From', 'FromName') as $key) {
            if (isset($_extras[$key])) {
                unset($_extras[$key]);
            }
        }

        if (!$this->store_smtp_password && isset($_mailer['Password'])) {
            $_mailer['Password'] = '*';
        }

        $this->_last_log_id = gdmaq_db()->email_log_add_entry(array(
            'operation' => $operation,
            'engine' => $engine,
            'status' => $status,
            'type' => $copy->type,
            'subject' => $copy->subject,
            'plain' => $copy->is_html() ? $copy->alt_body : $copy->body,
            'html' => $copy->is_html() ? $copy->body : '',
            'headers' => json_encode($copy->headers),
            'attachments' => json_encode($copy->attachments),
            'extras' => json_encode($_extras),
            'mailer' => json_encode($_mailer),
            'message' => $message
        ));

        foreach ($ids as $mail) {
            gdmaq_db()->email_log_add_relation($this->_last_log_id, $mail['id'], $mail['rel']);
        }
    }
}

/** @return gdmaq_core_log */
function gdmaq_logger() {
    return gdmaq_core_log::instance();
}
