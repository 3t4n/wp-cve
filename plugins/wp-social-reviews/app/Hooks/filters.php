<?php

/**
 * All registered filter's handlers should be in app\Hooks\Handlers,
 * addFilter is similar to add_filter and addCustomFlter is just a
 * wrapper over add_filter which will add a prefix to the hook name
 * using the plugin slug to make it unique in all wordpress plugins,
 * ex: $app->addCustomFilter('foo', ['FooHandler', 'handleFoo']) is
 * equivalent to add_filter('slug-foo', ['FooHandler', 'handleFoo']).
 */

/**
 * $app
 * @var WPFluent\Foundation\Application
 */

add_filter('cron_schedules', function ($schedules) {
    // Adds custom schedules to the existing schedules.

    if (!isset($schedules['5min'])) {
        $schedules['5min'] = array(
            'display'  => __('5 min', 'wp-social-reviews'),
            'interval' => 300,
        );
    }

    if (!isset($schedules['2days'])) {
        $schedules['2days'] = array(
            'display'  => __('Every 2 Day', 'wp-social-reviews'),
            'interval' => 172800,
        );
    }

    if (!isset($schedules['3days'])) {
        $schedules['3days'] = array(
            'display'  => __('Every 3 Day', 'wp-social-reviews'),
            'interval' => 259200,
        );
    }

    if (!isset($schedules['1week'])) {
        $schedules['1week'] = array(
            'display'  => __('Every 1 Week', 'wp-social-reviews'),
            'interval' => 604800,
        );
    }

    if (!isset($schedules['2weeks'])) {
        $schedules['2weeks'] = array(
            'display'  => __('Every 2 Week', 'wp-social-reviews'),
            'interval' => 1209600,
        );
    }

    if (!isset($schedules['1month'])) {
        $schedules['1month'] = array(
            'display'  => __('1 Month', 'wp-social-reviews'),
            'interval' => 60 * 60 * 24 * 30,
        );
    }

    if (!isset($schedules['1year'])) {
        $schedules['1year'] = array(
            'display'  => __('1 Year', 'wp-social-reviews'),
            'interval' => 60 * 60 * 24 * 365,
        );
    }

    return $schedules;
});

add_filter('admin_footer_text', function ($footer_text) {
    $current_screen = get_current_screen();
    $is_wpsn_screen = ($current_screen && false !== strpos($current_screen->id, 'wpsocialninja'));
    if ($is_wpsn_screen) {
        $footer_text = sprintf(
            __('We hope you are enjoying %1$s - %2$s - %3$s - %4$s', 'wp-social-reviews'),
            '<strong>' . __('WP Social Ninja', 'wp-social-reviews') . '</strong>',
            '<a href="https://wpsocialninja.com/docs/" target="_blank">Read Documentation</a>',
            '<a href="https://wpsocialninja.com/terms-conditions/" target="_blank">Terms & Conditions</a>',
            '<a href="https://wpsocialninja.com/privacy-policy/" target="_blank">Privacy Policy</a>'
        );
    }
    return $footer_text;
}, 11, 1);

/*
 * Exclude For WP Rocket Settings
 */
if (defined('WP_ROCKET_VERSION')) {
    add_filter('rocket_excluded_inline_js_content', function ($lines) {
        $lines[] = 'wpsr_popup_params';
        $lines[] = 'wpsr_ajax_params';
        $lines[] = 'WPSR_';
        $lines[] = 'wpsr_';
        return $lines;
    });

    add_filter('rocket_exclude_defer_js', function ($defers) {
        $defers[] = str_replace(ABSPATH, '/', WP_PLUGIN_DIR) . '/wp-social-reviews/assets/js/(.*).js';
        return $defers;
    });
}

add_filter('wpsocialreviews/display_frontend_error_message', function ($error_message = '', $account_ids = [], $hashtags = ''){
    $errors = (new \WPSocialReviews\App\Services\Platforms\PlatformErrorManager('instagram'))->getFrontEndErrors();

    if(!current_user_can('manage_options')){
        return false;
    }

    if( !empty($errors) && is_array($errors) ){
        $inner_html = '';

        foreach ($errors as $index => $error){
            if(!isset($error['error_message'])){
                return false;
            }

            $error_account_id = (new \WPSocialReviews\App\Services\Platforms\PlatformErrorManager('instagram'))->connectedAccountHasError($account_ids, $index);
            if((int)$error_account_id === (int)$index || $index === 'error_message' || (isset($error['hashtag']) && strpos($hashtags, '#'.$error['hashtag']) !== false) || (strpos($error['admin_only'], 'http_request_failed') !== false) ){
                $inner_html .= '<span>'.esc_html__($error['error_message']).'</span><br/>';
                $inner_html .=  '<strong>'.esc_html__($error['admin_only']).'</strong><br/><br/>';
            }
        }

        if(!empty($inner_html)){
            $html = '<div class="wpsr_frontend_errors">';
            $html .= '<span>'.esc_html__('This error message is only visible to WordPress admins', 'wp-social-reviews').'</span><br/>';
            $html .= $inner_html;
            $html .= '</div>';
            return $html;
        }

        return false;
    }

    if(!empty($error_message) ){
        return '<div class="wpsr_frontend_errors">
            <span>' . __('This error message is only visible to WordPress admins', 'wp-social-reviews') . '</span><br/>
            <strong>' . $error_message . '</strong>
       </div>';
    }

}, 10, 3);

add_filter('plugin_row_meta', function ($meta, $plugin_file){
    if ('wp-social-reviews/wp-social-reviews.php' === $plugin_file) {
        $row_meta = array(
            'docs'    => '<a rel="noopener" href="https://wpsocialninja.com/docs/" style="color: #197efb;font-weight: 600;" aria-label="' . esc_attr(esc_html__('View Documentation', 'wp-social-reviews')) . '" target="_blank">' . esc_html__('Docs', 'wp-social-reviews') . '</a>',
            'support' => '<a rel="noopener" href="https://wpmanageninja.com/support-tickets/#/" style="color: #197efb;font-weight: 600;" aria-label="' . esc_attr(esc_html__('Get Support', 'wp-social-reviews')) . '" target="_blank">' . esc_html__('Support', 'wp-social-reviews') . '</a>',
        );

        if(!defined('WPSOCIALREVIEWS_PRO')) {
            $row_meta['pro'] = '<a rel="noopener" href="https://wpsocialninja.com/?utm_source=wp_site&utm_medium=plugin&utm_campaign=upgrade" style="color: #5525d9;font-weight: bold;" aria-label="' . esc_attr(esc_html__('Upgrade to Pro', 'wp-social-reviews')) . '" target="_blank">' . esc_html__('Upgrade to Pro', 'wp-social-reviews') . '</a>';
        }
        return array_merge($meta, $row_meta);
    }
    return (array)$meta;
}, 10, 2);

// shortpixel plugin replace the IG cdn urls - we will add this filter by user feedback
//add_filter('shortpixel/ai/customRules', function($regexItems){
//    return [];
//});
