<?php
/**
 * Plugin Name: mypace Custom Meta Robots
 * Plugin URI: https://github.com/mypacecreator/mypace-custom-meta-robots
 * Description: This plugin allows you to edit meta robots tag at every singular post(posts, pages, custom post types). This is a very simple plugin.
 * Version: 1.1.1
 * Author: Kei Nomura (mypacecreator)
 * Author URI: http://mypacecreator.net/
 * Text Domain: mypace-custom-meta-robots
 * Domain Path: /languages
 *
 * @package Mypace_Custom_Meta_Robots
 */

if ( !class_exists( 'Mypace_Custom_Meta_Robots' ) ) {
	/**
	 * Class Mypace_Custom_Meta_Robots
	 */
	class Mypace_Custom_Meta_Robots{

		/**
		 * Mypace_Custom_Meta_Robots constructor.
		 *
		 * Register actions and filters.
		 */
		public function __construct() {
			add_action( 'admin_menu',                      array( $this, 'add_meta_box' ) );
			add_action( 'save_post',                       array( $this, 'save_metadata' ) );
			add_filter( 'wp_head',                         array( $this, 'custom_meta_robots' ) );
			add_action( 'admin_print_styles-post.php',     array( $this, 'robots_meta_box_styles' ) );
			add_action( 'admin_print_styles-post-new.php', array( $this, 'robots_meta_box_styles' ) );
			load_plugin_textdomain( 'mypace-custom-meta-robots' );
		}

		/**
		 * Make a meta box
		 */
		public function add_meta_box(){
			$post_types = wp_list_filter(
					get_post_types(array('public' => true)),
					array('attachment'),
					'NOT'
			);
			foreach ( $post_types as $post_type ){
				add_meta_box(
					'mypace-meta-robots',
					esc_html__( 'meta robots difinition', 'mypace-custom-meta-robots' ),
					array( $this, 'robots_meta_box' ),
					$post_type,
					'advanced'
				);
			}
		}

		/**
		 * Custom robots metabox for input form
		 */
		public function robots_meta_box(){
			wp_nonce_field( plugin_basename(__FILE__), 'mypace_robots_meta_noncename' );
			$field_name  = 'mypace_robots_meta';
			$field_value = get_post_meta( get_the_ID(), $field_name, true );
		?>

		<div id="mypace_robots_meta-box">
			<label><input type="radio" name="mypace_robots_meta" value="index, follow"<?php if ( 'index, follow' == $field_value ) echo ' checked="checked"'; ?> />index, follow</label>
			<label><input type="radio" name="mypace_robots_meta" value="noindex, follow"<?php if ( 'noindex, follow' == $field_value ) echo ' checked="checked"'; ?> />noindex, follow</label>
			<label><input type="radio" name="mypace_robots_meta" value="index, nofollow"<?php if ( 'index, nofollow' == $field_value ) echo ' checked="checked"'; ?> />index, nofollow</label>
			<label><input type="radio" name="mypace_robots_meta" value="noindex, nofollow"<?php if ( 'noindex, nofollow' == $field_value ) echo ' checked="checked"'; ?> />noindex, nofollow</label>
			<label><input type="radio" name="mypace_robots_meta" value=""<?php if ( empty( $field_value ) ) echo ' checked="checked"'; ?> /><?php esc_attr_e( "None (Do not output meta robots definition.)", 'mypace-custom-meta-robots' ); ?> </label>
		</div>

<?php
	}

		/**
		 * Custom robots metabox style.
		 */
		public function robots_meta_box_styles() {
		?>
		<style type="text/css" charset="utf-8">
			#mypace_robots_meta-box label {
				display: inline-block;
				cursor: pointer;
				width: 48%;
			}
		</style>
		<?php
		}

		/**
		 * Save Setting.
		 *
		 * @param int $post_id postID on save.
		 *
		 * @return string|void
		 */
		public function save_metadata($post_id){

			//permission check and save data
			// Check if our nonce is set.
			if ( !isset($_POST['mypace_robots_meta_noncename']) ){
				return;
			}
			// Verify that the nonce is valid.
			if ( !wp_verify_nonce( $_POST['mypace_robots_meta_noncename'], plugin_basename(__FILE__) ) ){
				return;
			}
			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
				return;
			}
			// Check the user's permissions.
			if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			$mydata = isset($_POST['mypace_robots_meta']) ? $_POST['mypace_robots_meta'] : '';
			if ( !empty($mydata) ){
				update_post_meta( $post_id, 'mypace_robots_meta', $mydata );
			} else {
				delete_post_meta( $post_id, 'mypace_robots_meta' );
			}
			return $mydata;
		}

		/**
		 * Output meta robots tag.
		 *
		 * @param string $robots_value for meta robots value.
		 *
		 * @return string
		 */
		public function custom_meta_robots(){
			if( is_singular() ){
				$post_id = get_the_ID();
				$robots_value = get_post_meta( $post_id, 'mypace_robots_meta', true );
				if( $robots_value ){
					$output = '<meta name="robots" content="' . esc_attr($robots_value) . '" />';
				echo $output . "\n";
				}
			}
		}

	}
	new Mypace_Custom_Meta_Robots();

}
