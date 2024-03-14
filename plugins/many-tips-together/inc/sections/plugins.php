<?php

/**
 * Section Plugins config
 * 
 * @package Admin Tweaks
 */

defined('ABSPATH') || exit;

$last_update_field = [];
if (!is_multisite()) {
    $last_update_field = [ # Last Update
        'id'       => 'plugins_add_last_updated',
        'type'     => 'switch',
        'title' => esc_html__('Add information about last update', 'mtt'),
        'desc' => sprintf(
            esc_html__('Incorporation of the plugin: %s. After enabling this, the first load of the plugins page may take a while for the information be retrieved. After the first load, the information is cached for 24 hours.', 'mtt'),
            ADTW()->makeTipCredit(
                'Plugin Last Updated',
                'http://wordpress.org/plugins/plugin-last-updated/'
            )
        ),
        'default'  => false,
    ];
}

$third_party_title = [];
$snippets_filter = [];
$snippets_move = [];
$acf_move = [];
$accessibility_move = [];
$notices_move = [];
$notices_rename_enable = [];
$notices_rename_text = [];
$third_party_plugins = false;



if (class_exists('WANC\Init')) :
    $third_party_plugins = true;
    $notices_move = [
        'id'               => 'plugins_hide_notices',
        'type'             => 'switch',
        'title'            => esc_html__('NOTIFICATION CENTER', 'mtt'),
        'subtitle'         => esc_html__('move to the Tools menu', 'mtt'),
    ];
    $notices_rename_enable = [
        'id'       => 'plugins_notices_rename',
        'type'     => 'switch',
        'title'    => esc_html__('NOTIFICATION CENTER', 'mtt'),
        'subtitle' => esc_html__('Rename "Notifications" on the admin bar', 'mtt'),
        'default'  => false,
    ];
    $notices_rename_text = [
        'id'       => 'plugins_notices_rename_text',
        'type'     => 'text',
        'title'    => esc_html__("New name for Notifications", 'mtt'),
        'required' => array('plugins_notices_rename', '=', true),
        'placeholder' => esc_html__('Leave empty for "Notices"')
    ];

endif;

if (class_exists('ACF')) :
    $third_party_plugins = true;
    $acf_move = [
        'id'               => 'plugins_acf_move_menu',
        'type'             => 'switch',
        'title'            => esc_html__('ADVANCED CUSTOM FIELDS', 'mtt'),
        'subtitle'         => esc_html__('move to the Tools menu', 'mtt'),
    ];

endif;

if (defined('CODE_SNIPPETS_FILE')) :
    $third_party_plugins = true;
    $snippets_filter = [
        'id'               => 'plugins_snippets_filter',
        'type'             => 'switch',
        'title'            => esc_html__('CODE SNIPPETS', 'mtt'),
        'desc'         => esc_html__('add filter by active/inactive and also by fragments', 'mtt'),
        'hint'     => array(
            'title'   => '',
            'content' => ADTW()->renderHintImg('general-filter-snippets.jpg'),
        ),
    ];
    $snippets_move = [
        'id'               => 'plugins_snippets_move_menu',
        'type'             => 'switch',
        'title'            => esc_html__('CODE SNIPPETS', 'mtt'),
        'subtitle'         => esc_html__('move to the Tools menu', 'mtt'),
    ];
endif;

if ($third_party_plugins) :
    $third_party_title = [
        'id'       => 'general-0',
        'type'     => 'section',
        'title'    => esc_html__('3RD PARTY PLUGINS', 'mtt'),
        'indent'   => false,
    ];
endif;


\Redux::set_section(
    $adtw_option,
    array(
        'title' => esc_html__('Plugins', 'mtt'),
        'id'    => 'plugins',
        'icon' => 'el el-inbox-alt',
        'fields' => [
            // MS: don't run
            array( # Live Filter
                'id'       => 'plugins_live_filter',
                'type'     => 'switch',
                'title' => esc_html__('Live filter by active/inactive', 'mtt'),
                'desc' => esc_html__('also by fragments of name/description/author', 'mtt'),
                'default'  => false,
                'hint'     => array(
                    'title'   => '',
                    'content' => "<br>" . ADTW()->renderHintImg('plugins-filtering.png'),
                )
            ),
            // MS: don't show
            array( # Block Upgrade check for all
                'id'       => 'plugins_block_update_notice',
                'type'     => 'switch',
                'title' => esc_html__('Block plugins upgrade check', 'mtt'),
                'default'  => false,
            ),
            // MS: don't show
            array( # Block Upgrade check for inactive
                'id'       => 'plugins_block_update_inactive_plugins',
                'type'     => 'switch',
                'title' => esc_html__('Block inactive plugins upgrade check', 'mtt'),
                'default'  => false,
            ),
            array( # Block email notifications for auto-updates
                'id'       => 'plugins_block_emails_updates',
                'type'     => 'switch',
                'title' => esc_html__('Disable auto-update email notifications', 'mtt'),
                'default'  => false,
            ),
            /*array( # Remove Extra notices
                'id'       => 'plugins_remove_plugin_notice',
                'type'     => 'switch',
                'title'    => esc_html__( 'Remove extra plugins notices (normally in yellow)', 'mtt' ),
                'default'  => false,
            ),*/
            $last_update_field,
            array( # Inactive Plugins BG color
                'id'          => 'plugins_inactive_bg_color',
                'type'        => 'color',
                'title'       => esc_html__('Inactive Plugins background color', 'mtt'),
                'transparent' => false,
                'color_alpha' => false,
            ),
            array( # My Plugins BG color
                'id'       => 'plugins_my_plugins_bg_color',
                'type'     => 'switch',
                'title'    => esc_html__('Colorize specific plugins.', 'mtt'),
                'desc'     => esc_html__('Use to display some plugins (yours!) with other color.', 'mtt'),
                'default'  => false,
            ),
            array( ## Names
                'id'       => 'plugins_my_plugins_names',
                'type'     => 'text',
                'title'    => esc_html__("Enter a list of keywords separated by comma, no spaces. Normally, you'll use the plugin authors.", 'mtt'),
                'required' => array('plugins_my_plugins_bg_color', '=', true),
            ),
            array( ## Color
                'id'          => 'plugins_my_plugins_color',
                'type'        => 'color',
                'title'       => esc_html__('Your Plugins background color ;o)', 'mtt'),
                'transparent' => false,
                'color_alpha' => false,
                'required' => array('plugins_my_plugins_bg_color', '=', true),
            ),
            array( ## Show Count
                'id'       => 'plugins_my_plugins_count',
                'type'     => 'switch',
                'title'    => esc_html__('Display count', 'mtt'),
                'default'  => false,
                'required' => array('plugins_my_plugins_bg_color', '=', true),
            ),

            ################# OTHER PLUGINS
            $third_party_title,
            $snippets_move,
            $snippets_filter,
            $acf_move,
            $accessibility_move,
            $notices_move,
            $notices_rename_enable,
            $notices_rename_text
        ]
    )
);
