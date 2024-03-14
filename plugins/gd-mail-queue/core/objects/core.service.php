<?php

if (!defined('ABSPATH')) { exit; }

abstract class gdmaq_core_service {
    protected $name = '';

    public function __construct() { }

    public function run() {
        if ($this->is_ready()) {
            $priority = apply_filters('gdmaq_mailer_phpmailer_core_smtp_priority', 1000000);
            add_action('phpmailer_init', array($this, 'phpmailer_smtp'), $priority);

            $priority = apply_filters('gdmaq_mailer_phpmailer_engine_smtp_priority', 1000000);
            add_action('gdmaq_phpmailer_prepare_engine', array($this, 'phpmailer_smtp'), $priority);
        }
    }

    abstract public function phpmailer_smtp(&$phpmailer);
    abstract public function is_ready();
}
