<?php

namespace MailOptin\CommentOptinConnect;

class Connect
{
    public function __construct()
    {
        Init::get_instance();
    }

    /**
     * @return Connect
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}
