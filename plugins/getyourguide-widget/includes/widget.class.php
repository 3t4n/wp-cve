<?php

/**
 * The GetYourGuide Wordpress widget that enables partners to easily display the widget on their Wordpress site.
 */
class GetYourGuide_Widget extends WP_Widget {
	/**
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * widget file.
	 *
	 * @since    1.0.0*
	 * @var      string
	 */
	const WIDGET_SLUG = 'getyourguide-widget';

	/**
	 * @inheritdoc
	 */
	public function __construct() {
		// Load plugin text domain
		add_action( 'init', [ $this, 'widget_textdomain' ] );

		parent::__construct(
			self::WIDGET_SLUG,
			__( 'GetYourGuide Widget', 'getyourguide-widget' ),
			[
				'classname'   => self::WIDGET_SLUG . '-class',
				'description' => __( 'Displays GetYourGuide tours and activities.', 'getyourguide-widget' ),
			]
		);

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', [ $this, 'register_widget_scripts' ] );
		add_filter( 'script_loader_tag', [ $this, 'add_id_to_script' ], 10, 3 );

		// Refreshing the widget's cached output with each new post
		add_action( 'save_post', [ $this, 'flush_widget_cache' ] );
		add_action( 'edit_post', [ $this, 'flush_widget_cache' ] );
		add_action( 'deleted_post', [ $this, 'flush_widget_cache' ] );
		add_action( 'switch_theme', [ $this, 'flush_widget_cache' ] );
	}

	// Giving a verbose output to inform user to use 'Guttenberg Blocks' to add GetYourGuide widgets
	public function widget($args, $instance) {
		echo $args['before_widget'];
		echo $args['before_title'] . apply_filters('widget_title', 'GetYourGuide Widget Plugin') . $args['after_title'];
		echo '>> This plugin does not support adding widgets via the wordpress appearance menu. To use this plugin, please add the widgets within your posts, by adding new blocks. <<';
		echo $args['after_widget'];
	}

	public function flush_widget_cache() {
		wp_cache_delete( self::WIDGET_SLUG, 'widget' );
	}

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {
		$domainPath = dirname( getyourguide_widget_plugin_self() ) . '/languages';
		load_plugin_textdomain( self::WIDGET_SLUG, false, $domainPath );
	}

	/**
	 * Inclusion of analytics script
	 */

	function add_id_to_script( $tag, $handle, $source ) {
		
		if ( self::WIDGET_SLUG . '-script' === $handle ) {
			$partnerId = get_option( GetYourGuide_Widget_Settings::OPTION_NAME_PARTNER_ID ); 
			$tag = '<script async defer src="'.$source.'" data-gyg-partner-id="'.$partnerId.'"></script>';
		}
		return $tag;
	}

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {
		wp_enqueue_script(
			self::WIDGET_SLUG . '-script',
			'https://widget.getyourguide.com/dist/pa.umd.production.min.js',
			[], // no dependencies
			null // don't add any version number
		);
	}

}
