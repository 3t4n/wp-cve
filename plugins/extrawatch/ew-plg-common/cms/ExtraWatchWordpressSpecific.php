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

require("ExtraWatchCMSSpecific.php");
require("ExtraWatchCMSEnum.php");


class ExtraWatchWordpressSpecific implements ExtraWatchCMSSpecific {


    public function getCMSURL() {
        return get_bloginfo('url')."/";
    }

    public function getPlatformIdentifier() {
        return ExtraWatchCMSEnum::WORDPRESS;
    }

    /* get */
    public function getPluginOptionProjectId() {
        return get_option(EXTRAWATCH_SETTING_PROJECT_ID);
    }

    public function getPluginOptionTempPassword() {
        return get_option(EXTRAWATCH_SETTING_TEMP_PASSWORD);
    }

    public function getPluginOptionToken() {
        return get_option(EXTRAWATCH_OAUTH2_TOKEN);
    }

    public function getPluginOptionEmail() {
        return get_option(EXTRAWATCH_SETTING_EMAIL);
    }
    public function getPluginOptionTerms() {
        return get_option(EXTRAWATCH_SETTING_TERMS);
    }
    public function getPluginOptionURL() {
        return get_option(EXTRAWATCH_SETTING_URL);
    }
    /* get */


    /* save */
    public function savePluginOptionProjectId($value) {
        update_option(EXTRAWATCH_SETTING_PROJECT_ID, $value, true);
    }

    public function savePluginOptionTempPassword($value) {
        update_option(EXTRAWATCH_SETTING_TEMP_PASSWORD, $value, true);
    }

    public function savePluginOptionToken($value) {
        update_option(EXTRAWATCH_OAUTH2_TOKEN, $value, true);
    }

    public function savePluginOptionEmail($value) {
        update_option(EXTRAWATCH_SETTING_EMAIL, $value, true);
    }
    public function savePluginOptionTerms($value) {
        update_option(EXTRAWATCH_SETTING_TERMS, $value, true);
    }
    public function savePluginOptionURL($value) {
        update_option(EXTRAWATCH_SETTING_URL, $value, true);
    }
    /* save */

    /* delete */
    public function deletePluginOptionProjectId() {
        delete_option(EXTRAWATCH_SETTING_PROJECT_ID);
    }

    public function deleteOptionTempPassword() {
        delete_option(EXTRAWATCH_SETTING_TEMP_PASSWORD);
    }

    public function deletePluginOptionToken() {
        delete_option(EXTRAWATCH_OAUTH2_TOKEN  );
    }
    /* save */

    public function getAdminEmail() {
        if (function_exists("get_option")) {
            return @get_option('admin_email');
        }
        return;
    }


    public function isAdmin()
    {
        return is_admin();
    }

    public function getPluginsURL() {
        $pluginsUrl = plugins_url();

        $pluginRootDir = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "..");
        $pluginName = plugin_basename($pluginRootDir);

        return $pluginsUrl."/".$pluginName;
    }

    public function getComponentPage()
    {
        return "wp-admin/admin.php?page=extrawatch";
    }

    public function getSanitizedFromPOST($field)
    {
        return sanitize_text_field(@$_POST[$field]);
    }


    public function getSanitizedFromGET($field)
    {
        return sanitize_text_field(@$_GET[$field]);
    }

    public function escapeOutput($output)
    {
        return esc_html($output);
    }

    public function sanitizeEmail($email)
    {
        return sanitize_email($email);
    }

    public function remoteGET($url)
    {
        $response = wp_remote_get($url);
        return ExtraWatchRequestHelper::isSuccessByHttpStatus($response['response']['code']);
    }

    public function remotePOST($url, $postFields, $token = "")
    {

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => $postFields
        );

        if ($token) {
            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Bearer ' . $token
                ),
                'body' => $postFields
            );
        }

        $response = wp_remote_post($url, $args);
        $body = $response['body'];

        if (!$body) {
            return ExtraWatchRequestHelper::isSuccessByHttpStatus($response);
        }

        return $body;
    }

    public function remotePOSTWithBasicAuth($url, $postFields, $basicAuthUsername, $basicAuthPassword) {

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode( $basicAuthUsername . ':' . $basicAuthPassword )
            ),
            'body' => $postFields
        );

        $response = wp_remote_post($url, $args);

        $body = $response['body'];

        if (!$body) {
            return ExtraWatchRequestHelper::isSuccessByHttpStatus($response);
        }

        return $body;
    }


    public function createNonce($name)
    {
        return wp_create_nonce($name);
    }

    public function verifyNonce($value, $name)
    {
        return wp_verify_nonce($value, $name);
    }
}