<?php

namespace WPSocialReviews\App\Services;
use WPSocialReviews\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register a widget that render a feed shortcode
 * @since 1.3.0
 */
class GlobalSettings
{
    public function formatGlobalSettings($settings = array())
    {
        return array(
            'global_settings' => array(
                'translations' => array(
                    'subscribers'       => sanitize_text_field(Arr::get($settings,'global_settings.translations.subscribers')),
                    'following'         => sanitize_text_field(Arr::get($settings,'global_settings.translations.following')),
                    'followers'         => sanitize_text_field(Arr::get($settings,'global_settings.translations.followers')),
                    'videos'            => sanitize_text_field(Arr::get($settings,'global_settings.translations.videos')),
                    'views'             => sanitize_text_field(Arr::get($settings,'global_settings.translations.views')),
                    'tweets'            => sanitize_text_field(Arr::get($settings,'global_settings.translations.tweets')),
                    'people_like_this'  => sanitize_text_field(Arr::get($settings,'global_settings.translations.people_like_this')),
                    'posts'             => sanitize_text_field(Arr::get($settings,'global_settings.translations.posts')),
                    'leave_a_review'    => sanitize_text_field(Arr::get($settings,'global_settings.translations.leave_a_review')),
                    'recommends'        => sanitize_text_field(Arr::get($settings,'global_settings.translations.recommends')),
                    'does_not_recommend' => sanitize_text_field(Arr::get($settings,'global_settings.translations.does_not_recommend')),
                    'on'                => sanitize_text_field(Arr::get($settings,'global_settings.translations.on')),
                    'read_all_reviews'  => sanitize_text_field(Arr::get($settings,'global_settings.translations.read_all_reviews')),
                    'read_more'         => sanitize_text_field(Arr::get($settings,'global_settings.translations.read_more')),
                    'read_less'         => sanitize_text_field(Arr::get($settings,'global_settings.translations.read_less')),
                    'comments'          => sanitize_text_field(Arr::get($settings,'global_settings.translations.comments')),
                    'view_on_fb'        => sanitize_text_field(Arr::get($settings,'global_settings.translations.view_on_fb')),
                    'view_on_ig'        => sanitize_text_field(Arr::get($settings,'global_settings.translations.view_on_ig')),
                    'view_on_tiktok'    => sanitize_text_field(Arr::get($settings,'global_settings.translations.view_on_tiktok')),
                    'likes'             => sanitize_text_field(Arr::get($settings,'global_settings.translations.likes')),
                    'people_responded'  => sanitize_text_field(Arr::get($settings,'global_settings.translations.people_responded')),
                    'online_event'      => sanitize_text_field(Arr::get($settings,'global_settings.translations.online_event')),
	                'interested'        => sanitize_text_field(Arr::get($settings,'global_settings.translations.interested')),
	                'going' 		   => sanitize_text_field(Arr::get($settings,'global_settings.translations.going')),
	                'went' 			   => sanitize_text_field(Arr::get($settings,'global_settings.translations.went')),
                ),
                'advance_settings' => array(
                    'has_gdpr'             => Arr::get($settings,'global_settings.advance_settings.has_gdpr', 'false'),
                    'preserve_plugin_data' => Arr::get($settings,'global_settings.advance_settings.preserve_plugin_data', 'true'),
                    'email_report' => array(
                        'status'  => Arr::get($settings,'global_settings.advance_settings.email_report.status', 'false'),
                        'sending_day'  => Arr::get($settings,'global_settings.advance_settings.email_report.sending_day', 'Mon'),
                        'recipients'  => Arr::get($settings,'global_settings.advance_settings.email_report.recipients', get_option( 'admin_email', '' )),
                    ),
                )
            )
        );
    }

    public static function getTranslations()
    {
        $settings = get_option('wpsr_global_settings', []);
        $translations_settings = (new self)->formatGlobalSettings($settings);
        return Arr::get($translations_settings, 'global_settings.translations', []);
    }

    public function getGlobalSettings($key)
    {
        $settings = get_option('wpsr_global_settings', []);
        $formattedSettings = $this->formatGlobalSettings($settings);
        return Arr::get($formattedSettings, 'global_settings.'.$key, []);
    }
}