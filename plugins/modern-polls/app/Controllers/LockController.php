<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Controllers/LockController.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Controllers;

use FelixTzWPModernPolls\Models\Locklist;


class LockController
{
    public $locklist;
    public $settingsController;

    public function __construct()
    {
        $this->locklist = new Locklist();
        $this->settingsController = new SettingsController();
    }

    public function hasVoted($id)
    {
        $settings = $this->settingsController->getAll();
        $settings = $settings[0];

        $ip = $settings->log_ip;
        $cookie = $settings->log_cookie;
        $user = $settings->log_user;

        if ($ip == 0 && $cookie == 0 && $user == 0) return false;

        if ($cookie && $this->checkCookie($id)) {
            return true;
        }

        if ($ip && $this->checkIP($id)) {
            return true;
        }

        if ($user && $this->checkUser($id)) {
            return true;
        }

        return false;
     }

    public function add($id, $answer, $ip, $host, $timestamp, $user, $userid)
    {
        return $this->locklist->add($id, $answer, $ip, $host, $timestamp, $user, $userid);
    }


    public function checkCookie($id)
    {
        if (!empty($_COOKIE['mpp_' . $id])) {
            return true;
        }
        return false;
    }

    public function checkIP($id)
    {
        return $this->locklist->checkIP($id);
    }

    public function checkUser($id)
    {
        global $user_ID;

        // Check IP If User Is Guest
        if (!is_user_logged_in()) {
            return false;
        }


        $userID = (int)$user_ID;

        return $this->locklist->checkUser($id, $userID);
    }

}