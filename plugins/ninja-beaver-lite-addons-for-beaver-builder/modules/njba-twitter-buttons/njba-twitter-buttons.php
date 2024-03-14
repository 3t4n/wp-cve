<?php

/**
 * @class NJBA_Twitter_Buttons_Module
 */
class NJBA_Twitter_Buttons_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Twitter Buttons', 'bb-njba' ),
			'description'     => __( 'A module to embed twitter buttons.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'social' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-twitter-buttons/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-twitter-buttons/',
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
FLBuilder::register_module( 'NJBA_Twitter_Buttons_Module', array(
	'general' => array( // Tab
		'title'       => __( 'General', 'bb-njba' ), // Tab title
		'description' => __( 'Required For used Button type Massage.</br></br> The user ID of the recipient @username that will receive the message.</br></br>Step 1 - Go to Twitter and sign in. Then click on profile button from header and open "Settings and privacy".</br></br> Step 2 - Click on the "Your Twitter data" tab from the sidebar and confirm your password.</br></br> Step 3 - Done. Now you can see your User ID, under the username.</br></br> For More Help <a href="https://ninjabeaveraddon.com/documentation/" target="_blank"> Click Here </a>',
			'bb-njba' ),
		'sections'    => array( // Tab Sections
			'general' => array( // Section
				'title'  => __( 'General', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'button_type'  => array(
						'type'    => 'select',
						'label'   => __( 'Button Type', 'bb-njba' ),
						'default' => 'share',
						'options' => array(
							'share'   => __( 'Share', 'bb-njba' ),
							'follow'  => __( 'Follow', 'bb-njba' ),
							'mention' => __( 'Mention', 'bb-njba' ),
							'hashtag' => __( 'Hashtag', 'bb-njba' ),
							'message' => __( 'Message', 'bb-njba' ),
						),
						'toggle'  => array(
							'share'   => array(
								'fields' => array( 'share_text', 'via', 'share_url' ),
							),
							'follow'  => array(
								'fields' => array( 'profile' ),
							),
							'mention' => array(
								'fields' => array( 'profile', 'via', 'share_text', 'share_url', 'show_count' ),
							),
							'hashtag' => array(
								'fields' => array( 'hashtag_url', 'via', 'share_text', 'share_url' ),
							),
							'message' => array(
								'fields' => array( 'profile', 'recipient_id', 'default_text' ),
							),
						),
					),
					'profile'      => array(
						'type'        => 'text',
						'label'       => __( 'Profile URL or Username', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'string', 'url' ),
					),
					'recipient_id' => array(
						'type'        => 'text',
						'label'       => __( 'Recipient ID', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'string' ),
					),
					'default_text' => array(
						'type'        => 'text',
						'label'       => __( 'Default Text', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'string', 'url' ),
						'help'        => __( 'Optional. Use this field to pre-populate message text.', 'bb-njba' )
					),
					'hashtag_url'  => array(
						'type'        => 'text',
						'label'       => __( 'Hashtag URL or #hashtag', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'string', 'url' ),
					),
					'via'          => array(
						'type'        => 'text',
						'label'       => __( 'Via (twitter handler)', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'string' ),
					),
					'share_text'   => array(
						'type'        => 'text',
						'label'       => __( 'Custom Share Text', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'string' ),
					),
					'share_url'    => array(
						'type'        => 'text',
						'label'       => __( 'Custom Share URL', 'bb-njba' ),
						'default'     => '',
						'connections' => array( 'string' ),
					),
					'show_count'   => array(
						'type'    => 'select',
						'label'   => __( 'Show Count', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
					'large_button' => array(
						'type'    => 'select',
						'label'   => __( 'Large Button?', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),
				),
			),
		),
	),
) );
