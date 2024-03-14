<?php

class Youzify_Messages {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

			// Actions.
			add_action( 'bp_init', array( self::$instance, 'hide_emoji_from_content' ) );

		}

		return self::$instance;

	}

	/**
	 * Strip Emoji From Content.
	 */
	function hide_emoji_from_content() {

	    // Hide Messages Emoji.
	    if ( 'off' == youzify_option( 'youzify_enable_messages_emoji', 'on' ) ) {
	        add_filter( 'bp_get_the_thread_message_content', 'youzify_remove_emoji' );
	        add_filter( 'bp_get_message_thread_excerpt', 'youzify_remove_emoji' );
	        add_filter( 'bp_get_message_notice_text', 'youzify_remove_emoji' );
	    }

	}

}

/**
 * Get a unique instance of Youzify Messages.
 */
function youzify_messages() {
	return Youzify_Messages::get_instance();
}

/**
 * Launch Youzify Messages!
 */
youzify_messages();