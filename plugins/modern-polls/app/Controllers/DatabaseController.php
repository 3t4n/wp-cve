<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Controllers/DatabaseController.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Controllers;

use FelixTzWPModernPolls\Models\Polls;
use FelixTzWPModernPolls\Models\PollInfos;
use FelixTzWPModernPolls\Models\Templates;
use FelixTzWPModernPolls\Models\Locklist;
use FelixTzWPModernPolls\Models\Settings;


class DatabaseController
{
    public $polls;
    public $pollInfos;
    public $locklist;
    public $templates;
    public $settings;
    public $db;

    public function __construct()
    {

        global $wpdb;
        $this->db = $wpdb;

        $this->polls = new Polls();
        $this->pollInfos = new PollInfos();
        $this->locklist = new Locklist();
        $this->templates = new Templates();
        $this->settings = new Settings();
    }

    public function init()
    {

        if (!$this->checkTableExists()) {
            $this->polls->create();
            $this->pollInfos->create();
            $this->locklist->create();
            $this->templates->create();
            $this->settings->create();

            $this->insertSampleData();
            return true;
        } else {
            return false;
        }
    }

    public function insertSampleData()
    {

        $insertId = $this->polls->sampleData();
        $this->pollInfos->sampleData($insertId);
        $this->templates->sampleData();
        $this->settings->sampleData();

        return true;
    }

    public function checkTableExists()
    {
        $table_polls = $this->db->get_results("SHOW TABLES LIKE '%mp_polls'");
        $table_pollinfos = $this->db->get_results("SHOW TABLES LIKE '%mp_pollinfos'");
        $table_locklist = $this->db->get_results("SHOW TABLES LIKE '%mp_locklist'");

        if (!empty($table_polls) || !empty($table_pollinfos) || !empty($table_locklist)) {
            return true;
        } else {
            return false;
        }
    }
}