<?php
/**
 * Expand Divi Setup
 * Setup plugin files
 *
 * @package  ExpandDiviSetup
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviSetup {
	public $options;

	/**
	 * constructor
	 */
	function __construct() {
		$this->options = get_option( 'expand_divi' );
	}

	/**
	 * register plugin setup functions
	 *
	 * @return void
	 */
	function expand_divi_register() {
		add_filter( "plugin_action_links_expand-divi/expand-divi.php", array( $this, 'expand_divi_add_settings_link' ) );

		add_filter( 'user_contactmethods', array( $this, 'expand_divi_profile_social_fields') );

		add_action( 'admin_enqueue_scripts', array( $this, 'expand_divi_enqueue_admin_scripts' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'expand_divi_enqueue_frontend_scripts' ) );

		// require the dashbaord/menu files 
		require_once( EXPAND_DIVI_PATH . 'inc/dashboard/dashboard.php' );
		
		// require widgets classes
		require_once( EXPAND_DIVI_PATH . 'inc/widgets/ExpandDiviRecentPostsWidget.php' );
		require_once( EXPAND_DIVI_PATH . 'inc/widgets/ExpandDiviTwitterFeedWidget.php' );
		require_once( EXPAND_DIVI_PATH . 'inc/widgets/ExpandDiviContactInfoWidget.php' );

		// require shortcodes
		require_once( EXPAND_DIVI_PATH . 'inc/shortcodes/share.php' );
		require_once( EXPAND_DIVI_PATH . 'inc/shortcodes/follow.php' );
		require_once( EXPAND_DIVI_PATH . 'inc/shortcodes/divi_library.php' );

		// require features classes
		if ( isset( $this->options['enable_preloader'] ) ) {
     		if( ! empty( $this->options['enable_preloader'] ) && $this->options['enable_preloader'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviPreloader.php' );
     		}
     	} 
		if ( isset( $this->options['enable_post_tags'] ) ) {
     		if( ! empty( $this->options['enable_post_tags'] ) && $this->options['enable_post_tags'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviSinglePostTags.php' );
     		}
     	} 
		if ( isset( $this->options['share_icons'] ) ) {
     		if( ! empty( $this->options['share_icons'] ) && $this->options['share_icons'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviShareIcons.php' );
     		}
     	} 
		if ( isset( $this->options['enable_author_box'] ) ) {
     		if( ! empty( $this->options['enable_author_box'] ) && $this->options['enable_author_box'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviAuthorBox.php' );
     		}
     	} 
		if ( isset( $this->options['enable_single_post_pagination'] ) ) {
     		if( ! empty( $this->options['enable_single_post_pagination'] ) && $this->options['enable_single_post_pagination'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviSinglePostPagination.php' );
     		}
     	} 
		if ( isset( $this->options['enable_related_posts'] ) ) {
     		if( ! empty( $this->options['enable_related_posts'] ) && $this->options['enable_related_posts'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviRelatedPosts.php' );
     		}
     	} 
		if ( isset( $this->options['enable_archive_blog_styles'] ) ) {
     		if( $this->options['enable_archive_blog_styles'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviArchiveBlogStyles.php' );
     		}
     	} 
		if ( isset( $this->options['remove_sidebar'] ) ) {
     		if( ! empty( $this->options['remove_sidebar'] ) && $this->options['remove_sidebar'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviRemoveSidebar.php' );
     		}
     	} 
		if ( isset( $this->options['enable_lightbox_everywhere'] ) ) {
     		if( ! empty( $this->options['enable_lightbox_everywhere'] ) && $this->options['enable_lightbox_everywhere'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviLightBoxEverywhere.php' );
     		}
     	} 
		if ( isset( $this->options['coming_soon'] ) ) {
     		if( ! empty( $this->options['coming_soon'] ) && $this->options['coming_soon'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviComingSoon.php' );
     		}
     	}
     	if ( isset( $this->options['login_page_url'] ) || isset( $this->options['login_page_img_url'] ) ) {
     		if( ! empty( $this->options['login_page_url'] ) || ! empty( $this->options['login_page_img_url'] ) ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviLogin.php' );
     		}
     	}
		if ( isset( $this->options['tos_to_register_page'] ) ) {
     		if( ! empty( $this->options['tos_to_register_page'] ) && $this->options['tos_to_register_page'] !== 0 ) {
     		    require_once( EXPAND_DIVI_PATH . 'inc/features/ExpandDiviTOS.php' );
     		}
     	}
	}

	/**
	 * add setting link in plugins page
	 *
	 * @return array
	 */
	function expand_divi_add_settings_link( $links ) {
		$settings = esc_html__( 'Settings', 'expand-divi' );
   		$links[] = '<a href="tools.php?page=expand-divi">' . $settings . '</a>';
		return $links;
	}

	/**
	 * add social fields to profile
	 *
	 * @return array
	 */
	function expand_divi_profile_social_fields( $user_contact ) {
		$new_fields = array(
			array(
				'social' => 'twitter',
				'label' => 'Twitter URL'
			),
			array(
				'social' => 'facebook',
				'label' => 'Facebook URL'
			),
			array(
				'social' => 'instagram',
				'label' => 'Instagram URL'
			),
			array(
				'social' => 'youtube',
				'label' => 'Youtube URL'
			),
			array(
				'social' => 'linkedin',
				'label' => 'Linkedin URL'
			),
			array(
				'social' => 'pinterest',
				'label' => 'Pinterest URL'
			),
			array(
				'social' => 'reddit',
				'label' => 'Reddit URL'
			)
		);
		foreach ( $new_fields as $field ) {
			if ( ! isset( $user_contact[$field['social']] ) ) {
				$user_contact[$field['social']] = $field['label'];
			}
		}
		return $user_contact;
	}

	/**
	 * load admin styles and scripts
	 *
	 * @return void
	 */
	function expand_divi_enqueue_admin_scripts() {
		$screen = get_current_screen();

		if ($screen->base == 'tools_page_expand-divi') {
			wp_enqueue_style( 'expand-divi-admin-styles', EXPAND_DIVI_URL . 'assets/styles/admin-styles.css', array(), null );
			wp_enqueue_script( 'expand-divi-admin-scripts', EXPAND_DIVI_URL . 'assets/scripts/admin-scripts.js', array( 'jquery' ), null );
			wp_enqueue_script( 'jquery-form' );
		}
	}

	/**
	 * load frontend styles and scripts
	 *
	 * @return void
	 */
	function expand_divi_enqueue_frontend_scripts() {
		global $post;
		
		$classes = get_body_class();
		$target = array('expand-divi-blog-grid', 'expand-divi-blog-list');

		// enqueue frontend css
		if ( count ( array_intersect( $classes, $target ) ) > 0 
			|| is_active_widget( false, false, 'expand_divi_twitter_feed', true ) 
			|| is_active_widget( false, false, 'expand_divi_recent_posts_widget', true ) 
			|| is_active_widget( false, false, 'ed_contact_info', true ) 
			|| ( isset( $post->post_content ) 
				&& ( has_shortcode( $post->post_content, 'ed_share_icons') || has_shortcode( $post->post_content, 'ed_follow_icons') ) ) 
			|| ( is_singular( 'post' ) 
				&& ( ( $this->options["enable_author_box"] == 1 ) 
					|| ( $this->options["enable_single_post_pagination"] == 1 ) 
					|| ( $this->options["enable_related_posts"] == 1 ) 
					|| ( $this->options["enable_post_tags"] == 1 ) 
					|| ( $this->options["share_icons"] == 1 ) ) ) ) {
			wp_enqueue_style( 'expand-divi-frontend-styles', EXPAND_DIVI_URL . 'assets/styles/frontend-styles.css' );
		}

		// enqueue frontend js
		if ( is_singular( 'post' ) && ( $this->options["enable_post_tags"] == 1 ) ) {
			wp_enqueue_script( 'expand-divi-frontend-scripts', EXPAND_DIVI_URL . 'assets/scripts/frontend-scripts.js', array( 'jquery' ), null );
		}

		// enqueue fontawesome css
		if ( $this->options["enable_fontawesome"] == 1 
			|| $this->options["enable_author_box"] == 1
			|| has_shortcode( $post->post_content, 'ed_share_icons') 
			|| has_shortcode( $post->post_content, 'ed_follow_icons') 
			|| is_active_widget( false, false, 'ed_contact_info_widget', true )  ) {
			wp_enqueue_style( 'font-awesome', EXPAND_DIVI_URL . 'assets/styles/font-awesome.min.css' );
		}
	}
}

if ( class_exists( 'ExpandDiviSetup' ) ) {
	$ExpandDiviSetup = new ExpandDiviSetup();
	$ExpandDiviSetup->expand_divi_register();
}