<?php

if ( ! class_exists( 'ccew_review_notice' ) ) {
	class ccew_review_notice {
		/**
		 * The Constructor
		 */
		public function __construct() {
			// register actions
			if ( is_admin() ) {
				add_action( 'admin_notices', array( $this, 'ccew_admin_notice_for_reviews' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'ccew_enqueue_scripts_styles' ) );
				add_action( 'wp_ajax_ccew_dismiss_notice', array( $this, 'ccew_dismiss_review_notice' ) );
			}
		}

		/**
		 * Enqueue scripts and styles
		 */
		public function ccew_enqueue_scripts_styles() {
			wp_enqueue_script( 'ccew-feedback-notice-script', CCEW_URL . '/assets/js/ccew-admin-feedback-notice.js', array( 'jquery' ), null, true );
			wp_enqueue_style( 'ccew-feedback-notice-styles', CCEW_URL . '/assets/css/ccew-admin-feedback-notice.css' );
		}

		/**
		 * Ajax callback for dismissing the review notice
		 */
		public function ccew_dismiss_review_notice() {
			  // Check for nonce security
			if ( ! wp_verify_nonce( $_POST['nonce'], 'ccew-nonce' ) ) {
				die( 'You don\'t have permission hide notice.' );
			}
			update_option( 'ccew-alreadyRated', 'yes' );
			wp_send_json_success();
		}

		/**
		 * Display the admin notice for reviews
		 */
		public function ccew_admin_notice_for_reviews() {
			if ( ! current_user_can( 'update_plugins' ) ) {
				return;
			}

			// Check if the user has already rated the plugin
			$already_rated = get_option( 'ccew-alreadyRated', 'no' );
			if ( $already_rated === 'yes' ) {
				return;
			}

			// Get installation date and compare it with the current date
			$installation_date = get_option( 'ccew_activation_time' );
			if ( is_numeric( $installation_date ) ) {
				$installation_date = gmdate( 'Y-m-d h:i:s', $installation_date );
			}

			$install_date = new DateTime( $installation_date );
			$current_date = new DateTime();
			$diff         = $install_date->diff( $current_date );
			$diff_days    = $diff->days;

			// Check if installation days is greater than or equal to 3
			if ( $diff_days >= 3 ) {
				echo wp_kses_post( $this->ccew_create_notice_content() );
			}
		}

		/**
		 * Generate the HTML content for the review notice
		 */
		public function ccew_create_notice_content() {
			$ajax_url           = admin_url( 'admin-ajax.php' );
			$ajax_callback      = 'ccew_dismiss_notice';
			$wrap_cls           = 'notice notice-info is-dismissible';
			$img_path           = CCEW_URL . 'assets/images/ccew-logo.png';
			$p_name             = 'Cryptocurrency Widgets For Elementor';
			$like_it_text       = 'Rate Now! ★★★★★';
			$already_rated_text = esc_html__( 'I already rated it', 'ccew' );
			$not_like_it_text   = esc_html__( 'No, not good enough, I do not like to rate it!', 'ccew' );
			$not_interested     = esc_html__( 'Not Interested', 'cool-timeline' );
			$p_link             = esc_url( 'https://wordpress.org/support/plugin/cryptocurrency-widgets-for-elementor/reviews/#new-post' );

			$nonce   = wp_create_nonce( 'ccew-nonce' );
			$message = sprintf(
				'Thanks for using <b>%s</b> WordPress plugin. We hope it meets your expectations!<br/>Please give us a quick rating, it works as a boost for us to keep working on more <a href="https://coolplugins.net" target="_blank"><strong>Cool Plugins</strong></a>!',
				$p_name
			);

			$html = '<div data-ajax-url="%s" data-ajax-callback="%s" 
			data-nonce="%s" 
			class="cool-feedback-notice-wrapper %s">
        <div class="logo_container"><a href="%s"><img src="%s" alt="%s"></a></div>
        <div class="message_container">%s
        <div class="callto_action">
        <ul>
            <li class="love_it"><a href="%s" class="like_it_btn button button-primary" target="_new" title="%s">%s</a></li>
            <li class="already_rated"><a href="#" class="already_rated_btn button ccew_dismiss_notice" title="%s">%s</a></li>
            <li class="already_rated"><a href="#" class="already_rated_btn button ccew_dismiss_notice" title="%s">%s</a></li>
        </ul>
        <div class="clrfix"></div>
        </div>
        </div>
        </div>';

			return sprintf(
				$html,
				$ajax_url,
				$ajax_callback,
				$nonce,
				$wrap_cls,
				$p_link,
				$img_path,
				$p_name,
				$message,
				$p_link,
				$like_it_text,
				$like_it_text,
				$already_rated_text,
				$already_rated_text,
				$not_interested,
				$not_interested
			);
		}
	} //class end
}
