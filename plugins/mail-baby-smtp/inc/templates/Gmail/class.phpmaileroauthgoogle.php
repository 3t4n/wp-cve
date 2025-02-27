<?php
/*
define ( 'SCOPES', implode ( ' ', array (
        \Google_Service_Gmail::GMAIL_COMPOSE 
) ) );
*/
class GmailXOAuth2 {

    private $oauthUserEmail = '';
    private $oauthRefreshToken = '';
    private $oauthClientId = '';
    private $oauthClientSecret = '';

    public function __construct(
        $UserEmail,
        $ClientSecret,
        $ClientId,
        $RefreshToken
    ) {
        $this->oauthClientId = $ClientId;
        $this->oauthClientSecret = $ClientSecret;
        $this->oauthRefreshToken = $RefreshToken;
        $this->oauthUserEmail = $UserEmail;
    }

        /*
         * @returns $google_client object
         */
    public static function getClient() {

        $google_client = new \Google_Client ();
        $options = MAIL_BABY_SMTP_get_option();
        $clientId = $options['oauth_client_id'];
        $clientSecret = $options['oauth_client_secret'];
        //$google_client->setApplicationName ( APPLICATION_NAME );
        $google_client->setScopes ( 'https://mail.google.com/' );
        $google_client->setClientId($clientId);
        $google_client->setClientSecret($clientSecret);
        $redirect_url = admin_url("options-general.php?page=mail-baby-smtp-settings&action=oauth_grant");
        $google_client->setRedirectUri($redirect_url);
        //$google_client->setAuthConfigFile ( APP_CREDENTIALS );
                /* Its a must to request for 'offile access type' */
        $google_client->setAccessType ( 'offline' );

        return $google_client;

    }

        /*
         * checks the credentials for the access token, if present; it returns that
         * or refreshes it if expired. 
         * if the credentials file is empty, it will return the authorization url to which you must redirect too 
         * for user user authorization 
         */
    public static function authenticate () {

        $client = GmailXOAuth2::getClient();
        $options = MAIL_BABY_SMTP_get_option();
        if (!empty($options['oauth_access_token'])) {

            $accessToken = $options['oauth_access_token'];

        } else {

            return array( 'authorization_uri' => $client->createAuthUrl() );

        }

        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {

            $client->refreshToken($client->getRefreshToken());

            $new_accessToken = $client->getAccessToken();

            $options['oauth_access_token'] = json_encode($new_accessToken); //json_encode the token since getAccessToken() no longer encodes it
            
            MAIL_BABY_SMTP_update_option($options);
            
            return json_decode($new_accessToken, true);

        }

        return json_decode($accessToken, true);

    }

        /*
         * call this in your callback (redirect url), code the authorization for and exchanges it for an 
         * access token. 
         * it stores this in the token file for future reference.
         * if the user denies your app access, it will still return just that error and not write to the token file
         */
    public static function resetCredentials( $authCode ) {

        $client = GmailXOAuth2::getClient();

        $accessToken = $client->authenticate( $authCode );
        
        $options = MAIL_BABY_SMTP_get_option();

        $allowed_html = array(
            'a'      => array(
                'class' => array(),
                'id' => array(),
                'href'  => array(),
                'title' => array(),
            ),
            'script'     => array(),
            'em'     => array(),
            'img' => array(),
            'td' => array(),
            'tr' => array()
        );
        
        
        if(!empty($accessToken)) {
            if(isset($accessToken['error']) || isset($accessToken['error_description'])){
                $content = '<div id="message" class="error"><p><strong>';
                $content = __('Error: '.$accessToken['error'].', Error Description: '.$accessToken['error_description'], 'mail-baby-smtp');
                $content = '</strong></p></div>';
                echo wp_kses($content, $allowed_html);
                return false;
            }
            //json_encode the token since authenticate() function returns it as an array now. It used to call getAccessToken() before which would return in json_encoded format.
            $options['oauth_access_token'] = json_encode($accessToken); 
            MAIL_BABY_SMTP_update_option($options);
            return $accessToken;

        }

        return false;

    }

    /**
     * GetOauth64
     * 
     * encode the user email related to this request along with the token in base64
     * this is used for authentication, in the phpmailer smtp class
     * 
     * @return string
     */
    public function getOauth64 () {

        $client = GmailXOAuth2::getClient();
        $options = MAIL_BABY_SMTP_get_option();
        
        if (!empty($options['oauth_access_token'])) {

            $accessToken = $options['oauth_access_token'];

        } else {

            return false;

        }

        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {

            $client->refreshToken($client->getRefreshToken());

            $accessToken = $client->getAccessToken();

            $options['oauth_access_token'] = json_encode($accessToken); //json_encode the token since getAccessToken() no longer encodes it
            MAIL_BABY_SMTP_update_option($options);

        }

        $offlineToken = GmailXOAuth2::request_offline_token();

        return base64_encode("user=" . $this->oauthUserEmail . "\001auth=Bearer " . $offlineToken . "\001\001");

    }

        /*
         * this makes a request to the Google API, using Curl to get another access token that we can use 
         * for authentication on the Gmail API when sending messages
         */
    private function request_offline_token() {

        $token_uri = "https://accounts.google.com/o/oauth2/token";
        $parameters = array(
                "grant_type" => 'refresh_token',
                "client_id" => $this->oauthClientId,
                "client_secret" => $this->oauthClientSecret,
                "refresh_token" => $this->oauthRefreshToken
        );

        // $curl = curl_init($token_uri);

        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        //$response = curl_exec($curl);

        $response = wp_remote_post( $token_uri, array(
			'body'    => $parameters,
			'headers' => array(
				'Accept'=> 'application/json',
				'Content-Type' => 'application/json',
				'X-API-KEY:'.$api_key
			)
		) );
		$res = wp_remote_retrieve_body($response);
        $data = wp_remote_retrieve_body($response);
        if(!is_wp_error( $res ) || wp_remote_retrieve_response_code( $res ) == 200){

			$parsed = json_decode($res, 1);
			if ( isset($parsed['status']) ){
				return $parsed['status'];
			}
		} else {
			return 'Some error was occured: ' . error_log( print_r( $res, true ) );
		}
		do_action( 'wp_mail_failed', new \WP_Error( 'wp_mail_failed', $res) );
        // curl_close($curl);

        return json_decode($data, true);

    }

}
