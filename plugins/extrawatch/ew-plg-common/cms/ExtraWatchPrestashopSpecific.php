<?php
/**
 * @file
 * ExtraWatch - Real-time Visitor Analytics and Stats
 * @package ExtraWatch
 * @version 4.0
 * @revision 34
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2018 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

require("ExtraWatchCMSSpecific.php");
require("ExtraWatchCMSEnum.php");

class ExtraWatchPrestashopSpecific implements ExtraWatchCMSSpecific {

    const EXTRAWATCH_SETTING_PROJECT_ID = 'extrawatch-projectId';
    const EXTRAWATCH_SETTING_TEMP_PASSWORD = 'extrawatch-temp-password';

    private $db;

    const TABLE_PROJECT = 'extrawatch_project';
    const COLUMN_PROJECT = 'projectId';

    const TABLE_TEMP_PWD = 'extrawatch_temp_pwd';
    const COLUMN_TEMP_PWD = 'temp_pwd';



    public function __construct() {
        $this->db = Db::getInstance(_PS_USE_SQL_SLAVE_);
    }


    public function getCMSURL() {
    }

    public function getPlatformIdentifier() {
        return ExtraWatchCMSEnum::WORDPRESS;
    }


    /* project Id */
    public function getPluginOptionProjectId() {
        return $this->retrieveOption(self::TABLE_PROJECT, self::COLUMN_PROJECT);
    }

    public function savePluginOptionProjectId($value) {
        $this->saveOption(self::TABLE_PROJECT, self::COLUMN_PROJECT, $value);
    }

    public function deletePluginOptionProjectId() {
        $this->deleteOption(self::TABLE_PROJECT);
    }
    /* project Id */



    /* temp pwd */
    public function getPluginOptionTempPassword() {
        return $this->retrieveOption(self::TABLE_TEMP_PWD, self::COLUMN_TEMP_PWD);
    }

    public function savePluginOptionTempPassword($value) {
        $this->saveOption(self::TABLE_TEMP_PWD, self::COLUMN_TEMP_PWD, $value);
    }

    public function deleteOptionTempPassword() {
        $this->deleteOption(self::TABLE_TEMP_PWD);
    }
    /* temp pwd */



    private function retrieveOption($table, $column) {
        $column = $this->db->escape($column);
        $table = $this->db->escape($table);
        $sql = "SELECT " . $column . " FROM " . _DB_PREFIX_ . $table;
        return $this->db->getValue($sql);
    }

    private function saveOption($table, $column, $value) {
        $column = $this->db->escape($column);
        $table = $this->db->escape($table);

        $this->deleteOption($table);
        $this->db->insert($table,
            array($column => $value)
        );
    }

    private function deleteOption($table) {
        $this->db->delete($table);
    }
    /* save */


    public function getPluginOptionToken()
    {
        // TODO: Implement getPluginOptionToken() method.
    }

    public function getPluginOptionEmail()
    {
        // TODO: Implement getPluginOptionEmail() method.
    }

    public function getPluginOptionTerms()
    {
        // TODO: Implement getPluginOptionTerms() method.
    }

    public function getPluginOptionURL()
    {
        // TODO: Implement getPluginOptionURL() method.
    }

    public function savePluginOptionToken($value)
    {
        // TODO: Implement savePluginOptionToken() method.
    }

    public function savePluginOptionEmail($value)
    {
        // TODO: Implement savePluginOptionEmail() method.
    }

    public function savePluginOptionTerms($value)
    {
        // TODO: Implement savePluginOptionTerms() method.
    }

    public function savePluginOptionURL($value)
    {
        // TODO: Implement savePluginOptionURL() method.
    }

    public function deletePluginOptionToken()
    {
        // TODO: Implement deletePluginOptionToken() method.
    }

    public function getAdminEmail()
    {
        // TODO: Implement getAdminEmail() method.
    }

    public function isAdmin()
    {
        // TODO: Implement isAdmin() method.
    }

    public function getComponentPath()
    {
        // TODO: Implement getComponentPath() method.
    }
}