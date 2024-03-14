<?php

if (!defined('ABSPATH')) exit;

function yottie_lite_add_action_links($links) {
    $links[] = '<a href="' . esc_url(admin_url('admin.php?page=yottie-lite')) . '">Settings</a>';
    $links[] = '<a href="' . YOTTIE_LITE_PRO_URL . '" target="_blank">More plugins by Elfsight</a>';
    return $links;
}
add_filter('plugin_action_links_' . YOTTIE_LITE_PLUGIN_SLUG, 'yottie_lite_add_action_links');


function yottie_lite_admin_init() {
    wp_register_style('yottie-lite-admin', plugins_url('assets/yottie-lite-admin.css', YOTTIE_LITE_FILE));
    wp_register_script('yottie-lite', plugins_url('assets/yottie-lite/dist/jquery.yottie-lite.bundled.js', YOTTIE_LITE_FILE), array('jquery'), YOTTIE_LITE_VERSION);
    wp_register_script('yottie-lite-admin', plugins_url('assets/yottie-lite-admin.js', YOTTIE_LITE_FILE), array('jquery', 'yottie-lite'));
}

function yottie_lite_admin_scripts() {
    wp_enqueue_style('yottie-lite-admin');
    wp_enqueue_script('yottie-lite');
    wp_enqueue_script('yottie-lite-admin');
}

function yottie_lite_create_menu() {
    $page_hook = add_menu_page(__('Yottie', YOTTIE_LITE_TEXTDOMAIN) , __('Yottie', YOTTIE_LITE_TEXTDOMAIN), 'manage_options', YOTTIE_LITE_SLUG, 'yottie_lite_settings_page', plugins_url('assets/img/yottie-wp-icon.png', YOTTIE_LITE_FILE));
    add_action('admin_init', 'yottie_lite_admin_init');
    add_action('admin_print_styles-' . $page_hook, 'yottie_lite_admin_scripts');
}
add_action('admin_menu', 'yottie_lite_create_menu');


function yottie_lite_underscore_to_cc($l) {
    return strtoupper(substr($l[0], 1));
}

function yottie_lite_update_youtube_connect() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'yottie_lite_update_youtube_connect_nonce')) {
        exit;
    }

    update_option('elfsight_yottie_youtube_api_key', !empty($_REQUEST['api_key']) ? $_REQUEST['api_key'] : '');
}
add_action('wp_ajax_yottie_lite_update_youtube_connect', 'yottie_lite_update_youtube_connect');


function yottie_lite_settings_page() {
    global $yottie_lite_defaults;

    $api_key = get_option('elfsight_yottie_youtube_api_key', '');

    // defaults to json
    $yottie_json = array();
    foreach ($yottie_lite_defaults as $name => $val) {
        $yottie_json[preg_replace_callback('/(_.)/', 'yottie_lite_underscore_to_cc', $name)] = $val;
    }

    ?><div class="yottie-admin wrap">
        <h2 class="yottie-admin-wp-messages-hack"></h2>

        <?php if (YOTTIE_LITE_SUPPORT_LINK) { ?>
            <div class="elfsight-support elfsight-support--hidden">
                <a class="elfsight-support-close">+</a>
                <div class="elfsight-support-heading"><span class="elfsight-support-heading-icon"></span> <?php _e("Need help?", YOTTIE_LITE_TEXTDOMAIN); ?></div>
                <div class="elfsight-support-text"><?php _e("If you have any question about our plugin or you need help with its installation, leave us a message and we'll glad to help you absolutely for free!", YOTTIE_LITE_TEXTDOMAIN); ?></div>
                <a class="elfsight-support-button" target="_blank" href="<?php echo YOTTIE_LITE_SUPPORT_LINK; ?>"><?php _e("GET FREE HELP", YOTTIE_LITE_TEXTDOMAIN); ?></a>
                <a class="elfsight-support-nevershow"><?php _e("Never show again", YOTTIE_LITE_TEXTDOMAIN); ?></a>
            </div>
        <?php } ?>

        <div class="yottie-admin-header">
            <div class="yottie-admin-header-pro">
                <a class="yottie-admin-header-pro-button" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Try Pro Free</a>

                <div class="yottie-admin-header-pro-text">Unlock 60+ awesome features</div>
            </div>

            <!-- <div class="yottie-admin-header-support">
                <span class="yottie-admin-icon-support yottie-admin-icon"></span>

                <h3 class="yottie-admin-header-support-title">Support</h3>

                <a class="yottie-admin-header-support-email" href="mailto:support@elfsight.com">
                    support@elfsight.com 
                    <svg class="yottie-admin-svg-arrow-more">
                        <line x1="0" y1="0" x2="4" y2="4"></line>
                        <line x1="0" y1="8" x2="4" y2="4"></line>
                    </svg>
                </a>

                <div class="yottie-admin-header-support-description">Face any issue installing our plugin? Got some ideas to improve it?<br>Reach us via email and we will answer any question!</div>
            </div> -->

            <a class="yottie-admin-header-logo" href="<?php echo admin_url('admin.php?page=yottie-lite'); ?>" title="<?php _e('Yottie Lite - WordPress YouTube Channel Plugin', YOTTIE_LITE_TEXTDOMAIN); ?>">
                <img src="<?php echo plugins_url('assets/img/logo.png', YOTTIE_LITE_FILE); ?>" width="260" height="63" alt="<?php _e('Yottie Lite - WordPress YouTube Channel Plugin', YOTTIE_LITE_TEXTDOMAIN); ?>">
            </a>

            <div class="yottie-admin-header-title"><?php _e('WordPress YouTube Channel Plugin', YOTTIE_LITE_TEXTDOMAIN); ?></div>
        </div>

        <div class="yottie-admin-youtube-connect<?php echo !empty($api_key) ? ' yottie-admin-youtube-connected' : ''?> yottie-admin-block">
            <div class="yottie-admin-block-icon"><span class="yottie-admin-icon-key yottie-admin-icon"></span></div>

            <div class="yottie-admin-block-inner">
                <div class="yottie-admin-youtube-connect-api-key">
                    <h2><?php _e('Connect to YouTube API', YOTTIE_LITE_TEXTDOMAIN); ?></h2>

                    <div class="yottie-admin-youtube-connect-api-key-text">
                        <p><?php _e('Generate YouTube API key to get independent API quota YouTube API by following this tutorial:', YOTTIE_LITE_TEXTDOMAIN); ?>
                            <a href="https://elfsight.com/help/how-to-get-youtube-api-key/?utm_source=markets&utm_medium=wordpressorg&utm_content=adminpanel&utm_campaign=YTWPlite&utm_term=howtogetYAK" target="_blank"><?php _e('How to get YouTube API key', YOTTIE_LITE_TEXTDOMAIN); ?></a>
                        </p>

                        <h3><?php _e('YouTube API Key:', YOTTIE_LITE_TEXTDOMAIN); ?></h3>

                        <form data-nonce="<?php echo wp_create_nonce('yottie_lite_update_youtube_connect_nonce'); ?>">
                            <input type="text" name="elfsight_yottie_youtube_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text">

                            <button class="yottie-admin-youtube-connect-api-key-submit" type="submit"><?php _e('Save', YOTTIE_LITE_TEXTDOMAIN); ?></button>
                        </form>
                    </div>
                </div>

                <div class="yottie-admin-youtube-connect-note">
                    <h3><?php _e('Note!', YOTTIE_LITE_TEXTDOMAIN); ?></h3>

                    <ul class="yottie-admin-youtube-connect-note-list">
                        <li><?php _e('Unlimited YouTube sources', YOTTIE_LITE_TEXTDOMAIN); ?></li>
                        <li><?php _e('20 options for layout customization', YOTTIE_LITE_TEXTDOMAIN); ?></li>
                        <li><?php _e('56 color options and 4 predefined color schemes', YOTTIE_LITE_TEXTDOMAIN); ?></li>
                        <li><?php _e('Monetization with AdSense', YOTTIE_LITE_TEXTDOMAIN); ?></li>
                    </ul>

                    <p><?php _e('Are available in <b>PRO version</b>!', YOTTIE_LITE_TEXTDOMAIN); ?></p>

                    <a class="yottie-admin-youtube-connect-note-button" href="<?php echo YOTTIE_LITE_PRO_URL;?>" target="_blank"><?php _e('Try Pro Free', YOTTIE_LITE_TEXTDOMAIN); ?></a>
                </div>
            </div>
        </div>

        <div class="yottie-admin-demo yottie-admin-block">
            <div class="yottie-admin-block-icon"><span class="yottie-admin-icon-settings yottie-admin-icon"></span></div>

            <div class="yottie-admin-block-inner">
                <div class="yottie-admin-demo-header">
                    <h2><?php _e('Installation', YOTTIE_LITE_TEXTDOMAIN); ?></h2>
                    <span class="yottie-admin-demo-header-hint"><?php _e('Adjust the plugin as you wish, get the shortcode and paste it into any page or post.', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                </div>

                <?php include(YOTTIE_LITE_PATH . '/includes/yottie-demo.php'); ?>

                <script>
                    function getYottieDefaults() {
                        return <?php echo json_encode($yottie_json); ?>;
                    }
                </script>
            </div>
        </div>

        <div class="yottie-admin-pro">
            <div class="yottie-admin-pro-features">
                <h2>Lite vs Pro</h2>

                <table class="yottie-admin-pro-features-list">
                    <thead>
                        <tr>
                            <th class="yottie-admin-pro-features-list-heading-options">Options</th>
                            <th class="yottie-admin-pro-features-list-heading-lite">Lite</th>
                            <th class="yottie-admin-pro-features-list-heading-pro">
                                <a href="<?php echo YOTTIE_LITE_DEMO_URL; ?>" target="_blank">
                                    Pro<br>
                                    <span>Try Demo Now</span>
                                </a>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Channel</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Source groups</td>
                            <td class="yottie-admin-pro-features-list-item-lite">2</td>
                            <td class="yottie-admin-pro-features-list-item-pro">Unlimited</td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Cache time</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header visible</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>
                        
                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Groups visible</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content columns</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content arrows control</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content drag control</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content transition effect</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content transition speed</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content auto</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content auto pause on hover</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Width</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Languages</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header layout</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header info</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header channel name</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header channel description</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header channel logo</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header channel banner</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content rows</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content gutter</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content scroll control</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content direction</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content free mode</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content scrollbar</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content responsive</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video layout</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video info</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video play mode</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup info</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup autoplay</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Color scheme</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header banner overlay color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header channel name color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header channel name on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header channel description color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Header counters color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Groups background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Groups link color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Groups link on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Groups active link color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Groups highlight color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Groups highlight on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Groups active highlight color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content arrows color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content arrows on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content arrows background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content arrows background on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content scrollbar background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Content scrollbar slider background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video overlay color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video play icon color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video play icon on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video duration color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video duration background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video title color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video title on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video date color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video description color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video anchor color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video anchor on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Video counters color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup overlay color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup title color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup channel name color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup channel name on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup views counter color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup likes ratio color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup dislikes ratio color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup likes counter color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup dislikes counter color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup date color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup description color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup anchor color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup anchor on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup description more button color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup description more button on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup comments username color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup comments username on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup comments passed time color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup comments likes color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup comments text color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup controls color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup controls on hover color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup controls mobile color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Popup controls mobile background color</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">AdSense Support</td>
                            <td class="yottie-admin-pro-features-list-item-lite"><span class="yottie-admin-icon-feature-not-available yottie-admin-icon"></span></td>
                            <td class="yottie-admin-pro-features-list-item-pro"><span class="yottie-admin-icon-feature-available yottie-admin-icon"></span></td>
                        </tr>

                        <tr>
                            <td class="yottie-admin-pro-features-list-item-option">Support</td>
                            <td class="yottie-admin-pro-features-list-item-lite">Bug fixes</td>
                            <td class="yottie-admin-pro-features-list-item-pro">Full support 24/7</td>
                        </tr>

                        <tr class="yottie-admin-pro-features-upgrade-row">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="yottie-admin-pro-features-upgrade">
                                <a href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Try Pro Free</a>
                            </td>
                    </tbody>
                </table>
            </div>

            <div class="yottie-admin-pro-key-features">
                <h2>Key Features of Pro Version</h2>
                <img src="<?php echo plugins_url('assets/img/pro-key-features.jpg', YOTTIE_LITE_FILE); ?>">
            </div>
        </div>
    </div>
<?php } ?>
