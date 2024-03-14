<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_engine_phpmailer extends gdmaq_core_engine {
    protected $name = 'phpmailer';

    public $mode = 'mail';

    private $services = array();

    /** @var PHPMailer\PHPMailer\PHPMailer */
    private $phpmailer;

    /** @return gdmaq_engine_phpmailer|gdmaq_core_engine */
    public static function instance() {
        static $_gdmaq_engine_phpmailer = false;

        if (!$_gdmaq_engine_phpmailer) {
            $_gdmaq_engine_phpmailer = new gdmaq_engine_phpmailer();
        }

        return $_gdmaq_engine_phpmailer;
    }

    protected function init_engine() {
        add_action('gdmaq_load_service_smtp', array($this, 'service_load_smtp'));
        $this->register_services('smtp', __("Custom SMTP Server", "gd-mail-queue"));

        do_action('gdmaq_phpmailer_register_services');

        foreach (array('mode') as $key) {
            $this->$key = gdmaq_settings()->get($key, 'engine_phpmailer');
        }

        if (isset($this->services[$this->mode])) {
            do_action('gdmaq_load_service_'.$this->mode);

            gdmaq_phpmailer_service()->run();
        }
    }

    protected function prepare_engine() {
        $this->phpmailer = new PHPMailer\PHPMailer\PHPMailer(true);
        $this->phpmailer->isMail();

        do_action_ref_array('gdmaq_phpmailer_prepare_engine', array(&$this->phpmailer));
    }

    private function reset_phpmailer() {
        $this->phpmailer->clearAllRecipients();
        $this->phpmailer->clearAttachments();
        $this->phpmailer->clearCustomHeaders();
        $this->phpmailer->clearReplyTos();
    }

    /** @param gdmaq_core_email $email */
    public function queue_send($email) {
        $this->reset_phpmailer();

        $status = $this->blank_result();

		try {
	        $this->phpmailer = $email->build_phpmailer($this->phpmailer, true);

            do_action_ref_array('gdmaq_queue_phpmailer_init', array(&$this->phpmailer), $email);

            $send = $this->phpmailer->send();

            if ($send === false) {
                $status['result'] = false;
                $status['message'] = $this->phpmailer->ErrorInfo;
            }
		} catch (PHPMailer\PHPMailer\Exception | Exception $e) {
            $status['result'] = false;
            $status['code'] = $e->getCode();
            $status['message'] = $e->getMessage();
        }

        $this->_last_sent_status = $status;

        do_action('qdmaq_queue_phpmailer_email_send', $status, $email, $this->phpmailer);

        return $status;
    }

    public function register_services($name, $label) {
        $this->services[$name] = $label;
    }

    public function get_service_label($service) {
        return isset($this->services[$service]) ? $this->services[$service] : __("PHP Mail Function", "gd-mail-queue");
    }

    public function service_load_smtp() {
        if (!function_exists('gdmaq_phpmailer_service')) {
            require_once(GDMAQ_PATH.'core/mail/service.smtp.php');
        }
    }
}

/** @return gdmaq_engine_phpmailer|gdmaq_core_engine */
function gdmaq_engine_sender() {
    return gdmaq_engine_phpmailer::instance();
}

gdmaq_engine_sender();
