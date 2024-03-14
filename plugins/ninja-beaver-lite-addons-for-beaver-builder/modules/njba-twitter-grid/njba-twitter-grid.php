<?php

/**
 * @class NJBA_Twitter_Grid_Module
 */
class NJBA_Twitter_Grid_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Twitter Embedded Grid', 'bb-njba' ),
			'description'     => __( 'A collection timeline displays multiple Tweets curated by a Twitter user in their chosen display order or sorted by time.',
				'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'social' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-twitter-grid/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-twitter-grid/',
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
FLBuilder::register_module( 'NJBA_Twitter_Grid_Module', array(
	'general' => array( // Tab
		'title'       => __( 'General', 'bb-njba' ),
		'description' => __( 'Looking for info <a href="https://help.twitter.com/en/using-twitter/advanced-tweetdeck-features?lang=browser" target="_blank"> Click here </a> and for more info <a href="https://developer.twitter.com/en/docs/tweets/curate-a-collection/overview/overview" target="_blank"> Click here </a>.</br></br> For Help Read <a href="https://ninjabeaveraddon.com/documentation/" target="_blank">Documentation</a>',
			'bb-njba' ),
		'sections'    => array( // Tab Sections
			'general' => array( // Section
				'title'  => __( 'General', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'url'         => array(
						'type'        => 'text',
						'label'       => __( 'Collection URL', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'url' ),
					),
					'footer'      => array(
						'type'    => 'select',
						'label'   => __( 'Show Footer?', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'width'       => array(
						'type'        => 'text',
						'label'       => __( 'Width', 'bb-njba' ),
						'default'     => '',
						'description' => __( 'px', 'bb-njba' ),
						'size'        => 5,
					),
					'tweet_limit' => array(
						'type'    => 'text',
						'label'   => __( 'Tweet Limit', 'bb-njba' ),
						'default' => '',
						'size'    => 5,
					),
				),
			),
		),
	),
) );
