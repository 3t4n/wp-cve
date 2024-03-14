<?php
/**
 * WP SendGrid Mailer Plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

namespace WPMailPlus;

class Settings extends BaseController
{
    public static $_view = 'views/Settings.php';

    public static function process()
    {
        $data = array();
        new self();
        $data['email_service'] = get_option('_wp_mailplus_enabled_service');
        $data['data'] = get_option('_wp_mailplus_service_info');
        $data['from_info'] = get_option('_wp_mailplus_from_info');
        self::output(self::$_view, $data);
    }

}