<?php

/**
 * easy-share-solution options
 *
 * @author Noor alam
 */
if (!class_exists('easy_share_solution_options')) :
    class easy_share_solution_options
    {

        private $settings_api;

        function __construct()
        {
            $this->settings_api = new born_for_share;

            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));
        }

        function admin_init()
        {

            //set the settings
            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());

            //initialize settings
            $this->settings_api->admin_init();
        }

        function admin_menu()
        {
            add_menu_page(
                __('Easy share solution', 'easy-share-solution'),
                __('Easy share solution', 'easy-share-solution'),
                'manage_options',
                'easy-share-solution-options.php',
                array($this, 'plugin_page')
            );
        }

        function get_settings_sections()
        {
            $sections = array(
                array(
                    'id' => 'easy_share_solution_settings',
                    'title' => __('Basic Settings', 'easy-share-solution')
                ),
                array(
                    'id' => 'easy_share_solution_other_option',
                    'title' => __('Other Settings', 'easy-share-solution')
                ),
                array(
                    'id' => 'easy_share_theme_rec',
                    'title' => __('Theme Recommendation', 'easy-share-solution')
                )

            );
            return $sections;
        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        function get_settings_fields()
        {
            $wplink = '<a href="https://wpthemespace.com/" class="button button-primary" target="_blank">Visit & Select Your Theme</a>';
            $settings_fields = array(
                'easy_share_solution_settings' => array(
                    array(
                        'name'    => 'social_one',
                        'label'   => __('Select social button one', 'easy-share-solution'),
                        'desc'    => __('Social button select for button one', 'easy-share-solution'),
                        'type'    => 'select',
                        'default' => 'Facebook',
                        'options' => array(
                            'Facebook'   => 'Facebook',
                            'Twitter'   => 'Twitter',
                            'Buffer'  => 'Buffer',
                            'Linkedin'  => 'Linkedin',
                            'Pinterest'  => 'Pinterest',
                            'Tumblr'  => 'Tumblr',
                            'Vk'  => 'Vk',
                            'Ok'  => 'Ok',
                            'Delicious'  => 'Delicious',
                            'Stumbleupon'  => 'Stumbleupon',
                            'Myspace'  => 'Myspace',
                            'Myworld'  => 'Myworld',
                            'Wordpress'  => 'Wordpress',
                            'Digg'  => 'Digg',
                            'Diigo'  => 'Diigo',
                            'Amazon'  => 'Amazon',
                            'Reddit'  => 'Reddit',
                            'Yahoo'  => 'Yahoo',
                            'Yahoomail'  => 'Yahoomail',
                            'Pocket'  => 'Pocket',
                            'Aim'  => 'Aim',
                            'Google'  => 'Google',
                            'Gmail'  => 'Gmail',
                            'Mixi'  => 'Mixi',
                            'Blogger'  => 'Blogger',
                            'Friendfeed'  => 'Friendfeed',
                            'Plurk'  => 'Plurk',
                            'Box'  => 'Box',
                            'Instapaper'  => 'Instapaper',
                            'Viadeo'  => 'Viadeo',
                            'Evernote'  => 'Evernote',
                        )
                    ),
                    array(
                        'name'    => 'social_two',
                        'label'   => __('Select social button two', 'easy-share-solution'),
                        'desc'    => __('Social button select for button two', 'easy-share-solution'),
                        'type'    => 'select',
                        'default' => 'Twitter',
                        'options' => array(
                            'Facebook'   => 'Facebook',
                            'Twitter'   => 'Twitter',
                            'Buffer'  => 'Buffer',
                            'Linkedin'  => 'Linkedin',
                            'Pinterest'  => 'Pinterest',
                            'Tumblr'  => 'Tumblr',
                            'Vk'  => 'Vk',
                            'Ok'  => 'Ok',
                            'Delicious'  => 'Delicious',
                            'Stumbleupon'  => 'Stumbleupon',
                            'Myspace'  => 'Myspace',
                            'Myworld'  => 'Myworld',
                            'Wordpress'  => 'Wordpress',
                            'Digg'  => 'Digg',
                            'Diigo'  => 'Diigo',
                            'Amazon'  => 'Amazon',
                            'Reddit'  => 'Reddit',
                            'Yahoo'  => 'Yahoo',
                            'Yahoomail'  => 'Yahoomail',
                            'Pocket'  => 'Pocket',
                            'Aim'  => 'Aim',
                            'Google'  => 'Google',
                            'Gmail'  => 'Gmail',
                            'Mixi'  => 'Mixi',
                            'Blogger'  => 'Blogger',
                            'Friendfeed'  => 'Friendfeed',
                            'Plurk'  => 'Plurk',
                            'Box'  => 'Box',
                            'Instapaper'  => 'Instapaper',
                            'Viadeo'  => 'Viadeo',
                            'Evernote'  => 'Evernote',
                        )
                    ),
                    array(
                        'name'    => 'social_three',
                        'label'   => __('Select social button three', 'easy-share-solution'),
                        'desc'    => __('Social button select for button three', 'easy-share-solution'),
                        'type'    => 'select',
                        'default' => 'Tumblr',
                        'options' => array(
                            'Facebook'   => 'Facebook',
                            'Twitter'   => 'Twitter',
                            'Buffer'  => 'Buffer',
                            'Linkedin'  => 'Linkedin',
                            'Pinterest'  => 'Pinterest',
                            'Tumblr'  => 'Tumblr',
                            'Vk'  => 'Vk',
                            'Ok'  => 'Ok',
                            'Delicious'  => 'Delicious',
                            'Stumbleupon'  => 'Stumbleupon',
                            'Myspace'  => 'Myspace',
                            'Myworld'  => 'Myworld',
                            'Wordpress'  => 'Wordpress',
                            'Digg'  => 'Digg',
                            'Diigo'  => 'Diigo',
                            'Amazon'  => 'Amazon',
                            'Reddit'  => 'Reddit',
                            'Yahoo'  => 'Yahoo',
                            'Yahoomail'  => 'Yahoomail',
                            'Pocket'  => 'Pocket',
                            'Aim'  => 'Aim',
                            'Google'  => 'Google',
                            'Gmail'  => 'Gmail',
                            'Mixi'  => 'Mixi',
                            'Blogger'  => 'Blogger',
                            'Friendfeed'  => 'Friendfeed',
                            'Plurk'  => 'Plurk',
                            'Box'  => 'Box',
                            'Instapaper'  => 'Instapaper',
                            'Viadeo'  => 'Viadeo',
                            'Evernote'  => 'Evernote',
                        )
                    ),
                    array(
                        'name'    => 'social_four',
                        'label'   => __('Select social button four', 'easy-share-solution'),
                        'desc'    => __('Social button select for button four', 'easy-share-solution'),
                        'type'    => 'select',
                        'default' => 'Linkedin',
                        'options' => array(
                            'Facebook'   => 'Facebook',
                            'Twitter'   => 'Twitter',
                            'Buffer'  => 'Buffer',
                            'Linkedin'  => 'Linkedin',
                            'Pinterest'  => 'Pinterest',
                            'Tumblr'  => 'Tumblr',
                            'Vk'  => 'Vk',
                            'Ok'  => 'Ok',
                            'Delicious'  => 'Delicious',
                            'Stumbleupon'  => 'Stumbleupon',
                            'Myspace'  => 'Myspace',
                            'Myworld'  => 'Myworld',
                            'Wordpress'  => 'Wordpress',
                            'Digg'  => 'Digg',
                            'Diigo'  => 'Diigo',
                            'Amazon'  => 'Amazon',
                            'Reddit'  => 'Reddit',
                            'Yahoo'  => 'Yahoo',
                            'Yahoomail'  => 'Yahoomail',
                            'Pocket'  => 'Pocket',
                            'Aim'  => 'Aim',
                            'Google'  => 'Google',
                            'Gmail'  => 'Gmail',
                            'Mixi'  => 'Mixi',
                            'Blogger'  => 'Blogger',
                            'Friendfeed'  => 'Friendfeed',
                            'Plurk'  => 'Plurk',
                            'Box'  => 'Box',
                            'Instapaper'  => 'Instapaper',
                            'Viadeo'  => 'Viadeo',
                            'Evernote'  => 'Evernote',
                        )
                    ),
                    array(
                        'name'    => 'social_five',
                        'label'   => __('Select social button five', 'easy-share-solution'),
                        'desc'    => __('Social button select for button five', 'easy-share-solution'),
                        'type'    => 'select',
                        'default' => 'Pinterest',
                        'options' => array(
                            'Facebook'   => 'Facebook',
                            'Twitter'   => 'Twitter',
                            'Buffer'  => 'Buffer',
                            'Linkedin'  => 'Linkedin',
                            'Pinterest'  => 'Pinterest',
                            'Tumblr'  => 'Tumblr',
                            'Vk'  => 'Vk',
                            'Ok'  => 'Ok',
                            'Delicious'  => 'Delicious',
                            'Stumbleupon'  => 'Stumbleupon',
                            'Myspace'  => 'Myspace',
                            'Myworld'  => 'Myworld',
                            'Wordpress'  => 'Wordpress',
                            'Digg'  => 'Digg',
                            'Diigo'  => 'Diigo',
                            'Amazon'  => 'Amazon',
                            'Reddit'  => 'Reddit',
                            'Yahoo'  => 'Yahoo',
                            'Yahoomail'  => 'Yahoomail',
                            'Pocket'  => 'Pocket',
                            'Aim'  => 'Aim',
                            'Google'  => 'Google',
                            'Gmail'  => 'Gmail',
                            'Mixi'  => 'Mixi',
                            'Blogger'  => 'Blogger',
                            'Friendfeed'  => 'Friendfeed',
                            'Plurk'  => 'Plurk',
                            'Box'  => 'Box',
                            'Instapaper'  => 'Instapaper',
                            'Viadeo'  => 'Viadeo',
                            'Evernote'  => 'Evernote',
                        )
                    ),
                    array(
                        'name'    => 'social_six',
                        'label'   => __('Select social button six', 'easy-share-solution'),
                        'desc'    => __('Social button select for button six', 'easy-share-solution'),
                        'type'    => 'select',
                        'default' => 'noselected',
                        'options' => array(
                            'Facebook'   => 'Facebook',
                            'Twitter'   => 'Twitter',
                            'Buffer'  => 'Buffer',
                            'Linkedin'  => 'Linkedin',
                            'Pinterest'  => 'Pinterest',
                            'Tumblr'  => 'Tumblr',
                            'Vk'  => 'Vk',
                            'Ok'  => 'Ok',
                            'Delicious'  => 'Delicious',
                            'Stumbleupon'  => 'Stumbleupon',
                            'Myspace'  => 'Myspace',
                            'Myworld'  => 'Myworld',
                            'Wordpress'  => 'Wordpress',
                            'Digg'  => 'Digg',
                            'Diigo'  => 'Diigo',
                            'Amazon'  => 'Amazon',
                            'Reddit'  => 'Reddit',
                            'Yahoo'  => 'Yahoo',
                            'Yahoomail'  => 'Yahoomail',
                            'Pocket'  => 'Pocket',
                            'Aim'  => 'Aim',
                            'Google'  => 'Google',
                            'Gmail'  => 'Gmail',
                            'Mixi'  => 'Mixi',
                            'Blogger'  => 'Blogger',
                            'Friendfeed'  => 'Friendfeed',
                            'Plurk'  => 'Plurk',
                            'Box'  => 'Box',
                            'Instapaper'  => 'Instapaper',
                            'Viadeo'  => 'Viadeo',
                            'Evernote'  => 'Evernote',
                            'noselected'  => 'no select'
                        )
                    ),
                    array(
                        'name'    => 'social_seven',
                        'label'   => __('Select social button seven', 'easy-share-solution'),
                        'desc'    => __('Social button select for button seven', 'easy-share-solution'),
                        'type'    => 'select',
                        'default' => 'noselect',
                        'options' => array(
                            'Facebook'   => 'Facebook',
                            'Twitter'   => 'Twitter',
                            'Buffer'  => 'Buffer',
                            'Linkedin'  => 'Linkedin',
                            'Pinterest'  => 'Pinterest',
                            'Tumblr'  => 'Tumblr',
                            'Vk'  => 'Vk',
                            'Ok'  => 'Ok',
                            'Delicious'  => 'Delicious',
                            'Stumbleupon'  => 'Stumbleupon',
                            'Myspace'  => 'Myspace',
                            'Myworld'  => 'Myworld',
                            'Wordpress'  => 'Wordpress',
                            'Digg'  => 'Digg',
                            'Diigo'  => 'Diigo',
                            'Amazon'  => 'Amazon',
                            'Reddit'  => 'Reddit',
                            'Yahoo'  => 'Yahoo',
                            'Yahoomail'  => 'Yahoomail',
                            'Pocket'  => 'Pocket',
                            'Aim'  => 'Aim',
                            'Google'  => 'Google',
                            'Gmail'  => 'Gmail',
                            'Mixi'  => 'Mixi',
                            'Blogger'  => 'Blogger',
                            'Friendfeed'  => 'Friendfeed',
                            'Plurk'  => 'Plurk',
                            'Box'  => 'Box',
                            'Instapaper'  => 'Instapaper',
                            'Viadeo'  => 'Viadeo',
                            'Evernote'  => 'Evernote',
                            'noselect'  => 'no select'
                        )
                    ),
                    array(
                        'name'    => 'btn_type',
                        'label'   => __('Select button type', 'easy-share-solution'),
                        'desc'    => __('Select type icon only or text and icon', 'easy-share-solution'),
                        'type'    => 'radio',
                        'default' => 'textandicon',
                        'options' => array(
                            'textandicon' => __('Icon with hover text', 'easy-share-solution'),
                            'icon'  => __('Squire icon', 'easy-share-solution'),
                            'round_icon'  => __('One side round icon', 'easy-share-solution'),
                        )
                    ),
                    array(
                        'name'    => 'show_hide',
                        'label'   => __('Use show hide click button', 'easy-share-solution'),
                        'desc'    => __('You can use show hide click button.Its user friendly and mobile friendly', 'easy-share-solution'),
                        'type'    => 'radio',
                        'default' => 'yes',
                        'options' => array(
                            'yes' => __('Yes', 'easy-share-solution'),
                            'no'  => __('No', 'easy-share-solution'),
                        )
                    ),
                    array(
                        'name'    => 'btn_position',
                        'label'   => __('Select button position', 'easy-share-solution'),
                        'desc'    => __('This plugin support right side of the page, left side of the page, bottom of the page and after the content share button position.', 'easy-share-solution'),
                        'type'    => 'multicheck',
                        'default' => array('one' => 'one'),
                        'options' => array(
                            'one' =>  __('Left side of the page', 'easy-share-solution'),
                            'two'  => __('Right side of the page', 'easy-share-solution'),
                            'three'  => __('Bottom of the page', 'easy-share-solution'),
                            'four'  => __('After the content', 'easy-share-solution'),
                            'five'  => __('None of this, only use widget.', 'easy-share-solution'),

                        )
                    ),

                ),
                'easy_share_solution_other_option' => array(

                    array(
                        'name'              => 'btn_top_set',
                        'label'   => __('Set button position', 'easy-share-solution'),
                        'desc'    => __('Set your button position.Set 25 to 40 for better view.', 'easy-share-solution'),
                        'type'              => 'number',
                        'default'           => '20',
                        'sanitize_callback' => 'intval'
                    ),
                    // array(
                    //     'name'    => 'select_count',
                    //     'label'   => __( 'Counter show', 'easy-share-solution' ),
                    //     'desc'    => __( 'Only 7 share button support counter, These are Facebook,Linkedin,Googleplus,Pinterest,ok,Myworld and Vk', 'easy-share-solution' ),
                    //     'type'    => 'radio',
                    // 	'default' => 'hover',
                    //     'options' => array(
                    //         'yes' => __( 'Show', 'easy-share-solution' ),
                    //         'hover' => __( 'Show on hover', 'easy-share-solution' ),
                    //         'no' => __( 'Hide', 'easy-share-solution' ),
                    //     )
                    // ),
                    array(
                        'name'    => 'all_sharebtn',
                        'label'   => __('Use all share button pop-up', 'easy-share-solution'),
                        'desc'    => __('You can active or hide all share button.', 'easy-share-solution'),
                        'type'    => 'select',
                        'default' => 'yes',
                        'options' => array(
                            'yes' => __('Yes', 'easy-share-solution'),
                            'no'  => __('No', 'easy-share-solution'),
                        )
                    ),
                    array(
                        'name'    => 'btn_pup_style',
                        'label'   => __('Pop-up icon style', 'easy-share-solution'),
                        'desc'    => __('Set your pop-up share icon style.', 'easy-share-solution'),
                        'type'    => 'radio',
                        'default' => 'squire',
                        'options' => array(
                            'squire' => __('Squire', 'easy-share-solution'),
                            'round'  => __('Round', 'easy-share-solution'),
                        )
                    ),
                    array(
                        'name'    => 'tee_active',
                        'label'   => __('Use text tweet', 'easy-share-solution'),
                        'desc'    => __('You can active or hide text tweet feature', 'easy-share-solution'),
                        'type'    => 'select',
                        'default' => 'yes',
                        'options' => array(
                            'yes' => __('Yes', 'easy-share-solution'),
                            'no'  => __('No', 'easy-share-solution'),
                        )
                    ),
                    array(
                        'name'              => 'min_text',
                        'label'             => __('Minimum length of selected text', 'easy-share-solution'),
                        'desc'              => __('Minimum length of selected text needed to show the "Tweet" button.', 'easy-share-solution'),
                        'type'              => 'number',
                        'default'           => 5,
                        'sanitize_callback' => 'intval'
                    ),
                    array(
                        'name'              => 'max_text',
                        'label'             => __('Maximum length of selected text', 'easy-share-solution'),
                        'desc'              => __('Maximum length of selected text after which the "Tweet" button is not shown.', 'easy-share-solution'),
                        'type'              => 'number',
                        'default'           => 60,
                        'sanitize_callback' => 'intval'
                    ),
                ),
                'easy_share_theme_rec' => array(

                    array(
                        'name'              => 'pro_theme',
                        'label'   => '',
                        'desc'    => __('Visit WP Theme Space and get your theme ', 'easy-share-solution') . $wplink,
                        'type'              => 'text',
                        'default'           => '20',
                        'sanitize_callback' => 'intval'
                    ),
                )
            );
            return $settings_fields;
        }
        function plugin_page()
        {
            echo '<a target="_blank" href="https://wpthemespace.com/pro-services/"><img src="https://wpthemespace.com/wp-content/uploads/2022/01/wordpress-service.jpg"></a>';
            echo '<div class="wrap easy-solution">';
            echo '<h1>' . esc_html__('Easy share solution settings', 'easy-share-solution') . '</h1>';
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();

            echo '</div>';
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages()
        {
            $pages = get_pages();
            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }

            return $pages_options;
        }
    }
endif;
require plugin_dir_path(__FILE__) . '/src/class.settings-api.php';
new easy_share_solution_options();


//Admin notice 
if (!function_exists('spacehide_go_me')) :
    function spacehide_go_me()
    {
        global $pagenow;
        if ($pagenow != 'themes.php') {
            return;
        }

        $class = 'notice notice-success is-dismissible';
        $url1 = esc_url('https://wpthemespace.com/product-category/pro-theme/');

        $message = __('<strong><span style="color:red;">Latest WordPress Theme:</span>  <span style="color:green"> If you find a Secure, SEO friendly, full functional premium WordPress theme for your site then </span>  </strong>', 'easy-share-solution');

        printf('<div class="%1$s" style="padding:10px 15px 20px;"><p>%2$s <a href="%3$s" target="_blank">' . __('see here', 'easy-share-solution') . '</a>.</p><a target="_blank" class="button button-danger" href="%3$s" style="margin-right:10px">' . __('View WordPress Theme', 'niso-carousel') . '</a></div>', esc_attr($class), wp_kses_post($message), $url1);
    }
    add_action('admin_notices', 'spacehide_go_me');
endif;

//Admin notice 
function easy_share_new_optins_texts()
{
    $estheme = wp_get_theme();
    $estheme_domain = $estheme->get('TextDomain');
    $hide_date = get_option('easy_share_newtext');
    if (!empty($hide_date)) {
        $clickhide = round((time() - strtotime($hide_date)) / 24 / 60 / 60);
        if ($clickhide < 25) {
            return;
        }
    }

    global $pagenow;
    if ($pagenow == 'themes.php' || $estheme_domain == 'newspaper-eye' || $estheme_domain == 'newspaper-eye-lite') {
        return;
    }

    $class = 'eye-notice notice notice-success is-dismissible';
    $message = __('<strong> Released Best New WordPress Theme For News, Blog & Magazine Websites. Try Free Today</strong> ', 'easy-share-solution');
    $url1 = esc_url(admin_url() . 'theme-install.php?search=newspaper-eye');
    $url2 = 'https://wpthemespace.com/newspaper-eye-demo/';

    printf('<div class="%1$s" style="padding:10px 15px 20px;"><p>%2$s</p><a  class="button button-primary" href="%3$s" style="margin-right:10px">' . __('Free, Check it now', 'easy-share-solution') . '</a><a target="_blank" class="button button-primary" href="%4$s" style="margin-right:10px">' . __('View Live Demo', 'easy-share-solution') . '</a><button class="button button-info wpepop-dismiss" style="margin-left:10px">' . __('No, Maybe later', 'easy-share-solution') . '</button></div>', esc_attr($class), wp_kses_post($message), $url1, $url2);
}
add_action('admin_notices', 'easy_share_new_optins_texts');

function easy_share_new_optins_texts_init()
{
    if (isset($_GET['dismissed']) && $_GET['dismissed'] == 1) {
        update_option('easy_share_newtext', current_time('mysql'));
    }
}
add_action('init', 'easy_share_new_optins_texts_init');
