<?php

namespace WPSocialReviews\App\Services\Platforms;

use WPSocialReviews\App\Services\Platforms\Feeds\Instagram\Common;
use WPSocialReviews\Framework\Support\Arr;

class PlatformErrorManager
{
    protected $errors = [];

    protected $platform = '';

    public function __construct($platform = '')
    {
        $this->platform = $platform;
        $this->errors = get_option('wpsr_errors', []);
        $this->errors = empty($this->errors) ? [] : $this->errors;

        if (!isset($this->errors[$this->platform])) {
            if(!empty($platform)){
                $this->errors[$this->platform] = array(
                    'connection' => [],
                    'error_log' => [],
                    'action_log' => [],
                    'upload_dir' => [],
                    'resizing' => [],
                    'hashtag' => [],
                    'revoked' => [],
                    'accounts' => []
                );
            }
        }

        //add_filter('wpsocialreviews/menu_item_platforms', [$this, 'menuItemPlatforms']);
    }

//    public function menuItemPlatforms($menu_item = [])
//    {
//        $has_admin_errors = $this->getAdminErrors();
//        $warning_icon = !empty($has_admin_errors) ? '<span class="update-plugins wpsr-notice-alert"><span>!</span></span>' : '';
//        $menu_item[0] = Arr::get($menu_item, '0').$warning_icon;
//        return $menu_item;
//    }

    public function addError($type = '', $errors = [], $accountDetails = [])
    {
            $userId  = Arr::get($accountDetails, 'user_id');
            $log_item = wp_date( 'd-M-Y H:i:s' ) . ' - ';

            if($type === 'api') {
                $connection_details = [
                    'error_code' => ''
                ];

                $connection_details['critical'] = false;
                if(Arr::get($errors, 'error.code')){
                    $connection_details['error_code'] = Arr::get($errors, 'error.code');

                    $username = Arr::get($accountDetails, 'username');
                    if($username){
                        $connection_details['username'] = $username;
                    }

                    if($this->isCriticalError($errors)){
                        $connection_details['critical'] = true;
                    }

                    if((new PlatformData($this->platform))->isAppPermissionError($errors)){
                        if(Arr::get($this->errors, $this->platform) && is_array($this->errors[$this->platform]['revoked']) && !in_array($userId, $this->errors[$this->platform]['revoked'], true)){
                            $this->errors[$this->platform]['revoked'][] = $userId;
                        }
                    }

                } elseif ( isset( $errors ) && is_wp_error( $errors ) ) {
                    if ( isset($errors->errors) ) {
                        foreach ( $errors->errors as $key => $item ) {
                            $connection_details['error_code'] = $key;
                        }
                        $connection_details['critical'] = true;
                    }
                }

                $this->errors[$this->platform]['accounts'][$userId][$type] = $errors;
                $connection_details['error_message'] = $this->generateErrorMessage($errors, $accountDetails);
                $log_item                              .= $connection_details['error_message']['admin_only'];
                $this->errors[$this->platform]['connection'][$userId] = $connection_details;
            }

            if($type === 'hashtag'){
                $response_error_hashtag = Arr::get($errors, 'error.hashtag');

                $hashtag_details = array(
                    'error_code' => '',
                    'hashtag'  => $response_error_hashtag,
                );

                if(Arr::get($errors, 'error.code')){
                    $hashtag_details['error_code'] = Arr::get($errors, 'error.code');
                } elseif ( isset( $errors ) && is_wp_error( $errors ) ) {
                    if ( isset($errors->errors) ) {
                        foreach ( $errors->errors as $key => $item ) {
                            $connection_details['error_code'] = $key;
                        }
                    }
                }
                $hashtag_details['error_message'] = $this->generateErrorMessage($errors, $accountDetails);
                $log_item                        .= $hashtag_details['error_message']['admin_only'];

                $found_hashtag = false;
                $response_hashtag_error_code = Arr::get($errors, 'error.code');

                $errors_hashtags = Arr::get( $this->errors, $this->platform.'.hashtag');
                if ( $response_error_hashtag && $errors_hashtags ) {
                    foreach ( $errors_hashtags as $this_hashtag_item ) {
                        if ( Arr::get($this_hashtag_item, 'hashtag')
                            && strtolower( $this_hashtag_item['hashtag'] ) === strtolower( $response_error_hashtag )
                            && $this_hashtag_item['error_code'] === $response_hashtag_error_code ) {
                            $found_hashtag = true;
                        }
                    }
                }

                $errors_hashtag_admin_only = Arr::get( $errors_hashtags, '0.error_message.admin_only');
                if(empty($response_error_hashtag) && $errors_hashtags && (strpos($errors_hashtag_admin_only, 'http_request_failed') !== false)){
                    $found_hashtag = true;
                }

                if ( !$found_hashtag ) {
                    $this->errors[$this->platform]['hashtag'][] = $hashtag_details;
                }
            }

            if($type === 'platform_data_deleted') {
                $this->errors[$this->platform]['platform_data_deleted'] = $errors;
                $log_item                              .= $errors;
            }

            if($type === 'image_editor') {
                $this->errors[$this->platform]['resizing'] = $errors;
                $log_item                              .= $errors;
            }

            if($type === 'upload_dir') {
                $this->errors[$this->platform]['upload_dir'] = $errors;
                $log_item                              .= $errors;
            }

            if($type === 'unused_feed') {
                $this->errors[$this->platform]['unused_feed'] = $errors;
                $log_item                              .= $errors;
            }

            $current_log = $this->errors[$this->platform]['error_log'];

            if(is_array($current_log) && count($current_log) >= 10){
                reset($current_log);
                unset($current_log[key($current_log)]);
            }

            $current_log[]                 = $log_item;
            $this->errors[$this->platform]['error_log'] = $current_log;

            update_option('wpsr_errors', $this->errors, false);
    }

    public function generateErrorMessage($errors, $accounts)
    {
        $userName = Arr::get($accounts, 'username', '');

        $return_message = array(
            'error_message'       => '',
            'admin_only'          => '',
            'frontend_directions' => '',
            'time'                => time(),
        );

        if(is_wp_error($errors)){
            $return_message['error_message']       = __('HTTP Error: Unable to connect to the '.ucfirst($this->platform).' API.', 'wp-social-reviews'). ' ' . __( 'As a result, your feed won\'t be able to update at the moment.', 'wp-social-reviews');
            $return_message['admin_only']          = sprintf( __( 'Error connecting to %s:', 'wp-social-reviews' ), Arr::get($errors, 'url') );

            if ( isset( $errors ) && isset($errors->errors) ) {
                $num = count( $errors->errors );
                $i   = 1;
                foreach ( $errors->errors as $key => $item ) {
                    $return_message['admin_only'] .= ' ' . $key . ' - ' . $item[0];
                    if ( $i < $num ) {
                        $return_message['admin_only'] .= ',';
                    }
                    $num++;
                }
            }

            return $return_message;
        }

        if(Arr::get($errors, 'error.message')){
            if( (int) Arr::get($errors, 'error.code') === 100 ){
                $return_message['error_message']       = __( 'Error: Access Token is not valid or has expired.', 'wp-social-reviews' ) . ' ' . __( 'Feed will not update.', 'wp-social-reviews' );
                $return_message['admin_only']          = sprintf( __( 'API error %s:', 'wp-social-reviews' ), $errors['error']['code'] ) . ' ' . $errors['error']['message'];
            } elseif ( (int) Arr::get($errors, 'error.code') === 18 ){
                $return_message['error_message']       = __( 'Error: Hashtag limit of 30 unique hashtags per week has been reached.', 'wp-social-reviews' );
                $return_message['admin_only']          = __( 'If you need to display more than 30 hashtag feeds on your site, consider connecting an additional business account from a separate Instagram Identity and Facebook page. Connecting an additional Instagram business account from the same Facebook page will not raise the limit.', 'wp-social-reviews' );
            } elseif ( (int) Arr::get($errors, 'error.code') === 10 ){
                $return_message['error_message']       = sprintf( __( 'Account(%s): Error: Connected account for the user %s does not have permission to use this feed type.', 'wp-social-reviews' ), $userName, $userName );
                $return_message['admin_only']          = sprintf(__( 'Simply tap on the "Continue with Instagram/Facebook" button on the "%s Configuration" modal to reconnect your account and update its permissions.', 'wp-social-reviews' ), ucfirst($this->platform));
            } elseif ( (int) Arr::get($errors, 'error.code') === 24 ){
                $return_message['error_message']       = __( 'Error: Cannot retrieve posts for this hashtag.', 'wp-social-reviews' );
                $return_message['admin_only']          = $errors['error']['error_user_msg'];
            } elseif ( (int) Arr::get($errors, 'error.code') === 190 && str_contains($errors['error']['message'], 'permission(s) must be granted before impersonating')){
                $return_message['error_message']       = sprintf( __( 'API error %s:', 'wp-social-reviews' ), $errors['error']['code'] ) . ' ' . str_replace( '"', '', $errors['error']['message']);
                $return_message['admin_only']          = sprintf(__( 'Simply tap on the "Continue with Instagram/Facebook" button on the "%s Configuration" modal to reconnect your account and update its permissions.', 'wp-social-reviews' ), ucfirst($this->platform));
            } else {
                $return_message['error_message']       = sprintf(__( 'Account(%1$s): There has been a problem with your account(%1$s) %2$s Feed.', 'wp-social-reviews'), $userName, ucfirst($this->platform));
                $return_message['admin_only']          = sprintf( __( 'API error %s:', 'wp-social-reviews' ), $errors['error']['code'] ) . ' ' . str_replace( '"', '', $errors['error']['message']);
            }
        } else {
            $return_message['error_message'] = __( 'An unknown error has occurred.', 'wp-social-reviews' );
            $return_message['admin_only']    = json_encode( $errors );
        }

        return $return_message;
    }

    public function getErrors($platform)
    {
        return $this->errors[$platform];
    }

    public function getAdminErrors()
    {
        $error_message = [];

        if(!empty($this->errors)){
            foreach($this->errors as $platform => $error) {
                if($this->hasCriticalErrors($platform)) {
                    $error_message[$platform] = $this->getCriticalErrors();
                }

                if(Arr::get($this->errors, $platform.'.upload_dir')){
                    $error_message[$platform]['upload_dir']['error_message'] = $this->errors[$platform]['upload_dir'];
                }

                if(Arr::get($this->errors, $platform.'.unused_feed')){
                    $error_message[$platform]['unused_feed']['error_title']   = __( 'Action Required Within 7 Days', 'wp-social-reviews' );
                    $error_message[$platform]['unused_feed']['error_message'] = $this->errors[$platform]['unused_feed'];
                    $error_message[$platform]['unused_feed']['direction_url_text'] = '';
                    $error_message[$platform]['unused_feed']['direction_url'] = '';
                }

                if(Arr::get($this->errors, $platform.'.platform_data_deleted')){
                    $accounts_revoked = $this->getRevokedAccounts($platform);
                    $accounts_revoked = empty($accounts_revoked) ? '' : $accounts_revoked;
                    $platformNameWithType = (new PlatformManager())->getPlatformOfficialName($platform, true);

                    $error_message[$platform]['platform_data_deleted']['error_type']  = 'platform_data_deleted';
                    $error_message[$platform]['platform_data_deleted']['main_title']  =  __( ''.$platformNameWithType.' account('.$accounts_revoked.') data has been removed:', 'wp-social-reviews' );
                    $error_message[$platform]['platform_data_deleted']['error_message'] = str_replace('account', 'account('.$accounts_revoked.')', $this->errors[$platform]['platform_data_deleted']);
                    $error_message[$platform]['platform_data_deleted']['direction_url_text'] = __('To fix your feeds, reconnect all accounts that were in use on the platform configuration.', 'wp-social-reviews');
                    $error_message[$platform]['platform_data_deleted']['direction_url'] = '';
                }
            }
        }
        return $error_message;
    }

    public function getFrontEndErrors()
    {
        $error_messages = [];
        if( Arr::get($this->errors, $this->platform.'.connection') ){
            foreach ($this->errors[$this->platform]['connection'] as $index => $connection_error){
                if(!empty($connection_error)){
                    $error_messages[$index] = Arr::get($connection_error, 'error_message');
                }
            }
        }

        if( Arr::get($this->errors, $this->platform.'.connection.configuration') ){
            $error_messages[] = $this->errors[$this->platform]['configuration'];
        }

        $accounts_revoked = $this->getRevokedAccounts($this->platform);
        if( Arr::get($this->errors, $this->platform.'.platform_data_deleted') ){
            $error_messages['error_message']['error_message'] = str_replace('account', 'account('.$accounts_revoked.')', $this->errors[$this->platform]['platform_data_deleted']);
            $error_messages['error_message']['admin_only'] = 'To fix your feeds, reconnect all accounts that were in use on the platform configuration.';
        }

        if( Arr::get($this->errors, $this->platform.'.hashtag.0') ){
            $hashtag_error_code_24         = array();
            $hashtag_error_code_24_message = array();
            foreach ( $this->errors[$this->platform]['hashtag'] as $index => $hashtag_error ) {
                if ( $hashtag_error['error_code'] === 24 ) {
                    if ( ! in_array( $hashtag_error['hashtag'], $hashtag_error_code_24, true ) ) {
                        $hashtag_error_code_24[] = $hashtag_error['hashtag'];
                    }
                    if ( empty( $hashtag_error_code_24_message ) ) {
                        $hashtag_error_code_24_message['hashtag']  = $hashtag_error['hashtag'];
                        $hashtag_error_code_24_message['admin_only']  = $hashtag_error['error_message']['admin_only'];
                        $hashtag_error_code_24_message['error_message']  = $hashtag_error['error_message']['error_message'];
                    }
                } else {
                    $error_messages[] = $hashtag_error['error_message'];
                }
            }

            if ( !empty( $hashtag_error_code_24_message ) ) {
                $error_messages[]  = $hashtag_error_code_24_message;
            }
        }

        return $error_messages;
    }

    public function hasCriticalErrors($platform)
    {
        if(!empty($platform)){
            if( Arr::get($this->errors, $platform.'.connection') ){
                foreach ($this->errors[$platform]['connection'] as $connection_error){
                    if(Arr::get($connection_error, 'critical')){
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function getRevokedAccounts($platform)
    {
        $accounts_revoked = Arr::get($this->errors, $platform.'.revoked');

        if(!empty($accounts_revoked) && is_array($accounts_revoked)){
            $accounts_revoked = implode( ', ', $accounts_revoked );
        }

        return $accounts_revoked;
    }

    public function getCriticalErrors()
    {
        $platforms = (new PlatformManager())->feedPlatforms();

        $error_message = [];
        foreach($platforms as $platform) {
            if(isset($this->errors[$platform])){
            if($this->hasCriticalErrors($platform)) {
                $accounts_revoked_string = '';
                $accounts_revoked = '';
                if (!empty($this->errors[$platform]['revoked'])) {
                    $accounts_revoked = $this->getRevokedAccounts($platform);
                    //$accounts_revoked_string = sprintf( __( 'Instagram Feed related data for the account(s) %s was removed due to permission for the WP Social Ninja App on Facebook or Instagram being revoked. <br><br> To prevent the automated data deletion for the account, please reconnect your account within 7 days.', 'wp-social-reviews' ), $accounts_revoked );
                }

                if (isset($this->errors[$platform]['connection'])) {
                    foreach ($this->errors[$platform]['connection'] as $index => $connection_errors) {
                        if (isset($connection_errors['critical'])) {
                            $errors = $this->getErrors($platform);

                            $revoke_platform_data = get_option('wpsr_' . $platform . '_revoke_platform_data', []);
                            $revoke_platform_data_timestamp = Arr::get($revoke_platform_data, 'revoke_platform_data_timestamp');

                            $username = Arr::get($errors, 'connection.' . $index . '.username');
                            $sub_title = '';
                            if ($revoke_platform_data_timestamp) {
                                $revoke_platform_data_timestamp = date_i18n('l, d F Y', $revoke_platform_data_timestamp);
                                $sub_title = sprintf(__('(%1$s account data will delete in %2$s)', 'wp-social-reviews'), $username, $revoke_platform_data_timestamp);
                            }

                            $error_message[$index]['error_type'] = 'connection';
                            $error_message[$index]['main_title'] = ucfirst($platform) . __(' Feed is currently experiencing an error that may prevent your feeds from updating. This issue is likely caused by the following reasons:', 'wp-social-reviews');
                            if ($errors['connection'][$index]['error_code'] === 190 && !str_contains(Arr::get($errors, 'connection.'.$index.'.error_message.error_message'), 'permission(s) must be granted before impersonating')) {
                                $error_message[$index]['error_title'] = sprintf(__('Error in Account %s:', 'wp-social-reviews'), $username);
                                $error_message[$index]['sub_title'] = sprintf(__('Action required within 7 days %s', 'wp-social-reviews'), $sub_title);
                                $error_message[$index]['error_message'] = sprintf(__('An account ("%1$s") admin has deauthorized the WP Social Ninja app used to power the WP Social Ninja plugin. If the %2$s source is not reconnected within 7 days then all %2$s data will be automatically deleted on your website for this account (ID: %3$s) due to Facebook data privacy rules. To prevent the automated data deletion for the source, please reconnect your account within 7 days.', 'wp-social-reviews'), $index, ucfirst($platform), $accounts_revoked);
                                $error_message[$index]['direction_url_text'] = __('More Information', 'wp-social-reviews');
                                $error_message[$index]['direction_url'] = 'https://wpsocialninja.com/docs/instagram-api-error-message-reference-social-feeds-wp-social-ninja/#8-toc-title';
                            } else {
                                $connection_error_message = $errors['connection'][$index]['error_message'];
                                $error_message[$index]['error_message'] = $connection_error_message['error_message'];
                                $error_message[$index]['direction_url_text'] = $connection_error_message['admin_only'];
                                $error_message[$index]['direction_url'] = '';
//                                if ( !empty($accounts_revoked_string) ) {
//                                    $error_message[$index]['error_message'] = $accounts_revoked_string;
//                                }
                            }
                        }

                        if (Arr::get($error_message, $index.'.error_message')) {
                            $error_message[$index]['error_message'] = str_replace('Please read the Graph API documentation at https://developers.facebook.com/docs/graph-api', '', Arr::get($error_message, $index.'.error_message'));
                        } else {
                            $error_message[$index]['error_message'] = '';
                        }

                    }

                }
            }
            }
        }

        return $error_message;
    }

    public function connectedAccountHasError($accounts = [], $account_id = null)
    {
        $connected_accounts = (new Common())->findConnectedAccounts();
        if(empty($connected_accounts) || empty($accounts)){
            return -1;
        }

        foreach ($accounts as $index => $account){

            if((int)$account_id === (int)$account && Arr::get($connected_accounts, $account.'.status') === 'error'){
                return $account;
            }
        }

        return -1;
    }

    public function isCriticalError($errors)
    {
        $error_code = Arr::get($errors, 'error.code');
        $critical_codes = array(
            803, // ID doesn't exist
            100, // access token or permissions
            190, // app removed
            10, // app permissions or scopes
        );

        return in_array( $error_code, $critical_codes, true );
    }

    public function hasCriticalError($platform)
    {
        if( Arr::get($this->errors, $platform.'.connection') ){
            foreach ($this->errors[$platform]['connection'] as $index => $connection_error){
                if(Arr::get($connection_error, 'critical')){
                   return true;
                }
            }
        }
        return false;
    }

    public function addActionLog($log_item)
    {
        $current_log = $this->errors[$this->platform]['action_log'];
        if(is_array($current_log) && count($current_log) >= 10){
            reset($current_log);
            unset($current_log[key($current_log)]);
        }

        $current_log[] = wp_date( 'd-M-Y H:i:s' ) . ' - ' . $log_item;
        $this->errors[$this->platform]['action_log'] = $current_log;
        update_option( 'wpsr_errors', $this->errors, false );
    }

    public function resetApiErrors($userId = null)
    {
        $connection_error = Arr::get($this->errors, $this->platform.'.connection.'.$userId);
        if($connection_error){
            unset($this->errors[$this->platform]['connection'][$userId]);
        }

        $accounts_error = Arr::get($this->errors, $this->platform.'.accounts.'.$userId);
        if($accounts_error){
           unset($this->errors[$this->platform]['accounts'][$userId]);
        }

        update_option( 'wpsr_errors', $this->errors, false );
    }

    public function removeErrors($type = '', $connected_accounts = [])
    {
       $update = false;

        if($type !== 'connection' && !empty($this->errors[$this->platform][$type])){
            $this->errors[$this->platform][$type] = [];
            $this->addActionLog('Cleared ' . $type . ' error.');
            $update = true;
        }

       if(!empty($connected_accounts)){
           if(Arr::get($connected_accounts, 'username')){
               $update = $this->maybeRemoveErrors($connected_accounts, $type);
           } else{
               foreach ($connected_accounts as $account){
                   $update = $this->maybeRemoveErrors($account, $type);
               }
           }
       }

       if($update){
           update_option( 'wpsr_errors', $this->errors, false );
       }
    }

    public function maybeRemoveErrors($account, $type)
    {
        $update = false;
        $user_id = Arr::get($account, 'user_id');

        if($type === 'connection' && !empty($this->errors[$this->platform][$type][$user_id])){
            unset($this->errors[$this->platform][$type][$user_id]);
            $this->addActionLog('Cleared ' . $type . ' error.');
            $update = true;
        }

        if($this->removeConnectedAccountError($account, $type)){
            $this->addActionLog('Cleared connected account ' . $account['username'] .' error.');
            $update = true;
        }

        if($type === 'connection'){
            if($this->removeConnectedAccountError($account, 'api')){
                $this->addActionLog('Cleared connected account ' . $account['username'] .' error.');
                $update = true;
            }
        }

        if(!empty($this->errors[$this->platform]['revoked'])){
            if( ( $key = array_search($account['user_id'], $this->errors[$this->platform]['revoked']) ) !== false){
                unset($this->errors[$this->platform]['revoked'][$key]);
                $update = true;
            }
        }

        return $update;
    }

    public function removeConnectedAccountError($clearing_account = [], $clearing_error_type = '')
    {
        $cleared = false;
        if(isset($this->errors[$this->platform]['accounts'])){
            if(!isset($clearing_account['user_id'])){
                return $cleared;
            }

            $clearing_account_id = Arr::get($clearing_account, 'user_id');

            foreach ($this->errors[$this->platform]['accounts'] as $account_id => $accounts){
                foreach ($accounts as $error_type => $account){
                    if((string) $account_id === (string) $clearing_account_id && $error_type === $clearing_error_type){
                        unset($this->errors[$this->platform]['accounts'][$account_id][$error_type]);
                        $cleared = true;
                    } else {
                        if(Arr::get($account, 'username')){
                            if($account['username'] === Arr::get($clearing_account, 'username') && $error_type === $clearing_error_type){
                                unset($this->errors[$this->platform]['accounts'][$account_id][$error_type]);
                                $cleared = true;
                            }
                        }
                    }
                }
            }

        }
        return $cleared;
    }

    public function removeAllErrors()
    {
        delete_option('wpsr_errors');
    }
}