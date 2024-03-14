<?php

/**
 * @class PPTwitterGridModule
 */
class PPTwitterGridModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          => __( 'Twitter Embedded Grid', 'bb-powerpack' ),
			'description'   => __( 'A collection timeline displays multiple Tweets curated by a Twitter user in their chosen display order or sorted by time.', 'bb-powerpack' ),
			'group'         	=> pp_get_modules_group(),
			'category'			=> pp_get_modules_cat( 'social' ),
			'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-twitter-grid/',
			'url'           	=> BB_POWERPACK_URL . 'modules/pp-twitter-grid/',
			'editor_export' 	=> true, // Defaults to true and can be omitted.
			'enabled'       	=> true, // Defaults to true and can be omitted.
		));

		$this->add_js( 'pp-twitter-widgets' );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('PPTwitterGridModule', array(
	'general'       => array( // Tab
		'title'         => __( 'General', 'bb-powerpack' ), // Tab title
		'description'	=> sprintf(
			__('<span style="color:red;">**Twitter removed support for embedded collections (%s). Please use List instead.**</span><br><br>Please refer to this guide to create List and get the List URL %s', 'bb-powerpack'),
			'<a href="https://twittercommunity.com/t/removing-support-for-embedded-like-collection-and-moment-timelines/150313">' . __('more details', 'bb-powerpack') . '</a>',
			'<a href="https://help.twitter.com/en/using-twitter/twitter-lists" target="_blank">https://help.twitter.com/en/using-twitter/twitter-lists</a>'
		),
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => __( 'General', 'bb-powerpack' ), // Section Title
				'fields'        => array( // Section Fields
					'username' => array(
						'type'  => 'text',
						'label' => __( 'Twitter Username', 'bb-powerpack' ),
						'connections' => array( 'string' ),
					),
					'url'     		=> array(
						'type'          => 'text',
						'label'         => __( 'List URL', 'bb-powerpack' ),
						'default'       => '',
						'connections'	=> array( 'url' ),
					),
					'footer'  		=> array(
						'type'			=> 'pp-switch',
						'label'         => __( 'Show Footer?', 'bb-powerpack' ),
						'default'       => 'yes',
						'options'       => array(
							'yes'			=> __( 'Yes', 'bb-powerpack' ),
							'no'       		=> __( 'No', 'bb-powerpack' ),
						),
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
					'tweet_limit'	=> array(
						'type'          => 'unit',
						'label'         => __( 'Tweet Limit', 'bb-powerpack' ),
						'default'       => '',
						'slider'		=> true,
					),
				),
			),
		),
	),
));
