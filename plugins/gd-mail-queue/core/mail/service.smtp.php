<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_core_service_smtp extends gdmaq_core_service {
    public $host = '';
    public $port = '25';
    public $encryption = '';
    public $auth = true;
    public $username = '';
    public $password = '';

    public function __construct() {
        foreach (array('host', 'port', 'encryption', 'auth', 'username', 'password') as $key) {
            $this->$key = gdmaq_settings()->get($key, 'service_smtp');
        }
    }

    /** @return gdmaq_core_service_smtp */
    public static function instance() {
        static $_gdmaq_sevice_smtp = false;

        if (!$_gdmaq_sevice_smtp) {
            $_gdmaq_sevice_smtp = new gdmaq_core_service_smtp();
        }

        return $_gdmaq_sevice_smtp;
    }

    /** @param PHPMailer\PHPMailer\PHPMailer $phpmailer */
    public function phpmailer_smtp(&$phpmailer) {
        $phpmailer->isSMTP();

        $phpmailer->Host = $this->host;
        $phpmailer->Port = $this->port;
        $phpmailer->SMTPSecure = $this->encryption;

        if ($this->auth) {
            $phpmailer->SMTPAuth = true;

            $phpmailer->Username = $this->username;
            $phpmailer->Password = $this->password;
        }
    }

    public function is_ready() {
        return !empty($this->host);
    }
}

/** @return gdmaq_core_service_smtp */
function gdmaq_phpmailer_service() {
    return gdmaq_core_service_smtp::instance();
}

gdmaq_phpmailer_service();
