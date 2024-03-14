<?php

if (!defined('ABSPATH')) { exit; }

abstract class gdmaq_core_engine {
    protected $name = '';
    protected $processor;

    protected $_last_sent_status = array();

    public function __construct() {
        $this->processor = 'GD Mail Queue '.gdmaq_settings()->plugin_version();

        $this->init_engine();
    }

    public function blank_result($operation = 'queue') {
        return array(
            'result' => true,
            'engine' => gdmaq_mailer()->engine,
            'operation' => $operation,
            'attachments' => 0,
            'code' => '',
            'message' => ''
        );
    }

    public function prepare() {
        $this->prepare_engine();
    }

    public function last_status() {
        return $this->_last_sent_status;
    }

    /** @param gdmaq_core_email $email */
    abstract public function queue_send($email);

    abstract protected function init_engine();
    abstract protected function prepare_engine();
}
