<?php
/**
 * @file
 * ExtraWatch - Real-time visitor dashboard and stats
 * @package ExtraWatch
 * @version 4.0
 * @revision 53
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2021 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

class ExtraWatchPrerequisites {

    public function prerequisiteCheck() {
        $message = "";
        if (!function_exists("curl_init")) {
            $message .= "Error: curl_init function not defined, PHP CURL extension is not installed.
            Please contact your system administrator to enable it";
            return $message;
        }
    }

}