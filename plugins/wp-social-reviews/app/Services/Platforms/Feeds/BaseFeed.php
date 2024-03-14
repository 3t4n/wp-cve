<?php

namespace WPSocialReviews\App\Services\Platforms\Feeds;

use WPSocialReviews\App\Models\Cache;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\App\Services\DataProtector;
use WPSocialReviews\App\Services\Platforms\PlatformManager;

abstract class BaseFeed
{
    public $platform;
    public function __construct($platform)
    {
        $this->platform = $platform;
    }

    public function registerHooks()
    {
        add_filter('wpsocialreviews/available_valid_feed_platforms', array($this, 'pushValidPlatform'));

        //handle verification credentials
        add_action('wpsocialreviews/verify_credential_' . $this->platform, array($this, 'handleCredential'));
        add_action('wpsocialreviews/get_verification_configs_' . $this->platform, array($this, 'getVerificationConfigs'));
        add_action('wpsocialreviews/clear_verification_configs_' . $this->platform, array($this, 'clearVerificationConfigs'));

        //handle editor meta
        add_action('wpsocialreviews/get_editor_settings_' . $this->platform, array($this, 'getEditorSettings'));
        add_action('wpsocialreviews/edit_editor_settings_' . $this->platform, array($this, 'editEditorSettings'), 10, 2);
        add_action('wpsocialreviews/update_editor_settings_' . $this->platform, array($this, 'updateEditorSettings'), 10, 2);

        //handle advance settings
        add_action('wpsocialreviews/save_advance_settings_' . $this->platform, array($this, 'saveAdvanceSettings'), 10, 2);
        add_action('wpsocialreviews/get_advance_settings_' . $this->platform, array($this, 'getAdvanceSettings'));
        add_action('wpsocialreviews/clear_cache_' . $this->platform, array($this, 'clearCache'));

        //handle cron job
        add_action('wpsr_' . $this->platform . '_feed_update', array($this, 'doCronEvent'));
    }

    /**
     * Get Advance Settings
     *
     * @return json
     * @since 1.2.5
     */
    public function getAdvanceSettings()
    {
        $settings = get_option('wpsr_' . $this->platform . '_global_settings');
        $settings = $this->formatPlatformGlobalSettings($settings);

        $platforms = ['tiktok'];
        if(!in_array($this->platform, $platforms)) {
            if(isset($settings['app_settings'])) {
                unset($settings['app_settings']);
            }
        }

        wp_send_json_success([
            'message'  => __('success', 'wp-social-reviews'),
            'settings' => $settings
        ], 200);
    }

    public function saveAdvanceSettings($settings = array())
    {
        $settings = $this->formatPlatformGlobalSettings($settings);
        update_option('wpsr_' . $this->platform . '_global_settings', $settings, 'no');

        if($this->platform === 'instagram'){
            $has_wpsr_optimize_images_table = get_option( 'wpsr_optimize_images_table_status', false);
            $optimized_images = Arr::get($settings, 'global_settings.optimized_images');
            $older_version = get_option('_wp_social_ninja_version', '3.9.4');

            if(version_compare($older_version, '3.10.0', '<=') && $optimized_images === 'true' && !$has_wpsr_optimize_images_table){
                \WPSocialReviews\Database\Migrations\ImageOptimizationMigrator::migrate();
            }
        }

        update_option('wpsr_' . $this->platform . '_global_settings', $settings, 'no');
        wp_send_json_success([
            'message' => __('Settings Saved Successfully', 'wp-social-reviews'),
        ], 200);
    }

    public function formatPlatformGlobalSettings($settings = [])
    {
//        $protector = new DataProtector();

        $expiration = 60*60*6;
        if($this->platform === 'youtube'){
            $expiration *= 12;
        }

        $configs = [
            'global_settings'   => [
                'caching_type'        => Arr::get($settings, 'global_settings.caching_type', 'background'),
                'expiration'          => Arr::get($settings, 'global_settings.expiration', $expiration),
                'optimized_images'    => Arr::get($settings, 'global_settings.optimized_images', 'false'),
                'is_enabled_platform' => (bool) (new PlatformManager())->isActivePlatform($this->platform)
            ],
        ];

//        if ($this->platform === 'tiktok') {
//            $redirectUri = rest_url('wpsocialreviews/tiktok_callback');
//            $redirectUri = 'https://gutendev.com/wp-json/wpsocialreviews/tiktok_callback';
//
//            $enableApp = Arr::get($settings, 'app_settings.enable_app', 'false');
//            $clientId = Arr::get($settings, 'app_settings.client_id', '');
//            $clientSecret = Arr::get($settings, 'app_settings.client_secret', '');
//
//            if($enableApp === 'true' && (empty($clientId) || empty($clientSecret) || empty($redirectUri))) {
//                wp_send_json_error([
//                    'message' => __('Please provide Client Key and Client Secret Key.', 'wp-social-reviews'),
//                ], 423);
//            }
//
//            $configs['app_settings'] =  [
//                'enable_app'    => $enableApp,
//                'client_id'     => !empty($clientId) ? $protector->encrypt($clientId) : '',
//                'client_secret' => !empty($clientSecret) ? $protector->encrypt($clientSecret) : '',
//                'redirect_uri'  => $redirectUri,
//            ];
//        }

        return $configs;
    }

	public function doCronEvent()
	{
		$expiredCaches = $this->cacheHandler->getExpiredCaches();

		if ($expiredCaches) {
			$caches = [];

			foreach ($expiredCaches as $name => $cache) {
				$caches[] = [
					'option_name' => $name,
					'option_value' => $cache
				];
			}

			$this->updateCachedFeeds($caches);
		}
	}
}
