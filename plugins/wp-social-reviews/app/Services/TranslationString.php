<?php


namespace WPSocialReviews\App\Services;

use WPSocialReviews\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class TranslationString
{
    public static function getStrings()
    {
        $translations = GlobalSettings::getTranslations();

        $i18n = array(
            'Subscribers' => Arr::get($translations, 'subscribers') ?: __('Subscribers', 'wp-social-reviews'),
            'Following' => Arr::get($translations, 'following') ?: __('Following', 'wp-social-reviews'),
            'Followers' => Arr::get($translations, 'followers') ?: __('Followers', 'wp-social-reviews'),
            'Videos' => Arr::get($translations, 'videos') ?: __('Videos', 'wp-social-reviews'),
            'Views' => Arr::get($translations, 'views') ?: __('Views', 'wp-social-reviews'),
            'Tweets' => Arr::get($translations, 'tweets') ?: __('Tweets', 'wp-social-reviews'),
            'People like this' => Arr::get($translations, 'people_like_this') ?: __('People like this', 'wp-social-reviews'),
            'Posts' => Arr::get($translations, 'posts') ?: __('Posts', 'wp-social-reviews'),
            'Where you want to leave a review' => Arr::get($translations, 'leave_a_review') ?: __('Where you want to leave a review', 'wp-social-reviews'),
            'Recommends' => Arr::get($translations, 'recommends') ?: __('Recommends', 'wp-social-reviews'),
            'Does not recommend' => Arr::get($translations, 'does_not_recommend') ?: __('Does not recommend', 'wp-social-reviews'),
            'On' => Arr::get($translations, 'on') ?: __('On', 'wp-social-reviews'),
            'Read all reviews' => Arr::get($translations, 'read_all_reviews') ?: __('Read all reviews', 'wp-social-reviews'),
            'Read More' => Arr::get($translations, 'read_more') ?: __('Read More', 'wp-social-reviews'),
            'Read Less' => Arr::get($translations, 'read_less') ?: __('Read Less', 'wp-social-reviews'),
            'Comments' => Arr::get($translations, 'comments') ?: __('Comments', 'wp-social-reviews'),
            'View on Facebook' => Arr::get($translations, 'view_on_fb') ?: __('View on Facebook', 'wp-social-reviews'),
            'View on Instagram' => Arr::get($translations, 'view_on_ig') ?: __('View on Instagram', 'wp-social-reviews'),
            'View on TikTok' => Arr::get($translations, 'view_on_tiktok') ?: __('View on TikTok', 'wp-social-reviews'),
            'Likes' => Arr::get($translations, 'likes') ?: __('Likes', 'wp-social-reviews'),
            'People Responded' => Arr::get($translations, 'people_responded') ?: __('People Responded', 'wp-social-reviews'),
            'Online Event' => Arr::get($translations, 'online_event') ?: __('Online Event', 'wp-social-reviews'),
            'Interested' => Arr::get($translations, 'interested') ?: __('Interested', 'wp-social-reviews'),
            'Going' => Arr::get($translations, 'going') ?: __('Going', 'wp-social-reviews'),
            'Went' => Arr::get($translations, 'went') ?: __('Went', 'wp-social-reviews'),
        );

        return apply_filters('wpsocialreviews/translation_strings_i18n', $i18n);
    }

}