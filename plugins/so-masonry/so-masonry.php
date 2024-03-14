<?php
/*
Plugin Name: SiteOrigin Masonry
Description: Gives you a stunning masonry layout for your website.
Version: 1.0.3
Author: Greg Priday
Author URI: http://siteorigin.com
License: GPL3
License URI: license.txt
*/

define('SITEORIGIN_MASONRY_VERSION', '1.0.3');

include plugin_dir_path(__FILE__).'inc/widget.php';
include plugin_dir_path(__FILE__).'inc/options.php';

class SiteOrigin_Masonry {
	/**
	 * @var
	 */
	static $single;

	function __construct(){
		add_action( 'init', array($this, 'register_image_sizes') );
		add_action( 'widgets_init', array($this, 'widgets_init') );
		add_action( 'add_meta_boxes', array($this, 'add_meta_boxes') );
		add_action( 'save_post', array($this, 'save_post') );
		add_shortcode( 'masonry', array($this, 'shortcode') );
	}

	static function single(){
		if(empty(self::$single)) {
			self::$single = new SiteOrigin_Masonry;
		}

		return self::$single;
	}

	function add_meta_boxes(){
		$masonry_post_types = get_option( 'siteorigin_masonry_post_types', array('post') );

		foreach ($masonry_post_types as $screen) {
			add_meta_box(
				'so_masonry_metabox',
				__( 'Masonry Settings', 'so-masonry' ),
				array($this, 'meta_box_render'),
				$screen,
				'side'
			);
		}
	}

	static function get_settings($post_id){
		$settings = (array) get_post_meta( $post_id, 'masonry_settings', true );
		$settings = wp_parse_args($settings, array(
			'size' => '11',
		));
		return $settings;
	}

	function meta_box_render($post){
		$settings = $this->get_settings($post->ID);

		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><?php _e('Brick Size', 'so-masonry') ?></th>
				<td>
					<select name="masonry_post[size]">
						<option value="11" <?php selected( '11', $settings['size'] ) ?>><?php _e( '1 by 1', 'so-masonry' ) ?></option>
						<option value="12" <?php selected( '12', $settings['size'] ) ?>><?php _e( '1 by 2', 'so-masonry' ) ?></option>
						<option value="21" <?php selected( '21', $settings['size'] ) ?>><?php _e( '2 by 1', 'so-masonry' ) ?></option>
						<option value="22" <?php selected( '22', $settings['size'] ) ?>><?php _e( '2 by 2', 'so-masonry' ) ?></option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
		wp_nonce_field('save', '_so_masonry_nonce');
	}

	function register_image_sizes(){
		// These are the image sizes used by the masonry widget
		add_image_size( 'so-masonry-size-11', 280, 280, true );
		add_image_size( 'so-masonry-size-12', 280, 560, true );
		add_image_size( 'so-masonry-size-21', 560, 280, true );
		add_image_size( 'so-masonry-size-22', 560, 560, true );
	}

	function save_post($post_id){
		if(empty($_POST['_so_masonry_nonce']) || !wp_verify_nonce( $_POST['_so_masonry_nonce'], 'save' ) ) return;
		if(!current_user_can('edit_post', $post_id)) return;

		$settings = array_map( 'stripslashes', $_POST['masonry_post'] );
		update_post_meta( $post_id, 'masonry_settings', $settings );
	}

	/**
	 * Enqueue the scripts
	 */
	function enqueue(){
		static $enqueued = false;
		if($enqueued) return;

		wp_enqueue_script( 'siteorigin-masonry' , plugin_dir_url(__FILE__) . 'js/jquery.masonry.min.js', array('jquery'), '2.1.07' );
		wp_enqueue_script( 'siteorigin-masonry-main' , plugin_dir_url(__FILE__) . 'js/main.min.js', array('jquery'), SITEORIGIN_MASONRY_VERSION );
		wp_enqueue_style( 'siteorigin-masonry-main' , plugin_dir_url(__FILE__).'css/masonry.css' , array(), SITEORIGIN_MASONRY_VERSION );
		wp_localize_script( 'siteorigin-masonry-main', 'soMasonrySettings', array(
			'loader' => plugin_dir_url(__FILE__).'images/ajax-loader.gif'
		) );
		$enqueued = true;
	}

	/**
	 * Initialize the widgets
	 */
	function widgets_init(){
		register_widget( 'SiteOrigin_Masonry_Widget' );
	}

	function shortcode($atts){
		the_widget( 'SiteOrigin_Masonry_Widget',  array(
			'additional' => isset($atts['query']) ? $atts['query'] : '',
		) );
	}
}
SiteOrigin_Masonry::single();