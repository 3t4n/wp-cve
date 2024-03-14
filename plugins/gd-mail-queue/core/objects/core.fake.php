<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_fake_phpmailer {
    public function __construct() { }

    public function __get($name) {
        return false;
    }

    public function __set($name, $value) {
        return false;
    }

    public function __call($name, $arguments) {
        return false;
    }

    public function send() {
        return true;
    }
}
