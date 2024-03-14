<?php

namespace WPSocialReviews\App\Hooks\Handlers;

use WPSocialReviews\App\Models\OptimizeImage;
use WPSocialReviews\App\Services\Maintenance;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\App\Services\Platforms\PlatformManager;

class SchedulerHandler
{
    private $feed_platforms = ['instagram', 'twitter', 'youtube', 'facebook_feed' , 'tiktok'];

	private $all_platforms = [
		'google',
		'airbnb',
		'zomato',
		'yelp',
		'tripadvisor',
		'amazon',
		'aliexpress',
		'booking.com',
		'woocommerce',
		'facebook',
		'twitter',
		'youtube',
		'instagram',
        'facebook_feed',
        'tiktok'
	];

	public function handle()
	{
		$platforms = apply_filters('wpsocialreviews/platforms', $this->all_platforms);
		foreach ($platforms as $platform) {
            $is_active = (new PlatformManager())->isActivePlatform($platform);
			if ($is_active) {
				if (in_array($platform, $this->feed_platforms)){
					do_action('wpsr_' . $platform . '_feed_update');
					if ($platform === 'instagram') {
						do_action('wpsr_instagram_access_token_refresh_weekly');
					}
				} else {
					do_action('wpsr_' . $platform . '_reviews_update');
				}
			}
		}
	}

    public function processDailyTask()
    {
        foreach ($this->feed_platforms as $platform) {
            $is_active = (new PlatformManager())->isActivePlatform($platform);
            if ($is_active) {
                do_action('wpsr_'.$platform.'_send_email_report');
            }
        }
    }

    public function processWeekly()
    {
        (new Maintenance())->maybeProcessData();

        $is_active = (new PlatformManager())->isActivePlatform('instagram');
        if($is_active){
            $optimize_images_user_ids = (new OptimizeImage())->getUserIds();

            $configs            = get_option('wpsr_instagram_verification_configs', []);
            $connected_accounts = Arr::get($configs, 'connected_accounts', []);

            if(count($optimize_images_user_ids)) {
                foreach ($connected_accounts as $account) {
                    $userId = Arr::get($account, 'user_id');
                    if (!empty($userId) && in_array($userId, $optimize_images_user_ids)) {
                        //check this account is valid, if not delete all images and clear db
                        do_action('wpsocialreviews/check_instagram_access_token_validity_weekly', $account);
                    }
                }
            }
        }
    }
}
