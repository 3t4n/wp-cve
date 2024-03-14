<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Simple_Page_Access_Restriction_Admin' ) ) {

	/**
	 * Main Simple_Page_Access_Restriction_Admin class
	 *
	 * @since       1.0.0
	 */
	class Simple_Page_Access_Restriction_Admin {

		public function __construct() {
			$this->hooks();
			$this->review_notice_callout();
		}

		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		private function hooks() {

			add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );
			add_action( 'save_post', array( $this, 'save_meta_box' ), 10, 3 );
			
			add_action( 'admin_menu', array( $this, 'create_setting_menu' ) );
			add_action( 'plugin_row_meta', array( $this,'add_plugin_row_meta' ) , 10, 2 );
			add_action( 'admin_footer', array( $this, 'add_deactive_modal' ) );
			add_action( 'wp_ajax_ps_simple_par_deactivation', array( $this, 'deactivation_popup' ) );
			add_action( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 2 );
			
			add_action( 'ps_simple_par_after_settings_title', array( $this, 'subscription_callout' ), 10, 2 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
			add_action( 'wp_ajax_ps_simple_par_handle_subscription_request', array( $this, 'process_subscription' ) );
		}

		/**
		 * Admin Enqueue style and scripts
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		public function enqueue_admin_scripts() {
			if ( SIMPLE_PAGE_ACCESS_RESTRICTION_LOAD_NON_MIN_SCRIPTS ) {
				$suffix = '';
			} else {
				$suffix = '.min';
			}

			if ( ps_simple_par_is_admin_page( 'admin.php' ) || ps_simple_par_is_admin_page( 'plugins.php' ) ) {
				wp_enqueue_style( 'ps_simple_par_admin_style', SIMPLE_PAGE_ACCESS_RESTRICTION_URL . 'assets/admin/css/admin' . $suffix . '.css' , array() , SIMPLE_PAGE_ACCESS_RESTRICTION_VER );
				wp_enqueue_script( 'ps_simple_par_admin_script', SIMPLE_PAGE_ACCESS_RESTRICTION_URL . 'assets/admin/js/admin' . $suffix . '.js' , array() , SIMPLE_PAGE_ACCESS_RESTRICTION_VER );
			}

			$main_page = ps_simple_par_get_admin_page_by_name();

			if ( ps_simple_par_is_admin_page( 'admin.php', $main_page['slug'] ) ) {

				wp_enqueue_media(); // load media scripts

				wp_enqueue_style(
					'ps_simple_par_settings_style',
					SIMPLE_PAGE_ACCESS_RESTRICTION_URL . 'assets/admin/css/settings' . $suffix . '.css',
					array(),
					SIMPLE_PAGE_ACCESS_RESTRICTION_VER
				);

				wp_enqueue_script(
					'ps_simple_par_settings_script',
					SIMPLE_PAGE_ACCESS_RESTRICTION_URL . 'assets/admin/js/settings' . $suffix . '.js',
					array( 'jquery' ),
					SIMPLE_PAGE_ACCESS_RESTRICTION_VER,
					true
				);
			}

			if ( ps_simple_par_is_admin_page( 'plugins.php' ) ) {
				wp_enqueue_style(
					'ps_simple_par_deactivation_style',
					SIMPLE_PAGE_ACCESS_RESTRICTION_URL . 'assets/admin/css/deactivation' . $suffix . '.css',
					array(),
					SIMPLE_PAGE_ACCESS_RESTRICTION_VER
				);

				wp_enqueue_script(
					'ps_simple_par_deactivation_script',
					SIMPLE_PAGE_ACCESS_RESTRICTION_URL . 'assets/admin/js/deactivation' . $suffix . '.js',
					array( 'jquery' ),
					SIMPLE_PAGE_ACCESS_RESTRICTION_VER,
					true
				);
			}

		}

		public function register_metabox() {
			$settings = ps_simple_par_get_settings();

			foreach ( $settings['post_types'] as $post_type ) {
				add_meta_box(
					'ps_simple_par_metabox_' . $post_type,
					__( 'Simple Page Access Restriction', 'simple-page-access-restriction' ), // meta box title
					array( $this, 'metabox' ),
					$post_type, // post type or page. 
					'side', // context, where on the screen
					'high' // priority, where should this go in the context
				);
			}
		}

		public function metabox( $post ) {
			$is_same_as_login_redirect = ( $post->ID === ps_simple_par_get_login_page_id() );

			$is_new_and_restricted = false;
			$existing_value = get_post_meta( $post->ID, 'page_access_restricted', true );

			// If it is a new post and setting is enabled
			if ( '' === $existing_value && ps_simple_par_is_new_post_restricted() ) {
				$is_new_and_restricted = true;
			}

			
			echo '<input type="checkbox" checked name="_page_access_restricted" value="0" style="display:none;" />';
			echo 
				'<label ' . ( $is_same_as_login_redirect ? 'style="opacity:0.5;"' : '' ) . '>
					<input type="checkbox" name="_page_access_restricted" value="1" ' . ( $is_same_as_login_redirect ? 'disabled' : '' ) . ' ' . ( ps_simple_par_is_page_restricted( $post->ID ) || $is_new_and_restricted ? 'checked' : '' ) . ' />
					<span>' . __( 'For Logged-In Users Only', 'simple-page-access-restriction' ) . '</span>
				</label>';
		}

		public function save_meta_box( $post_id, $post, $update ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( isset( $_POST['_page_access_restricted'] ) ) {
				update_post_meta( $post_id, 'page_access_restricted', intval( $_POST['_page_access_restricted'] ) );
			} elseif( ! $update && ps_simple_par_is_new_post_restricted() ) {
				// If New Post Restriction is enabled, then enable the checkbox.
				$settings           = ps_simple_par_get_settings();
				$post_types_enabled = $settings['post_types'];

				if ( in_array( $post->post_type, $post_types_enabled ) ) {
					update_post_meta( $post_id, 'page_access_restricted', 1 );
				}
			}
		}

		/**
		* Add plugin row meta
		*
		* @since 1.0.0
		* @param array  $plugin_meta
		* @param string $plugin_file
		*/
		public function add_plugin_row_meta( $plugin_meta, $plugin_file ) {
			if ( plugin_basename( SIMPLE_PAGE_ACCESS_RESTRICTION_FILE ) === $plugin_file ) {
				array_push( $plugin_meta, '<a href="' . SIMPLE_PAGE_ACCESS_RESTRICTION_DOCUMENTATION_URL . '" target="_blank">' . __( 'Documentation', 'simple-page-access-restriction' ) . '</a>' );

				array_push( $plugin_meta, '<a href="' . SIMPLE_PAGE_ACCESS_RESTRICTION_OPEN_TICKET_URL . '" target="_blank">' . __( 'Open Support Ticket', 'simple-page-access-restriction' ) . '</a>' );

				array_push( $plugin_meta, '<a href="' . SIMPLE_PAGE_ACCESS_RESTRICTION_REVIEW_URL . '" target="_blank">' . __( 'Post Review', 'simple-page-access-restriction' ) . '</a>' );
			}

			return $plugin_meta;
		}

		/**
		 * Show Subscription Modal, if not shown already
		 *
		 * @since 1.0.2
		 * @access public
		 * @return void
		 */
		public function subscription_callout() {
			require SIMPLE_PAGE_ACCESS_RESTRICTION_DIR . 'includes/admin/templates/subscription.php';
		}

		/**
		 * Processes the subscription request
		 *
		 * @since 1.0.2
		 * @access public
		 * @return void
		 */
		public function process_subscription() {
			// Get the email from options
			$email = get_option( 'admin_email' );
			
			// Get the input email
			$input_email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ): '';

			// Check if the input email is valid
			if ( filter_var( $input_email, FILTER_VALIDATE_EMAIL ) ) {
				// Set the email
				$email = $input_email;
			}

			wp_remote_post( SIMPLE_PAGE_ACCESS_RESTRICTION_SUBSCRIBE_URL, array(
				'body' => array(
					'email'       => $email,
					'plugin_name' => SIMPLE_PAGE_ACCESS_RESTRICTION_NAME,
				),
			) );

			if ( ! isset( $_POST['from_callout'] ) ) {
				update_option( 'ps_simple_par_subscription_shown', 'y', false );
			}
			
			wp_send_json( array(
				'processed' => 1,
			) );
		}

		/**
		 * Add page to admin menu
		 *
		 * @since 1.0.2
		 * @access public
		 * @return void
		 */
		public function create_setting_menu() {
			
			$pages = ps_simple_par_get_admin_pages();

			$menu = add_menu_page(
				$pages['main']['title'],
				$pages['main']['title'],
				'manage_options',
				$pages['main']['slug'],
				array( $this, 'load_main_page' ),
				SIMPLE_PAGE_ACCESS_RESTRICTION_URL . '/assets/admin/images/' . $pages['main']['icon']
			);

		}

		public function load_main_page(){
			require_once( SIMPLE_PAGE_ACCESS_RESTRICTION_DIR . 'includes/admin/settings/promos.php' );
			require_once( SIMPLE_PAGE_ACCESS_RESTRICTION_DIR . 'includes/admin/settings/settings.php' );
		}

		private function review_notice_callout() {
			// AJAX action hook to disable the 'review request' notice.
			add_action( 'wp_ajax_ps_simple_par_review_notice', array( $this,'dismiss_review_notice' ) );

			if ( ! get_option( 'ps_simple_par_review_time' ) ) {
				$review_time = time() + 7 * DAY_IN_SECONDS;
				add_option( 'ps_simple_par_review_time', $review_time, '', false );
			}

			if (
				is_admin() &&
				get_option( 'ps_simple_par_review_time' ) &&
				get_option( 'ps_simple_par_review_time' ) < time() &&
				! get_option( 'ps_simple_par_dismiss_review_notice' )
			) {
				add_action( 'admin_notices', array( $this, 'notice_review' ) );
				add_action( 'admin_footer', array( $this, 'notice_review_script' ) );
			}
		}

		/**
		 * Disables the notice about leaving a review.
		 */
		public function dismiss_review_notice() {
			update_option( 'ps_simple_par_dismiss_review_notice', true, false );
			wp_die();
		}

		/**
		 * Ask the user to leave a review for the plugin.
		 */
		public function notice_review() {

			global $current_user; 
			wp_get_current_user();
			$user_n = '';
			
			if ( ! empty( $current_user->display_name ) ) {
				$user_n = " " . $current_user->display_name;    
			}
			
			echo "<div id='simple-page-access-restriction-review' class='notice notice-info is-dismissible'><p>" .

			sprintf( __( "Hi%s, Thank you for using <b>" . SIMPLE_PAGE_ACCESS_RESTRICTION_NAME . "</b>. Please don't forget to rate our plugin. We sincerely appreciate your feedback.", 'simple-page-access-restriction' ), $user_n )
			.
			'<br><a target="_blank" href="' . SIMPLE_PAGE_ACCESS_RESTRICTION_REVIEW_URL . '" class="button-secondary">' . esc_html__( 'Post Review', 'simple-page-access-restriction' ) . '</a>' .
			'</p></div>';
		}

		/**
		 * Loads the inline script to dismiss the review notice.
		 */
		public function notice_review_script() {
			wp_enqueue_script( 'ps_simple_par_admin_review_notice', SIMPLE_PAGE_ACCESS_RESTRICTION_URL . 'assets/admin/js/review.min.js', array( 'jquery' ), SIMPLE_PAGE_ACCESS_RESTRICTION_VER, true );
		}

		/**
		 * Add deactivate modal layout.
		 */
		public function add_deactive_modal() {
			global $pagenow;

			if ( 'plugins.php' !== $pagenow ) {
				return;
			}

			include SIMPLE_PAGE_ACCESS_RESTRICTION_DIR . 'includes/admin/templates/deactivation.php';
		}

		/**
		 * Called after the user has submitted his reason for deactivating the plugin.
		 *
		 * @since  1.0.0
		 */
		
		public function deactivation_popup() {
			
			wp_verify_nonce( $_REQUEST['ps_simple_par_deactivation_nonce'], 'ps_simple_par_deactivation_nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die();
			}

			$reason_id = intval( sanitize_text_field( wp_unslash( $_POST['reason'] ) ) );

			if ( empty( $reason_id ) ) {
				wp_die();
			}
			
			$reason_info = sanitize_text_field( wp_unslash( $_POST['reason_details'] ) );

			if ( 1 === $reason_id ) {
				$reason_text = __( 'I only needed the plugin for a short period', 'simple-page-access-restriction' );
			} elseif ( 2 === $reason_id ) {
				$reason_text = __( 'I found a better plugin', 'simple-page-access-restriction' );
			} elseif ( 3 === $reason_id ) {
				$reason_text = __( 'The plugin broke my site', 'simple-page-access-restriction' );
			} elseif ( 4 === $reason_id ) {
				$reason_text = __( 'The plugin suddenly stopped working', 'simple-page-access-restriction' );
			} elseif ( 5 === $reason_id ) {
				$reason_text = __( 'I no longer need the plugin', 'simple-page-access-restriction' );
			} elseif ( 6 === $reason_id ) {
				$reason_text = __( 'It\'s a temporary deactivation. I\'m just debugging an issue.', 'simple-page-access-restriction' );
			} elseif ( 7 === $reason_id ) {
				$reason_text = __( 'Other', 'simple-page-access-restriction' );
			}

			$current_user = wp_get_current_user();

			
			$to         = 'info@pluginsandsnippets.com';
			$subject    = 'Plugin Uninstallation';
			
			$body  = '<p>Plugin Name: ' . SIMPLE_PAGE_ACCESS_RESTRICTION_NAME . '</p>';
			$body .= '<p>Plugin Version: ' . SIMPLE_PAGE_ACCESS_RESTRICTION_VER . '</p>';
			$body .= '<p>Reason: '. $reason_text . '</p>';
			$body .= '<p>Reason Info: ' . $reason_info . '</p>';
			$body .= '<p>Admin Name: ' . $current_user->display_name . '</p>';
			$body .= '<p>Admin Email: ' . get_option( 'admin_email' ) . '</p>';
			$body .= '<p>Website: ' . get_site_url() . '</p>';
			$body .= '<p>Website Language: ' . get_bloginfo( 'language' ) . '</p>';
			$body .= '<p>Wordpress Version: ' . get_bloginfo( 'version' ) . '</p>';
			$body .= '<p>PHP Version: ' . PHP_VERSION . '</p>';
			$body .= '<p>Plugin URL: https://wordpress.org/plugins/simple-page-access-restriction/</p>';
			
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			 
			wp_mail( $to, $subject, $body, $headers );
			wp_die();
		}

		/**
		 * Add a link to the settings page to the plugins list
		 *
		 * @since  1.0.0
		 * @param array  $actions
		 * @param string $plugin_file
		 */
		public function add_plugin_action_links( $actions, $plugin_file ) {
			if ( plugin_basename( SIMPLE_PAGE_ACCESS_RESTRICTION_FILE ) === $plugin_file ) {
				$main_page = ps_simple_par_get_admin_page_by_name();
				$settings_link = sprintf( esc_html__( '%1$s Settings %2$s', 'simple-page-access-restriction' ), '<a href="' . admin_url( 'admin.php?page='. $main_page['slug'] ) . '">', '</a>' );
				
				array_unshift( $actions, $settings_link );
			}

			return $actions;
		}
	}
}