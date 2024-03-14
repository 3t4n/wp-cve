<?php

/**
* Get social sharing button options
* @return array Options in array format
*/
function dvkss_get_options()
{
	static $options;

	// load options from database
	if( ! $options ) {

		// default options
		$defaults = array(
			'load_icon_css' => 1,
			'load_popup_js' => 0,
			'icon_size' => 32,
			'twitter_username' => '',
			'auto_add_post_types' => array( 'post' ),
			'before_text' => "Share this post: ",
            'social_options' => array( 'twitter', 'facebook', 'googleplus' ),
		);

		// get options from db
		$options = get_option( 'dvk_social_sharing', array() );

		// add option to database if not set, saves a query
		if( ! $options ) {
			update_option( 'dvk_social_sharing', $defaults );
		}

		// merge with default options to prevent notices
		$options = array_merge( $defaults, $options );
	}

	return $options;
}
