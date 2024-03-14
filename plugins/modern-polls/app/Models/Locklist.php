<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Models/Locklist.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Models;

use FelixTzWPModernPolls\Helpers\AppHelper;

class Locklist extends Model
{
    public function create()
    {
        $qry = "CREATE TABLE " . $this->db->mp_locklist . " (" .
            "id int(10) NOT NULL auto_increment," .
            "mp_poll_id int(10) NOT NULL default '0'," .
            "mp_pollinfo_id int(10) NOT NULL default '0'," .
            "ip varchar(100) NOT NULL default ''," .
            "host VARCHAR(200) NOT NULL default ''," .
            "timestamp int(10) NOT NULL default '0'," .
            "user tinytext NOT NULL," .
            "userid int(10) NOT NULL default '0'," .
            "PRIMARY KEY  (id)," .
            "KEY ip (ip)," .
            "KEY mp_poll_id (mp_poll_id)," .
            "KEY ip_mp_poll_id (ip, mp_poll_id)" .
            ") $this->charsetCollate;";
        dbDelta($qry);
    }

    public function add($id, $answer, $ip, $host, $timestamp, $user, $userid)
    {
        $this->db->insert(
            $this->db->mp_locklist,
            [
                'mp_poll_id' => $id,
                'mp_pollinfo_id' => $answer,
                'ip' => $ip,
                'host' => $host,
                'timestamp' => $timestamp,
                'user' => $user,
                'userid' => $userid
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%d']
        );
    }

    public function checkIP($id)
    {
        $qry = $this->db->get_col($this->db->prepare("SELECT mp_pollinfo_id FROM " . $this->db->mp_locklist . " WHERE mp_poll_id = %d AND ip = %s", $id, AppHelper::getIpAddress()));

        if (count($qry) > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function checkUser($id, $userID)
    {
        $qry = $this->db->get_col($this->db->prepare("SELECT mp_pollinfo_id FROM " . $this->db->mp_locklist . " WHERE mp_poll_id = %d AND userid = %s", $id, $userID));
        if (count($qry) > 0) {
            return true;
        } else {
            return false;
        }
    }
}