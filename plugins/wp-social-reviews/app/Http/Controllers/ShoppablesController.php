<?php

namespace WPSocialReviews\App\Http\Controllers;

use WPSocialReviews\Framework\Request\Request;
use WPSocialReviews\App\Services\Helper as GlobalHelper;
use WPSocialReviews\Framework\Support\Arr;

class ShoppablesController extends Controller
{

    /**
     *
     * Get all shoppable settings from options table.
     *
     * @param $request
     *
     * @return array
     * @since 3.7.3
     *
     **/
    public function index(Request $request)
    {
        $postType = $request->get('postType');
        $default = array(
            'instagram' => [],
            'facebook' => []
        );
        $settings = get_option('wpsr_global_shoppable_settings', $default);

        $has_item = false;
        foreach ($settings as $setting){
            if(count($setting) >= 1) {
                $has_item = true;
                break;
            }
        }

        return [
            'settings' => $settings,
            'has_item' => $has_item,
            'posts' => GlobalHelper::getPostsByPostType($postType),
            'post_types' => GlobalHelper::getPostTypes(),
        ];
    }

    /**
     *
     * Update shoppable settings
     *
     * @param $request
     *
     * @return array
     * @since 3.7.3
     *
     **/
    public function update(Request $request)
    {
        $settings = $request->get('settings');
        $validate_rules = ['hashtags' => 'required'];
        $settings = $this->recursive_sanitize($settings, $validate_rules);

        update_option('wpsr_global_shoppable_settings', $settings);
        return [
            'message' => __("Settings Saved Successfully.",
                'wp-social-reviews'),
        ];
    }

    /**
     *
     * Delete shoppable settings
     *
     * @param $request
     *
     * @return array
     * @since 3.7.3
     *
     **/
    public function delete(Request $request)
    {
        $settings = $request->get('settings');
        update_option('wpsr_global_shoppable_settings', $settings);
        return [
            'message' => __("Deleted Successfully.", 'wp-social-reviews'),
        ];
    }

    public function storeTemplateSettings(Request $request, $postId)
    {
        $json_data = $request->get('shoppable_fields');
        $raw_data  = json_decode($json_data, true);

        $sanitized_data = $this->recursive_sanitize($raw_data);

        $platform = $request->get('platform');

        $settings_json = $request->get('settings');
        $settings  = json_decode($settings_json, true);

        $feed_json = $request->get('feed');
        $feed  = json_decode($feed_json, true);

        $settings['feed_settings']['shoppable_settings']['shoppable_feeds'][$feed['username']][$feed['id']] = $sanitized_data;

        do_action('wpsocialreviews/update_editor_settings_' . $platform, $settings, $postId);
    }

    public function getPosts(Request $request)
    {
        $postType = $request->get('postType');
        return [
            'posts' => GlobalHelper::getPostsByPostType($postType),
        ];
    }

    public function get_sanitize_rules()
    {
        return [
            'url'           => 'sanitize_url',
            'text'          => 'sanitize_text_field',
            'hashtags'      => 'sanitize_text_field'
        ];
    }

    public function recursive_sanitize($settings, $validate_rules = array())
    {
        foreach ($settings as $key => &$value) {
            $source_type = Arr::get($settings, 'source_type', '');
            if (is_array($value)) {
                $value = $this->recursive_sanitize($value, $validate_rules);
                if (!empty($source_type) && $source_type === 'custom_url') {
                    $this->validate($settings['url_settings'], [
                        'url' => 'required'
                    ]);
                }

                if (!empty($source_type) && $source_type !== 'custom_url') {
                    $this->validate($settings['url_settings'], [
                        'id' => 'required'
                    ], [
                        'id.required' => 'The link to field is required.'
                    ]);
                }
            } else {
                /*validate data*/
                if(!empty($validate_rules) && array_key_exists($key, $validate_rules)) {
                    $this->validate($settings, [
                        $key => Arr::get($validate_rules, $key, '')
                    ]);
                }

                if($key === 'id' && !empty($value)) {
                    $settings['url_title'] = get_the_title($value);
                    $settings['url'] = get_the_permalink($value);
                }

                /*sanitize data*/
                $sanitize_rules = $this->get_sanitize_rules();
                $value = $this->sanitize($value, Arr::get($sanitize_rules, $key, ''));
            }
        }

        return $settings;
    }

    public function sanitize($value, $sanitize_method)
    {
        if(!empty($sanitize_method)) {
            return $sanitize_method($value);
        }

        return $value;
    }
}