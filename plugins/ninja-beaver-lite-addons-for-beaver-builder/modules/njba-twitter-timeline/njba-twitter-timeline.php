<?php

/**
 * @class NJBA_Twitter_Timeline_Module
 */
class NJBA_Twitter_Timeline_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Twitter Embedded Timeline', 'bb-njba' ),
			'description'     => __( 'A module to embed twitter timeline.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'social' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-twitter-timeline/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-twitter-timeline/',
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
FLBuilder::register_module( 'NJBA_Twitter_Timeline_Module', array(
	'general' => array( // Tab
		'title'       => __( 'General', 'bb-njba' ), // Tab title
		'description' => __( 'For Help Read <a href="https://ninjabeaveraddon.com/documentation/" target="_blank">Documentation</a>', 'bb-njba' ),
		'sections'    => array( // Tab Sections
			'general' => array( // Section
				'title'  => __( 'General', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'username'     => array(
						'type'        => 'text',
						'label'       => __( 'User Name', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'string' ),
					),
					'theme'        => array(
						'type'    => 'select',
						'label'   => __( 'Theme', 'bb-njba' ),
						'default' => 'light',
						'options' => array(
							'light' => __( 'Light', 'bb-njba' ),
							'dark'  => __( 'Dark', 'bb-njba' ),
						),
					),
					'show_replies' => array(
						'type'    => 'select',
						'label'   => __( 'Show Replies', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'layout'       => array(
						'type'         => 'select',
						'label'        => __( 'Layout', 'bb-njba' ),
						'default'      => '',
						'options'      => array(
							''            => '',
							'noheader'    => __( 'No Header', 'bb-njba' ),
							'nofooter'    => __( 'No Footer', 'bb-njba' ),
							'noborders'   => __( 'No Borders', 'bb-njba' ),
							'transparent' => __( 'Transparent', 'bb-njba' ),
							'noscrollbar' => __( 'No Scroll Bar', 'bb-njba' ),
						),
						'multi-select' => true,
						'description'  => __( 'Press <strong>ctrl + click</strong> OR <strong>cmd + click</strong> OR <strong>shift + click</strong> to select multiple.',
							'bb-njba' )
					),
					'width'        => array(
						'type'        => 'text',
						'label'       => __( 'Width', 'bb-njba' ),
						'default'     => '',
						'description' => __( 'px', 'bb-njba' ),
						'size'        => 5,
					),
					'height'       => array(
						'type'        => 'text',
						'label'       => __( 'Height', 'bb-njba' ),
						'default'     => '',
						'description' => __( 'px', 'bb-njba' ),
						'size'        => 5,
					),
					'tweet_limit'  => array(
						'type'    => 'text',
						'label'   => __( 'Tweet Limit', 'bb-njba' ),
						'default' => '',
						'size'    => 5,
					),
					'link_color'   => array(
						'type'       => 'color',
						'label'      => __( 'Link Color', 'bb-njba' ),
						'show_reset' => true,
					),
					'border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
					),
				),
			),
		),
	),
) );
