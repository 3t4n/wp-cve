<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Class
 *
 * Class for post views count
 *
 * @package Easy Post Views Count
 * @since 1.0.0
 */
class Epvc_public {

   	public function __construct() {

   		// To update post views count 
		add_action( 'wp', array($this, 'epvc_update_post_count') , 100);

		// Display post views count
		add_filter( "the_content", array($this, "epvc_display_post_views") );

		// Print style for views icon
		add_action( "wp_print_styles", array($this, "epvc_print_style") );

		// Shortcode to print post views cout
		// Shortcode : [epvc_views id=""]
		add_shortcode( 'epvc_views', array($this, 'epvc_display_post_views_shortcode') );
	}

	/**
 	* Post count update
	*
	* Update post views count
	* 
	* @package Easy Post Views Count
	* @since 1.0.0
	 */
	function epvc_update_post_count(){
		global $post, $epvc_settings;

		if( is_singular() ){

			$post_types 	= is_array($epvc_settings['post_types'])?$epvc_settings['post_types']:array();
			$login_users	= sanitize_text_field($epvc_settings['login_users']);
			$ips 			= sanitize_text_field($epvc_settings['ips']);

			$excluded_ips = array();
			if( !empty($ips) ){
				$excluded_ips = array_filter( explode(",", $ips) );

			}

			$storedIds = array();
			if( isset($_COOKIE['epvc_post_views']) && $_COOKIE['epvc_post_views'] != 'null' ) {
				$storedIds = json_decode( $_COOKIE['epvc_post_views'] );
				$postIds = json_decode( $_COOKIE['epvc_post_views']);
			}
			
			if( in_array( $post->post_type, array_keys($post_types) ) && !in_array( $_SERVER['REMOTE_ADDR'] , $excluded_ips ) && !in_array($post->ID ,$storedIds) ){

				$postCount = get_post_meta( $post->ID, 'post_count_'.$post->ID, true );
				if( $login_users == 'yes' && is_user_logged_in() ){ 
				} else {
					$postCount = !empty($postCount)?$postCount+1:1;
					$postIds[] = $post->ID;
					setcookie("epvc_post_views", json_encode($postIds) , time()+3600*24*365*10, '/');
				}
				update_post_meta( $post->ID, 'post_count_'.$post->ID, $postCount );
			}
		}
	}

	/**
 	* Post content display
	*
	* Display post views
	* 
	* @package Easy Post Views Count
	* @since 1.0.0
	 */
	function epvc_display_post_views( $content ){

		global $epvc_settings;

		$position = $epvc_settings['position'];
		$CountContent = epvc_display_post_views();

		if( $position == 'after_content' ){
			$postContent = $content.$CountContent;
		} else if( $position == 'before_content' ) {
			$postContent = $CountContent.$content;
		} else {
			$postContent = $content;
		}
		return $postContent;
	}

	/**
 	* Post views count style
	*
	* Css for post count icon
	* 
	* @package Easy Post Views Count
	* @since 1.0.0
	 */
	function epvc_print_style(){
	?>
		<style type="text/css">
			.epvc-eye {
				margin-right: 3px;
				width: 13px;
				display: inline-block;
				height: 13px;
				border: solid 1px #000;
				border-radius:  75% 15%;
				position: relative;
				transform: rotate(45deg);
			}
			.epvc-eye:before {
				content: '';
				display: block;
				position: absolute;
				width: 5px;
				height: 5px;
				border: solid 1px #000;
				border-radius: 50%;
				left: 3px;
				top: 3px;
			}
		</style>
	<?php
	}

	/**
 	* Shortcode
	*
	* Get post view shortcode
	* 
	* @package Easy Post Views Count
	* @since 1.0.0
	 */
	function epvc_display_post_views_shortcode( $atts, $content ){
		// Getting attributes of shortcode
		extract( shortcode_atts( array(
			'id'	=> '',
		), $atts ) );
		ob_start();

		echo epvc_display_post_views( $id );

		$content .= ob_get_clean();
		return $content;
	}
}
return new Epvc_public();