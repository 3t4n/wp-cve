<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: VA Simple Expires
Plugin URI: https://github.com/VisuAlive/va-simple-expires
Description: This is the fork of Simple Expires created by Mr. abmcr.
Simple plugin which can set up the term of validity.
Author: KUCKLU & VisuAlive
Version: 1.0.6
Author URI: https://www.visualive.jp/
Text Domain: va-simple-expires
Domain Path: /languages
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

VisuAlive WordPress Plugin, Copyright (C) 2014 VisuAlive and KUCKLU

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
define( 'VA_SIMPLE_EXPIRES_PLUGIN_URL',  plugin_dir_url(__FILE__) );
define( 'VA_SIMPLE_EXPIRES_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'VA_SIMPLE_EXPIRES_DOMAIN',      dirname( plugin_basename(__FILE__) ) );
define( 'VA_SIMPLE_EXPIRES_TEXTDOMAIN',  'va-simple-expires' );

/**
 * Ran plugin.
 */
if ( !function_exists( 'va_simple_expires_setup' ) ) :
	function va_simple_expires_setup() {
		if ( !class_exists( 'VA_SIMPLE_EXPIRES_SETUP' ) ) {
			class VA_SIMPLE_EXPIRES_SETUP extends _VA_SIMPLE_EXPIRES {
				protected function __construct() {
					parent::__construct();
				}
			}
		}

		$va_simple_expires = VA_SIMPLE_EXPIRES_SETUP::instance();
	}
endif; // va_simple_expires_setup
add_action( 'plugins_loaded', 'va_simple_expires_setup' );

/**
 * Class _VA_SIMPLE_EXPIRES
 */
class _VA_SIMPLE_EXPIRES {
	/**
	 * Holds the singleton instance of this class
	 *
	 * @var array
	 */
	private static $instances = false;

	/**
	 * Instance
	 *
	 * @return self
	 */
	public static function instance(){
		if( !self::$instances ) {
			self::$instances = new _VA_SIMPLE_EXPIRES;
		}

		return self::$instances;
	}

	/**
	 * This hook is called once any activated plugins have been loaded.
	 */
	protected function __construct() {
		load_plugin_textdomain( VA_SIMPLE_EXPIRES_TEXTDOMAIN, false, VA_SIMPLE_EXPIRES_DOMAIN .'/languages' );

		add_action( 'plugins_loaded', array( &$this, 'plugins_loaded' )          );
		add_action( 'admin_menu',     array( &$this, 'loadAdmin' )               );
		add_action( 'admin_head',     array( &$this, 'add_post_status' )         );
		add_action( 'init',           array( &$this, 'simple_expires' )          );
		add_action( 'add_meta_boxes', array( &$this, 'scadenza_add_custom_box' ) );
		add_action( 'save_post',      array( &$this, 'scadenza_save_postdata' )  );
	}

	public function deactivation() {
		//  remove rows from wp_postmeta tables
		global $wpdb;
		$wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->postmeta WHERE meta_key='scadenza-enable' OR meta_key='scadenza-date'" ) );
	}

	function loadAdmin() {
		wp_enqueue_style( 'vase-style', VA_SIMPLE_EXPIRES_PLUGIN_URL . '/css/vase-style.css' );
	}

	private function va_se_get_post_types() {
		$default_post = array('post' => 'post', 'page' => 'page');
		$custom_post  = get_post_types( array( 'public' => true, '_builtin' => false ), 'names' );

		return array_merge( $default_post, $custom_post );
	}

	function add_post_status() {
		global $post;
		$complete = '';
		$label = '';
		$get_posttype = $this->va_se_get_post_types();
		$now_posttype = get_post_type();

		if( in_array($now_posttype, $get_posttype) ) :
			if($post->post_status == 'expiration'){
				$complete = ' selected=\"selected\"';
				$label = '<span id=\"post-status-display\"> ' . __( 'Ended', VA_SIMPLE_EXPIRES_TEXTDOMAIN ) . '</span>';
			}
			$option = '<option value=\"expiration\" '.$complete.'> ' . __( 'Ended', VA_SIMPLE_EXPIRES_TEXTDOMAIN ) . '</option>';
			?>
<script>
jQuery(document).ready(function($){
	$("select#post_status").append("<?php echo $option; ?>");
	$("span#post-status-display").append("<?php echo $label; ?>");
});
</script>
		<?php
		endif;
	}

	function simple_expires() {
		global $wpdb;

		// Register post status
		register_post_status( 'expiration', array(
			'label'                     => _x( 'Ended', 'post', VA_SIMPLE_EXPIRES_TEXTDOMAIN ),
			'protected'                 => true,
			'_builtin'                  => true,
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Ended <span class="count">(%s)</span>', 'Ended <span class="count">(%s)</span>', VA_SIMPLE_EXPIRES_TEXTDOMAIN )
		) );

		//20 june 2011: bug fix by Kevin Roberts for timezone
		$result = $wpdb->get_results( $wpdb->prepare("
			SELECT postmetadate.post_id
			FROM $wpdb->postmeta AS postmetadate, $wpdb->postmeta AS postmetadoit, $wpdb->posts AS posts
			WHERE postmetadoit.meta_key = 'scadenza-enable'
			AND postmetadoit.meta_value = '1'
			AND postmetadate.meta_key = 'scadenza-date'
			AND postmetadate.meta_value <= %s
			AND postmetadate.post_id = postmetadoit.post_id
			AND postmetadate.post_id = posts.ID
			AND posts.post_status = 'publish'
		", current_time("mysql") ) );
		// Act upon the results
		if ( ! empty( $result ) ) :
			$update_post = array();
			// Proceed with the updating process
			// step through the results
			foreach ( $result as $cur_post ) :
				$update_post['ID'] = $cur_post->post_id;
				// Get the Post's ID into the update array
				$update_post['post_status'] = 'expiration';
				remove_action( 'simple_expires','save_post' );
				wp_update_post( $update_post );
				add_action( 'simple_expires','save_post' );
			endforeach;
		endif;
	}

	/* Adds a box to the main column on the Post and Page edit screens */
	function scadenza_add_custom_box() {
		$custom_post_types = $this->va_se_get_post_types();
		foreach ( $custom_post_types as $t ) {
			add_meta_box( 'scadenza_plugin', __( 'Expire', VA_SIMPLE_EXPIRES_TEXTDOMAIN ), array( $this, 'scadenza_' ), $t, 'side', 'core' );
		}
	}

	/* Prints the box content */
	function scadenza_( $post ) {
		global $wp_locale;
		// Use nonce for verification
		wp_nonce_field( plugin_basename(__FILE__), 'simple-expires-nonce' );

		$scadenza = get_post_meta( $post->ID,'scadenza-date', true );
		$time_adj = current_time('timestamp');
		$anno     = ( ! empty($scadenza) ) ? mysql2date( 'Y', $scadenza, false ) : gmdate( 'Y', $time_adj );
		$mese     = ( ! empty($scadenza) ) ? mysql2date( 'm', $scadenza, false ) : gmdate( 'm', $time_adj );
		$giorno   = ( ! empty($scadenza) ) ? mysql2date( 'd', $scadenza, false ) : gmdate( 'd', $time_adj );
		$ore      = ( ! empty($scadenza) ) ? mysql2date( 'H', $scadenza, false ) : gmdate( 'H', $time_adj );
		$min      = ( ! empty($scadenza) ) ? mysql2date( 'i', $scadenza, false ) : gmdate( 'i', $time_adj );

		$years = "<select  id=\"anno\" name=\"anno\">\n";
		$years_this  = gmdate( 'Y', $time_adj );
		$years_limit = $years_this + 11;
		for ( $i = $years_this; $i < $years_limit; $i = $i +1 ) {
			$years .= "\t\t\t" . '<option value="' . esc_attr($i) . '"';
			if ( $i == $anno )
				$years .= ' selected="selected"';
				$years .= '>' . esc_html($i) . "</option>\n";
		}
		$years .= '</select>';

		$month = "<select  id=\"mese\" name=\"mese\">\n";
		for ( $i = 1; $i <= 12; $i = $i +1 ) {
			$month .= "\t\t\t" . '<option value="' . esc_attr( zeroise( $i, 2 ) ) . '"';
			if ( $i == $mese )
				$month .= ' selected="selected"';
				$month .= '>' . esc_html( $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ) . "</option>\n";
		}
		$month .= '</select>';

		$days = "<select  id=\"giorno\" name=\"giorno\">\n";
		for ( $i = 1; $i <= 31; $i = $i +1 ) {
			$days .= "\t\t\t" . '<option value="' . esc_attr( zeroise($i, 2) ) . '"';
			if ( $i == $giorno )
				$days .= ' selected="selected"';
				$days .= '>' . esc_html( zeroise($i, 2) ) . "</option>\n";
		}
		$days .= '</select>';

		$time_h = "<select  id=\"ore\" name=\"ore\">\n";
		for ( $i = 0; $i <= 23; $i = $i +1 ) {
			$time_h .= "\t\t\t" . '<option value="' . esc_attr( str_pad($i, 2, "0", STR_PAD_LEFT) ) . '"';
			if ( $i == $ore )
				$time_h .= ' selected="selected"';
				$time_h .= '>' . esc_html( str_pad($i, 2, "0", STR_PAD_LEFT) ) . "</option>\n";
		}
		$time_h .= '</select>';

		$time_i = "<select  id=\"min\" name=\"min\">\n";
		for ( $i = 0; $i <= 59; $i = $i +1 ) {
			$time_i .= "\t\t\t" . '<option value="' . esc_attr( str_pad($i, 2, "0", STR_PAD_LEFT) ) . '"';
			if ( $i == $min )
				$time_i .= ' selected="selected"';
				$time_i .= '>' . esc_html( str_pad($i, 2, "0", STR_PAD_LEFT) ) . "</option>\n";
		}
		$time_i .= '</select>';

		echo'<div id="timestampdiv_scadenza" class="">';
		$the_data = get_post_meta( $post->ID, 'scadenza-enable', true );
		// Checkbox for scheduling this Post / Page, or ignoring
		$items = array( __( 'Enabled', VA_SIMPLE_EXPIRES_TEXTDOMAIN ), __( 'Disabled', VA_SIMPLE_EXPIRES_TEXTDOMAIN ) );
		$value = array( 1, 0 );
		$i     = 0;
		foreach( $value as $item) {
			$checked = ( ( $the_data == $item ) || ( $the_data=='') ) ? ' checked="checked" ' : '';
			echo "<label><input" . $checked . " value='" . $item . "' name='scadenza-enable' id='scadenza-enable' type='radio'>" . $items[$i] . " </label>";
			$i++;
		} // end foreach
		echo "<br>\n<br>\n";
		echo '<div class="">' . sprintf( __( '%2$s%3$s%1$s<br>%4$s : %5$s', VA_SIMPLE_EXPIRES_TEXTDOMAIN ), $years, $month, $days, $time_h, $time_i ) . '</div></div>';
		echo "<p>".__( 'Insert a date for expire.', VA_SIMPLE_EXPIRES_TEXTDOMAIN )."</p>";
	}

	/* When the post is saved, saves our custom data */
	function scadenza_save_postdata( $post_id ) {
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( isset( $_POST['simple-expires-nonce'] ) ) {
			$nonce = esc_attr( $_POST['simple-expires-nonce'] );
		} else {
			$nonce = NULL;
		}

		if ( ! wp_verify_nonce( $nonce, plugin_basename(__FILE__) ) ) {
			return $post_id;
		}

		// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
		// to do anything
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return $post_id;

		// Check permissions
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		// OK, we're authenticated: we need to find and save the data
		$mydata = esc_attr($_POST['anno']) . "-" . esc_attr($_POST['mese']) . "-" . esc_attr(zeroise( $_POST['giorno'], 2 )) . " " . esc_attr(zeroise( $_POST['ore'], 2 )) . ":" . esc_attr($_POST['min']) . ":00";
		($mydata);
		$enabled = esc_attr($_POST['scadenza-enable']);

		// Do something with $mydata
		update_post_meta( $post_id,'scadenza-date', $mydata );
		update_post_meta( $post_id, 'scadenza-enable', $enabled );
		return $mydata;
	}
} // class _VA_SIMPLE_EXPIRES
