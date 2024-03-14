<?php
/**
 * All In One WP Migration actions to be added to the Actions settings page.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$actions = array(
    'main_file'    => 'all-in-one-wp-migration/all-in-one-wp-migration.php',
    'is_active'    => defined( 'AI1WM_PATH' ),
    'ajax_actions' => array(
        'ai1wm_import' => array(
            'description' => esc_html__( 'installation importing', 'freesoul-deactivate-plugins' ),
            'notes'       => esc_html__( 'Be careful, you should disable the unwanted plugins both on the source before the exporting and on the destination installation', 'freesoul-deactivate-plugins' ),
        ),
        'ai1wm_export' => array( 'description' => esc_html__( 'installation exporting', 'freesoul-deactivate-plugins' ) ),
        'ai1wm_status' => array( 'description' => esc_html__( 'checking status', 'freesoul-deactivate-plugins' ) ),
    ),
);