<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Models/Model.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Models;

class Model
{
    public $db;
    public $charsetCollate;

    public function __construct()
    {

        global $wpdb;
        $this->db = $wpdb;
        $this->charsetCollate = $this->db->get_charset_collate();

        $this->db->mp_pollinfos = $this->db->prefix . 'mp_pollinfos';
        $this->db->mp_polls = $this->db->prefix . 'mp_polls';
        $this->db->mp_templates = $this->db->prefix . 'mp_templates';
        $this->db->mp_settings = $this->db->prefix . 'mp_settings';
        $this->db->mp_locklist = $this->db->prefix . 'mp_locklist';
    }
}