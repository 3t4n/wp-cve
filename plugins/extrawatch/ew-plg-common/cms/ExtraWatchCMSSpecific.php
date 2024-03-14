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

interface ExtraWatchCMSSpecific {

    public function getCMSURL();
    public function getPlatformIdentifier();

    public function getPluginOptionProjectId();
    public function getPluginOptionTempPassword();
    public function getPluginOptionToken();
    public function getPluginOptionEmail();
    public function getPluginOptionTerms();
    public function getPluginOptionURL();

    public function savePluginOptionProjectId($value);
    public function savePluginOptionTempPassword($value);
    public function savePluginOptionToken($value);
    public function savePluginOptionEmail($value);
    public function savePluginOptionTerms($value);
    public function savePluginOptionURL($value);

    public function deletePluginOptionProjectId();
    public function deleteOptionTempPassword();
    public function deletePluginOptionToken();

    public function getAdminEmail();
    public function isAdmin();
    public function getComponentPage();
    public function getPluginsURL();

    public function createNonce($name);
    public function verifyNonce($value, $name);


    public function getSanitizedFromPOST($field);
    public function getSanitizedFromGET($field);

    public function escapeOutput($output);
    public function sanitizeEmail($email);
    public function remoteGET($url);
    public function remotePOST($url, $postFields, $token = "");
    public function remotePOSTWithBasicAuth($url, $postFields, $basicAuthUsername, $basicAuthPassword);




}