<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_core_mailer {
    public $phpmailer_version = false;
    public $phpmailer_wp = 'legacy';

    public $intercept = false;
    public $htmlfy = false;
    public $q = true;
    public $queue = 'ccbcc';
    public $engine = 'phpmailer';
    public $from = false;
    public $from_email = '';
    public $from_name = '';
    public $reply = false;
    public $reply_email = '';
    public $reply_name = '';

    public $plain_text_check = 'tags';
    public $fix_content_type = false;

    private $_current_type = 'mail';
    private $_current_copy = false;
    private $_current_email = false;
    private $_detect_type;

    private $_prevent_intercept = false;

    public function __construct() {
        foreach (array('intercept', 'htmlfy', 'q', 'queue', 'engine', 'from', 'from_email', 'from_name', 'reply', 'reply_email', 'reply_name', 'plain_text_check', 'fix_content_type') as $key) {
            $this->$key = gdmaq_settings()->get($key);
        }

        add_action('gdmaq_plugin_init', array($this, 'init'), 25);
    }

    /** @return gdmaq_core_mailer */
    public static function instance() {
        static $_gdmaq_mailer = false;

        if (!$_gdmaq_mailer) {
            $_gdmaq_mailer = new gdmaq_core_mailer();
        }

        return $_gdmaq_mailer;
    }

    public function init() {
        do_action_ref_array('gdmaq_mailer_init', array(&$this));

        do_action('gdmaq_load_engine_'.$this->engine);

        $this->_detect_type = new gdmaq_core_type_detect();

        if ($this->from) {
            $priority = apply_filters('gdmaq_mailer_phpmailer_change_from_priority', 100000);

            add_filter('wp_mail_from', array($this, 'from_email'), $priority);
            add_filter('wp_mail_from_name', array($this, 'from_name'), $priority);
        }

        if ($this->reply) {
            $priority = apply_filters('gdmaq_mailer_phpmailer_change_reply_to_priority', 100000);

            add_action('phpmailer_init', array($this, 'reply_to'), $priority);
        }

        if ($this->intercept) {
            $priority = apply_filters('gdmaq_mailer_phpmailer_intercept_priority', 100000000);

            add_action('phpmailer_init', array($this, 'phpmailer'), $priority);

            if ($this->engine == 'phpmailer') {
                add_action('wp_mail_failed', array($this, 'mail_failed'));
            }
        }
    }

    /** @param PHPMailer\PHPMailer\PHPMailer $phpmailer */
    public function phpmailer(&$phpmailer) {
        if ($this->htmlfy) {
            if ($this->_is_email_plain_text($phpmailer)) {
                do_action_ref_array('gdmaq_mailer_phpmailer_htmlfy', array(&$phpmailer));
            } else {
                if ($this->fix_content_type) {
                    $phpmailer->isHTML();
                }
            }
        }

        $_add_to_queue = false;
        $_fake_mailer = false;

        $this->_current_copy = new gdmaq_mirror_phpmailer($phpmailer, $this->get_current_type());
        $this->_current_email = new gdmaq_core_email('mirror', $this->_current_copy);

        if ($this->allow_intecept()) {
            if ($this->_should_we_add_to_queue()) {
                $this->add_to_queue($this->copy());

                $_add_to_queue = true;
                $_fake_mailer = true;
            }
        }

        gdmaq_settings()->update_statistics('wp_mail_sent', 1);
        gdmaq_settings()->save('statistics');

        if ($this->engine == 'phpmailer') {
            do_action('gdmaq_mailer_phpmailer_to_log', $phpmailer, $_add_to_queue ? 'queue' : 'mail');
        } else {
            $_fake_mailer = true;
        }

        if (!$_add_to_queue) {
            if ($this->is_paused()) {
                $_fake_mailer = true;
            }
        }

        if ($_fake_mailer) {
            $phpmailer = new gdmaq_fake_phpmailer();
        } else {
            do_action_ref_array('gdmaq_phpmailer_ready_to_send', array(&$phpmailer));
        }
    }

    public function mail_failed($error) {
        gdmaq_settings()->update_statistics('wp_mail_failed', 1);
        gdmaq_settings()->save('statistics');
    }

    /** @return gdmaq_mirror_phpmailer|bool */
    public function copy() {
        return $this->_current_copy;
    }

    /** @return gdmaq_core_email|bool */
    public function email() {
        return $this->_current_email;
    }

    /** @return gdmaq_core_type_detect */
    public function detection() {
        return $this->_detect_type;
    }

    public function get_current_type() {
        return $this->_current_type;
    }

    public function set_current_type($type) {
        $this->_current_type = $type;
    }

    public function reset_current_type() {
        $this->_current_type = 'mail';
    }

    public function allow_intecept() {
        return !$this->_prevent_intercept;
    }

    public function is_paused() {
        return apply_filters('gdmaq_email_paused', false);
    }

    /** @param $copy gdmaq_mirror_phpmailer */
    public function add_to_queue($copy) {
        $copy->add_to_queue();

        gdmaq_settings()->update_statistics('intercepted_mails', 1);
        gdmaq_settings()->update_statistics_for_type($this->get_current_type(), 'intercepted_mails', 1);

        gdmaq_settings()->save('statistics');

        $this->reset_current_type();
    }

    public function from_email($from_email) {
        if (!empty($this->from_email)) {
            $from_email = $this->from_email;
        }

        return $from_email;
    }

    public function from_name($from_name) {
        if (!empty($this->from_email)) {
            $from_name = $this->from_name;
        }

        return $from_name;
    }

    /** @param PHPMailer\PHPMailer\PHPMailer $phpmailer */
    public function reply_to(&$phpmailer) {
        if (!empty($this->reply_email)) {
            $phpmailer->clearReplyTos();
            $phpmailer->addReplyTo($this->reply_email, $this->reply_name);
        }
    }

    public function pause_intercept() {
        $this->_prevent_intercept = true;
    }

    public function resume_intercept() {
        $this->_prevent_intercept = false;
    }

    /** @param PHPMailer\PHPMailer\PHPMailer $phpmailer */
    private function _is_email_plain_text($phpmailer) {
        $is_plain_text = false;

        if ($this->plain_text_check == 'type') {
            $is_plain_text = $phpmailer->ContentType == 'text/plain';
        } else if ($this->plain_text_check == 'tags') {
            if (!($phpmailer->ContentType == 'text/html')) {
                $tags = apply_filters('gdmaq_mailer_email_plain_text_check_tags', array("<html", "<body", "<head", "</head>", "</html>", "</body>"));

                foreach ($tags as $tag) {
                    if (strpos($phpmailer->Body, $tag) === false) {
                        $is_plain_text = true;
                        break;
                    }
                }
            }
        }

        return apply_filters('gdmaq_mailer_is_email_plain_text', $is_plain_text, $phpmailer);
    }

    private function _should_we_add_to_queue() {
        $add = $this->q && $this->copy()->is_queue_eligible($this->queue);

        return apply_filters('gdmaq_mailer_add_to_queue', $add, $this->copy(), $this->get_current_type());
    }

    public function get_from() {
        if ($this->from && !empty($this->from_email)) {
            return array(
                'email' => $this->from_email,
                'name' => $this->from_name
            );
        }

        return gdmaq_default_from();
    }

    public function get_reply_to() {
        if ($this->reply && !empty($this->reply_email)) {
            return array(
                'email' => $this->reply_email,
                'name' => $this->reply_name
            );
        }

        return gdmaq_default_from();
    }
}

/** @return gdmaq_core_mailer */
function gdmaq_mailer() {
    return gdmaq_core_mailer::instance();
}
