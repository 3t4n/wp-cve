<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    Sirv Limited <support@sirv.com>
 *  @copyright Copyright (c) 2017 Sirv Limited. All rights reserved
 *  @license   https://www.magictoolbox.com/license/
 */

require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/utils.class.php');

class SirvAPIClient
{
    private $clientId = '';
    private $clientSecret = '';
    private $clientId_default = 'CCvbv8cbDcgijrSOrLd4sQ80jiN';
    private $clientSecret_default = '02gC7DoQ/wyKUliskFeQnjaYIZtMEFzJu7/TH3ayyNahkKfd4Nmaxw871FikWeRG2W9KEKB0JOelKibQw6QbeA==';
    private $token = '';
    private $tokenExpireTime = 0;
    private $connected = false;
    private $lastResponse;
    private $userAgent;

    public function __construct(
        $clientId,
        $clientSecret,
        $token,
        $tokenExpireTime,
        $userAgent
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->token = $token;
        $this->tokenExpireTime = $tokenExpireTime;
        $this->userAgent = $userAgent;
    }


    public function fetchImage($imgs)
    {

        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $res = $this->sendRequest(
            'v2/files/fetch',
            $imgs,
            'POST'
        );

        //if ($res && $res->http_code == 200) {
        if ($res) {
            $this->connected = true;
            return $res;
        } else {
            $this->connected = false;
            $this->nullToken();
            $this->updateParentClassSettings();
            return false;
        }
    }


    public function uploadImage($fs_path, $sirv_path)
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        /* //fix dirname if uploaded throuth browser
        $path_info = pathinfo(rawurldecode($sirv_path));
        $path_info['dirname'] = $path_info['dirname'] == '.' ? '' : '/' . $path_info['dirname'];
        //$encoded_sirv_path = $path_info['dirname'] . '/' . rawurlencode($path_info['basename']);
        $encoded_sirv_path = rawurlencode($path_info['dirname'] . '/' . $path_info['basename']);
        //$encoded_sirv_path = $this->clean_symbols($encoded_sirv_path); */

        if( ! Utils::startsWith('/', $sirv_path)){
            $sirv_path = '/' . $sirv_path;
        }


        $content_type = '';
        if (function_exists('mime_content_type')) {
            $content_type = mime_content_type($fs_path) !== false ? mime_content_type($fs_path) : 'application/octet-stream';
        } else {
            $content_type = "image/" . pathinfo($sirv_path, PATHINFO_EXTENSION);
        }

        $headers = array(
            'Content-Type'   => $content_type,
            'Content-Length' => filesize($fs_path),
        );

        $res = $this->sendRequest(
            //'v2/files/upload?filename=' . $encoded_sirv_path,
            'v2/files/upload?filename=' . $sirv_path,
            file_get_contents($fs_path),
            'POST',
            '',
            $headers,
            true);

        if ($res && $res->http_code == 200) {
            $this->connected = true;
            return array('upload_status' => 'uploaded');
        } else {
            $this->connected = false;
            $this->nullToken();
            $this->updateParentClassSettings();
            return array('upload_status' => 'failed');
        }
    }


    private function clean_symbols($str)
    {
        $str = str_replace('%40', '@', $str);
        $str = str_replace('%5D', '[', $str);
        $str = str_replace('%5B', ']', $str);
        $str = str_replace('%7B', '{', $str);
        $str = str_replace('%7D', '}', $str);
        $str = str_replace('%2A', '*', $str);
        $str = str_replace('%3E', '>', $str);
        $str = str_replace('%3C', '<', $str);
        $str = str_replace('%24', '$', $str);
        $str = str_replace('%3D', '=', $str);
        $str = str_replace('%2B', '+', $str);
        $str = str_replace('%27', "'", $str);
        $str = str_replace('%28', '(', $str);
        $str = str_replace('%29', ')', $str);

        return $str;
    }


    public function search($query, $from){
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $data = array(
            'query' => $query,
            'from' => $from,
            'size' => 50,
            'sort' => array("basename.raw" => "asc")
        );

        $res = $this->sendRequest('v2/files/search', $data, 'POST');

        if ($res){
            $this->connected = true;

            if($res->http_code == 200){
                if ($res->result->total > $from + 50) {
                    $res->result->isContinuation = true;
                    $res->result->from = $from + 50;
                } else {
                    $res->result->isContinuation = false;
                }
            }

            if ($res->http_code == 400) {
                //some code here
                $res->result->total = 0;
            }

            return $res->result;

        } else {
            $this->connected = false;
            $this->nullToken();
            $this->updateParentClassSettings();
            return false;
        }
    }


    public function copyFile($filePath, $copyFilePath)
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $res = $this->sendRequest(
            "v2/files/copy?from=$filePath&to=$copyFilePath",
            array(),
            'POST'
        );

        return ($res && $res->http_code == 200);
    }


    public function deleteFile($filename, $isPreOperationCheck = true)
    {
        if( $isPreOperationCheck ){
            $preCheck = $this->preOperationCheck();
            if (!$preCheck) {
                return false;
            }
        }

        $res = $this->sendRequest(
            'v2/files/delete?filename=/'. rawurlencode(rawurldecode($filename)),
            array(),
            'POST'
        );

        return ($res && $res->http_code == 200);
    }


    public function deleteFiles($files){
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $delete_count = 0;
        $undelete_count = 0;

        for( $i=0; $i < count($files); $i++ ){
            $result = $this->deleteFile(stripslashes($files[$i]), false);

            if( $result ){
                $delete_count++;
            }else{
                $undelete_count++;
            }
        }

        return array("delete" => $delete_count, "undelete" => $undelete_count);

    }


    public function createFolder($folderPath){
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $res = $this->sendRequest(
            'v2/files/mkdir?dirname=/' . rawurlencode(rawurldecode(stripcslashes($folderPath))),
            array(),
            'POST'
        );

        return ($res && $res->http_code == 200);
    }


    public function renameFile($oldFilePath, $newFilePath){
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $oldFilePath = rawurlencode(rawurldecode(stripcslashes($oldFilePath)));
        $newFilePath = rawurlencode(rawurldecode(stripcslashes($newFilePath)));

        $res = $this->sendRequest(
            "v2/files/rename?from=$oldFilePath&to=$newFilePath",
            array(),
            'POST'
        );

        return ($res && $res->http_code == 200);
    }


    public function setMetaTitle($filename, $title)
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $res = $this->sendRequest(
            'v2/files/meta/title?filename=' . $filename,
            array(
                'title' => $title
            ),
            'POST');

        return ($res && $res->http_code == 200);

    }


    public function setMetaDescription($filename, $description)
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $res = $this->sendRequest(
            'v2/files/meta/description?filename=' . $filename,
            array(
                'description' => $description
            ),
            'POST');

        return ($res && $res->http_code == 200);
    }


    public function configFetching($url, $status, $minify)
    {

        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $data = array();

        if ($status) {
            $data = array(
                'minify' => array(
                    "enabled" => $minify
                ),
                'fetching' => array(
                    "enabled" => true,
                    "type" => "http",
                    "http" => array(
                        "url" => $url,
                    ),
                )
            );
        } else {
            $data = array(
                'minify' => array(
                    "enabled" => false
                ),
                'fetching' => array(
                    "enabled" => false
                )
            );
        }

        $res = $this->sendRequest('v2/account', $data, 'POST');

        if ($res) {
            $this->connected = true;
            return true;
        } else {
            $this->connected = false;
            $this->nullToken();
            $this->updateParentClassSettings();
            return false;
        }
    }


    public function configCDN($status, $alias)
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $data = array(
            'aliases' => array(
                $alias => array(
                    "cdn" => $status
                )
            )
        );

        $res = $this->sendRequest('v2/account', $data, 'POST');

        if ($res) {
            $this->connected = true;
            return true;
        } else {
            $this->connected = false;
            $this->nullToken();
            $this->updateParentClassSettings();
            return false;
        }
    }

    public function preOperationCheck()
    {
        if ($this->connected) {
            return true;
        }

        if (empty($this->token) || $this->isTokenExpired()) {
            if (!$this->getNewToken()) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function isConnected()
    {
        return $this->connected;
    }

    public function getNewToken()
    {
        if (empty($this->clientId) || empty($this->clientSecret)) {
            $this->nullClientLogin();
            $this->nullToken();
            $this->updateParentClassSettings();
            return false;
        }
        $res = $this->sendRequest('v2/token', array(
            "clientId" => $this->clientId,
            "clientSecret" => $this->clientSecret,
        ));

        if ($res && $res->http_code == 200 && !empty($res->result->token) && !empty($res->result->expiresIn)) {
            $this->connected = true;
            $this->token = $res->result->token;
            $this->tokenExpireTime = time() + $res->result->expiresIn;
            $this->updateParentClassSettings();
            return $this->token;
        } else {
            $this->connected = false;
            if (!empty($res->http_code) && $res->http_code == 401) {
                $this->nullClientLogin();
            }
            $this->nullToken();
            $this->updateParentClassSettings();
            return false;
        }
    }


    protected static function usersSortFunc($a, $b)
    {
        if ($a->alias == $b->alias) {
            return 0;
        }
        return ($a->alias < $b->alias) ? -1 : 1;
    }


    protected static function alphabeticallSortFunc($a, $b){
        return strcasecmp($a->alias, $b->alias);
    }


    public function getUsersList($email, $password, $otpToken)
    {
        $res = $this->sendRequest('v2/token', array(
            "clientId" => $this->clientId_default,
            "clientSecret" => $this->clientSecret_default,
        ));

        if ($res && $res->http_code == 200 && !empty($res->result->token) && !empty($res->result->expiresIn)) {
            $requestOptions = array(
                "email" => $email,
                "password" => $password
            );
            if (!empty($otpToken)) {
                $requestOptions['otpToken'] = $otpToken;
            }

            $res = $this->sendRequest('v2/user/accounts', $requestOptions, 'POST', $res->result->token);
            if($res){
                if($res->http_code == 417){
                    return array(
                        "isOtpToken" => true
                    );
                }else if($res->http_code == 200){
                    if(!empty($res->result) && is_array($res->result)){

                        uasort($res->result, array('SirvAPIClient', 'usersSortFunc'));
                        $res->result = array_values($res->result);
                        return $res->result;
                    }
                }else{
                    //return http code issue and error message
                }
            }
        }

        return false;
    }


    public function getFolderOptions($filename)
    {
        $res = $this->sendRequest(
            'v2/files/options?filename=/'.rawurlencode($filename).'&withInherited=true',
            array(),
            'GET'
        );
        if ($res && $res->http_code == 200) {
            return $res->result;
        } else {
            return false;
        }
    }


    public function getFileStat($filename)
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $res = $this->sendRequest(
            'v2/files/stat?filename=/'. $filename,
            array(),
            'GET'
        );

        if ($res && $res->http_code == 200) {
            return $res->result;
        } else {
            return false;
        }
    }


    public function getProfiles()
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $res = $this->sendRequest(
            'v2/files/readdir?dirname=/Profiles',
            array(),
            'GET'
        );

        if ($res && $res->http_code == 200) {
            return $res->result;
        } else {
            return false;
        }
    }


    public function setFolderOptions($filename, $options)
    {
        $res = $this->sendRequest(
            'v2/files/options?filename=/'.rawurlencode($filename),
            $options,
            'POST'
        );
        return ($res && $res->http_code == 200);
    }


    public function registerAccount($email, $password, $firstName, $lastName, $alias)
    {
        $res = $this->sendRequest('v2/token', array(
            "clientId" => $this->clientId_default,
            "clientSecret" => $this->clientSecret_default,
        ));

        if ($res && $res->http_code == 200 && !empty($res->result->token) && !empty($res->result->expiresIn)) {
            $res = $this->sendRequest('v2/account', array(
                "email" => $email,
                "password" => $password,
                "firstName" => $firstName,
                "lastName" => $lastName,
                "alias" => $alias,
            ), 'PUT', $res->result->token);

            if ($res && $res->http_code == 200) {
                return true;
            } else {
                return false;
            }
        }
    }


    public function setupClientCredentials($token)
    {
        $res = $this->sendRequest('v2/rest/credentials', array(), 'GET', $token);
        if ($res && $res->http_code == 200 && !empty($res->result->clientId) && !empty($res->result->clientSecret)) {
            $this->clientId = $res->result->clientId;
            $this->clientSecret = $res->result->clientSecret;
            $this->getNewToken();
            $this->updateParentClassSettings();
            return true;
        } else {
            return false;
        }
    }


    public function setupS3Credentials($email = '')
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $res = $this->sendRequest('v2/account/users', array(), 'GET');

        if ($res && $res->http_code == 200 && !empty($res->result) && is_array($res->result) && count($res->result)) {
            $res_user = false;

            foreach ($res->result as $user) {
                $tmp_res = $this->sendRequest('v2/user?userId=' . $user->userId, array(), 'GET');
                if ($tmp_res && $tmp_res->http_code == 200 && strtolower($tmp_res->result->email) == strtolower($email)) {
                    $res_user = $tmp_res;
                    break;
                }
            }

            if ($res_user && $res_user->http_code == 200 &&
                !empty($res_user->result->s3Secret) && !empty($res_user->result->email)) {
                $res_alias = $this->sendRequest('v2/account', array(), 'GET');

                if ($res_alias && $res_alias->http_code == 200 &&
                    !empty($res_alias->result) && !empty($res_alias->result->alias)) {
                    $this->updateParentClassSettings(array(
                        'SIRV_ACCOUNT_NAME' => $res_alias->result->alias,
                    ));
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
            return true;
        } else {
            $this->updateParentClassSettings(array(
                'SIRV_ACCOUNT_NAME' => '',
            ));
            return false;
        }
    }


    public function updateParentClassSettings($extra_options = array())
    {
        if(function_exists('update_option')){
            update_option('SIRV_CLIENT_ID', $this->clientId);
            update_option('SIRV_CLIENT_SECRET', $this->clientSecret);
            update_option('SIRV_TOKEN', $this->token);
            update_option('SIRV_TOKEN_EXPIRE_TIME', $this->tokenExpireTime);
            if (count($extra_options)){
                foreach ($extra_options as $option => $value) {
                    update_option($option, $value);
                }
            }
        }
        return true;
    }


    public function nullClientLogin()
    {
        $this->clientId = '';
        $this->clientSecret = '';
        $this->updateParentClassSettings(array(
            'SIRV_ACCOUNT_NAME' => '',
        ));
    }


    public function nullToken()
    {
        $this->token = '';
        $this->tokenExpireTime = 0;
    }


    public function isTokenExpired()
    {
        return $this->tokenExpireTime < time();
    }


    public function getAccountInfo()
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $result = $this->sendRequest('v2/account', array(), 'GET');

        if (!$result || empty($result->result) || $result->http_code != 200 || empty($result->result)) {
            $this->connected = false;
            $this->nullToken();
            $this->updateParentClassSettings();
            return false;
        }

        return $result->result;
    }


    public function getStorageInfo()
    {
        $preCheck = $this->preOperationCheck();
        if (!$preCheck) {
            return false;
        }

        $storageInfo = array();

        $result = $this->sendRequest('v2/account', array(), 'GET');
        $result_storage = $this->sendRequest('v2/account/storage', array(), 'GET');

        if (!$result || empty($result->result) || $result->http_code != 200
            || !$result_storage->result || empty($result->result) || $result_storage->http_code != 200) {
            $this->connected = false;
            $this->nullToken();
            $this->updateParentClassSettings();
            return false;
        }

        $result = $result->result;
        $result_storage = $result_storage->result;

        if (isset($result->alias)) {
            $storageInfo['account'] = $result->alias;

            $billing = $this->sendRequest('v2/billing/plan', array(), 'GET');

            $billing->result->dateActive = preg_replace(
                '/.*([0-9]{4}\-[0-9]{2}\-[0-9]{2}).*/ims',
                '$1',
                $billing->result->dateActive
            );

            $planEnd = strtotime('+30 days', strtotime($billing->result->dateActive));
            $now = time();

            $datediff = (int) round(($planEnd - $now) / (60 * 60 * 24));

            $until = ($planEnd > $now) ? ' (' . $datediff . ' day' . ($datediff > 1 ? 's' : '') . ' left)' : '';

            if ($planEnd < $now) {
                $until = '';
            }

            $storageInfo['plan'] = array(
                'name' => $billing->result->name,
                'trial_ends' => preg_match('/trial/ims', $billing->result->name) ?
                    'until ' . date("j F", strtotime('+30 days', strtotime($billing->result->dateActive))) . $until
                    : '',
                'storage' => $billing->result->storage,
                'storage_text' => Utils::getFormatedFileSize($billing->result->storage),
                'dataTransferLimit' => isset($billing->result->dataTransferLimit) ? $billing->result->dataTransferLimit : '',
                'dataTransferLimit_text' => isset($billing->result->dataTransferLimit) ? Utils::getFormatedFileSize($billing->result->dataTransferLimit) : '&#8734',
            );

            $storage = $this->sendRequest('v2/account/storage', array(), 'GET');

            $storage->result->plan = $storage->result->plan + $storage->result->extra;

            $storageInfo['storage'] = array(
                'allowance' => $storage->result->plan,
                'allowance_text' => Utils::getFormatedFileSize($storage->result->plan),
                'used' => $storage->result->used,
                'available' => $storage->result->plan - $storage->result->used,
                'available_text' => Utils::getFormatedFileSize($storage->result->plan - $storage->result->used),
                'available_percent' => number_format(
                    ($storage->result->plan - $storage->result->used) / $storage->result->plan * 100,
                    2,
                    '.',
                    ''
                ),
                'used_text' => Utils::getFormatedFileSize($storage->result->used),
                'used_percent' => number_format($storage->result->used / $storage->result->plan * 100, 2, '.', ''),
                'files' => $storage->result->files,
            );

            $storageInfo['traffic'] = array(
                'allowance' => isset($billing->result->dataTransferLimit) ? $billing->result->dataTransferLimit : '',
                'allowance_text' => isset($billing->result->dataTransferLimit) ?
                Utils::getFormatedFileSize($billing->result->dataTransferLimit) : '&#8734',
            );

            $dates = array(
                'This month' => array(
                    date("Y-m-01"),
                    date("Y-m-t"),
                ),
                date("F Y", strtotime("first day of -1 month")) => array(
                    date("Y-m-01", strtotime("first day of -1 month")),
                    date("Y-m-t", strtotime("last day of -1 month")),
                ),
                date("F Y", strtotime("first day of -2 month")) => array(
                    date("Y-m-01", strtotime("first day of -2 month")),
                    date("Y-m-t", strtotime("last day of -2 month")),
                ),
                date("F Y", strtotime("first day of -3 month")) => array(
                    date("Y-m-01", strtotime("first day of -3 month")),
                    date("Y-m-t", strtotime("last day of -3 month")),
                ),
            );

            $dataTransferLimit = isset($billing->result->dataTransferLimit) ?
            $billing->result->dataTransferLimit : PHP_INT_MAX;

            $count = 0;
            foreach ($dates as $label => $date) {
                $traffic = $this->sendRequest('v2/stats/http?from=' . $date[0] . '&to=' . $date[1], array(), 'GET');

                if (!$traffic || $traffic->http_code != 200) {
                    $this->connected = false;
                    $this->nullToken();
                    $this->updateParentClassSettings();
                    return false;
                }

                unset($traffic->http_code);

                $traffic = (array)$traffic->result;

                $storageInfo['traffic']['traffic'][$label]['size'] = 0;
                $storageInfo['traffic']['traffic'][$label]['order'] = $count++;

                if (count($traffic)) {
                    foreach ($traffic as $v) {
                        $storageInfo['traffic']['traffic'][$label]['size'] += (isset($v->total->size))
                        ? $v->total->size : 0;
                    }
                }
                $storageInfo['traffic']['traffic'][$label]['percent'] = number_format(
                    $storageInfo['traffic']['traffic'][$label]['size'] / $dataTransferLimit * 100,
                    2,
                    '.',
                    ''
                );
                $storageInfo['traffic']['traffic'][$label]['percent_reverse'] = number_format(
                    100 - $storageInfo['traffic']['traffic'][$label]['size'] / $dataTransferLimit * 100,
                    2,
                    '.',
                    ''
                );
                $storageInfo['traffic']['traffic'][$label]['size_text'] =
                    Utils::getFormatedFileSize($storageInfo['traffic']['traffic'][$label]['size']);
            }
        }

        $result = $this->sendRequest('v2/account/limits', array(), 'GET');

        if ($result && !empty($result->result) && $result->http_code == 200) {
            $storageInfo['limits'] = $result->result;
            $storageInfo['limits'] = (array) $storageInfo['limits'];
            //$date = new DateTime();
            //$timeZone = $date->getTimezone();
            foreach ($storageInfo['limits'] as $type => $value) {
                $storageInfo['limits'][$type] = (array) $value;
                $value = (array) $value;
                /* $dt = new DateTime('@' . $value['reset']);
                $dt->setTimeZone(new DateTimeZone($timeZone->getName()));
                $storageInfo['limits'][$type]['reset_str'] = $dt->format("H:i:s");*/
                $storageInfo['limits'][$type]['reset_timestamp'] = (int)$value['reset'];
                $storageInfo['limits'][$type]['reset_str'] = date('H:i:s e', $value['reset']);
                $storageInfo['limits'][$type]['count_reset_str'] = $this->calcTime((int) $value['reset']);
                //$storageInfo['limits'][$type]['used'] = (round($value['count'] / $value['limit'] * 10000) / 100) . '%';
                $storageInfo['limits'][$type]['used'] = $value['count'] == 0 || $value['limit'] == 0 ? 0 : (round($value['count'] / $value['limit'] * 10000) / 100) . '%';
                $storageInfo['limits'][$type]['type'] = $type;
            }
            //$storageInfo['limits'] = array_chunk($storageInfo['limits'], (int) count($storageInfo['limits']) / 2);
        }else{
            $storageInfo['limits'] = array();
        }

        return $storageInfo;
    }

    public function calcTime($end){
        $mins = round(($end - time())/60);

        return "$mins minutes";
    }


    public function getMuteError(){
        $reset_time = (int) get_option('SIRV_MUTE');
        $error_message = get_option('SIRV_MUTE_ERROR_MESSAGE');
        //$error = 'Module disabled due to exceeding API usage rate limit. Refresh this page in ' . $this->calcTime($reset_time) . ' ' . date("F j, Y, H:i a (e)", $reset_time);
        /* $default_error = 'Module settings temporarily unavailable due to exceeded API usage limit. Limits refresh every hour. Try again in '. $this->calcTime($reset_time) .' ('. date("H:i (e)", $reset_time) . '). <a href="https://my.sirv.com/#/account/usage">Current API usage</a> is shown in your Sirv account.'; */

        $error = 'Plugin settings cannot load due API usage limit reached.<br><br>Please refresh this page in <b>' . $this->calcTime($reset_time) . '</b>, once the hourly limit has refreshed (' . date("H:i e", $reset_time) . ').<br><br>

        <a target="_blank" href="https://my.sirv.com/#/account/usage">Current API usage</a> is shown in your Sirv account.<br><br>

        <hr>API response:<br><br><i>' . $error_message .'</i>';

        return $error;
    }


    public function getContent($path='/', $continuation='')
    {
        $preCheck = $this->preOperationCheck();
            if (!$preCheck) {
                return false;
            }

            $params = $continuation !== ''
                ? 'dirname='.$path.'&continuation='.$continuation
                : 'dirname='.$path;

        $content = $this->sendRequest('v2/files/readdir?' . $params, array(), 'GET');
        if (!$content || $content->http_code != 200) {
            $this->connected = false;
            $this->nullToken();
            $this->updateParentClassSettings();
            return false;
        }

        return $content->result;
    }


    public function getLastResponse()
    {
        return $this->lastResponse;
    }


    public function muteRequests($timestamp, $errorMessage)
    {
        update_option('SIRV_MUTE', $timestamp, 'no');
        update_option('SIRV_MUTE_ERROR_MESSAGE', $errorMessage, 'no');
    }


    public function isMuted()
    {
        return ((int)get_option('SIRV_MUTE') > time());
    }


    private function sendRequest($url, $data, $method = 'POST', $token = '', $headers = null, $isFile = false)
    {

        if ($this->isMuted()) {
            $this->curlInfo = array('http_code' => 429);
            return false;
        }

        if (is_null($headers)) $headers = array();

        if (!empty($token)) {
            $headers['Authorization'] = "Bearer " . ((!empty($token)) ? $token : $this->token);
        } else {
            $headers['Authorization'] = "Bearer " . $this->token;
        }
        if(!array_key_exists('Content-Type', $headers)) $headers['Content-Type'] = "application/json";

        foreach ($headers as $k => $v){
            $headers[$k] = "$k: $v";
        }

        //$fp = fopen(dirname(__FILE__) . '/curl_errorlog.txt', 'w');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sirv.com/" . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_CONNECTTIMEOUT => 0.1,
            CURLOPT_TIMEOUT => 0.1,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => (!$isFile) ? json_encode($data) : $data,
            CURLOPT_HTTPHEADER => $headers,
            //CURLOPT_SSL_VERIFYPEER => false,
            //CURLOPT_VERBOSE => true,
            //CURLOPT_STDERR => $fp,
        ));

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);

        if(IS_DEBUG){
            global $logger;

            $logger->info($result, '$result')->filename('network.log')->write();
            $logger->info($info, '$info')->filename('network.log')->write();
        }

        $res_object = json_decode($result);

        if ($this->isLimitRequestReached($res_object, $info)) {
            $time = time() + 60*60;

            $errorMessage = $this->getLimitRequestReachedMessage($res_object, $info);

            if(preg_match('/stop sending requests until ([0-9]{4}\-[0-9]{2}\-[0-9]{2}.*?\([a-z]{1,}\))/ims', $errorMessage, $m)) {
                $time = strtotime($m[1]);
            }

            $this->muteRequests($time, $errorMessage);
        }

        $info['http_code_text'] = $this->get_http_code_text($info['http_code']);
        $response = (object) $info;
        $response->result = $res_object;

        //TODO: if result html then return result_txt or empty
        $response->result_txt = trim($result);
        $response->error = curl_error($curl);

        $this->lastResponse = $response;

        curl_close($curl);
        //fclose($fp);

        return $response;
    }


    protected function isLimitRequestReached($result, $info){
        if ( $info['http_code'] == 429 ) return true;

        if(! empty($result) ){

            if( is_object($result) && isset($result->message) && stripos($result->message, 'rate limit exceeded') !== false ) return true;

            if( is_array($result) && isset($result[0]->error) && stripos($result[0]->error, 'rate limit exceeded') !== false ) return true;
        }


        return false;
    }


    protected function getLimitRequestReachedMessage($result, $info){
        if (is_object($result) && isset($result->message) && stripos($result->message, 'rate limit exceeded') !== false) return $result->message;

        if( is_array($result) && isset($result[0]->error) && stripos($result[0]->error, 'rate limit exceeded') !== false ) return $result[0]->error;

        return "Error message did not receive.";
    }


    protected function get_http_code_text($code){
        $http_status_codes = array(
            100 => 'Informational: Continue',
            101 => 'Informational: Switching Protocols',
            102 => 'Informational: Processing',
            200 => 'Successful: OK',
            201 => 'Successful: Created',
            202 => 'Successful: Accepted',
            203 => 'Successful: Non-Authoritative Information',
            204 => 'Successful: No Content',
            205 => 'Successful: Reset Content',
            206 => 'Successful: Partial Content',
            207 => 'Successful: Multi-Status',
            208 => 'Successful: Already Reported',
            226 => 'Successful: IM Used',
            300 => 'Redirection: Multiple Choices',
            301 => 'Redirection: Moved Permanently',
            302 => 'Redirection: Found',
            303 => 'Redirection: See Other',
            304 => 'Redirection: Not Modified',
            305 => 'Redirection: Use Proxy',
            306 => 'Redirection: Switch Proxy',
            307 => 'Redirection: Temporary Redirect',
            308 => 'Redirection: Permanent Redirect',
            400 => 'Client Error: Bad Request',
            401 => 'Client Error: Unauthorized',
            402 => 'Client Error: Payment Required',
            403 => 'Client Error: Forbidden',
            404 => 'Client Error: Not Found',
            405 => 'Client Error: Method Not Allowed',
            406 => 'Client Error: Not Acceptable',
            407 => 'Client Error: Proxy Authentication Required',
            408 => 'Client Error: Request Timeout',
            409 => 'Client Error: Conflict',
            410 => 'Client Error: Gone',
            411 => 'Client Error: Length Required',
            412 => 'Client Error: Precondition Failed',
            413 => 'Client Error: Request Entity Too Large',
            414 => 'Client Error: Request-URI Too Long',
            415 => 'Client Error: Unsupported Media Type',
            416 => 'Client Error: Requested Range Not Satisfiable',
            417 => 'Client Error: Expectation Failed',
            418 => 'Client Error: I\'m a teapot',
            419 => 'Client Error: Authentication Timeout',
            422 => 'Client Error: Unprocessable Entity',
            423 => 'Client Error: Locked',
            424 => 'Client Error: Failed Dependency',
            425 => 'Client Error: Unordered Collection',
            426 => 'Client Error: Upgrade Required',
            428 => 'Client Error: Precondition Required',
            429 => 'Client Error: Too Many Requests',
            431 => 'Client Error: Request Header Fields Too Large',
            444 => 'Client Error: No Response',
            449 => 'Client Error: Retry With',
            450 => 'Client Error: Blocked by Windows Parental Controls',
            451 => 'Client Error: Unavailable For Legal Reasons',
            494 => 'Client Error: Request Header Too Large',
            495 => 'Client Error: Cert Error',
            496 => 'Client Error: No Cert',
            497 => 'Client Error: HTTP to HTTPS',
            499 => 'Client Error: Client Closed Request',
            500 => 'Server Error: Internal Server Error',
            501 => 'Server Error: Not Implemented',
            502 => 'Server Error: Bad Gateway',
            503 => 'Server Error: Service Unavailable',
            504 => 'Server Error: Gateway Timeout',
            505 => 'Server Error: HTTP Version Not Supported',
            506 => 'Server Error: Variant Also Negotiates',
            507 => 'Server Error: Insufficient Storage',
            508 => 'Server Error: Loop Detected',
            509 => 'Server Error: Bandwidth Limit Exceeded',
            510 => 'Server Error: Not Extended',
            511 => 'Server Error: Network Authentication Required',
            598 => 'Server Error: Network read timeout error',
            599 => 'Server Error: Network connect timeout error',
        );

        $code = (int) $code;

        if( ! in_array($code, array_keys($http_status_codes)) ){
            return "Unknown http code";
        }

        return $http_status_codes[$code];
    }
}
