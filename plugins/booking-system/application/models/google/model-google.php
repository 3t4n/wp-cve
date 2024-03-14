<?php

/*
 * Title                   : Pinpoint Booking System
 * File                    : application/models/model-google.php
 * Author                  : Dot on Paper
 * Copyright               : © 2016-2020 Dot on Paper
 * Website                 : https://www.dotonpaper.net
 * Description             : Google model PHP class.
 */

if (!class_exists('DOTModelGoogle')){
    class DOTModelGoogle{
        /*
         * Constructor
         *
         * @usage
         *      The constructor is called when a class instance is created.
         *
         * @params
         *      -
         *
         * @post
         *      -
         *
         * @get
         *      -
         *
         * @sessions
         *      -
         *
         * @cookies
         *      -
         *
         * @constants
         *      -
         *
         * @globals
         *      -
         *
         * @functions
         *      -
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      -
         *
         * @return_details
         *      -
         *
         * @dv
         *      -
         *
         * @tests
         *      -
         */
        function __construct(){
        }

        /*
         *
         * @usage
         *
         * @params
         *      -
         *
         * @post
         *      -
         *
         * @get
         *      -
         *
         * @sessions
         *      -
         *
         * @cookies
         *      -
         *
         * @constants
         *      -
         *
         * @globals
         *      DOT (object): DOT framework main class variable
         *
         * @functions
         *      -
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      -
         *
         * @return_details
         *      -
         *
         * @dv
         *      -
         *
         * @tests
         *
         */
        function connect($calendar_id){
            global $DOT;

            include_once $DOT->paths->abs.'/application/libraries/google/vendor/autoload.php';

            if ($this->credentialsDb($calendar_id) == '0'){
                return false;
            }
            else{
                $client = new Google_Client();
                $client->setApplicationName('Pinpoint Calendars');
                $client->setScopes(Google_Service_Calendar::CALENDAR);
                $client->setAuthConfig(json_decode('{"web":'.$this->credentialsDb($calendar_id).'}',
                                                   true));
                $client->setAccessType('offline');
                $client->setPrompt('select_account consent');
                // Get the token data from database.

                if ($this->getToken() !== null){
                    $accessToken = json_decode($this->getToken(),
                                               true);
                    $client->setAccessToken($accessToken);
                }
                else{
                    $authUrl = $client->createAuthUrl();
                    $link = sprintf(wp_kses(__('<a href="%s" style="background-color: #dbdfea; border-radius: 2px; color: #3e3f40; display: block; font-size: 16px; line-height: 24px; margin: auto; max-width: 200px; min-width: 200px; padding: 12px 0; text-align: center; text-decoration: none; width: 200px;">Google Sync</a>',
                                               '$text_domain'),
                                            array('a' =>
                                                          array(
                                                                  'href' => array(),
                                                                  'style' => array()))),
                                    esc_url($authUrl));
                    echo $link;
                    // exit;
                }
                // If there is no previous token or it's expired.
                if (isset($client)
                        && $client->isAccessTokenExpired()){
                    // Refresh the token if possible, else fetch a new one.

                    if ($client->getRefreshToken()){
                        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

                        //update token
                        $this->saveToken($client->getAccessToken(),
                                         $client->getRefreshToken());
                    }
                    elseif (isset($_GET['code'])){
                        // Request authorization from the user.

                        $authCode = $_GET['code'];

                        // Exchange authorization code for an access token.
                        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                        $client->setAccessToken($accessToken);

                        // Check to see if there was an error.
                        if (array_key_exists('error',
                                             $accessToken)){
                            throw new Exception(join(', ',
                                                     $accessToken));
                        }

                        //update token
                        $this->saveToken($client->getAccessToken(),
                                         $client->getRefreshToken());
                    }
                    else{
                        return false;
                    }
                }

                return $client;
            }
        }

        function getToken(){
            global $DOT;

            $token = $DOT->db->row($DOT->db->safe('SELECT VALUE FROM '.$DOT->tables->settings_calendar.' WHERE name=%s',
                                                  array('google_token')));
            return isset($token->VALUE)
                    ? $token->VALUE
                    : null;
        }

        /*
         * token update function
         */
        function saveToken($accessToken,
                           $refreshToken){
            global $DOT;

            $token = new stdClass();
            $token->access_token = $accessToken['access_token'];
            $token->expires_in = 3599;
            $token->refresh_token = $refreshToken;
            $token->scope = 'https://www.googleapis.com/auth/calendar';
            $token->token_type = "Bearer";
            $token->created = $accessToken['created'];

            /*
             * Save the token into the database.
             */
            if (!$this->getToken()){
                $DOT->db->insert($DOT->tables->settings_calendar,
                                 array('name' => 'google_token',
                                       'value' => json_encode($token)));
            }
            else{
                $DOT->db->update($DOT->tables->settings_calendar,
                                 array('name' => 'google_token',
                                       'value' => json_encode($token)),
                                 array('calendar_id' => 0));
            }
        }

        /*
         * Get credentials from db
         */
        function credentialsDb($calendar_id){
            global $DOT;

            $cred = $DOT->db->results($DOT->db->safe('SELECT * FROM '.$DOT->tables->settings_calendar.' WHERE (name=%s AND CALENDAR_ID='.$calendar_id.') OR (name=%s AND CALENDAR_ID='.$calendar_id.') OR (name=%s AND CALENDAR_ID='.$calendar_id.') OR (name=%s AND CALENDAR_ID='.$calendar_id.')',
                                                     array('google_client_id',
                                                           'google_client_secret',
                                                           'google_project_id',
                                                           'google_token_uri')));
            for ($i = 0; $i<4; $i++){
                if ($cred[$i]->name == 'google_client_id'){
                    $client_id = $cred[$i]->value;
                }
                if ($cred[$i]->name == 'google_client_secret'){
                    $client_secret = $cred[$i]->value;
                }
                if ($cred[$i]->name == 'google_project_id'){
                    $project_id = $cred[$i]->value;
                }
                if ($cred[$i]->name == 'google_token_uri'){
                    $google_token_uri = $cred[$i]->value;
                }
            }
            if ($client_id == '' || $client_secret == '' || $project_id == ''){
                return 0;
            }

            $credentials = new stdClass();
            $credentials->client_id = $client_id;
            $credentials->project_id = $project_id;
            $credentials->auth_uri = "https://accounts.google.com/o/oauth2/auth";
            $credentials->token_uri = "https://oauth2.googleapis.com/token";
            $credentials->auth_provider_x509_cert_url = "https://www.googleapis.com/oauth2/v1/certs";
            $credentials->client_secret = $client_secret;
            $credentials->redirect_uris = [$google_token_uri];

            return json_encode($credentials);
        }

    }
}