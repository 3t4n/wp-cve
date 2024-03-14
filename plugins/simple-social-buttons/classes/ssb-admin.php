<?php


/**
 * Admin class
 *
 * Gets only initiated if this plugin is called inside the admin section ;)
 */
if ( ! class_exists( 'SimpleSocialButtonsPR_Admin' ) ) :

	class SimpleSocialButtonsPR_Admin extends SimpleSocialButtonsPR {

		/**
		 * Automatically  when object created
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct();

			include_once SSB_PLUGIN_DIR . '/classes/ssb-settings.php';

			add_action( 'add_meta_boxes', array( $this, 'ssb_meta_box' ) );
			add_action( 'save_post', array( $this, 'ssb_save_meta' ), 10, 2 );

			add_filter( 'plugin_row_meta', array( $this, '_row_meta' ), 10, 2 );

			add_action( 'admin_footer', array( $this, 'add_deactive_modal' ) );
			add_action( 'wp_ajax_ssb_deactivate', array( $this, 'ssb_deactivate' ) );
			add_action( 'admin_init', array( $this, 'review_notice' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'in_admin_header', array( $this, 'skip_notices' ), 100000 );
			add_action( 'enqueue_block_editor_assets', array( $this, 'blocks_scripts' ) );

		}


		/**
		 * Admin side enqueued script
		 *
		 * @param string $page
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function admin_enqueue_scripts( $page ) {

			if ( 'toplevel_page_simple-social-buttons' == $page || 'social-buttons_page_ssb-help' == $page || 'widgets.php' == $page ) {
				wp_enqueue_style( 'ssb-admin-cs', plugins_url( 'assets/css/admin.css', plugin_dir_path( __FILE__ ) ), false, SSB_VERSION );
				wp_enqueue_script( 'ssb-admin-js', plugins_url( 'assets/js/admin.js', plugin_dir_path( __FILE__ ) ), array( 'jquery', 'jquery-ui-sortable' ), SSB_VERSION );
				wp_localize_script(
					'ssb-admin-js',
					'ssb',
					array(
						'ssb_export_help_nonce' => wp_create_nonce( 'ssb-export-security-check' ),
					)
				);
			}
		}

		/**
		 * Ssb Block editor assets.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function blocks_scripts() {
			$ssb_block_dependencies = [ 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-components', 'wp-editor' ];
			wp_enqueue_script( 'ssb-blocks-editor-js', plugins_url( 'assets/js/blocks.editor.js', plugin_dir_path( __FILE__ ) ), $ssb_block_dependencies, SSB_VERSION );
			wp_enqueue_style( 'ssb-blocks-editor-css', plugins_url( 'assets/css/blocks.editor.css', plugin_dir_path( __FILE__ ) ), array(), SSB_VERSION );
			wp_enqueue_style( 'ssb-front-css', plugins_url( 'assets/css/front.css', plugin_dir_path( __FILE__ ) ), false, SSB_VERSION );

			$is_pro = class_exists( 'Simple_Social_Buttons_Pro') ? rest_sanitize_boolean( true ) : rest_sanitize_boolean( false );
			wp_localize_script( 'ssb-blocks-editor-js', 'SSB', array( 'plugin_url' => SSB_PLUGIN_URL, 'is_pro' => $is_pro ) );

		}

		/**
		 * Register meta box to hide/show SSB plugin on single post or page
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function ssb_meta_box() {
			$postId             = isset( $_GET['post'] ) ? sanitize_post( $_GET['post'] ) : rest_sanitize_boolean( false );
			$postType           = get_post_type( $postId );
			$ssb_positions      = get_option( 'ssb_positions' );
			$selected_post_type = array();

			// Check if a SSB position is selected or not.
			if ( isset( $ssb_positions['position'] ) && ! empty( $ssb_positions['position'] ) ) {
				foreach ( $ssb_positions['position'] as $key => $value ) {
					$options = get_option( 'ssb_' . $value );

					if ( isset( $options['posts'] ) && ! empty( $options['posts'] ) ) {
						foreach ( $options['posts']  as $allow_post_type ) {
							$selected_post_type[ $allow_post_type ] = $allow_post_type;
						}
					}
				}
			}

			$allow_post_type = apply_filters( 'ssb_cpt_visibility_mb', $selected_post_type );
			if ( ! in_array( $postType, $allow_post_type ) ) {
				return false;
			}

			//Upon Editing or adding the post.
			$currentSsbHide = get_post_custom_values( $this->hideCustomMetaKey, $postId );
			$currentSsbHide[0] = isset( $currentSsbHide ) ? $currentSsbHide[0] : false;

			if ( $currentSsbHide[0] == 'true' ) {
				$checked = true;
			} else {
				$checked = false;
			}

			// Rendering meta box.
			if ( ! function_exists( 'add_meta_box' ) ) {
				include 'includes/template.php';
			}
			add_meta_box(
				'ssb_meta_box',
				__( 'SSB Settings', 'simple-social-buttons' ),
				array( $this, 'render_ssb_meta_box' ),
				$postType,
				'side',
				'default',
				array(
					'type'    => $postType,
					'checked' => $checked,
				)
			);
		}

		/**
		 * Showing custom meta field
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function render_ssb_meta_box( $post, $metabox ) {
			wp_nonce_field( plugin_basename( __FILE__ ), 'ssb_noncename' );
			?>

		  <label for="<?php echo $this->hideCustomMetaKey; ?>"><input type="checkbox" id="<?php echo $this->hideCustomMetaKey; ?>" name="<?php echo $this->hideCustomMetaKey; ?>" value="true"
				<?php if ( $metabox['args']['checked'] ) : ?>
				 checked="checked"
		<?php endif; ?>/>
	  &nbsp;<?php echo __( 'Hide Simple Social Buttons', 'simple-social-buttons' ); ?></label>
			<?php
		}


		/**
		 * Saving custom meta value.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function ssb_save_meta( $post_id, $post ) {
			$postId = (int) $post_id;
			// Verify if this is an auto save routine.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! isset( $_POST['ssb_noncename'] ) ) {
				return;
			}

			// Verify this came from the our screen and with proper authorization
			if ( ! wp_verify_nonce( $_POST['ssb_noncename'], plugin_basename( __FILE__ ) ) ) {
				return;
			}

			// Check permissions
			if ( 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			// Saving data
			$newValue = ( isset( $_POST[ $this->hideCustomMetaKey ] ) ) ?  sanitize_text_field( $_POST[ $this->hideCustomMetaKey ] ) :  'false';

			update_post_meta( $postId, $this->hideCustomMetaKey, $newValue );
			
		}


		/**
		 * Show the popup on pluing deactivate
		 *
		 * @access public
		 * @since 1.9.0
		 * @return void
		 */
		public function add_deactive_modal() {
			global $pagenow;

			if ( 'plugins.php' !== $pagenow ) {
				return;
			}

			include SSB_PLUGIN_DIR . 'inc/ssb-deactivate-form.php';
		}

		/**
		 * Send the user response to api.
		 *
		 * @access public
		 * @since 1.9.0
		 * @return void
		 */
		public function ssb_deactivate() {
			$email         = get_option( 'admin_email' );
			$_reason       = sanitize_text_field( wp_unslash( $_POST['reason'] ) );
			$reason_detail = sanitize_text_field( wp_unslash( $_POST['reason_detail'] ) );
			$reason        = '';

			if ( $_reason == '1' ) {
				$reason = 'I only needed the plugin for a short period';
			} elseif ( $_reason == '2' ) {
				$reason = 'I found a better plugin';
			} elseif ( $_reason == '3' ) {
				$reason = 'The plugin broke my site';
			} elseif ( $_reason == '4' ) {
				$reason = 'The plugin suddenly stopped working';
			} elseif ( $_reason == '5' ) {
				$reason = 'I no longer need the plugin';
			} elseif ( $_reason == '6' ) {
				$reason = 'It\'s a temporary deactivation. I\'m just debugging an issue.';
			} elseif ( $_reason == '7' ) {
				$reason = 'Other';
			}
			$fields = array(
				'email'             => $email,
				'website'           => get_site_url(),
				'action'            => 'Deactivate',
				'reason'            => $reason,
				'reason_detail'     => $reason_detail,
				'blog_language'     => get_bloginfo( 'language' ),
				'wordpress_version' => get_bloginfo( 'version' ),
				'plugin_version'    => SSB_VERSION,
				'php_version'       => PHP_VERSION,
				'plugin_name'       => 'Simple Social Buttons',
			);

			$response = wp_remote_post(
				SSB_FEEDBACK_SERVER,
				array(
					'method'      => 'POST',
					'timeout'     => 5,
					'httpversion' => '1.0',
					'blocking'    => false,
					'headers'     => array(),
					'body'        => $fields,
				)
			);

			wp_die();
		}

		/**
		 * Check either to show notice or not.
		 *
		 * @access public
		 * @since 1.9.0
		 * @return void
		 */
		public function review_notice() {

			$this->review_dismissal();
			$this->review_prending();

			$review_dismissal = get_site_option( 'ssb_review_dismiss' );
			if ( 'yes' == $review_dismissal ) {
				return;
			}

			$activation_time = get_site_option( 'ssb_active_time' );
			if ( ! $activation_time ) {

				$activation_time = time();
				add_site_option( 'ssb_active_time', $activation_time );
			}

			// 1296000 = 15 Days in seconds.
			if ( time() - $activation_time > 1296000 ) {
				add_action( 'admin_notices', array( $this, 'review_notice_message' ) );
			}

		}

		/**
		 * Show review Message After 15 days.
		 *
		 * @access public
		 * @since 1.9.0
		 * @return void
		 */
		public function review_notice_message() {

			$scheme      = ( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY ) ) ? '&' : '?';
			$url         = $_SERVER['REQUEST_URI'] . $scheme . 'ssb_review_dismiss=yes';
			$dismiss_url = wp_nonce_url( $url, 'ssb-review-nonce' );

			$_later_link = $_SERVER['REQUEST_URI'] . $scheme . 'ssb_review_later=yes';
			$later_url   = wp_nonce_url( $_later_link, 'ssb-review-nonce' );

			?>
		  <style media="screen">
		  .ssb-review-notice { padding: 15px 15px 15px 0; background-color: #fff; border-radius: 3px; margin: 20px 20px 0 0; border-left: 4px solid transparent; } .ssb-review-notice:after { content: ''; display: table; clear: both; }
		  .ssb-review-thumbnail { width: 114px; float: left; line-height: 80px; text-align: center; border-right: 4px solid transparent; }
		  .ssb-review-thumbnail img { width: 74px; vertical-align: middle; }
		  .ssb-review-text { overflow: hidden; }
		  .ssb-review-text h3 { font-size: 24px; margin: 0 0 5px; font-weight: 400; line-height: 1.3; }
		  .ssb-review-text p { font-size: 13px; margin: 0 0 5px; }
		  .ssb-review-ul { margin: 0; padding: 0; }
		  .ssb-review-ul li { display: inline-block; margin-right: 15px; }
		  .ssb-review-ul li a { display: inline-block; color: #10738B; text-decoration: none; padding-left: 26px; position: relative; }
		  .ssb-review-ul li a span { position: absolute; left: 0; top: -2px; }
		  </style>
		  <div class="ssb-review-notice">
			<div class="ssb-review-thumbnail">
		  <img src="<?php echo plugins_url( '../assets/images/ssb_grey_logo.png', __FILE__ ); ?>" alt="">
		</div>
		<div class="ssb-review-text">
		<h3><?php _e( 'Leave A Review?', 'simple-social-buttons' ); ?></h3>
		<p><?php _e( 'We hope you\'ve enjoyed using Simple Social Buttons! Would you consider leaving us a review on WordPress.org?', 'simple-social-buttons' ); ?></p>
		<ul class="ssb-review-ul"><li><a href="https://wordpress.org/support/plugin/simple-social-buttons/reviews/?filter=5" target="_blank"><span class="dashicons dashicons-external"></span><?php _e( 'Sure! I\'d love to!', 'simple-social-buttons' ); ?></a></li>
		  <li><a href="<?php echo $dismiss_url; ?>"><span class="dashicons dashicons-smiley"></span><?php _e( 'I\'ve already left a review', 'simple-social-buttons' ); ?></a></li>
		  <li><a href="<?php echo $later_url; ?>"><span class="dashicons dashicons-calendar-alt"></span><?php _e( 'Maybe Later', 'simple-social-buttons' ); ?></a></li>
		  <li><a href="<?php echo $dismiss_url; ?>"><span class="dashicons dashicons-dismiss"></span><?php _e( 'Never show again', 'simple-social-buttons' ); ?></a></li></ul>
		</div>
		</div>
			<?php
		}

		/**
		 * Set time to current so review notice will popup after 15 days
		 *
		 * @access public
		 * @since 1.9.0
		 * @return void
		 */
		public function review_prending() {

			// delete_site_option( 'ssb_review_dismiss' );
			if ( ! is_admin() ||
			! current_user_can( 'manage_options' ) ||
			! isset( $_GET['_wpnonce'] ) ||
			! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'ssb-review-nonce' ) ||
			! isset( $_GET['ssb_review_later'] ) ) {

				return;
			}

			// Reset Time to current time.
			update_site_option( 'ssb_active_time', time() );

		}

		/**
		 * Check and Dismiss review message.
		 *
		 * @access private
		 * @since 1.9.0
		 * @return void
		 */
		private function review_dismissal() {

			// delete_site_option( 'ssb_review_dismiss' );
			if ( ! is_admin() ||
			! current_user_can( 'manage_options' ) ||
			! isset( $_GET['_wpnonce'] ) ||
			! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'ssb-review-nonce' ) ||
			! isset( $_GET['ssb_review_dismiss'] ) ) {

				return;
			}

			add_site_option( 'ssb_review_dismiss', 'yes' );
		}


		/**
		 * Skip the all the notice from settings page.
		 *
		 * @access public
		 * @since 1.9.0
		 * @return void
		 */
		public function skip_notices() {

			if ( 'toplevel_page_simple-social-buttons' === get_current_screen()->id ) {

				global $wp_filter;

				if ( is_network_admin() and isset( $wp_filter['network_admin_notices'] ) ) {
					unset( $wp_filter['network_admin_notices'] );
				} elseif ( is_user_admin() and isset( $wp_filter['user_admin_notices'] ) ) {
					unset( $wp_filter['user_admin_notices'] );
				} else {
					if ( isset( $wp_filter['admin_notices'] ) ) {
						unset( $wp_filter['admin_notices'] );
					}
				}

				if ( isset( $wp_filter['all_admin_notices'] ) ) {
					unset( $wp_filter['all_admin_notices'] );
				}
			}

		}



		/**
		 * Add Thumbs Up Icon
		 *
		 * @access public
		 * @since 1.9.0
		 * @version 2.1.5
		 * @return mixed
		 */
		public function _row_meta( $links, $file ) {

			if ( strpos( $file, 'simple-social-buttons.php' ) !== false ) {

				echo '<style>.ssb-rate-stars { display: inline-block; color: #ffb900; position: relative; top: 3px; }.ssb-rate-stars svg{ fill:#ffb900; } .ssb-rate-stars svg:hover{ fill:#ffb900 } .ssb-rate-stars svg:hover ~ svg{ fill:none; } </style>';

				$plugin_rate   = 'https://wordpress.org/support/plugin/simple-social-buttons/reviews/?rate=5#new-post';
				$plugin_filter = 'https://wordpress.org/support/plugin/simple-social-buttons/reviews/?filter=5';
				$svg_xmlns     = 'https://www.w3.org/2000/svg';
				$svg_icon      = '';

				for ( $i = 0; $i < 5; $i++ ) {
					$svg_icon .= "<svg xmlns='" . esc_url( $svg_xmlns ) . "' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>";
				}
				// Set link for Reviews.
				$links [] = '<a href=' . esc_url( $plugin_filter ) . '  target="_blank"><span class="dashicons dashicons-thumbs-up"></span> ' . __( 'Vote!', 'simplesocialbuttons' ) . '</a>';

				$links[] = "<a href='" . esc_url( $plugin_rate ) . "' target='_blank' title='" . esc_html__( 'Rate', 'simplesocialbuttons' ) . "'><i class='ssb-rate-stars'>" . $svg_icon . '</i></a>';

			}

			return $links;
		}

	} // end SimpleSocialButtonsPR_Admin

endif;
?>
