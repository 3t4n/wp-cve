<?php

if (!defined('ABSPATH')) exit;

class gdmaq_core_info {
    public $name = 'GD Mail Queue';
    public $code = 'gd-mail-queue';

    public $version = '4.2.1';
    public $build = 141;
    public $edition = 'free';
    public $status = 'stable';
    public $updated = '2023.08.09';
    public $url = 'https://plugins.dev4press.com/gd-mail-queue/';
    public $author_name = 'Milan Petrovic';
    public $author_url = 'https://www.dev4press.com/';
    public $released = '2019.05.02';

    public $php = '7.3';
    public $mysql = '5.1';
    public $wordpress = '5.5';

    public $install = false;
    public $update = false;
    public $previous = 0;

    function __construct() { }

    public function to_array() {
        return (array)$this;
    }
}
