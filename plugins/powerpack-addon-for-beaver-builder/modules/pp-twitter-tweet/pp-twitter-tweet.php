<?php

/**
 * @class PPTwitterTweetModule
 */
class PPTwitterTweetModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          => __( 'Twitter Embedded Tweet', 'bb-powerpack' ),
			'description'   => __( 'A module to embed twitter tweet.', 'bb-powerpack' ),
			'group'         	=> pp_get_modules_group(),
			'category'			=> pp_get_modules_cat( 'social' ),
			'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-twitter-tweet/',
			'url'           	=> BB_POWERPACK_URL . 'modules/pp-twitter-tweet/',
			'editor_export' 	=> true, // Defaults to true and can be omitted.
			'enabled'       	=> true, // Defaults to true and can be omitted.
		));

		$this->add_js( 'pp-twitter-widgets' );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('PPTwitterTweetModule', array(
	'general'       => array( // Tab
		'title'         => __( 'General', 'bb-powerpack' ), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => __( 'General', 'bb-powerpack' ), // Section Title
				'fields'        => array( // Section Fields
					'tweet_url'     	=> array(
						'type'          => 'text',
						'label'         => __( 'Tweet URL', 'bb-powerpack' ),
						'default'       => '',
						'connections'	=> array( 'url' ),
					),
					'theme'	=> array(
						'type'		=> 'pp-switch',
						'label'     => __( 'Theme', 'bb-powerpack' ),
						'default'   => 'light',
						'options'   => array(
							'light'		=> __( 'Light', 'bb-powerpack' ),
							'dark'		=> __( 'Dark', 'bb-powerpack' ),
						),
					),
					'expanded'	=> array(
						'type'		=> 'pp-switch',
						'label'     => __( 'Expanded', 'bb-powerpack' ),
						'default'   => 'yes',
						'options'   => array(
							'yes'		=> __( 'Yes', 'bb-powerpack' ),
							'no'		=> __( 'No', 'bb-powerpack' ),
						),
					),
					'alignment'	=> array(
						'type'		=> 'align',
						'label'     => __( 'Alignment', 'bb-powerpack' ),
						'default'   => 'center',
					),
					'width'     	=> array(
						'type'          => 'unit',
						'label'         => __( 'Width', 'bb-powerpack' ),
						'default'       => '',
						'units'   		=> array( 'px' ),
						'slider'		=> array(
							'min'			=> '1',
							'max'			=> '2000',
							'step'			=> '50'
						),
					),
					'link_color'     	=> array(
						'type'          => 'color',
						'label'         => __( 'Link Color', 'bb-powerpack' ),
						'show_reset'	=> true
					),
				),
			),
		),
	),
));
