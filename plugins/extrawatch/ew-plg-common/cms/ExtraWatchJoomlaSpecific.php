<?php
/**
 * @file
 * ExtraWatch - Real-time Visitor Analytics and Stats
 * @package ExtraWatch
 * @version 4.0
 * @revision 53
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2021 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

require("ExtraWatchCMSSpecific.php");

class ExtraWatchJoomlaSpecific implements ExtraWatchCMSSpecific {

    const REQUEST_TIMEOUT = 5;
    const COMPONENTS_COM_EXTRAWATCH_FOLDER = "/components/com_extrawatch";

    private $db;

    const EW_TABLE_SETTINGS = '#__extrawatch_settings';

    const EW_COLUMN_KEY = 'key';
    const EW_COLUMN_VALUE = 'value';

    public function __construct() {
        $this->db = JFactory::getDbo();
    }

    public function getPlatformIdentifier() {
        return ExtraWatchCMSEnum::JOOMLA;
    }

    public function getCMSURL() {
        return str_replace("/administrator","", JURI::base());
    }

    /* project Id */
    public function getPluginOptionProjectId() {
        return $this->getValue(EXTRAWATCH_SETTING_PROJECT_ID);
    }

    public function savePluginOptionProjectId($value) {
        $this->insertValueIntoTable(EXTRAWATCH_SETTING_PROJECT_ID, $value);
    }

    public function deletePluginOptionProjectId() {
    }
    /* project Id */



    /* temp pwd */
    public function getPluginOptionTempPassword() {
        return $this->getValue(EXTRAWATCH_SETTING_TEMP_PASSWORD);
    }

    public function savePluginOptionTempPassword($value) {
        $this->insertValueIntoTable(EXTRAWATCH_SETTING_TEMP_PASSWORD, $value);
    }

    public function deleteOptionTempPassword() {
    }
    /* temp pwd */


    private function getValue($key) {
        $query = $this->db->getQuery(true);
        $query->select($this->db->quoteName(self::EW_COLUMN_VALUE));
        $query->from($this->db->quoteName(self::EW_TABLE_SETTINGS));
        $query->where($this->db->quoteName(self::EW_COLUMN_KEY).' = '. $this->db->quote($key));
        $this->db->setQuery($query,0,1);
        $result = $this->db->loadResult();
        return $result;
    }


    public function insertValueIntoTable($key, $value) {
        $this->deleteKey($key);
        $query = $this->db->getQuery(true);
        $columns = array(self::EW_COLUMN_KEY, self::EW_COLUMN_VALUE);
        $values = array($this->db->quote($key), $this->db->quote($value));

        $query
            ->insert($this->db->quoteName(self::EW_TABLE_SETTINGS))
            ->columns($this->db->quoteName($columns))
            ->values(implode(',', $values));

        $this->db->setQuery($query);
        $this->db->execute();
    }

    private function deleteKey($key) {
        $query = $this->db->getQuery(true);

        $conditions = array(
            $this->db->quoteName(self::EW_COLUMN_KEY) . ' = '. $this->db->quote($key),
        );

        $query->delete($this->db->quoteName(self::EW_TABLE_SETTINGS));
        $query->where($conditions);

        $this->db->setQuery($query);
        $this->db->execute();
    }


    public function getPluginOptionToken()
    {
        return $this->getValue(EXTRAWATCH_OAUTH2_TOKEN);
    }

    public function getPluginOptionEmail()
    {
        return $this->getValue(EXTRAWATCH_SETTING_EMAIL);
    }

    public function getPluginOptionTerms()
    {
        return $this->getValue(EXTRAWATCH_SETTING_TERMS);
    }

    public function getPluginOptionURL()
    {
        return $this->getValue(EXTRAWATCH_SETTING_URL);
    }

    public function savePluginOptionToken($value)
    {
        $this->insertValueIntoTable(EXTRAWATCH_OAUTH2_TOKEN, $value);
    }

    public function savePluginOptionEmail($value)
    {
        $this->insertValueIntoTable(EXTRAWATCH_SETTING_EMAIL, $value);
    }

    public function savePluginOptionTerms($value)
    {
        $this->insertValueIntoTable(EXTRAWATCH_SETTING_TERMS, $value);
    }

    public function savePluginOptionURL($value)
    {
        $this->insertValueIntoTable(EXTRAWATCH_SETTING_URL, $value);
    }

    public function deletePluginOptionToken()
    {

    }

    public function getAdminEmail()
    {
        $config=new JConfig();

        return $config->mailfrom;
    }

    public function isAdmin()
    {
        $app = JFactory::getApplication();
        return $app->isAdmin();
    }

    public function getComponentPage()
    {
        return "administrator/index.php?option=com_extrawatch";
    }

    public function getSanitizedFromPOST($field)
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->getValue($field);
    }

    public function getSanitizedFromGET($field)
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->getValue($field);
    }

    public function escapeOutput($output)
    {
        return htmlentities($output);
    }

    public function sanitizeEmail($email)
    {
        return filter_var($email,FILTER_SANITIZE_EMAIL);
    }

    public function remoteGET($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_TIMEOUT, self::REQUEST_TIMEOUT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($curl);
        $status = $this->getStatus($curl);
        if (ExtraWatchRequestHelper::isSuccessByHttpStatus($curl)) {
            return $return;
        }
        return ExtraWatchRequestHelper::isSuccessByHttpStatus($curl);
    }

    public function remotePOST($url, $postFields, $token = "")
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_TIMEOUT, self::REQUEST_TIMEOUT);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $this->addHttpHeaderWithAuthToken($token, $curl);
        $return = curl_exec($curl);
        curl_close($curl);
        if (!$return) {
            return ExtraWatchRequestHelper::isSuccessByHttpStatus($curl);
        }
        return $return;

    }

    public function remotePOSTWithBasicAuth($url, $postFields, $basicAuthUsername, $basicAuthPassword)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_USERPWD, $basicAuthUsername . ":" . $basicAuthPassword);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::REQUEST_TIMEOUT);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($curl);
        curl_close($curl);
        return $return;
    }

    /**
     * @param $token
     * @param $curl
     */
    private function addHttpHeaderWithAuthToken($token, $curl)
    {
        if ($token) {
            $authorization = $this->createTokenHttpHeader($token);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array($authorization));
        }
    }

    /**
     * @param $token
     * @return string
     */
    private function createTokenHttpHeader($token) {
        $authorization = "Authorization: Bearer $token";
        return $authorization;
    }


    /**
     * @param $curl
     * @return mixed
     */
    private function getStatus($curl) {
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $http_status;
    }


    public function getPluginsURL() {
        return JURI::base(true). self::COMPONENTS_COM_EXTRAWATCH_FOLDER;
    }

    public function createNonce($name) {
    }

    public function verifyNonce($value, $name) {
        return true;
    }
}