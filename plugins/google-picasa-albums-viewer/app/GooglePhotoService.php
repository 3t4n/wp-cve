<?php

namespace App;

use Google\Auth\Credentials\UserRefreshCredentials;
use Google\Photos\Library\V1\PhotosLibraryClient;

class GooglePhotoService
{
    private $gapi;
    public $saveTokenFunction = null;
    private $clientId;
    private $clientSecret;
    private $siteurl;

    private $scopes;

	/**
	 * Create the Google API Client.
	 *
	 * @since    4.0.0
	 */
    private function createGoogleClient()
    {
        $gapi = get_option('cws_gpp_gapi');
        $this->clientId = $gapi['client_id'];
        $this->clientSecret = $gapi['client_secret'];
        
        $this->siteurl = get_site_url();
        $this->scopes = 'https://www.googleapis.com/auth/photoslibrary.readonly https://www.googleapis.com/auth/photoslibrary.sharing';

        $client = new \Google_Client();
        $client->setApplicationName('GooglePhotoSample');
        $client->setScopes($this->scopes);
        $client->setClientId($this->clientId);
        $client->setClientSecret($this->clientSecret);
        $client->setRedirectUri($this->siteurl . '/wp-admin/admin.php?page=cws_gpp');
        $client->setAccessType('offline');

        return $client;
    }

    /**
	 * Create the Autorization URL to take user to Google.
	 *
	 * @since    4.0.0
	 */
    public function createAuthUrl()
    {   
        $client = $this->createGoogleClient();

        return $client->createAuthUrl();
    }

    /**
	 * Get the Access Token from Google.
	 *
	 * @since    4.0.0
	 */
    public function getAccessToken($authCode)
    {
        $client = $this->createGoogleClient();
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        return $accessToken;
    }

    /**
	 * Create Google Photos API Client
	 *
	 * @since    4.0.0
	 */
    public function getPhotosLibraryClient($accessToken)
    {
        $client = $this->createGoogleClient();
        $client->setAccessToken($accessToken, true);

        if($client->isAccessTokenExpired())
        {
            $refresh_token = get_option('cws_gpp_refresh_token');
            $client->fetchAccessTokenWithRefreshToken($refresh_token);
        }
        
        $authCredentials = new UserRefreshCredentials('https://www.googleapis.com/auth/photoslibrary.readonly https://www.googleapis.com/auth/photoslibrary.sharing', [
            "client_id" => $client->getClientId(),
            "client_secret" => $client->getClientSecret(),
            "refresh_token" => $refresh_token
        ]);

        return new PhotosLibraryClient(['credentials' => $authCredentials]);
    }

}
