<?php
/**
 * Google Analytics for WordPress by MonsterInsights actions to be added to the Actions settings page.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$actions = array(
    'main_file'    => 'google-analytics-for-wordpress/googleanalytics.php',
    'is_active'    => defined( 'MONSTERINSIGHTS_PLUGIN_NAME' ),
    'ajax_actions' => array(
        'monsterinsights_vue_get_notifications'  => array( 'description' => esc_html__( 'Get notifications', 'freesoul-deactivate-plugins' ) ),
        'monsterinsights_vue_get_settings'       => array( 'description' => esc_html__( 'Get settings', 'freesoul-deactivate-plugins' ) ),
        'monsterinsights_vue_get_profile'        => array( 'description' => esc_html__( 'Get profile', 'freesoul-deactivate-plugins' ) ),
        'monsterinsights_vue_get_addons'         => array( 'description' => esc_html__( 'Get addons', 'freesoul-deactivate-plugins' ) ),
        'monsterinsights_get_floatbar'           => array( 'description' => esc_html__( 'Get floatbar', 'freesoul-deactivate-plugins' ) ),
    )
);