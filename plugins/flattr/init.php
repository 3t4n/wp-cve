<?php

if ( isset($_GET['page'], $_GET['code']) && !isset($_GET['flattrJAX']) ) {
    if ($_GET['page'] == "flattr/flattr.php") {

        $flattr_domain = 'flattr.com';

        $key = get_option('flattrss_api_key');
        $sec = get_option('flattrss_api_secret');
        $callback = urlencode(home_url()."/wp-admin/admin.php?page=flattr/flattr.php");

        include_once 'flattr_client.php';

        $basic_options = array(
            'client_id'         => $key,
            'client_secret'     => $sec,
            'base_url'          => 'https://api.' . $flattr_domain . '/rest/v2',
            'site_url'          => 'https://' . $flattr_domain,
            'authorize_url'     => 'https://' . $flattr_domain . '/oauth/authorize',
            'access_token_url'  => 'https://' . $flattr_domain . '/oauth/token',

            'redirect_uri'      => $callback,
            'scopes'            => 'thing+flattr',
            'token_param_name'  => 'Bearer',
            'response_type'     => 'code',
            'grant_type'        => 'authorization_code',
            'refresh_token'     => null,
            'access_token'      => null,
            'code'              => null,
            'developer_mode'    => false
        );

        $client = new OAuth2Client($basic_options); 

        try { 

            $access_token = $client->fetchAccessToken($_GET['code']);

            $client = new OAuth2Client(array_merge($basic_options, array(
                'access_token' => $access_token
            )));

            try {

                $user = $client->getParsed('/user');

                if (!isset($user['error'])) {
                    update_user_meta( get_current_user_id(), "user_flattrss_api_oauth_token", $access_token );

                    if (current_user_can('activate_plugins')) {
                        update_option('flattr_access_token', $access_token);
                    }
                }
                if (!current_user_can('activate_plugins')) {
                    header("Status: 307");
                    header("Location: ". home_url()."/wp-admin/users.php?page=flattr/flattr.php?user");
                    flush();
                    exit (0);
                }
            } catch (Exception $e) {}
        } catch (Exception $e) {}
    } 
}