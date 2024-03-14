<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Controllers/SettingsController.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Controllers;

use FelixTzWPModernPolls\Models\Settings;


class SettingsController
{
    public $settings;

    public function __construct()
    {
        $this->settings = new Settings();
    }

    public function get($column)
    {
        return $this->settings->get($column);
    }

    public function getAll()
    {
        return $this->settings->getAll();
    }

    public function save($post)
    {
        $log_ip = $post['mpp_log_ip'] ?? 0;
        $log_cookie = $post['mpp_log_cookie'] ?? 0;
        $log_user = $post['mpp_log_user'] ?? 0;
        $log_expire = $post['mpp_log_time'] ?? 0;
        $closed_poll = $post['mpp_poll_closed'] ?? 0;

        return $this->settings->save($log_ip, $log_cookie, $log_user, $log_expire, $closed_poll);
    }
}