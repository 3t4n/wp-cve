<?php

namespace MoSharePointObjectSync\API;

use MoSharePointObjectSync\Observer\adminObserver;
use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;

class Authorization
{
    private static $instance;

    public static function getController()
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function mo_sps_get_access_token_using_client_credentials($endpoints, $config, $scope)
    {

        if (!empty($config) && (isset($config['client_id']) && isset($config['client_secret']) && isset($config['tenant_id']))) {
            $client_secret = wpWrapper::mo_sps_decrypt_data($config['client_secret'], hash("sha256", $config['client_id']));
    
            $args =  [
                'body' => [
                    'grant_type' => 'client_credentials',
                    'client_secret' => $client_secret,
                    'client_id' => $config['client_id'],
                    'scope' => $scope
                ],
                'headers' => [
                    'Content-type' => 'application/x-www-form-urlencoded'
                ]

            ];
            
            $response = wp_remote_post(esc_url_raw($endpoints['token']), $args);

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                wp_die("Error Occurred : " . esc_html($error_message));
            } else {
                $body = json_decode($response["body"], true);
                if (isset($body['error']) && isset($_REQUEST['option']) && ($_REQUEST['option'] == 'testSPSApp' || $_REQUEST['option'] == 'testSPSUser')) {
                    $observer = adminObserver::getObserver();
                    $error_code = [
                        "Error" => $body['error'],
                        "Description" => $body['error_description']
                    ];
                    $observer->mo_sps_display_error_message($error_code);
                }
                if (isset($body["access_token"])) {
                    return ['status' => true, 'data' => $body['access_token']];
                }
            }
            return false;
        } else {
            $observer = adminObserver::getObserver();
            $error_code = [
                "Error" => 'mo_sps_error_001',
                "Description" => 'Your configuration might not get saved correctly.'
            ];
            $observer->mo_sps_display_error_message($error_code);
        }
    }

    public function mo_sps_get_access_token_using_authorization_code($endpoints, $config, $scope, $send_rftk=false)
    {

        $mo_client_id = (pluginConstants::CID);
        $mo_client_secret = (pluginConstants::CSEC);
        $server_url = (pluginConstants::CONNECT_SERVER_URI);

        $refresh_token = wpWrapper::mo_sps_get_option(pluginConstants::SPS_RFTK);
        $connector =get_option(pluginConstants::CLOUD_CONNECTOR);
        
        if (empty($refresh_token)) {

            $code = wpWrapper::mo_sps_get_option(pluginConstants::SPSAUTHCODE);

            $args =  [
                'body' => [
                    'grant_type' => 'authorization_code',
                    'client_secret' => $mo_client_secret,
                    'client_id' => $mo_client_id,
                    'code' => $code,
                    'redirect_uri' => $server_url
                ],
                'headers' => [
                    'Content-type' => 'application/x-www-form-urlencoded'
                ]
            ];
        } else {
            $args =  [
                'body' => [
                    'grant_type' => 'refresh_token',
                    'client_secret' => $mo_client_secret,
                    'client_id' => $mo_client_id,
                    'refresh_token' => $refresh_token,
                ],
                'headers' => [
                    'Content-type' => 'application/x-www-form-urlencoded'
                ]
            ];
        }


        if($connector == 'personal') {
            $response = wp_remote_post(esc_url_raw($endpoints['sps_personal_onedrive']), $args);
        } else {
            $response = wp_remote_post(esc_url_raw($endpoints['sps_common_token']), $args);
        }
                    
        if (is_wp_error($response)) {
            return ['status' => false, 'data' => ['error' => 'Request timeout', 'error_description' => 'Unexpected error occurred! Please check your internet connection and try again.']];
            $error_message = $response->get_error_message();
            wp_die("Error Occurred : " . esc_html($error_message));
        } else {
            $body = json_decode($response['body'], true);

            if (isset($body['refresh_token'])) {
                wpWrapper::mo_sps_set_option(pluginConstants::SPS_RFTK, $body['refresh_token']);
                $refresh_token = $body['refresh_token'];
                if($send_rftk) {
                    $new_res = ['status'=>true,'data'=>['refresh_token'=>$refresh_token]];
                    if (isset($body['access_token'])) {
                        $new_res['data']['access_token'] = $body['access_token'];
                    }
                    if($connector == 'personal' && isset($body['id_token'])) {
                        $new_res['data']['id_token'] = $body['id_token'];
                    }
                    return $new_res;
                }
            }
            if (isset($body['access_token'])) {
                return ['status' => true, 'data' => $body['access_token']];
            } else if (isset($body['error'])) {
                return ['status' => false, 'data' => $body];
            }
        }

        return ['status' => false, 'data' => ['error' => 'Unexpected Error', 'error_description' => 'Check your configurations once again']];
    }
    

    public function mo_sps_get_grpah_access_token_using_client_credentials($endpoints, $config, $scope)
    {
        $client_secret = wpWrapper::mo_sps_decrypt_data($config['client_secret'], hash("sha256", $config['client_id']));

        $args =  [
            'body' => [
                'grant_type' => 'client_credentials',
                'client_secret' => $client_secret,
                'client_id' => $config['client_id'],
                'scope' => $scope
            ],
            'headers' => [
                'Content-type' => 'application/x-www-form-urlencoded'
            ]

        ];


        $response = wp_remote_post(esc_url_raw($endpoints['graph_token']), $args);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            wp_die("Error Occurred : " . esc_html($error_message));
        } else {
            $body = json_decode($response["body"], true);
            if (isset($body["access_token"])) {
                return $body["access_token"];
            }
        }
        return false;
    }

    public function mo_sps_get_request($url,$headers){
        $args = [
            'headers' => $headers
        ];

        $response = wp_remote_get(esc_url_raw($url),$args);

        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $body = json_decode($response["body"],true);

            if(empty($body))
                return ['status'=>false,'data'=>['error'=>'Unauthorized','error_description'=>'Unexpected error occured']];
            else if(isset($body['error']))
                return ['status'=>false,'data'=>['error'=>$body['error']['code'],'error_description'=>$body['error']['message']]];

            return ['status'=>true,'data'=>$body];
        } else {
            return ['status'=>false,'data'=>['error'=>'Request timeout','error_description'=>'Unexpected error occurred! Please check your internet connection and try again.']];
            wp_die("Error occurred: ".esc_html($response->get_error_message()));
        }
    }
}
