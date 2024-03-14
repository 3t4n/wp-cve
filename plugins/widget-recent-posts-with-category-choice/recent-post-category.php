<?php
/*
Plugin Name: Recent Posts Widget Advanced
Version: 1.3.5
Description: Simple Recent Post Widget with advanced choice.
Author: KGM Servizi
Author URI: https://kgmservizi.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget_Recent_Posts_In_Category extends WP_Widget {
    
   /*
    * Sets up plugin with original WP Widget Recent Post class.
    */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_entries',
			'description' => __( 'Your site most recent Posts with category choice.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'widget-recent-posts-in-category', __( 'Advanced Recent Posts' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_posts_in_category';
	}

	/*
	 * Outputs the content of plugin.
     */
	public function widget( $args, $instance ) {
        include( plugin_dir_path( __FILE__ ) . 'includes/query.php' );
		
		// Check if there is a template for override default
		$frontend = get_stylesheet_directory() . '/widget-recent-post-advanced/frontend-1.php';
		if ( ! file_exists( $frontend ) ) {
			// Fallback to default if no child template exist
			$frontend = __DIR__ . '/templates/frontend-1.php';
		}

		// Check if hide on same cpt are enabled
		$hide_on_same_cpt_page = isset( $instance['hide_on_same_cpt_page'] ) ? (bool) $instance['hide_on_same_cpt_page'] : false;

		// Include right template
		if ( $hide_on_same_cpt_page == true ) {
			if ( $instance['post_type'] !==  get_post_type() ) {
				include $frontend;
			}
		} else {
			include $frontend;
		}
	}

	/*
	 * Update settings.
	 */
	public function update( $new_instance, $old_instance ) {
        include( plugin_dir_path( __FILE__ ) . 'includes/update.php' );
        return $instance;
	}

	/*
	 * BackEnd Widget Form.
	 */
	public function form( $instance ) {
        include( plugin_dir_path( __FILE__ ) . 'includes/form.php' );
	}

}

/*
 * Register Widget.
 */

function add_widget(){
    register_widget( 'Widget_Recent_Posts_In_Category' );
}
add_action( 'widgets_init', 'add_widget' );

/*
 * Includes.
 */

include( plugin_dir_path( __FILE__ ) . 'includes/list.php' );
include( plugin_dir_path( __FILE__ ) . 'includes/admin-id.php' );
include( plugin_dir_path( __FILE__ ) . 'includes/option-page.php' );
