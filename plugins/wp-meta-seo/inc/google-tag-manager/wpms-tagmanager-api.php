<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

if (!class_exists('WpmsTagManagerController')) {

    /**
     * Class WpmsTagManagerController
     */
    class WpmsTagManagerController
    {
        /**
         * Google API client
         *
         * @var \WPMSGoogle\Client
         */
        public $client;
        /**
         * Google service tag manager
         *
         * @var Google_Service_TagManager
         */
        public $tagmanager_service;

        /**
         * WpmsTagManagerController constructor.
         */
        public function __construct()
        {
            $google_alanytics = get_option('wpms_google_alanytics');

            require_once WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php';
            $this->client = WpmsGaTools::initClient($google_alanytics['wpmsga_dash_clientid'], $google_alanytics['wpmsga_dash_clientsecret']);

            $this->managequota = 'u' . get_current_user_id() . 's' . get_current_blog_id();
            $this->tagmanager_service = new WPMSGoogle_Service_TagManager($this->client);
            if (!empty($google_alanytics['googleCredentials'])) {
                $token = $google_alanytics['googleCredentials'];
                if ($token) {
                    if (WpmsGaTools::isTokenExpired($token)) {
                        $token =  WpmsGaTools::getAccessTokenFromRefresh($google_alanytics['wpmsga_dash_clientid'], $google_alanytics['wpmsga_dash_clientsecret'], $token) ;
                    }
                    $this->client->setAccessToken($token);
                }
            }
        }

        /**
         * Get list GTM accounts
         *
         * @return Google_Service_TagManager_ListAccountsResponse
         */
        public function getListAccounts()
        {
            return $this->tagmanager_service->accounts->listAccounts();
        }

        /**
         * Get list GTM containers
         *
         * @param string $account_id GTM account ID
         *
         * @return Google_Service_TagManager_ListContainersResponse
         */
        public function getListContainers($account_id)
        {
            return $this->tagmanager_service->accounts_containers->listAccountsContainers('accounts/' . $account_id);
        }
    }
}
