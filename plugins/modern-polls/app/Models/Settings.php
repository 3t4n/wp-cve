<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Models/Settings.php
 * @date       12.06.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Models;


class Settings extends Model
{
    public function create()
    {
        $qry = "CREATE TABLE " . $this->db->mp_settings . " (" .
            "id int(10) NOT NULL auto_increment," .
            "log_ip tinyint(1) NOT NULL default '0'," .
            "log_cookie tinyint(1) NOT NULL default '1'," .
            "log_user tinyint(1) NOT NULL default '0'," .
            "log_expire varchar(20) NOT NULL default '86400'," .
            "closed_poll tinyint(1) NOT NULL default '0'," .
            "PRIMARY KEY  (id)" .
            ") $this->charsetCollate;";
        dbDelta($qry);
    }

    public function sampleData()
    {
        $exc = $this->db->insert($this->db->mp_settings,
            ['log_ip' => 0,
             'log_cookie' => 1,
             'log_user' => 0,
             'log_expire' => 86400],
            ['%d', '%d', '%d', '%d']);

        return $exc;
    }

    public function getAll()
    {
        $qry = $this->db->get_results("SELECT * FROM " . $this->db->mp_settings . " ");
        return $qry;
    }

    public function get($column)
    {
        $qry = $this->db->get_row("SELECT '" . $column . "' FROM " . $this->db->mp_settings . " ");
        return $qry;
    }

    public function save($log_ip, $log_cookie, $log_user, $log_expire, $closed_poll)
    {
        $qry = $this->db->update($this->db->mp_settings,
            [
                'log_ip' => $log_ip,
                'log_cookie' => $log_cookie,
                'log_user' => $log_user,
                'log_expire' => $log_expire,
                'closed_poll' => $closed_poll
            ],
            ['id' => 1],
            ['%d', '%d', '%d', '%d', '%d'],
            ['%d']
        );

        if (!$qry) return false;

        return true;
    }
}