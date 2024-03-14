<?php
/*
Plugin Name: BNS Corner Logo
Plugin URI: http://buynowshop.com/plugins/bns-corner-logo/
Description: Widget to display a user selected image as a logo; or, used as a plugin that displays the image fixed in one of the four corners of the display.
Version: 2.2
Text Domain: bns-corner-logo
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Corner Logo
 *
 * Widget to display a user selected image as a logo; or, used as a plugin that
 * displays the image fixed in one of the four corners of the display.
 *
 * @package     BNS_Corner_Logo
 * @link        http://buynowshop.com/plugins/bns-corner-logo/
 * @link        https://github.com/Cais/bns-corner-logo/
 * @link        https://wordpress.org/plugins/bns-corner-logo/
 * @version     2.2
 * @author      Edward Caissie <edward.caissie@gmail.com>
 * @copyright   Copyright (c) 2009-2016, Edward Caissie
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.
 *
 * You may NOT assume that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to:
 *
 *      Free Software Foundation, Inc.
 *      51 Franklin St, Fifth Floor
 *      Boston, MA  02110-1301  USA
 *
 * The license for this software can also likely be found here:
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @version     2.2
 * @date        January 2016
 */
class BNS_Corner_Logo extends WP_Widget {

	private static $instance = null;

	/**
	 * Create Instance
	 *
	 * Creates a single instance of the class
	 *
	 * @package BNS_Corner_Logo
	 * @since   2.1
	 * @date    November 26, 2015
	 *
	 * @return null|BNS_Corner_Logo
	 */
	public static function create_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * BNS Corner Logo
	 *
	 * Used to extend WP_Widget class
	 *
	 * @package BNS_Corner_Logo
	 * @since   0.1
	 *
	 * @uses    WP_CONTENT_DIR
	 * @uses    WP_Widget
	 * @uses    __
	 * @uses    add_action
	 * @uses    add_filter
	 * @uses    content_url
	 * @uses    load_plugin_textdomain
	 * @uses    plugin_basename
	 * @uses    register_activation_hook
	 *
	 * @version 2.1
	 * @date    November 26, 2015
	 * Moved compatibility check to be parsed first via `register_activation_hook`
	 * Moved `update_message` function into class a method call
	 *
	 * @version 2.2
	 * @date    January 3, 2016
	 * Updated inline documentation and added call to dashboard widget
	 */
	function __construct() {

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		load_plugin_textdomain( 'bns-corner-logo' );

		/** Widget settings */
		$widget_ops = array(
			'classname'   => 'bns-corner-logo',
			'description' => __( 'Widget to display a logo; or, used as a plugin displays image fixed in one of the four corners.', 'bns-corner-logo' )
		);

		/** Widget control settings */
		$control_ops = array( 'width' => 200, 'id_base' => 'bns-corner-logo' );

		/** Create the widget */
		parent::__construct( 'bns-corner-logo', 'BNS Corner Logo', $widget_ops, $control_ops );

		/** Define location for BNS plugin customizations */
		if ( ! defined( 'BNS_CUSTOM_PATH' ) ) {
			define( 'BNS_CUSTOM_PATH', WP_CONTENT_DIR . '/bns-customs/' );
		}
		if ( ! defined( 'BNS_CUSTOM_URL' ) ) {
			define( 'BNS_CUSTOM_URL', content_url( '/bns-customs/' ) );
		}

		/** Add widget */
		add_action( 'widgets_init', array( $this, 'load_bnscl_widget' ) );

		/** Add scripts and style */
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_and_styles' ) );

		/** Add update message */
		add_action( 'in_plugin_update_message-' . plugin_basename( __FILE__ ), array( $this, 'update_message' ) );

		/** Add Plugin Row Meta details */
		add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), 10, 2 );

		/** Add Dashboard widget for support references */
		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_widget_for_bns_corner_logo_support' ) );

		/** Hide Dashboard widget by default via Screen Options */
		add_filter( 'default_hidden_meta_boxes', array( $this, 'default_screen_option' ) );

	}


	/**
	 * Check installed WordPress version for compatibility
	 *
	 * @package     BNS_Corner_Logo
	 * @since       2.1
	 * @date        November 27, 2015
	 *
	 * @internal    Version 3.0 being used in reference to home_url()
	 *
	 * @uses        BNS_Corner_Logo::plugin_data
	 * @uses        __
	 * @uses        apply_filters
	 * @uses        deactivate_plugins
	 * @uses        get_bloginfo
	 *
	 * @version     2.2
	 * @date        January 3, 2016
	 * Added hook to allow for arbitrary change to "Requires at least" version
	 * Minor change to `$exit_message` text
	 */
	function install() {

		/** @var float $version_required - see "Requires at least" from `readme.txt` */
		$version_required = apply_filters( 'bns_corner_logo_requires_at_least_version', '3.0' );

		$plugin_data = $this->plugin_data();

		/** @var string $exit_message - build an explanation message */
		$exit_message = sprintf( __( '%1$s requires WordPress version %2$s or later.', 'bns-corner-logo' ), $plugin_data['Name'], $version_required );
		$exit_message .= '<br />';
		$exit_message .= sprintf( '<a href="http://codex.wordpress.org/Upgrading_WordPress" target="_blank">%1$s</a>', __( 'Please Update!', 'bns-corner-logo' ) );

		/** Conditional check of current WordPress version */
		if ( version_compare( get_bloginfo( 'version' ), floatval( $version_required ), '<' ) ) {

			deactivate_plugins( basename( __FILE__ ) );
			exit( $exit_message );

		}

	}


	/**
	 * Plugin Data
	 *
	 * Returns the plugin header data as an array
	 *
	 * @package    BNS_Corner_Logo
	 * @since      2.1
	 *
	 * @uses       get_plugin_data
	 *
	 * @return array
	 */
	function plugin_data() {

		/** Call the wp-admin plugin code */
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		/** @var $plugin_data - holds the plugin header data */
		$plugin_data = get_plugin_data( __FILE__ );

		return $plugin_data;

	}


	/**
	 * Override widget method of class WP_Widget
	 *
	 * @param   $args
	 * @param   $instance
	 *
	 * @uses    apply_filters
	 * @uses    esc_attr
	 * @uses    get_avatar
	 * @uses    get_userdata
	 *
	 * @return  void
	 *
	 * @version 1.8.4
	 * @date    January 29, 2014
	 * Properly escape the alt image attribute
	 *
	 * @version 1.8.5
	 * @date    January 30, 2015
	 * More sanitizing - `$image_url` and `$image_link`; thanks ScheRas
	 *
	 * @version 2.0
	 * @date    July 10, 2015
	 * Added Mallory-Everest filter hook `bnscl_image_tag_alt_title`
	 *
	 * @version 2.2
	 * @date    December 20, 2015
	 * Include attribute in `bnscl_image_tag_alt_title` default filter value
	 */
	function widget( $args, $instance ) {

		extract( $args );
		/** User-selected settings */
		$title            = apply_filters( 'widget_title', $instance['title'] );
		$use_gravatar     = $instance['use_gravatar'];
		$gravatar_user_id = $instance['gravatar_user_id'];
		$gravatar_size    = $instance['gravatar_size'];
		$image_url        = $instance['image_url'];
		$image_alt_text   = $instance['image_alt_text'];
		$image_link       = $instance['image_link'];
		$new_window       = $instance['new_window'];
		$widget_plugin    = $instance['widget_plugin'];
		$logo_location    = $instance['logo_location'];

		if ( ! $widget_plugin ) {
			/** @var $before_widget string - define by theme */
			echo $before_widget;

			/* Title of widget (before and after defined by themes). */
			if ( $title ) {
				/**
				 * @var $before_title   string - defined by theme
				 * @var $after_title    string - defined by theme
				 */
				echo $before_title . $title . $after_title;
			}

			/** Display image based on widget settings */ ?>
			<div class="bns-logo">
				<a <?php if ( $new_window ) {
					echo 'target="_blank"';
				} ?> href="<?php echo esc_url( $image_link ); ?>">
					<!-- Use FIRST Admin gravatar user ID = 1 as default -->
					<?php if ( $use_gravatar ) {
						$user_details = get_userdata( $gravatar_user_id );
						$user_email   = $user_details->user_email;
						echo get_avatar( $user_email, $gravatar_size );
					} else {
						echo $image_tag = '<img ' . apply_filters( 'bnscl_image_tag_alt_title', 'alt="' ) . esc_attr( $image_alt_text ) . '" src="' . esc_url( $image_url ) . '" />';
					} ?>
				</a>
			</div><!-- .bns-logo -->

		<?php } else {

			if ( $logo_location == "Bottom-Right" ) {
				$logo_position = 'fixed-bottom-right';
			} elseif ( $logo_location == "Bottom-Left" ) {
				$logo_position = 'fixed-bottom-left';
			} elseif ( $logo_location == "Top-Right" ) {
				$logo_position = 'fixed-top-right';
			} elseif ( $logo_location == "Top-Left" ) {
				$logo_position = 'fixed-top-left';
			} ?>

			<div class="bns-logo <?php echo $logo_position; ?>">
				<a <?php if ( $new_window ) {
					echo 'target="_blank"';
				} ?> href="<?php echo esc_url( $image_link ); ?>">
					<!-- Use FIRST Admin gravatar user ID = 1 as default -->
					<?php if ( $use_gravatar ) {
						$user_details = get_userdata( $gravatar_user_id );
						$user_email   = $user_details->user_email;
						echo get_avatar( $user_email, $gravatar_size );
					} else {
						echo $image_tag = '<img ' . apply_filters( 'bnscl_image_tag_alt_title', 'alt="' ) . esc_attr( $image_alt_text ) . '"  src="' . esc_url( $image_url ) . '" />';
					} ?>
				</a>
			</div><!-- .bns-logo -->
		<?php }
		/** End - Display image based on widget settings */

		/** After widget (defined by themes) */
		if ( ! $widget_plugin ) {
			/** @var $after_widget   string - defined by theme */
			echo $after_widget;
		}

	}


	/**
	 * Override update method of class WP_Widget
	 *
	 * @param   $new_instance
	 * @param   $old_instance
	 *
	 * @return  array - widget options and settings
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		/** Strip tags (if needed) and update the widget settings */
		$instance['title']            = strip_tags( $new_instance['title'] );
		$instance['use_gravatar']     = $new_instance['use_gravatar'];
		$instance['gravatar_user_id'] = $new_instance['gravatar_user_id'];
		$instance['gravatar_size']    = $new_instance['gravatar_size'];
		$instance['image_url']        = strip_tags( $new_instance['image_url'] );
		$instance['image_alt_text']   = strip_tags( $new_instance['image_alt_text'] );
		$instance['image_link']       = strip_tags( $new_instance['image_link'] );
		$instance['new_window']       = $new_instance['new_window'];
		$instance['widget_plugin']    = $new_instance['widget_plugin'];
		$instance['logo_location']    = $new_instance['logo_location'];

		return $instance;

	}


	/**
	 * Overrides form method of class WP_Widget
	 *
	 * @package BNS_Corner_Logo
	 * @since   1.0
	 *
	 * @param   $instance
	 *
	 * @uses    checked
	 * @uses    get_field_id
	 * @uses    get_field_name
	 * @uses    selected
	 *
	 * @return  void
	 *
	 * @version 1.7
	 * @date    November 21, 2012
	 * Added i18n support to position drop-down in widget control panel
	 */
	function form( $instance ) {

		/** Set up some default widget settings */
		$defaults = array(
			'title'            => __( 'My Logo Image', 'bns-corner-logo' ),
			'use_gravatar'     => false,
			'gravatar_user_id' => '1',
			'gravatar_size'    => '96',
			'image_url'        => '',
			'image_alt_text'   => '',
			'image_link'       => '',
			'new_window'       => false,
			'widget_plugin'    => false,
			'logo_location'    => 'Bottom-Right'
		);
		$instance = wp_parse_args( ( array ) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bns-corner-logo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( ( bool ) $instance['use_gravatar'], true ); ?> id="<?php echo $this->get_field_id( 'use_gravatar' ); ?>" name="<?php echo $this->get_field_name( 'use_gravatar' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'use_gravatar' ); ?>"><?php printf( __( 'Use your %1$s image?', 'bns-corner-logo' ), '<a href="http://gravatar.com">Gravatar</a>' ); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'gravatar_user_id' ); ?>"><?php _e( 'Set Gravatar by User ID Number', 'bns-corner-logo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'gravatar_user_id' ); ?>" name="<?php echo $this->get_field_name( 'gravatar_user_id' ); ?>" value="<?php echo $instance['gravatar_user_id']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'gravatar_size' ); ?>"><?php _e( 'Gravatar size in pixels:', 'bns-corner-logo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'gravatar_size' ); ?>" name="<?php echo $this->get_field_name( 'gravatar_size' ); ?>" value="<?php echo $instance['gravatar_size']; ?>" style="width:100%;" />
		</p>

		<hr /><!-- Aesthetic separator -->

		<p>
			<label for="<?php echo $this->get_field_id( 'image_url' ); ?>"><?php _e( 'URL of Image:', 'bns-corner-logo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image_url' ); ?>" name="<?php echo $this->get_field_name( 'image_url' ); ?>" value="<?php echo $instance['image_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image_alt_text' ); ?>"><?php _e( 'ALT text of Image:', 'bns-corner-logo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image_alt_text' ); ?>" name="<?php echo $this->get_field_name( 'image_alt_text' ); ?>" value="<?php echo $instance['image_alt_text']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image_link' ); ?>"><?php _e( 'URL to follow:', 'bns-corner-logo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image_link' ); ?>" name="<?php echo $this->get_field_name( 'image_link' ); ?>" value="<?php echo $instance['image_link']; ?>" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( ( bool ) $instance['new_window'], true ); ?> id="<?php echo $this->get_field_id( 'new-window' ); ?>" name="<?php echo $this->get_field_name( 'new_window' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'new_window' ); ?>"><?php _e( 'Open "URL to follow" in new window?', 'bns-corner-logo' ); ?></label>
		</p>

		<hr /><!-- Separates functionality: Widget above - plugin below -->

		<p>
			<input class="checkbox" type="checkbox" <?php checked( ( bool ) $instance['widget_plugin'], true ); ?> id="<?php echo $this->get_field_id( 'widget_plugin' ); ?>" name="<?php echo $this->get_field_name( 'widget_plugin' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'widget_plugin' ); ?>"><?php _e( 'Use like a Plugin?', 'bns-corner-logo' ); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'logo_location' ); ?>"><?php _e( 'Plugin Logo Location:', 'bns-corner-logo' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'logo_location' ); ?>" name="<?php echo $this->get_field_name( 'logo_location' ); ?>" class="widefat">
				<option <?php selected( __( 'Bottom-Right', 'bns-corner-logo' ), $instance['logo_location'], true ); ?>><?php _e( 'Bottom-Right', 'bns-corner-logo' ); ?></option>
				<option <?php selected( __( 'Bottom-Left', 'bns-corner-logo' ), $instance['logo_location'], true ); ?>><?php _e( 'Bottom-Left', 'bns-corner-logo' ); ?></option>
				<option <?php selected( __( 'Top-Right', 'bns-corner-logo' ), $instance['logo_location'], true ); ?>><?php _e( 'Top-Right', 'bns-corner-logo' ); ?></option>
				<option <?php selected( __( 'Top-Left', 'bns-corner-logo' ), $instance['logo_location'], true ); ?>><?php _e( 'Top-Left', 'bns-corner-logo' ); ?></option>
			</select>
		</p>

	<?php }


	/**
	 * Enqueue Plugin Scripts and Styles
	 *
	 * Adds plugin stylesheet and allows for custom stylesheet to be added by end-user.
	 *
	 * @package BNS_Corner_Logo
	 * @since   1.5
	 *
	 * @uses    (CONSTANT) WP_CONTENT_DIR
	 * @uses    BNS_Corner_Logo::plugin_data
	 * @uses    content_url
	 * @uses    plugin_dir_url
	 * @uses    plugin_dir_path
	 * @uses    wp_enqueue_style
	 *
	 * @version 1.8.3
	 * @date    December 30, 2013
	 * Added functional option to place `bns-corner-logo-custom-style.css` in the `/wp-content/` folder
	 *
	 * @version 1.9
	 * @date    March 31, 2015
	 * Added calls to custom JavaScript and CSS files in the `/bns-customs/` folder
	 * Corrected typo in custom JavaScript file name
	 *
	 * @version 2.1
	 * @date    November 27, 2015
	 * Moved `plugin_data` into its own method and modified variable used
	 */
	function scripts_and_styles() {

		/** @var array $plugin_data - get the plugin header data */
		$plugin_data = $this->plugin_data();

		/** Scripts */
		wp_enqueue_script( 'BNS-Corner-Logo-Script', plugin_dir_url( __FILE__ ) . 'bns-corner-logo-scripts.js', array( 'jquery' ), $plugin_data['Version'], 'screen' );

		/** Only enqueue if available */
		if ( is_readable( plugin_dir_path( __FILE__ ) . 'bns-corner-logo-custom-scripts.js' ) ) {
			wp_enqueue_style( 'BNS-Corner-Logo-Custom-Script', plugin_dir_url( __FILE__ ) . 'bns-corner-logo-custom-scripts.js', array( 'jquery' ), $plugin_data['Version'], 'screen' );
		}

		/** For custom stylesheets in the /wp-content/bns-custom/ folder */
		if ( is_readable( BNS_CUSTOM_PATH . 'bns-corner-logo-custom-scripts.js' ) ) {
			wp_enqueue_style( 'BNS-Corner-Logo-Custom-Script', BNS_CUSTOM_URL . 'bns-corner-logo-custom-scripts.js', array( 'jquery' ), $plugin_data['Version'], 'screen' );
		}

		/** Styles */
		wp_enqueue_style( 'BNS-Corner-Logo-Style', plugin_dir_url( __FILE__ ) . 'bns-corner-logo-style.css', array(), $plugin_data['Version'], 'screen' );

		/** Only enqueue if available */
		if ( is_readable( plugin_dir_path( __FILE__ ) . 'bns-corner-logo-custom-style.css' ) ) {
			wp_enqueue_style( 'BNS-Corner-Logo-Custom-Style', plugin_dir_url( __FILE__ ) . 'bns-corner-logo-custom-style.css', array(), $plugin_data['Version'], 'screen' );
		}
		if ( is_readable( WP_CONTENT_DIR . '/bns-corner-logo-custom-style.css' ) ) {
			wp_enqueue_style( 'BNS-Corner-Logo-Custom-Style', content_url() . '/bns-corner-logo-custom-style.css', array(), $plugin_data['Version'], 'screen' );
		}

		/** For custom stylesheets in the /wp-content/bns-custom/ folder */
		if ( is_readable( BNS_CUSTOM_PATH . 'bnsft-custom-style.css' ) ) {
			wp_enqueue_style( 'BNSFT-Custom-Style', BNS_CUSTOM_URL . 'bnsft-custom-style.css', array(), $bnsft_data['Version'], 'screen' );
		}

	}


	/**
	 * BNS Corner Logo Update Message
	 *
	 * @package BNS_Corner_Logo
	 * @since   1.9
	 *
	 * @uses    get_transient
	 * @uses    is_wp_error
	 * @uses    set_transient
	 * @uses    wp_kses_post
	 * @uses    wp_remote_get
	 *
	 * @param $args
	 *
	 * @version 2.1
	 * @date    November 26, 2015
	 * Moved into class
	 */
	function update_message( $args ) {

		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		$bnscl_data = get_plugin_data( __FILE__ );

		$transient_name = 'bnscl_upgrade_notice_' . $args['Version'];
		if ( false === ( $upgrade_notice = get_transient( $transient_name ) ) ) {

			/** @var string $response - get the readme.txt file from WordPress */
			$response = wp_remote_get( 'https://plugins.svn.wordpress.org/bns-corner-logo/trunk/readme.txt' );

			if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
				$matches = null;
			}
			$regexp         = '~==\s*Changelog\s*==\s*=\s*(.*)\s*=(.*)(=\s*' . preg_quote( $bnscl_data['Version'] ) . '\s*=|$)~Uis';
			$upgrade_notice = '';

			if ( preg_match( $regexp, $response['body'], $matches ) ) {
				$version = trim( $matches[1] );
				$notices = (array) preg_split( '~[\r\n]+~', trim( $matches[2] ) );

				if ( version_compare( $bnscl_data['Version'], $version, '<' ) ) {

					/** @var string $upgrade_notice - start building message (inline styles) */
					$upgrade_notice = '<style type="text/css">
							.bnscl_plugin_upgrade_notice { padding-top: 20px; }
							.bnscl_plugin_upgrade_notice ul { width: 50%; list-style: disc; margin-left: 20px; margin-top: 0; }
							.bnscl_plugin_upgrade_notice li { margin: 0; }
						</style>';

					/** @var string $upgrade_notice - start building message (begin block) */
					$upgrade_notice .= '<div class="bnscl_plugin_upgrade_notice">';

					$ul = false;

					foreach ( $notices as $index => $line ) {

						if ( preg_match( '~^=\s*(.*)\s*=$~i', $line ) ) {

							if ( $ul ) {
								$upgrade_notice .= '</ul><div style="clear: left;"></div>';
							}
							/** End if - unordered list created */

							$upgrade_notice .= '<hr/>';
							continue;

						}
						/** End if - non-blank line */

						/** @var string $return_value - body of message */
						$return_value = '';

						if ( preg_match( '~^\s*\*\s*~', $line ) ) {

							if ( ! $ul ) {
								$return_value = '<ul">';
								$ul           = true;
							}
							/** End if - unordered list not started */

							$line = preg_replace( '~^\s*\*\s*~', '', htmlspecialchars( $line ) );
							$return_value .= '<li style=" ' . ( $index % 2 == 0 ? 'clear: left;' : '' ) . '">' . $line . '</li>';

						} else {

							if ( $ul ) {
								$return_value = '</ul><div style="clear: left;"></div>';
								$return_value .= '<p>' . $line . '</p>';
								$ul = false;
							} else {
								$return_value .= '<p>' . $line . '</p>';
							}
							/** End if - unordered list started */

						}
						/** End if - non-blank line */

						$upgrade_notice .= wp_kses_post( preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $return_value ) );

					}
					/** End foreach - line parsing */

					$upgrade_notice .= '</div>';

				}
				/** End if - version compare */

			}
			/** End if - response message exists */

			/** Set transient - minimize calls to WordPress */
			set_transient( $transient_name, $upgrade_notice, DAY_IN_SECONDS );

		}
		/** End if - transient check */

		echo $upgrade_notice;

	}


	/**
	 * BNSCL Plugin Meta
	 *
	 * Adds additional links to plugin meta links
	 *
	 * @package    BNS_Corner_logo
	 * @since      2.1
	 *
	 * @uses       __
	 * @uses       plugin_basename
	 *
	 * @param   $links
	 * @param   $file
	 *
	 * @return  array $links
	 *
	 * @version    2.2
	 * @date       December 7, 2015
	 * Added "Add your translation" link to https://translate.wordpress.org/
	 */
	function plugin_meta( $links, $file ) {

		$plugin_file = plugin_basename( __FILE__ );

		if ( $file == $plugin_file ) {

			$links = array_merge(
				$links, array(
					'fork_link'      => '<a href="https://github.com/Cais/BNS-Corner-Logo">' . __( 'Fork on GitHub', 'bns-corner-logo' ) . '</a>',
					'wish_link'      => '<a href="http://www.amazon.ca/registry/wishlist/2NNNE1PAQIRUL">' . __( 'Grant a wish?', 'bns-corner-logo' ) . '</a>',
					'support_link'   => '<a href="http://wordpress.org/support/plugin/bns-corner-logo">' . __( 'WordPress support forums', 'bns-corner-logo' ) . '</a>',
					'translate_link' => '<a href="https://translate.wordpress.org/projects/wp-plugins/bns-corner-logo">' . __( 'Add your translation', 'bns-corner-logo' ) . '</a>'
				)
			);

		}

		return $links;

	}


	/**
	 * Dashboard Widget
	 *
	 * Add a dashboard widget to display relevant support references
	 *
	 * @package BNS_Corner_Logo
	 * @since   2.2
	 * @date    December 2, 2015
	 *
	 * @uses    BNS_Corner_Logo::plugin_data
	 * @uses    __
	 * @uses    wp_add_dashboard_widget
	 */
	function dashboard_widget_for_bns_corner_logo_support() {

		$plugin_date = $this->plugin_data();

		/**  Create a custom dashboard widget */
		wp_add_dashboard_widget( 'bns_corner_logo_dashboard_support_widget', sprintf( __( '%1$s Support References', 'bns-corner-logo' ), $plugin_date['Name'] ), array( $this, 'dashboard_widget_support_messages_display' ) );

	}


	/**
	 * Dashboard Widget Display
	 *
	 * Displays content in the dashboard widget
	 *
	 * @package BNS_Corner_Logo
	 * @since   2.2
	 * @date    December 2, 2015
	 *
	 * @uses    BNS_Corner_Logo::plugin_data
	 * @uses    __
	 */
	function dashboard_widget_support_messages_display() {

		$plugin_data = $this->plugin_data();

		$support_forums_link = '<a href="http://wordpress.org/support/plugin/bns-corner-logo">' . __( 'WordPress support forums', 'bns-corner-logo' ) . '</a>';
		$home_page_link      = '<a href="' . $plugin_data['PluginURI'] . '" >' . __( 'home page', 'bns-corner' ) . '</a>';
		$github_link         = '<a href="https://github.com/Cais/BNS-Corner-Logo">' . sprintf( __( 'GitHub', 'bns-corner-logo' ), $plugin_data['Name'] ) . '</a>';

		$message = sprintf( __( 'Visit the %1$s for help with %2$s; or visit the %3$s of %2$s.', 'bns-corner-logo' ), $support_forums_link, $plugin_data['Name'], $home_page_link ) . '<br />';
		$message .= sprintf( __( 'See %1$s on %2$s for the latest development version.', 'bns-corner-logo' ), $plugin_data['Name'], $github_link ) . '<br />';

		echo $message;

	}


	/**
	 * Hide the Dashboard widget by default under Screen Options
	 *
	 * @package BNS_Corner_Logo
	 * @since   2.2
	 * @date    January 3, 2016
	 *
	 * @param $hidden
	 *
	 * @return array
	 */
	function default_screen_option( $hidden ) {

		/** Add Dashboard widget ID to default hidden Screen Options array */
		$hidden[] = 'bns_corner_logo_dashboard_support_widget';

		return $hidden;

	}


	/**
	 * Register widget
	 *
	 * @uses    register_widget
	 *
	 * @return  void
	 */
	function load_bnscl_widget() {
		register_widget( 'BNS_Corner_Logo' );
	}

}


/** @var $bnscl - instantiate the class */
$bnscl = new BNS_Corner_Logo();