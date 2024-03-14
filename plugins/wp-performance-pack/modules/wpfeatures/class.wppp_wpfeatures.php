<?php
/**
 * Disable or change WordPress features.
 * e.g. disable header elements, disable emoji support or change Heartbeat settings
 *
 * @author Björn Ahrens
 * @package WP Performance Pack
 * @since 2.0
 */

class WPPP_WPFeatures extends WPPP_Module {

	public function load_renderer() {
		return new WPPP_WPFeatures_Advanced( $this->wppp );
	}

	static function clear_edit_locks() {
		global $wpdb;
		$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_edit_lock' ) );
	}

	public function early_init() {
		if ( !$this->wppp->options[ 'jquery_migrate' ] ) {
			//Remove JQuery migrate
			add_action( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ) );
		}

		if ( !$this->wppp->options[ 'comments' ] ) {
			// Completely disable comments
			add_action( 'widgets_init', array( $this, 'disable_recent_comments_widget' ) ); // Check if WPPP disable widgets is acitvated and if so, use that feature instead
			remove_action( 'init', 'register_block_core_post_comments' );
			remove_action( 'init', 'register_block_core_post_comments_form' );
		}
	}

	function init() {
		if ( !$this->wppp->options[ 'emojis' ] )
			$this->remove_emoji();
		if ( !$this->wppp->options[ 'editlock' ] )
			add_filter( 'update_post_metadata', array( $this, 'update_metadata_filter' ), 10, 5 );


		//Heartbeat control features based on https://wordpress.org/support/plugin/heartbeat-control by Jeff Matson 
		if ( $this->wppp->options[ 'heartbeat_location' ] !== 'default' )
			$this->stop_heartbeat();
		if ( is_numeric( $this->wppp->options[ 'heartbeat_frequency' ] ) )
			add_filter( 'heartbeat_settings', array( $this, 'heartbeat_frequency' ) );

		if ( !$this->wppp->options[ 'rsd_link' ] ) 
			remove_action( 'wp_head', 'rsd_link' ); //removes EditURI/RSD (Really Simple Discovery) link.
		if ( !$this->wppp->options[ 'wlwmanifest_link' ] )
			remove_action( 'wp_head', 'wlwmanifest_link' ); //removes wlwmanifest (Windows Live Writer) link.
		if ( !$this->wppp->options[ 'wp_generator' ] )
			remove_action( 'wp_head', 'wp_generator' ); //removes meta name generator.
		if ( !$this->wppp->options[ 'wp_shortlink_wp_head' ] )
			remove_action( 'wp_head', 'wp_shortlink_wp_head' ); //removes shortlink.
		if ( !$this->wppp->options[ 'feed_links' ] )
			remove_action( 'wp_head', 'feed_links', 2 ); //removes feed links.
		if ( !$this->wppp->options[ 'feed_links_extra' ] || !$this->wppp->options[ 'comments' ] )
			remove_action( 'wp_head', 'feed_links_extra', 3 );  //removes comments feed.
		if ( !$this->wppp->options[ 'adjacent_posts_links' ] )
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' ); //Removes prev and next links
		if ( !$this->wppp->options[ 'big_image_scaling' ] )
			// https://make.wordpress.org/core/2019/10/09/introducing-handling-of-big-images-in-wordpress-5-3/
			add_filter( 'big_image_size_threshold', '__return_false' );

		if ( !$this->wppp->options[ 'comments' ] ) {
			// Completely disable comments

			// Hide existing comments
			add_filter( 'comments_array', '__return_empty_array', 10, 2 );
			add_filter( 'wp_count_comments', array( $this, 'filter_wp_count_comments' ), 20, 2 ); // prevent comment queries

			// Disable Recent Comments Widget
			// TODO: Check if WPPP disable widgets is acitvated and if so, use that feature instead
			add_action( 'widgets_init', function() {
				unregister_widget('WP_Widget_Recent_Comments');
				add_filter( 'show_recent_comments_widget_style', '__return_false' ); // Remove the widgets style action - source "disable comments" plugin
			} );

			// Close comments on the front-end
			add_filter( 'comments_open', '__return_false', 20, 2 );
			add_filter( 'pings_open', '__return_false', 20, 2 );

			// Disable Pingbacks (source https://www.pmg.com/blog/pingback-killer)
			add_filter( 'wp_headers', array( $this, 'remove_pingback_header' ), 10, 1 );
			add_filter( 'bloginfo_url', array( $this, 'kill_pingback_url' ), 10, 2 );
			add_filter( 'pre_update_default_ping_status', '__return_false' );
			add_filter( 'pre_option_default_ping_status', '__return_zero' );
			add_filter( 'pre_update_default_pingback_flag', '__return_false' );
			add_filter( 'pre_option_default_pingback_flag', '__return_zero' );

			// Disable Comments REST API Endpoint
			add_filter( 'rest_endpoints', array( $this, 'uncomment_restapi' ) );
			add_filter( 'rest_pre_insert_comment', function() { return; }, 10, 2);
			add_filter( 'xmlrpc_methods', array( $this, 'uncomment_xmlrpc' ) );

			if ( is_admin() ) {
				// Remove comments page in menu
				add_action( 'admin_menu', function() {
					remove_menu_page('edit-comments.php');
					remove_submenu_page( 'options-general.php', 'options-discussion.php' );
				} );
			}
		}
	}

	function admin_init() {
		if ( !$this->wppp->options[ 'comments' ] ) {
			// Completely disable comments

			// Redirect any user trying to access a comments related page
			global $pagenow;
			if ( $pagenow == 'comment.php' || $pagenow == 'edit-comments.php' || $pagenow == 'options-discussion.php' ) {
				wp_safe_redirect( admin_url() );
				exit;
			}

			// Disable support for comments and trackbacks in post types
			foreach ( get_post_types() as $post_type ) {
				if ( post_type_supports( $post_type, 'comments' ) ) {
					remove_post_type_support( $post_type, 'comments' );
					remove_post_type_support( $post_type, 'trackbacks' );
				}
			}

			// Prevent self pings (source: https://wordpress.org/plugins/no-self-ping)
			add_action( 'pre_ping', array( $this, 'no_self_ping' ) );

			// Remove admin bar link
			if ( is_admin_bar_showing() ) {
				remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 ); // Remove comments links from admin bar.
			}

			if ( is_admin() ) {
				// Backend

				// Remove comment related dashboard widgets
				add_action( 'wp_dashboard_setup', array( $this, 'disable_dashboard_widgets' ) );
			} else {
				// Frontend
				
			}
		}
	}




	function remove_jquery_migrate( &$scripts ) {
		if ( ! is_admin() && isset( $scripts->registered[ 'jquery' ] ) ) {
			$script = $scripts->registered[ 'jquery' ];
			if ( $script->deps ) { // Check whether the script has any dependencies
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			}
		}
	}

	function update_metadata_filter() {
		$args = func_get_args();
		if( isset( $args[ 2 ] ) && $args[ 2 ] == '_edit_lock' )
			return false;
	}

	function remove_emoji() {
		// Source: https://fastwp.de/4903/
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_false' );
		add_filter( 'tiny_mce_plugins', array( $this, 'remove_tinymce_emoji' ) );
	}

	function remove_tinymce_emoji( $plugins ) {
		// Source: https://fastwp.de/4903/
		if ( !is_array( $plugins ) ) {
			return array();
		}
		return array_diff( $plugins, array( 'wpemoji' ) );
	}

	/*
	 * Heartbeat control functions
	 */

	function stop_heartbeat() {
		global $pagenow;
		$loc = $this->wppp->options[ 'heartbeat_location' ];
		if ( ( $loc == 'disable_all' )
			|| ( ( $loc == 'disable_dashboard' ) && ( $pagenow == 'index.php' ) )
			|| ( ( $loc == 'allow_post' ) && ( $pagenow != 'post.php' && $pagenow != 'post-new.php' ) ) ) {
			wp_deregister_script( 'heartbeat' );
			wp_deregister_script( 'wp-auth-check' ); // depends on heartbeat
		}
	}

	function heartbeat_frequency( $settings ) {
		$settings[ 'interval' ] = $this->wppp->options[ 'heartbeat_frequency' ];
		return $settings;
	}

	/*
	 * Disable comments functions
	 */

	public function filter_wp_count_comments( $comments, $post_id ) {
		return (object) array(
			'approved'            => 0,
			'awaiting_moderation' => 0,
			'moderated'           => 0,
			'spam'                => 0,
			'trash'               => 0,
			'post-trashed'        => 0,
			'total_comments'      => 0,
			'all'                 => 0
		);
	}

	public function disable_dashboard_widgets() {
		// Replace original activity widget with a copy that doesn't check for comments
		wp_add_dashboard_widget( 'dashboard_activity_nc', __( 'Activity' ), array( $this, 'dashboard_site_activity_nocomments' ) );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	function dashboard_site_activity_nocomments() {
		// This is a copy of wp_dashboard_site_activity from dashboard.php but with anything regarding comment disabled
		// it replaces the original activiy dashboard widget
		echo '<div id="activity-widget">';

		$future_posts = wp_dashboard_recent_posts(
			array(
				'max'    => 5,
				'status' => 'future',
				'order'  => 'ASC',
				'title'  => __( 'Publishing Soon' ),
				'id'     => 'future-posts',
			)
		);
		$recent_posts = wp_dashboard_recent_posts(
			array(
				'max'    => 5,
				'status' => 'publish',
				'order'  => 'DESC',
				'title'  => __( 'Recently Published' ),
				'id'     => 'published-posts',
			)
		);

		//$recent_comments = wp_dashboard_recent_comments();

		if ( ! $future_posts && ! $recent_posts ) { //&& ! $recent_comments ) {
			echo '<div class="no-activity">';
			echo '<p>' . __( 'No activity yet!' ) . '</p>';
			echo '</div>';
		}

		echo '<p>Comments disabled by WPPP</p>';

		echo '</div>';
	}

	public function disable_recent_comments_widget()
	{
		unregister_widget( 'WP_Widget_Recent_Comments' );
		add_filter( 'show_recent_comments_widget_style', '__return_false' ); // Remove the widgets style action - source "disable comments" plugin
	}

	public function uncomment_xmlrpc( $methods ) {
		if ( isset( $methods[ 'wp.newComment' ] ) ) {
			unset( $methods[ 'wp.newComment' ] );
		}
		if ( isset( $methods[ 'pingback.ping' ] ) ) {
			unset( $methods[ 'pingback.ping' ] );
		}
		return $methods;
	}

	public function uncomment_restapi( $endpoints ) {
		if ( isset( $endpoints[ 'comments' ] ) ) {
			unset( $endpoints[ 'comments' ] );
		}
		return $endpoints;
	}

	function remove_pingback_header( $headers ) {
		if( isset( $headers[ 'X-Pingback' ] ) )	{
			unset( $headers[ 'X-Pingback' ] );
		}
		return $headers;
	}

	function kill_pingback_url( $output, $show ) {
		if( $show == 'pingback_url' ) {
			$output = '';
		}
		return $output;
	}

	function no_self_ping( &$links ) {
		$home = esc_url( home_url() );
		// Process each link in the content and remove is it matches the current site URL
		foreach ( $links as $l => $link ) {
			if ( 0 === strpos( $link, $home ) ) {
				unset( $links[ $l ] );
			}
		}
	}
}