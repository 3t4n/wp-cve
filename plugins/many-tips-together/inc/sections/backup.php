<?php
/**
 * Section Backup config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

\Redux::set_section(
	$adtw_option,
	array(
		'id'               => 'import-export-options',
		'title'            => esc_html__( 'Backups', 'mtt' ),
		'subtitle'         => esc_html__( 'Import and Export the plugin options settings from file, text or URL.', 'mtt' ),
		'icon'             => 'dashicons dashicons-backup',
		'fields'           => array(
			array(
				'id'       => 'import-export',
				'type'     => 'import_export',
				'title' => esc_html__( 'Save and restore Admin Tweaks options', 'mtt' ),
			),
		),
	)
);