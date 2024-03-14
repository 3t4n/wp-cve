<?php
namespace WPSocialReviews\App\Services\Platforms;

use WPSocialReviews\App\Models\Review;
use WPSocialReviews\App\Services\Platforms\Feeds\CacheHandler;
use WPSocialReviews\App\Services\Platforms\Feeds\Facebook\FacebookFeed;
use WPSocialReviews\App\Services\Platforms\Feeds\Instagram\Common;
use WPSocialReviews\Framework\Support\Arr;

class PlatformData
{
    public $platform;

    private $wpsr_status_option_key = 'wpsr_statuses';

    private $wpsr_revoke_platform_data_option_key = '';

    private $wpsr_app_permission_revoked_status_key = 'wpsr_app_permission_revoked';

    private $wpsr_unused_feed_warning_email_sent_status_key = 'wpsr_unused_feed_warning_email_sent_status';

    public function __construct($platform)
    {
        $this->platform = $platform;
        $this->wpsr_revoke_platform_data_option_key = 'wpsr_'.$this->platform.'_revoke_platform_data';
        $this->registerHooks();
    }

    public function registerHooks()
    {
        $platform_hook = $this->platform === 'facebook_feed' || $this->platform === 'facebook' ? $this->platform : $this->platform.'_feed';

        add_action( 'wpsocialreviews/'.$platform_hook.'_api_connect_response', [$this, 'handlePlatformDataOnApiResponse'], 10, 1 );
        add_action( 'wpsocialreviews/before_display_'.$platform_hook, [$this, 'handleAppPermissionError'] );
        add_action( 'wpsocialreviews/'.$platform_hook.'_app_permission_revoked', [$this, 'handleAppPermissionStatus']);

        add_action( 'wpsocialreviews/before_display_'.$platform_hook, [$this, 'updateLastUsed'] );
        add_action( 'wpsr_scheduled_weekly', [$this, 'maybeDeleteOldData']);

        add_action( 'wpsocialreviews/before_delete_old_data', [$this, 'beforeDeleteOldData'], 10, 1);
    }

    public function handlePlatformDataOnApiResponse( $response )
    {
        if ( is_wp_error( $response ) ) {
            return;
        }

        if ( empty( $response['response'] ) || empty( $response['response']['code'] ) ) {
            return;
        }

        if ( $response['response']['code'] !== 200 ) {
            return;
        }

        (new PlatformErrorManager($this->platform))->removeErrors('platform_data_deleted');

        $statuses_option = get_option( $this->wpsr_status_option_key, [] );

        if (empty( $statuses_option[$this->platform][ $this->wpsr_app_permission_revoked_status_key ] )) {
            return;
        }

        if($this->platform === 'instagram') {
            $api_response = json_decode( Arr::get($response, 'body'), true );
            $api_response_username = Arr::get($api_response, 'username', '');

            $connectedAccounts = (new Common())->findConnectedAccounts();

            $username = '';
            foreach ($connectedAccounts as $account){
                $username = Arr::get($account, 'username', '');
                if($username === $api_response_username){
                    break;
                }
            }

            if ( empty($username) ) {
                return;
            }

            if ( $username !== $api_response_username ) {
                return;
            }
        }

        if($this->platform === 'facebook_feed') {
            $api_response = json_decode( Arr::get($response, 'body'), true );
            $api_response_page_id = (string) Arr::get($api_response, 'id', '');

            $connectedSources = (new FacebookFeed())->getConncetedSourceList();

            $account_id = '';
            foreach ($connectedSources as $source){
                $page_id = Arr::get($source, 'page_id', '');
                $source_page_id = Arr::get($source, 'id', '');

                if($page_id === $source_page_id){
                    $account_id = Arr::get($source, 'account_id');
                }

                if($account_id === $api_response_page_id){
                    break;
                }
            }

            if ( empty($account_id) ) {
                return;
            }

            if ( $account_id !== $api_response_page_id ) {
                return;
            }
        }

        if($this->platform === 'facebook') {
            $api_response = json_decode( Arr::get($response, 'body'), true );
            $api_response_page_id = (string) Arr::get($api_response, 'id', '');
            $connectedAccounts = $this->findConnectedFacebookAccounts();

            $account_id = '';
            foreach ($connectedAccounts as $account){
                $account_id = Arr::get($account, 'account_id', '');
                if ( $account_id === $api_response_page_id ) {
                    break;
                }
            }

            if ( empty($account_id) ) {
                return;
            }

            if ( $account_id !== $api_response_page_id ) {
                return;
            }
        }

        $this->deleteRevokedAccount( $statuses_option );

        (new PlatformErrorManager($this->platform))->resetApiErrors();
    }

    public function handleAppPermissionError()
    {
        $wpsr_statuses = get_option( $this->wpsr_status_option_key, [] );

        if ( empty( $wpsr_statuses[$this->platform][ $this->wpsr_app_permission_revoked_status_key ] ) ) {
            return;
        }

        $revoke_platform_data = get_option( $this->wpsr_revoke_platform_data_option_key, [] );
        $revoke_platform_data_timestamp = Arr::get($revoke_platform_data, 'revoke_platform_data_timestamp', 0);

        if ( !$revoke_platform_data_timestamp ) {
            return;
        }

        $current_timestamp = current_time( 'timestamp', true );
        if ( $current_timestamp < $revoke_platform_data_timestamp ) {
            return;
        }
        
        $this->deletePlatformData();
        $this->sendPlatformDataDeleteEmail();
        $this->deleteRevokedAccount( $wpsr_statuses );
        $platformNameWithType = (new PlatformManager())->getPlatformOfficialName($this->platform, true);

        $error = __( 'An account admin has deauthorized the WP Social Ninja app used to power the WP Social Ninja plugin. The account was not reconnected within the 7 day limit and all '.$platformNameWithType.' account data was automatically deleted on your website due to Facebook data privacy rules.', 'wp-social-reviews' );
        (new PlatformErrorManager($this->platform))->addError('platform_data_deleted', $error);

    }

    public function deleteDataByUser($connectedAccount)
    {
        $userId = Arr::get($connectedAccount, 'user_id', '');
        $has_wpsr_optimize_images_table = get_option( 'wpsr_optimize_images_table_status', false);

        if($has_wpsr_optimize_images_table){
            (new ImageOptimizationHandler())->cleanData($connectedAccount);
        }

        (new PlatformErrorManager($this->platform))->resetApiErrors($userId);
    }

    public function deletePlatformData()
    {
        $cacheHandler = new CacheHandler($this->platform);
        if($this->platform === 'instagram') {
            $connectedAccounts = (new Common())->findConnectedAccounts();
            foreach ($connectedAccounts as $connectedAccount) {
                $user_id = Arr::get($connectedAccount, 'user_id', '');
                $has_app_permission_error = Arr::get($connectedAccount, 'has_app_permission_error', false);

                $errors = (new PlatformErrorManager($this->platform))->getErrors($this->platform);
                $error_accounts = Arr::get($errors, 'accounts', []);

                if($has_app_permission_error && in_array($user_id, array_keys($error_accounts))){
                    $api_errors = Arr::get($error_accounts, $user_id.'.api');
                    if($this->isAppPermissionError($api_errors)){
                        $this->deleteDataByUser($connectedAccount);
                    }
                }
            }
        }

        if($this->platform === 'facebook') {
            $connectedAccounts = $this->findConnectedFacebookAccounts();
            $facebookBusinessInfo = get_option('wpsr_reviews_facebook_business_info');

            foreach ($connectedAccounts as $key => $connectedAccount) {
                $placeId = Arr::get($connectedAccount, 'place_id');
                $has_error = Arr::get($connectedAccount, 'status') === 'error';
                $has_app_permission_error = Arr::get($connectedAccount, 'has_app_permission_error', false);

                if ($has_error && $has_app_permission_error) {
                    Review::where('platform_name', $this->platform)->where('source_id', $placeId)->delete();
                    $cacheHandler->clearCacheByAccount($placeId);
                    unset($facebookBusinessInfo[$placeId]);
                }
            }
            update_option('wpsr_reviews_facebook_business_info', $facebookBusinessInfo);
        }

        if($this->platform === 'facebook_feed') {
            $sourceList = get_option('wpsr_facebook_feed_connected_sources_config', []);
            $connectedAccounts = Arr::get($sourceList, 'sources', []);

            foreach ($connectedAccounts as $connectedAccount) {
                $has_error = Arr::get($connectedAccount, 'status') === 'error';
                $has_app_permission_error = Arr::get($connectedAccount, 'has_app_permission_error', false);

                if ($has_error && $has_app_permission_error) {
                    $pageId = Arr::get($connectedAccount, 'page_id');
                    $cacheHandler->clearCacheByAccount($pageId);
                }
            }
        }
    }

    public function handleAppPermissionStatus()
    {
        $wpsr_statuses = get_option( $this->wpsr_status_option_key, [] );

        // if wpsr_app_permission_revoked is true then we return
        if ( isset( $wpsr_statuses[$this->platform]['wpsr_app_permission_revoked'] ) && true === $wpsr_statuses[$this->platform]['wpsr_app_permission_revoked'] ) {
            return;
        }

        $this->updateAppPermissionRevokedStatus( $wpsr_statuses, true );

        $current_timestamp              = current_time( 'timestamp', true );
        $revoke_platform_data_timestamp = strtotime( '+7 days', $current_timestamp );

        update_option( $this->wpsr_revoke_platform_data_option_key, [
            'revoke_platform_data_timestamp' => $revoke_platform_data_timestamp
        ] );

        $this->sendAppPermissionErrorEmail();
    }

    protected function updateAppPermissionRevokedStatus( $wpsr_statuses, $is_revoked )
    {
        if ( $is_revoked ) {
            $wpsr_statuses[$this->platform][ $this->wpsr_app_permission_revoked_status_key ] = true;
        } else {
            unset( $wpsr_statuses[$this->platform][ $this->wpsr_app_permission_revoked_status_key ] );
        }
        update_option( $this->wpsr_status_option_key, $wpsr_statuses );
    }

    public function deleteRevokedAccount( $statuses_option )
    {
        $this->updateAppPermissionRevokedStatus( $statuses_option, false );

        delete_option( $this->wpsr_revoke_platform_data_option_key );
    }

    public function getAllMetaPlatformConnectedAccounts()
    {
        $connectedIgAccounts = get_option('wpsr_instagram_verification_configs');
        $connectedFbFeedAccounts = get_option('wpsr_facebook_feed_connected_sources_config');
        $connectedFbReviewsAccounts = get_option('wpsr_reviews_facebook_business_info', []);

        $data = wp_parse_args(
            [
                'instagram' => array_keys(Arr::get($connectedIgAccounts, 'connected_accounts', [])),
                'facebook_feed' => array_keys(Arr::get($connectedFbFeedAccounts, 'sources', [])),
                'facebook' => array_keys($connectedFbReviewsAccounts),
            ]
        );

        return $data;
    }

    public function updateLastUsed($accounts = [])
    {
         if(empty($accounts)) return;

         $wpsr_statuses = get_option( $this->wpsr_status_option_key, []);
         $wpsr_statuses = empty($wpsr_statuses) ? [] : $wpsr_statuses;

         $validConnectedAccounts = $this->getAllMetaPlatformConnectedAccounts();

         foreach($accounts as $account_id) {
             if(in_array($account_id, $validConnectedAccounts[$this->platform])) {
                 if (isset($wpsr_statuses[$this->platform]['last_used'][$account_id])) {
                     if ($wpsr_statuses[$this->platform]['last_used'][$account_id] < time() - 3600) {
                         $wpsr_statuses[$this->platform]['last_used'][$account_id] = time();
                     }
                 } else {
                     if (isset($wpsr_statuses[$this->platform]['last_used']) && gettype($wpsr_statuses[$this->platform]['last_used']) === 'integer') {
                         $wpsr_statuses[$this->platform]['last_used'] = [];
                     }
                     $wpsr_statuses[$this->platform]['last_used'][$account_id] = time();
                 }

                 update_option($this->wpsr_status_option_key, $wpsr_statuses);
             }
         }
         (new PlatformErrorManager($this->platform))->removeErrors('unused_feed');

        $unused_feed_warning_email_sent_status_key = Arr::get($wpsr_statuses, $this->platform.'.'.$this->wpsr_unused_feed_warning_email_sent_status_key);
        if($unused_feed_warning_email_sent_status_key){
            unset($wpsr_statuses[$this->platform][$this->wpsr_unused_feed_warning_email_sent_status_key]);
        }

        update_option($this->wpsr_status_option_key, $wpsr_statuses);
    }

    public function deleteLastUsedTime($userId)
    {
        $wpsr_statuses = get_option( $this->wpsr_status_option_key, []);
        $wpsr_statuses = empty($wpsr_statuses) ? [] : $wpsr_statuses;

        if(isset($wpsr_statuses[$this->platform]['last_used'][$userId])) {
            unset($wpsr_statuses[$this->platform]['last_used'][$userId]);
            update_option('wpsr_statuses', $wpsr_statuses );
        }
    }

    public function beforeDeleteOldData($user_id)
    {
        $wpsr_statuses = get_option( $this->wpsr_status_option_key, [] );

        $already_unused_feed_warning_email_sent = Arr::get($wpsr_statuses, $this->platform.'.'.$this->wpsr_unused_feed_warning_email_sent_status_key);
        if($already_unused_feed_warning_email_sent){
            return;
        }

        if( isset($wpsr_statuses[$this->platform]['last_used'][$user_id]) && $wpsr_statuses[$this->platform]['last_used'][$user_id] < time() - ( 53 * DAY_IN_SECONDS ) ){
            $platform = (new PlatformManager())->getPlatformOfficialName($this->platform, true);

            $error = __( 'Your '.$platform.' has been not viewed in the last 53 days. Due to meta data privacy rules, all data for this feed will be deleted in 7 days time. To avoid automated data deletion, simply view the '.$platform.' feed on your website within the next 7 days.', 'wp-social-reviews' );
            (new PlatformErrorManager($this->platform))->addError('unused_feed', $error);

            $this->sendUnusedFeedEmail();

            $wpsr_statuses[$this->platform][$this->wpsr_unused_feed_warning_email_sent_status_key] = true;
            update_option($this->wpsr_status_option_key, $wpsr_statuses);
        }
    }

    public function maybeDeleteOldData()
    {
        $wpsr_statuses = get_option( $this->wpsr_status_option_key, [] );
        $cacheHandler = new CacheHandler($this->platform);

        if( $this->platform === 'instagram' ) {
            $connectedAccounts = (new Common())->findConnectedAccounts();
            foreach ($connectedAccounts as $connectedAccount) {
                $user_id = Arr::get($connectedAccount, 'user_id', '');
                do_action( 'wpsocialreviews/before_delete_old_data', $user_id );

                if( isset($wpsr_statuses[$this->platform]['last_used'][$user_id]) && $wpsr_statuses[$this->platform]['last_used'][$user_id] < time() - ( 60 * DAY_IN_SECONDS ) ) {
                    $this->deleteDataByUser($connectedAccount);
                }
            }
        } else if($this->platform === 'facebook' || $this->platform === 'facebook_feed') {
            if ($this->platform === 'facebook') {
                $connectedAccounts = get_option('wpsr_reviews_facebook_settings', []);
                $facebookBusinessInfo = get_option('wpsr_reviews_facebook_business_info');

                foreach ($connectedAccounts as $connectedAccount) {
                    $placeId = Arr::get($connectedAccount, 'place_id');

                    if (isset($wpsr_statuses[$this->platform]['last_used'][$placeId]) && $wpsr_statuses[$this->platform]['last_used'][$placeId] < time() - (60 * DAY_IN_SECONDS)) {
                        Review::where('platform_name', $this->platform)->where('source_id', $placeId)->delete();
                        $cacheHandler->clearCacheByAccount($placeId);
                    }

                    if (isset($wpsr_statuses[$this->platform]['last_used'][$placeId]) && $wpsr_statuses[$this->platform]['last_used'][$placeId] < time() - (90 * DAY_IN_SECONDS)) {
                        unset($facebookBusinessInfo[$placeId]);
                    }
                }

                update_option('wpsr_reviews_facebook_business_info', $facebookBusinessInfo);
            }

            if ($this->platform === 'facebook_feed') {
                $sourceList = get_option('wpsr_facebook_feed_connected_sources_config', []);
                $connectedAccounts = Arr::get($sourceList, 'sources', []);

                foreach ($connectedAccounts as $connectedAccount) {
                    $pageId = Arr::get($connectedAccount, 'page_id');
                    if (isset($wpsr_statuses[$this->platform]['last_used'][$pageId]) && $wpsr_statuses[$this->platform]['last_used'][$pageId] < time() - (60 * DAY_IN_SECONDS)) {
                        $cacheHandler->clearCacheByAccount($pageId);
                    }
                }
            }
        }
    }

    public function isAppPermissionError($response)
    {
        $error_code    = (int) Arr::get($response, 'error.code', 0);
        $error_subcode = (int) Arr::get($response, 'error.error_subcode', 0);

        //personal account access token or app authorized permissions error
        $error_codes_to_check = array(
            190,
        );

        //business account access token or app authorized permissions error
        $error_subcodes_to_check = array(
            458,
        );

        if (in_array( $error_code, $error_codes_to_check, true )) {
            if (str_contains(Arr::get($response, 'error.message'), 'user has not authorized application') || str_contains(Arr::get($response, 'error.message'), 'Error validating access token')) {
                return true;
            }
            return in_array( $error_subcode, $error_subcodes_to_check, true );
        }

        return false;
    }

    public function findConnectedFacebookAccounts()
    {
        $accounts = get_option('wpsr_reviews_facebook_settings');
        if(empty($accounts)) {
            return [];
        }

        return $accounts;
    }

    protected function sendUnusedFeedEmail()
    {
        if($this->platform !== 'instagram'){
            return;
        }
        $platform = (new PlatformManager())->getPlatformOfficialName($this->platform, true);

        $subject         = sprintf(__( 'There has been a problem with your %s', 'wp-social-reviews' ), $platform);
        $title          = __( 'Action Required Within 7 Days', 'wp-social-reviews' );

        $site_url      = sprintf( '<a href="%s">%s<a/>', esc_url( home_url() ), __( 'your website', 'wp-social-reviews' ) );
        $data['platform'] = $platform;
        $data['message']  = sprintf(__('Your %1$s on %2$s has been not viewed in the last 53 days. Due to meta data privacy rules, all data for this feed will be deleted in 7 days time.', 'wp-social-reviews'), $platform, $site_url);
        $data['direction']  = sprintf(__('To avoid automated data deletion, simply view the %s on your website within the next 7 days.', 'wp-social-reviews'), $platform);

        (new EmailNotification())->send($subject, $title, $data);
    }

    protected function sendAppPermissionErrorEmail()
    {
        if($this->platform !== 'instagram'){
            return;
        }
        $platform = (new PlatformManager())->getPlatformOfficialName($this->platform, true);

        $plugin_settings_link = admin_url( 'admin.php?page=wpsocialninja.php#/');
        $configuration_page = sprintf( '<a href="%s">%s</a>', esc_url( $plugin_settings_link ), esc_html__( 'Configuration Modal', 'wp-social-reviews' ) );

        $subject         = sprintf(__( 'There has been a problem with your %s', 'wp-social-reviews' ), $platform);
        $title          = __( 'Action Required Within 7 Days', 'wp-social-reviews' );

        $site_url      = sprintf( '<a href="%s">%s<a/>', esc_url( home_url() ), __( 'your website', 'wp-social-reviews' ) );

        $data['platform'] = $platform;
        $data['message']  = sprintf(__('An account admin has deauthorized the WP Social Ninja app used to power the WP Social Ninja plugin on %1$s. If the %2$s source is not reconnected within 7 days then all %2$s data will be automatically deleted on your website due to Facebook data privacy rules.', 'wp-social-reviews'), $site_url, $platform);
        $data['direction'] = sprintf( __( 'To prevent the automated deletion of data for the account, please reconnect your source for the plugin platforms %s within 7 days.', 'wp-social-reviews' ), $configuration_page );

        (new EmailNotification())->send($subject, $title, $data);
    }

    protected function sendPlatformDataDeleteEmail()
    {
        if($this->platform !== 'instagram'){
            return;
        }
        $platform = (new PlatformManager())->getPlatformOfficialName($this->platform, true);

        $plugin_settings_link = admin_url( 'admin.php?page=wpsocialninja.php#/');
        $configuration_page = sprintf( '<a href="%s">%s</a>', esc_url( $plugin_settings_link ), esc_html__( 'Configuration Modal', 'wp-social-reviews' ) );

        $subject         = sprintf(__( 'All %s Data has been Removed', 'wp-social-reviews' ), $platform);
        $title          = __( 'An account admin has deauthorized the WP Social Ninja Facebook App used to power the WP Social Ninja plugin.', 'wp-social-reviews' );

        $site_url      = sprintf( '<a href="%s">%s<a/>', esc_url( home_url() ), __( 'your website', 'wp-social-reviews' ) );

        $data['platform'] = $platform;
        $data['message']  = sprintf(__('The page was not reconnected within the 7 day limit and all %1$s data was automatically deleted on %2$s due to Facebook data privacy rules.', 'wp-social-reviews'), $platform, $site_url);
        $data['direction'] = sprintf( __( 'To fix your feeds, reconnect all accounts that were in use on the %1$s %2$s.', 'wp-social-reviews' ), $platform, $configuration_page );

        (new EmailNotification())->send($subject, $title, $data);
    }

    public function sendScheduleEmailReport()
    {
        if($this->platform !== 'instagram'){
            return;
        }
        $emailNotification = new EmailNotification();
        $settings = $emailNotification->getEmailReportSettings();

        $currentDay = date('D');
        $reportingDay = $settings['sending_day'];

        $is_sending_day = [
            'status' => $currentDay == $reportingDay,
        ];
        if (!$is_sending_day['status']) {
            return;
        }

        $platform = (new PlatformManager())->getPlatformOfficialName($this->platform);
        $platformWithType = (new PlatformManager())->getPlatformOfficialName($this->platform, true);

        $plugin_settings_link = admin_url( 'admin.php?page=wpsocialninja.php#/');

        $site_url      = str_replace( array( 'http://', 'https://' ), '', home_url() );
        $subject         = sprintf(__( '%1$s Report for %2$s', 'wp-social-reviews' ), $platformWithType, $site_url);

        $title          = sprintf(__( 'There\'s an Issue with an %s on Your Website', 'wp-social-reviews' ), $platformWithType);

        $data['platform'] = $platformWithType;
        $data['message']  = sprintf(__('An %1$s on your website is currently unable to connect to the %2$s API to retrieve new posts. Rest assured, your feed is still visible using a cached version, but it cannot display the latest posts.', 'wp-social-reviews'), $platformWithType, $platform);
        $data['direction'] = sprintf( __( 'This issue is caused by a problem with your %1$s account connection to the %1$s API. To find out more about the specific problem and receive clear instructions on how to resolve it, kindly visit the %2$sPlatforms Settings Page%3$s of the WP Social Ninja plugin on your website.', 'wp-social-reviews' ), $platform, '<a href="' . esc_url( $plugin_settings_link ) . '">', '</a>' );

        $emailNotification->send($subject, $title, $data);
    }
}
