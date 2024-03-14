<?php

class WpSaioHelper {

    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function sanitize_array($var) {
        if (is_array($var)) {
            return array_map('self::sanitize_array', $var);
        } else {
            return is_scalar($var) ? sanitize_text_field($var) : $var;
        }
    }
}