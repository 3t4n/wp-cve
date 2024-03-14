<?php

/**
 * @class PPTwitterTimelineModule
 */
class PPTwitterTimelineModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          => __( 'Twitter Embedded Timeline', 'bb-powerpack' ),
			'description'   => __( 'A module to embed twitter timeline.', 'bb-powerpack' ),
			'group'         	=> pp_get_modules_group(),
			'category'			=> pp_get_modules_cat( 'social' ),
			'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-twitter-timeline/',
			'url'           	=> BB_POWERPACK_URL . 'modules/pp-twitter-timeline/',
			'editor_export' 	=> true, // Defaults to true and can be omitted.
			'enabled'       	=> true, // Defaults to true and can be omitted.
		));

		$this->add_js( 'pp-twitter-widgets' );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('PPTwitterTimelineModule', array(
	'general'       => array( // Tab
		'title'         => __( 'General', 'bb-powerpack' ), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => __( 'General', 'bb-powerpack' ), // Section Title
				'fields'        => array( // Section Fields
					'username'     	=> array(
						'type'          => 'text',
						'label'         => __( 'User Name', 'bb-powerpack' ),
						'default'       => '',
						'connections'	=> array( 'string' ),
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
					'show_replies'	=> array(
						'type'		=> 'pp-switch',
						'label'     => __( 'Show Replies', 'bb-powerpack' ),
						'default'   => 'no',
						'options'   => array(
							'yes'		=> __( 'Yes', 'bb-powerpack' ),
							'no'		=> __( 'No', 'bb-powerpack' ),
						),
					),
					'layout'  => array(
						'type'			=> 'select',
						'label'         => __( 'Layout', 'bb-powerpack' ),
						'default'       => '',
						'options'       => array(
							''				=> '',
							'noheader'		=> __( 'No Header', 'bb-powerpack' ),
							'nofooter'      => __( 'No Footer', 'bb-powerpack' ),
							'noborders'     => __( 'No Borders', 'bb-powerpack' ),
							'transparent'   => __( 'Transparent', 'bb-powerpack' ),
							'noscrollbar'   => __( 'No Scroll Bar', 'bb-powerpack' ),
						),
						'multi-select'  => true,
						'description'	=> __('Press <strong>ctrl + click</strong> OR <strong>cmd + click</strong> OR <strong>shift + click</strong> to select multiple.', 'bb-powerpack')
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
					'height'     	=> array(
						'type'          => 'unit',
						'label'         => __( 'Height', 'bb-powerpack' ),
						'description'	=> __('It will not work when Tweet Limit is set.', 'bb-powerpack'),
						'default'       => '',
						'units'   		=> array( 'px' ),
						'slider'		=> array(
							'min'			=> '1',
							'max'			=> '2000',
							'step'			=> '50'
						),
					),
					'tweet_limit'     	=> array(
						'type'          => 'unit',
						'label'         => __( 'Tweet Limit', 'bb-powerpack' ),
						'default'       => '',
						'slider'		=> true,
					),
					'link_color'     	=> array(
						'type'          => 'color',
						'label'         => __( 'Link Color', 'bb-powerpack' ),
						'show_reset'	=> true,
					),
					'border_color'     	=> array(
						'type'          => 'color',
						'label'         => __( 'Border Color', 'bb-powerpack' ),
						'show_reset'	=> true,
					),
				),
			),
		),
	),
));
