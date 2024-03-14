<?php

namespace Wpo\Services;

// Prevent public access to this script
defined('ABSPATH') or die();

use \Wpo\Core\Request;
use \Wpo\Services\Access_Token_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\User_Service;

if (!class_exists('\Wpo\Services\Request_Service')) {

    class Request_Service
    {

        private $requests = array();

        private static $instance = null;

        private function __construct()
        {
        }

        public static function get_instance($create_new_request = false)
        {

            if (empty(self::$instance)) {
                self::$instance = new Request_Service();
            }

            if ($create_new_request) {
                $request = self::$instance->get_request($GLOBALS['WPO_CONFIG']['request_id']);
                $request->set_item(
                    'request_log',
                    array(
                        'debug_log' => Options_Service::get_global_boolean_var('debug_log', false),
                        'log' => array(),
                    )
                );
            }

            return self::$instance;
        }

        public function get_request($id)
        {

            if (!array_key_exists($id, $this->requests)) {
                $request = new Request($id);
                $this->requests[$id] = $request;
            }

            return $this->requests[$id];
        }

        public static function shutdown()
        {

            $request = self::$instance->get_request($GLOBALS['WPO_CONFIG']['request_id']);
            $mode = $request->get_item('mode');

            if (!empty($mode)) {
                Log_Service::flush_log();
                $request->clear();
                return;
            }

            $id_token = $request->get_item('id_token');

            if (!empty($id_token)) {
                $request->remove_item('id_token');
            }

            $authorization_code = $request->get_item('code');

            if (!empty($authorization_code)) {
                Access_Token_Service::save_authorization_code($authorization_code);
                $request->remove_item('authorization_code');
            }

            $access_tokens = $request->get_item('access_tokens');

            if (!empty($access_tokens)) {
                Access_Token_Service::save_access_tokens($access_tokens);
                $request->remove_item('access_tokens');
            }

            $refresh_token = $request->get_item('refresh_token');

            if (!empty($refresh_token)) {
                Access_Token_Service::save_refresh_token($refresh_token);
                $request->remove_item('refresh_token');
            }

            $pkce_code_verifier = $request->get_item('pkce_code_verifier');

            if (Options_Service::get_global_boolean_var('use_pkce') && class_exists('\Wpo\Services\Pkce_Service') && !empty($pkce_code_verifier)) {
                \Wpo\Services\Pkce_Service::save_personal_pkce_code_verifier($pkce_code_verifier);
                $request->remove_item('pkce_code_verifier');
            }

            $wpo_usr = $request->get_item('wpo_usr');

            if (!empty($wpo_usr)) {
                User_Service::save_user_principal_name($wpo_usr->upn);
                User_Service::save_user_tenant_id($wpo_usr->tid);
                User_Service::save_user_object_id($wpo_usr->oid);
            }

            Log_Service::flush_log();

            $request->clear();
        }
    }
}
