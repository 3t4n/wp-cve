<?php

/**
 * @class NJBAT_witter_Tweet_Module
 */
class NJBAT_witter_Tweet_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Twitter Embedded Tweet', 'bb-njba' ),
			'description'     => __( 'A module to embed twitter tweet.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'social' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-twitter-tweet/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-twitter-tweet/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
		) );
		$this->add_js( 'njba-twitter-widgets' );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBAT_witter_Tweet_Module', array(
	'general' => array( // Tab
		'title'       => __( 'General', 'bb-njba' ), // Tab title
		'description' => __( 'For Help Read <a href="https://ninjabeaveraddon.com/documentation/" target="_blank">Documentation</a>', 'bb-njba' ),
		'sections'    => array( // Tab Sections
			'general' => array( // Section
				'title'  => __( 'General', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'tweet_url'  => array(
						'type'        => 'text',
						'label'       => __( 'Tweet URL', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'url' ),
					),
					'theme'      => array(
						'type'    => 'select',
						'label'   => __( 'Theme Schema', 'bb-njba' ),
						'default' => 'light',
						'options' => array(
							'light' => __( 'Light', 'bb-njba' ),
							'dark'  => __( 'Dark', 'bb-njba' ),
						),
					),
					'expanded'   => array(
						'type'    => 'select',
						'label'   => __( 'Tweet Expanded', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'alignment'  => array(
						'type'    => 'select',
						'label'   => __( 'Tweet Alignment', 'bb-njba' ),
						'default' => 'center',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
					),
					'width'      => array(
						'type'        => 'text',
						'label'       => __( 'Tweet Width', 'bb-njba' ),
						'default'     => '',
						'description' => 'px',
						'size'        => 5,
					),
					'link_color' => array(
						'type'       => 'color',
						'label'      => __( 'Link Color', 'bb-njba' ),
						'show_reset' => true
					),
				),
			),
		),
	),
) );
