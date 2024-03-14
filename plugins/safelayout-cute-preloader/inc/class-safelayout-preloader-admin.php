<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'Safelayout_Preloader_Admin' ) ) {

	class Safelayout_Preloader_Admin {
		protected $options = null;
		protected $default_options = null;
		protected $options_page_hook= null;

		public function __construct() {
			$this->options = safelayout_preloader_get_options();

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 999 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_for_feedback' ) );
			add_action( 'admin_footer-plugins.php', array( $this, 'add_code_for_feedback' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'init_settings' ) );
			add_action( 'admin_init', array( $this, 'add_rate_reminder' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxe' ) );
			add_action( 'save_post', array( $this, 'save_meta_boxe' ) );
			add_action( 'wp_ajax_slpl_preloader_feedback', array( $this, 'preloader_feedback_ajax_handler' ) );
			add_filter( 'http_request_host_is_external', array( $this, 'allow_preloader_feedback_host' ), 10, 3 );
			add_action( 'updated_post_meta', array( $this, 'updated_special_loader_code' ), 10, 4 );
			add_action( 'added_post_meta', array( $this, 'updated_special_loader_code' ), 10, 4 );
		}

		// Update old options
		public function update_old_options() {
			$op1 = get_option( 'safelayout_preloader_options', array() );
			if ( empty( $op1 ) || version_compare( $op1['version'], '2.0.40', '<' ) ) {
				$op1 = wp_parse_args( $op1, safelayout_preloader_get_default_options() );
				$op1['version'] = SAFELAYOUT_PRELOADER_VERSION;
				update_option( 'safelayout_preloader_options', $this->set_HTML_and_CSS_code( $op1, '' ) );
			}
		}

		// Add shortcode meta box
		public function add_meta_boxe() {
			if ( $this->options['special_meta'] === 'enable' ) {
				$posts = get_post_types( array( 'public' => true ) );
				foreach ( $posts as $post ) {
					if ( strpos( $post, 'page' ) !== false ||
						 strpos( $post, 'post' ) !== false ||
						 strpos( $post, 'product' ) !== false ) {
							add_meta_box(
								'safelayout_preloader_meta_box',
								__( 'Safelayout Preloader Shortcode', 'safelayout-cute-preloader' ),
								array( $this, 'meta_box_callback' ),
								$post,
								'advanced',
								'high'
							);
					}
				}
			}
		}

		// Shortcode meta box code
		public function meta_box_callback( $post ) {
			$meta = get_post_meta( $post->ID, 'safelayout_preloader_shortcode', true );
			wp_nonce_field( basename( __FILE__ ), 'safelayout_preloader_meta_nonce' );
			echo '<textarea name="safelayout_preloader_shortcode" style="width: 100%;" rows="5">' . esc_html( $meta ) . '</textarea>';
		}

		// Save shortcode meta box
		public function save_meta_boxe( $post_id ) {
			if ( wp_is_post_autosave( $post_id ) ||
				 wp_is_post_revision( $post_id ) ||
				 ! current_user_can( 'edit_post', $post_id ) ||
				 ! isset( $_POST[ 'safelayout_preloader_meta_nonce' ] ) ||
				 ! wp_verify_nonce( $_POST[ 'safelayout_preloader_meta_nonce' ], basename( __FILE__ ) )	) {
					return $post_id;
			}
			update_post_meta( $post_id, 'safelayout_preloader_shortcode', sanitize_text_field( $_POST[ 'safelayout_preloader_shortcode' ] ) );
		}

		// Add & Updated post meta, Add html and CSS code to option database
		public function updated_special_loader_code( $meta_id, $object_id, $meta_key, $_meta_value ) {
			if ( $meta_key === 'safelayout_preloader_shortcode' ) {
				$meta = stripslashes( $_meta_value );
				if ( $meta != '' && substr( $meta, 1, 20 ) === 'safelayout_preloader' ) {
					$options = $this->set_HTML_and_CSS_code( shortcode_parse_atts( substr( $meta, 22, -1 ) ), $object_id );
					update_option( 'safelayout_preloader_special_post' . $object_id, $options['code_CSS_HTML'] );
				} else {
					delete_option( 'safelayout_preloader_special_post' . $object_id );
				}
			}
		}

		// allow feedback host
		public function allow_preloader_feedback_host( $allow, $host, $url ) {
			return ( false !== strpos( $host, 'safelayout' ) ) ? true : $allow;
		}

		// Add css and js file
		public function enqueue_scripts( $hook ) {
			if ( ! $hook || $hook != $this->options_page_hook ) {
				return;
			}
			wp_enqueue_media();
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				'safelayout-cute-preloader-script-admin',
				SAFELAYOUT_PRELOADER_URL . 'assets/js/safelayout-cute-preloader-admin.min.js',
				array(),
				SAFELAYOUT_PRELOADER_VERSION,
				true
			);
			wp_enqueue_style(
				'safelayout-cute-preloader-style-admin',
				SAFELAYOUT_PRELOADER_URL . 'assets/css/safelayout-cute-preloader-admin.min.css',
				array(),
				SAFELAYOUT_PRELOADER_VERSION
			);
		}

		// Return preloader upgrade data
		public function get_upgrade_data() {
			$upgrade = get_option( 'safelayout_preloader_options_upgrade' );
			if ( ! $upgrade ) {
				$upgrade = time();
				update_option( 'safelayout_preloader_options_upgrade', $upgrade );
			}
			return $upgrade;
		}

		// Return preloader rate reminder data
		public function get_rate_data() {
			$rate = get_option( 'safelayout_preloader_options_rate' );
			if ( ! $rate ) {
				$rate = array(
					'time'	=> time(),
					'later'	=> time(),
				);
				update_option( 'safelayout_preloader_options_rate', $rate );
			}
			return $rate;
		}

		// Add rate reminder
		public function add_rate_reminder() {
			if ( is_super_admin() ) {
				$rate = $this->get_rate_data();
				$upgrade = $this->get_upgrade_data();
				if ( $rate['later'] != 0 && $rate['later'] < strtotime( '-3 day' ) ) {
					add_action( 'admin_notices', array( $this, 'show_rate_reminder' ), 0 );
					add_action( 'wp_ajax_slpl_preloader_rate_reminder', array( $this, 'preloader_rate_reminder_ajax_handler' ) );
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_for_rate_reminder' ) );
				} else if ( $upgrade < strtotime( '-20 day' ) ) {
					add_action( 'admin_notices', array( $this, 'show_upgrade_message' ), 0 );
					add_action( 'wp_ajax_slpl_preloader_upgrade', array( $this, 'preloader_upgrade_ajax_handler' ) );
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_for_rate_reminder' ) );
				}
			}
		}

		// ajax handlers for upgrade message
		public function preloader_upgrade_ajax_handler() {
			check_ajax_referer( 'slpl_preloader_ajax' );
			update_option( 'safelayout_preloader_options_upgrade', time() );
			wp_die();
		}

		// Add upgrade message
		public function show_upgrade_message() {
			global $current_user;
			?>
			<div id="sl-pl-upgrade-reminder" class="notice notice-success">
				<div class="sl-pl-msg-container">
					<p>
						<?php
						printf(
							esc_html__(
								'Howdy, %1$s! Thank you for using %2$s! Please consider %3$s, get full features and %4$s.%5$s',
								'safelayout-cute-preloader'
							),
							'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
							'<strong>' . esc_html__( 'Safelayout Cute Preloader', 'safelayout-cute-preloader' ) . '</strong>',
							'<strong>' . esc_html__( 'upgrading to the PRO version', 'safelayout-cute-preloader' ) . '</strong>',
							'<strong>' . esc_html__( 'support the developer', 'safelayout-cute-preloader' ) . '</strong>',
							'<br>' . esc_html__( 'We really appreciate your support!', 'safelayout-cute-preloader' ) . '<strong> -Safelayout-</strong>'
						);
						?>
					</p>
					<div class="sl-pl-upgrade-reminder-footer">
						<a id="sl-pl-upgrade" class="button" href="https://safelayout.com" target="_blank">
							<span class="dashicons dashicons-smiley"></span><?php esc_html_e( 'Upgrade to Pro', 'safelayout-cute-preloader' ); ?>
						</a>
						<a id="sl-pl-upgrade-later" class="button">
							<span class="dashicons dashicons-calendar"></span><?php esc_html_e( 'Remind me later', 'safelayout-cute-preloader' ); ?>
						</a> 
					</div>
				</div>
			</div>
			<?php
		}

		// ajax handlers for rate reminder
		public function preloader_rate_reminder_ajax_handler() {
			check_ajax_referer( 'slpl_preloader_ajax' );
			$type = sanitize_text_field( $_POST['type'] );
			$rate = $this->get_rate_data();
			if ( $type === 'sl-pl-rate-later' ) {
				$rate['later'] = time();
			} else {
				$rate['later'] = 0;
			}
			update_option( 'safelayout_preloader_options_rate', $rate );

			wp_die();
		}

		// Add rate reminder
		public function show_rate_reminder() {
			global $current_user;
			?>
			<div id="sl-pl-rate-reminder" class="notice notice-success">
				<img class="" alt="safelayout cute preloader" src="https://ps.w.org/safelayout-cute-preloader/assets/icon-128x128.gif">
				<div class="sl-pl-msg-container">
					<p>
						<?php
						printf(
							esc_html__(
								'Howdy, %1$s! Thank you for using %2$s! Could you please do us a BIG favor and %3$s? Just to help us spread the word and boost our motivation.%4$s',
								'safelayout-cute-preloader'
							),
							'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
							'<strong>' . esc_html__( 'Safelayout Cute Preloader', 'safelayout-cute-preloader' ) . '</strong>',
							'<strong>' . esc_html__( 'give it a 5-star rating on WordPress.org', 'safelayout-cute-preloader' ) . '</strong>',
							'<br>' . esc_html__( 'We really appreciate your support!', 'safelayout-cute-preloader' ) . '<strong> -Safelayout-</strong>'
						);
						?>
					</p>
					<div class="sl-pl-rate-reminder-footer">
						<a id="sl-pl-rate-ok" class="button" href="https://wordpress.org/support/plugin/safelayout-cute-preloader/reviews/?filter=5" target="_blank">
							<?php esc_html_e( 'Yes, I will help ★★★★★', 'safelayout-cute-preloader' ); ?>
						</a>
						<a id="sl-pl-rate-later" class="button"><span class="dashicons dashicons-calendar"></span><?php esc_html_e( 'Remind me later', 'safelayout-cute-preloader' ); ?></a>
						<a id="sl-pl-rate-already" class="button"><span class="dashicons dashicons-smiley"></span><?php esc_html_e( 'I already did', 'safelayout-cute-preloader' ); ?></a>
					</div>
				</div>
			</div>
			<?php
		}

		// Add css and js file for rate reminder
		public function enqueue_scripts_for_rate_reminder( $hook ) {
			$this->enqueue_scripts_for_feedback_and_rate();
		}

		// Add css and js file for feedback
		public function enqueue_scripts_for_feedback( $hook ) {
			if ( $hook != 'plugins.php' ) {
				return;
			}
			$this->enqueue_scripts_for_feedback_and_rate();
		}

		// Add css and js file for feedback & rate reminder
		public function enqueue_scripts_for_feedback_and_rate() {
			wp_enqueue_script(
				'safelayout-cute-preloader-script-admin-feedback',
				SAFELAYOUT_PRELOADER_URL . 'assets/js/safelayout-cute-preloader-admin-feedback.min.js',
				array( 'jquery' ),
				SAFELAYOUT_PRELOADER_VERSION,
				true
			);
			$temp_obj = array(
				'ajax_url'	=> admin_url( 'admin-ajax.php' ),
				'nonce'		=> wp_create_nonce( 'slpl_preloader_ajax' ),
			);
			wp_localize_script( 'safelayout-cute-preloader-script-admin-feedback', 'slplPreloaderAjax', $temp_obj );
			wp_enqueue_style(
				'safelayout-cute-preloader-style-admin-feedback',
				SAFELAYOUT_PRELOADER_URL . 'assets/css/safelayout-cute-preloader-admin-feedback.min.css',
				array(),
				SAFELAYOUT_PRELOADER_VERSION
			);
		}

		// ajax handlers for feedback
		public function preloader_feedback_ajax_handler() {
			check_ajax_referer( 'slpl_preloader_ajax' );
			$type = sanitize_text_field( $_POST['type'] );
			$text = sanitize_text_field( $_POST['text'] );
			$apiUrl = 'https://safelayout.com/feedback/feedback.php';
			$rate = $this->get_rate_data();

			$data = array (
				'php'		=> phpversion(),
				'wordpress'	=> get_bloginfo( 'version' ),
				'version'	=> SAFELAYOUT_PRELOADER_VERSION,
				'time'		=> $rate['time'],
				'type'		=> $type,
				'text'		=> $text,
				'plugin'	=> 'preloader',
			);
			$arg = array (
				'body'			=> $data,
				'timeout'		=> 30,
				'sslverify'		=> false,
				'httpversion'	=> 1.1,
			);

			$ret = wp_safe_remote_post( $apiUrl, $arg );
			if ( is_wp_error( $ret ) ) {
				$apiUrl = 'http://' . substr( $apiUrl, 8 );
				$ret = wp_remote_post( $apiUrl, $arg );
			}
			var_dump( $ret );

			wp_die();
		}

		// Add html code for feedback
		public function add_code_for_feedback( $hook ) {
			?>
			<div id="sl-pl-feedback-modal">
				<div class="sl-pl-feedback-window">
					<div class="sl-pl-feedback-header"><?php esc_html_e( 'Quick Feedback', 'safelayout-cute-preloader' ); ?></div>
					<div class="sl-pl-feedback-body">
						<div class="sl-pl-feedback-title">
							<?php esc_html_e( 'If you have a moment, please share why you are deactivating', 'safelayout-cute-preloader' ); ?>
							<span class="dashicons dashicons-smiley"></span>
						</div>
						<div class="sl-pl-feedback-item">
							<input type="radio" name="sl-pl-feedback-radio" value="temporary deactivation" id="sl-pl-feedback-item1">
							<label for="sl-pl-feedback-item1"><?php esc_html_e( "It's a temporary deactivation", 'safelayout-cute-preloader' ); ?></label>
						</div>
						<div class="sl-pl-feedback-item">
							<input type="radio" name="sl-pl-feedback-radio" value="site broken" id="sl-pl-feedback-item2">
							<label for="sl-pl-feedback-item2"><?php esc_html_e( 'The plugin broke my site', 'safelayout-cute-preloader' ); ?></label><br>
							<textarea rows="2" id="sl-pl-feedback-item2-text" placeholder="<?php esc_html_e( 'Please explain the problem.', 'safelayout-cute-preloader' ); ?>"></textarea>
						</div>
						<div class="sl-pl-feedback-item">
							<input type="radio" name="sl-pl-feedback-radio" value="better plugin" id="sl-pl-feedback-item5">
							<label for="sl-pl-feedback-item5"><?php esc_html_e( 'I found a better plugin', 'safelayout-cute-preloader' ); ?></label><br>
							<input type="text" id="sl-pl-feedback-item5-text" placeholder="<?php esc_html_e( "What's the plugin name?", 'safelayout-cute-preloader' ); ?>">
						</div>
						<div class="sl-pl-feedback-item">
							<input type="radio" name="sl-pl-feedback-radio" value="Other" id="sl-pl-feedback-item6">
							<label for="sl-pl-feedback-item6"><?php esc_html_e( 'Other', 'safelayout-cute-preloader' ); ?></label><br>
							<textarea rows="2" id="sl-pl-feedback-item6-text" placeholder="<?php esc_html_e( 'Please share the reason.', 'safelayout-cute-preloader' ); ?>"></textarea>
						</div>
						<p>
							<?php esc_html_e( 'No email address, domain name or IP addresses are transmitted after you submit the survey.', 'safelayout-cute-preloader' ); ?><br>
							<?php esc_html_e( 'You can see the source code here: ', 'safelayout-cute-preloader' ); ?> /wp-content/plugins/safelayout-cute-preloader/inc/class-safelayout-preloader-admin.php ( line: 293 ).
						</p>
					</div>
					<div class="sl-pl-feedback-footer">
						<a id="sl-pl-feedback-submit" class="button"><?php esc_html_e( 'Submit & Deactivate', 'safelayout-cute-preloader' ); ?></a>
						<a id="sl-pl-feedback-skip" class="button"><?php esc_html_e( 'Skip & Deactivate', 'safelayout-cute-preloader' ); ?></a> 
					</div>
					<div id="sl-pl-feedback-loader"><div id="sl-pl-dots-rate" class="sl-pl-spin-rate"><div><span></span><span></span><span></span><span></span></div>
					<div id="sl-pl-feedback-loader-msg"><?php esc_html_e( 'Wait ...', 'safelayout-cute-preloader' ); ?></div></div></div>
					<div id="sl-pl-feedback-loader-msg-tr"><?php esc_html_e( 'Redirecting ...', 'safelayout-cute-preloader' ); ?></div>
				</div>
			</div>
			<?php
		}

		// Add an admin menu for preloader
		public function admin_menu() {
			$this->options_page_hook = add_options_page(
				esc_html__( 'Safelayout Cute Preloader Options', 'safelayout-cute-preloader' ),
				esc_html__( 'Safelayout Preloader', 'safelayout-cute-preloader' ),
				'manage_options',
				'safelayout-cute-preloader',
				array( $this, 'admin_menu_page' )
			);
		}

		// Admin menu page
		public function admin_menu_page() {
			$this->default_options = safelayout_preloader_get_default_options();
			?>
			<div class="wrap">
				<h2><?php esc_html_e( 'Safelayout Cute Preloader Options', 'safelayout-cute-preloader' ); ?></h2>
				<?php settings_errors( 'safelayout-cute-preloader' ); ?>
				<div id="tabs">
					<div class="ui-tabs-side">
						<ul>
							<li><a href="#tabs-1"><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'Display settings', 'safelayout-cute-preloader' ); ?></a></li>
							<li><a href="#tabs-2"><span class="dashicons dashicons-desktop"></span> <?php esc_html_e( 'Background', 'safelayout-cute-preloader' ); ?></a></li>
							<li><a href="#tabs-3"><span class="dashicons dashicons-welcome-widgets-menus" id="sl-pl-progress-bar-icon"></span> <?php esc_html_e( 'Progress Bar', 'safelayout-cute-preloader' ); ?></a></li>
							<li><a href="#tabs-4"><span class="dashicons dashicons-awards"></span> <?php esc_html_e( 'Brand Image', 'safelayout-cute-preloader' ); ?></a></li>
							<li><a href="#tabs-5"><span class="dashicons dashicons-star-empty"></span> <?php esc_html_e( 'Icon', 'safelayout-cute-preloader' ); ?></a></li>
							<li><a href="#tabs-6"><span class="dashicons dashicons-editor-ol" id="sl-pl-counter-icon"></span> <?php esc_html_e( 'Counter', 'safelayout-cute-preloader' ); ?></a></li>
							<li><a href="#tabs-7"><span class="dashicons dashicons-editor-textcolor"></span> <?php esc_html_e( 'Text', 'safelayout-cute-preloader' ); ?></a></li>
							<li><a href="#tabs-8"><span class="dashicons dashicons-edit"></span> <?php esc_html_e( 'Special Preloader', 'safelayout-cute-preloader' ); ?></a></li>
							<a href="https://safelayout.com" target="_blank" class="button" id="sl-pl-side-button-upgrade" title="<?php esc_html_e( 'Upgrade to pro version and get full features.', 'safelayout-cute-preloader' ); ?>">
								<span class="dashicons dashicons-unlock sl-pl-side-button-icon"></span> <?php esc_html_e( 'Upgrade to Pro', 'safelayout-cute-preloader' ); ?></a>
							<a href="https://wordpress.org/support/plugin/safelayout-cute-preloader/reviews/?filter=5" target="_blank" class="button" id="sl-pl-side-button-rate" title="<?php esc_html_e( 'Like the plugin? Please give us a rating!', 'safelayout-cute-preloader' ); ?>">
								<span class="dashicons dashicons-star-filled sl-pl-side-button-icon"></span> <?php esc_html_e( 'Rate The Plugin', 'safelayout-cute-preloader' ); ?></a>
						</ul>
					</div>
					<div class="ui-tabs-content">
						<form method="post" action="options.php">
							<?php settings_fields( 'safelayout_preloader_options_group' ); ?>
							<div id="tabs-1">
								<?php do_settings_sections( 'safelayout-cute-preloader-advanced' ); ?>
							</div>
							<div id="tabs-2">
								<?php do_settings_sections( 'safelayout-cute-preloader-background' ); ?>
							</div>
							<div id="tabs-3">
								<?php do_settings_sections( 'safelayout-cute-preloader-progress-bar' ); ?>
							</div>
							<div id="tabs-4">
								<?php do_settings_sections( 'safelayout-cute-preloader-brand' ); ?>
							</div>
							<div id="tabs-5">
								<?php do_settings_sections( 'safelayout-cute-preloader-icon' ); ?>
							</div>
							<div id="tabs-6">
								<?php do_settings_sections( 'safelayout-cute-preloader-counter' ); ?>
							</div>
							<div id="tabs-7">
								<?php do_settings_sections( 'safelayout-cute-preloader-text' ); ?>
							</div>
							<div id="tabs-8">
								<?php do_settings_sections( 'safelayout-cute-preloader-special' ); ?>
							</div>
							<div id="sl-pl-options-button-container">
								<?php submit_button( esc_html__( 'Save Changes', 'safelayout-cute-preloader' ), 'primary', 'submit', false ); ?>
								<span style="margin: 15px;"></span>
								<?php submit_button( esc_html__( 'Restore Defaults', 'safelayout-cute-preloader' ), 'delete', 'delete', false ); ?>
							</div>
							<input type="text" id="ui_tabs_activate" name="safelayout_preloader_options[ui_tabs_activate]" style="display:none" value="<?php echo esc_html( $this->options['ui_tabs_activate'] ); ?>" />
						</form>
					</div>
					<a id="sl-pl-other-plugins" href="https://safelayout.com/safelayout-elegant-icons-pro-demo/" target="_blank">
						<img alt="safelayout elegant icons" src="<?php echo SAFELAYOUT_PRELOADER_URL . 'assets/image/safelayout-elegant-icons.png'; ?>"/>
					</a>
				</div>
			</div>
			<?php
		}

		// Add settings fields
		public function init_settings() {
			$this->update_old_options();
			register_setting(
				'safelayout_preloader_options_group',
				'safelayout_preloader_options',
				array( $this, 'option_sanitize' )
			);

			add_settings_section(
				'safelayout_preloader_section_icon',
				esc_html__( 'Icon settings', 'safelayout-cute-preloader' ),
				array( $this, 'section_icon_info' ),
				'safelayout-cute-preloader-icon'
			);

			add_settings_field(
				'icon',
				esc_html__( 'Preloader icon', 'safelayout-cute-preloader' ),
				array( $this, 'settings_icon_callback' ),
				'safelayout-cute-preloader-icon',
				'safelayout_preloader_section_icon'
			);

			add_settings_field(
				'icon_preview',
				esc_html__( 'Icon preview', 'safelayout-cute-preloader' ),
				array( $this, 'settings_icon_preview_callback' ),
				'safelayout-cute-preloader-icon',
				'safelayout_preloader_section_icon'
			);

			add_settings_field(
				'icon_size',
				esc_html__( 'Icon size', 'safelayout-cute-preloader' ),
				array( $this, 'settings_icon_size_callback' ),
				'safelayout-cute-preloader-icon',
				'safelayout_preloader_section_icon'
			);

			add_settings_field(
				'icon_gradient_value',
				esc_html__( 'Icon color', 'safelayout-cute-preloader' ),
				array( $this, 'settings_icon_free_color_callback' ),
				'safelayout-cute-preloader-icon',
				'safelayout_preloader_section_icon'
			);

			add_settings_field(
				'icon_effect',
				esc_html__( 'Icon effect', 'safelayout-cute-preloader' ),
				array( $this, 'settings_icon_free_effect_callback' ),
				'safelayout-cute-preloader-icon',
				'safelayout_preloader_section_icon'
			);

			add_settings_section(
				'safelayout_preloader_section_background',
				esc_html__( 'Background settings', 'safelayout-cute-preloader' ),
				array( $this, 'section_icon_info' ),
				'safelayout-cute-preloader-background'
			);

			add_settings_field(
				'background_anim',
				esc_html__( 'Background Animation', 'safelayout-cute-preloader' ),
				array( $this, 'settings_background_anim_callback' ),
				'safelayout-cute-preloader-background',
				'safelayout_preloader_section_background'
			);

			add_settings_field(
				'background_alpha',
				esc_html__( 'Background opacity', 'safelayout-cute-preloader' ),
				array( $this, 'settings_background_alpha_callback' ),
				'safelayout-cute-preloader-background',
				'safelayout_preloader_section_background'
			);

			add_settings_field(
				'background_gradient_value',
				esc_html__( 'Background color', 'safelayout-cute-preloader' ),
				array( $this, 'settings_background_free_color_callback' ),
				'safelayout-cute-preloader-background',
				'safelayout_preloader_section_background'
			);

			add_settings_section(
				'safelayout_preloader_section_text',
				esc_html__( 'Text settings', 'safelayout-cute-preloader' ),
				array( $this, 'section_icon_info' ),
				'safelayout-cute-preloader-text'
			);

			add_settings_field(
				'text',
				esc_html__( 'Text', 'safelayout-cute-preloader' ),
				array( $this, 'settings_text_callback' ),
				'safelayout-cute-preloader-text',
				'safelayout_preloader_section_text'
			);

			add_settings_field(
				'text_anim',
				esc_html__( 'Text animation', 'safelayout-cute-preloader' ),
				array( $this, 'settings_text_anim_callback' ),
				'safelayout-cute-preloader-text',
				'safelayout_preloader_section_text'
			);

			add_settings_field(
				'text_preview',
				esc_html__( 'Text preview', 'safelayout-cute-preloader' ),
				array( $this, 'settings_text_preview_callback' ),
				'safelayout-cute-preloader-text',
				'safelayout_preloader_section_text'
			);

			add_settings_field(
				'text_color',
				esc_html__( 'Text color', 'safelayout-cute-preloader' ),
				array( $this, 'settings_text_color_callback' ),
				'safelayout-cute-preloader-text',
				'safelayout_preloader_section_text'
			);

			add_settings_field(
				'text_size',
				esc_html__( 'Font size', 'safelayout-cute-preloader' ),
				array( $this, 'settings_text_size_callback' ),
				'safelayout-cute-preloader-text',
				'safelayout_preloader_section_text'
			);

			add_settings_field(
				'text_margin_top',
				esc_html__( 'Margin top', 'safelayout-cute-preloader' ),
				array( $this, 'settings_text_margin_callback' ),
				'safelayout-cute-preloader-text',
				'safelayout_preloader_section_text'
			);

			add_settings_section(
				'safelayout_preloader_section_brand',
				esc_html__( 'Brand Image', 'safelayout-cute-preloader' ),
				array( $this, 'section_icon_info' ),
				'safelayout-cute-preloader-brand'
			);

			add_settings_field(
				'brand_description',
				'',
				array( $this, 'settings_brand_description_callback' ),
				'safelayout-cute-preloader-brand',
				'safelayout_preloader_section_brand'
			);

			add_settings_field(
				'brand_url',
				esc_html__( 'Image URL', 'safelayout-cute-preloader' ),
				array( $this, 'settings_brand_callback' ),
				'safelayout-cute-preloader-brand',
				'safelayout_preloader_section_brand'
			);

			add_settings_field(
				'brand_anim',
				esc_html__( 'Image animation', 'safelayout-cute-preloader' ),
				array( $this, 'settings_brand_anim_callback' ),
				'safelayout-cute-preloader-brand',
				'safelayout_preloader_section_brand'
			);

			add_settings_field(
				'brand_preview',
				esc_html__( 'Image preview', 'safelayout-cute-preloader' ),
				array( $this, 'settings_brand_preview_callback' ),
				'safelayout-cute-preloader-brand',
				'safelayout_preloader_section_brand'
			);

			add_settings_field(
				'brand_position',
				esc_html__( 'Image position', 'safelayout-cute-preloader' ),
				array( $this, 'settings_brand_position_callback' ),
				'safelayout-cute-preloader-brand',
				'safelayout_preloader_section_brand'
			);

			add_settings_field(
				'brand_margin_top',
				esc_html__( 'Margin top', 'safelayout-cute-preloader' ),
				array( $this, 'settings_brand_margin_top_callback' ),
				'safelayout-cute-preloader-brand',
				'safelayout_preloader_section_brand'
			);

			add_settings_field(
				'brand_margin_bottom',
				esc_html__( 'Margin bottom', 'safelayout-cute-preloader' ),
				array( $this, 'settings_brand_margin_bottom_callback' ),
				'safelayout-cute-preloader-brand',
				'safelayout_preloader_section_brand'
			);

			add_settings_section(
				'safelayout_preloader_section_advanced',
				esc_html__( 'Display settings', 'safelayout-cute-preloader' ),
				array( $this, 'section_icon_info' ),
				'safelayout-cute-preloader-advanced'
			);

			add_settings_field(
				'enable_preloader',
				esc_html__( 'Enable Preloader', 'safelayout-cute-preloader' ),
				array( $this, 'settings_enable_preloader_callback' ),
				'safelayout-cute-preloader-advanced',
				'safelayout_preloader_section_advanced'
			);

			add_settings_field(
				'display_on',
				esc_html__( 'Display on', 'safelayout-cute-preloader' ),
				array( $this, 'settings_display_on_callback' ),
				'safelayout-cute-preloader-advanced',
				'safelayout_preloader_section_advanced'
			);

			add_settings_field(
				'minimum_time',
				esc_html__( 'Minimum load time', 'safelayout-cute-preloader' ),
				array( $this, 'settings_minimum_time_callback' ),
				'safelayout-cute-preloader-advanced',
				'safelayout_preloader_section_advanced'
			);

			add_settings_field(
				'maximum_time',
				esc_html__( 'Maximum load time', 'safelayout-cute-preloader' ),
				array( $this, 'settings_maximum_time_callback' ),
				'safelayout-cute-preloader-advanced',
				'safelayout_preloader_section_advanced'
			);

			add_settings_field(
				'close_button',
				esc_html__( 'Show close button after', 'safelayout-cute-preloader' ),
				array( $this, 'settings_close_button_callback' ),
				'safelayout-cute-preloader-advanced',
				'safelayout_preloader_section_advanced'
			);

			add_settings_section(
				'safelayout_preloader_section_special',
				esc_html__( 'Special preloader', 'safelayout-cute-preloader' ),
				array( $this, 'section_icon_info' ),
				'safelayout-cute-preloader-special'
			);

			add_settings_field(
				'special_description',
				'',
				array( $this, 'settings_special_description_callback' ),
				'safelayout-cute-preloader-special',
				'safelayout_preloader_section_special'
			);

			add_settings_field(
				'special_meta',
				esc_html__( 'Show meta box', 'safelayout-cute-preloader' ),
				array( $this, 'settings_special_meta_callback' ),
				'safelayout-cute-preloader-special',
				'safelayout_preloader_section_special'
			);

			add_settings_field(
				'special_code_gen',
				esc_html__( 'Preloader Shortcode', 'safelayout-cute-preloader' ),
				array( $this, 'settings_special_callback' ),
				'safelayout-cute-preloader-special',
				'safelayout_preloader_section_special'
			);

			add_settings_field(
				'special_description1',
				'',
				array( $this, 'settings_special_description1_callback' ),
				'safelayout-cute-preloader-special',
				'safelayout_preloader_section_special'
			);

			add_settings_section(
				'safelayout_preloader_section_progress_bar',
				esc_html__( 'Progress Bar', 'safelayout-cute-preloader' ),
				array( $this, 'section_icon_info' ),
				'safelayout-cute-preloader-progress-bar'
			);

			add_settings_field(
				'bar_shape',
				esc_html__( 'Bar shape', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_shape_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_preview',
				esc_html__( 'Bar preview', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_preview_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_light',
				esc_html__( 'Bar light', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_light_effect_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_gradient_value',
				esc_html__( 'Bar color', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_free_color_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_position',
				esc_html__( 'Bar position', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_position_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_width',
				esc_html__( 'Bar width', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_width_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_height',
				esc_html__( 'Bar height', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_height_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_border_radius',
				esc_html__( 'Border radius', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_border_radius_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_border_color',
				esc_html__( 'Border color', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_border_color_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_margin_top',
				esc_html__( 'Margin top', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_margin_top_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_margin_bottom',
				esc_html__( 'Margin bottom', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_margin_bottom_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_field(
				'bar_margin_left',
				esc_html__( 'Margin left', 'safelayout-cute-preloader' ),
				array( $this, 'settings_bar_margin_left_callback' ),
				'safelayout-cute-preloader-progress-bar',
				'safelayout_preloader_section_progress_bar'
			);

			add_settings_section(
				'safelayout_preloader_section_counter',
				esc_html__( 'Counter', 'safelayout-cute-preloader' ),
				array( $this, 'section_icon_info' ),
				'safelayout-cute-preloader-counter'
			);

			add_settings_field(
				'counter',
				esc_html__( 'Show counter', 'safelayout-cute-preloader' ),
				array( $this, 'settings_show_counter_callback' ),
				'safelayout-cute-preloader-counter',
				'safelayout_preloader_section_counter'
			);

			add_settings_field(
				'counter_text',
				esc_html__( 'Counter Text', 'safelayout-cute-preloader' ),
				array( $this, 'settings_counter_text_callback' ),
				'safelayout-cute-preloader-counter',
				'safelayout_preloader_section_counter'
			);

			add_settings_field(
				'counter_preview',
				esc_html__( 'Counter preview', 'safelayout-cute-preloader' ),
				array( $this, 'settings_counter_preview_callback' ),
				'safelayout-cute-preloader-counter',
				'safelayout_preloader_section_counter'
			);

			add_settings_field(
				'counter_position',
				esc_html__( 'Counter position', 'safelayout-cute-preloader' ),
				array( $this, 'settings_counter_position_callback' ),
				'safelayout-cute-preloader-counter',
				'safelayout_preloader_section_counter'
			);

			add_settings_field(
				'counter_color',
				esc_html__( 'Counter color', 'safelayout-cute-preloader' ),
				array( $this, 'settings_counter_color_callback' ),
				'safelayout-cute-preloader-counter',
				'safelayout_preloader_section_counter'
			);

			add_settings_field(
				'counter_size',
				esc_html__( 'Font size', 'safelayout-cute-preloader' ),
				array( $this, 'settings_counter_size_callback' ),
				'safelayout-cute-preloader-counter',
				'safelayout_preloader_section_counter'
			);

			add_settings_field(
				'counter_margin_top',
				esc_html__( 'Margin top', 'safelayout-cute-preloader' ),
				array( $this, 'settings_counter_margin_top_callback' ),
				'safelayout-cute-preloader-counter',
				'safelayout_preloader_section_counter'
			);

			add_settings_field(
				'counter_margin_bottom',
				esc_html__( 'Margin bottom', 'safelayout-cute-preloader' ),
				array( $this, 'settings_counter_margin_bottom_callback' ),
				'safelayout-cute-preloader-counter',
				'safelayout_preloader_section_counter'
			);

			add_settings_field(
				'counter_margin_left',
				esc_html__( 'Margin left', 'safelayout-cute-preloader' ),
				array( $this, 'settings_counter_margin_left_callback' ),
				'safelayout-cute-preloader-counter',
				'safelayout_preloader_section_counter'
			);
		}

		// Section info
		public function section_icon_info() {
		}

		// Icon field code
		public function settings_icon_callback() {
			$icons = array( 'No', 'moons', 'tube', 'stream', 'cycle', 'planet', 'cube', '3d-bar', 'cube1', 'balloons',
				'blade-vertical', 'blade-vertical1', 'fold', 'blade-horizontal', 'blade-horizontal1', 'circle',
				'bubble', 'triple-spinner', 'bubble1', 'leap', 'window', 'wheel', 'turn', 'turn1', 'spinner', 'flight', 
				'gear', 'dive', 'trail', '3d-square', 'infinite', 'grid', 'jump', '3d-plate', 'crawl', 'queue', 'Custom', );
			$temp0 = array( 'bubble' => 0, 'cycle' => 1, 'stream' => 2, 'tube' => 3, 'wheel' => 4, 'turn' => 5, 'turn1' => 6, 'triple-spinner' => 7, );
			$temp1 = array( 'water', 'ball', 'spinner1', 'dots', 'cube2', '3d-grid', 'gear1', 'turn2', 'octagon', 'octagon1', 'bubble2', );
			$counter = 0;
			$icon_type = in_array( $this->options['icon'], $temp1 ) ? 'bubble' : $this->options['icon'];

			echo '<div class="sl-pl-icon-container">';
			foreach ( $icons as $icon ) {
				echo ( $icon === 'Custom' ? '<br>' : '' ) . '<input class="sl-pl-icon-radio" type="radio" id="preloader_icon_' . esc_html( $counter ) . '" ' .
					 'name="safelayout_preloader_options[icon]" value="' . esc_html( $icon ) . '" ' .
					 checked( esc_attr( $icon_type ), esc_html( $icon ), false ) . ' />' . '<label class="sl-pl-icon-label" for="preloader_icon_' .
					 esc_html( $counter ) . '" title="' . esc_html( $icon ) . '" id="sl-pl-icon-label-' . esc_html( $icon ) . '">';
				if ( $icon === 'No' ) {
					echo '<div class="sl-pl-icon-div-text">' . esc_html__( 'No Icon', 'safelayout-cute-preloader' ) . '</div></label>';
				} else if ( $icon === 'Custom' ) {
					echo '<div class="sl-pl-icon-div-text">' . esc_html__( 'Custom Icon', 'safelayout-cute-preloader' ) . '</div></label>';
				} else {
					echo '<div class="sl-pl-icon-div">';

					$grad = '';
					if ( array_key_exists( $icon, $temp0 ) ) {
						$grad = $this->get_gradients( $temp0[ $icon ] );
					}

					$this->set_icon( $this->options, $icon, $grad );
					echo '</div></label>';
				}
				$counter++;
			}

			$msg = __( 'You can add your own icon by entering the URL here. (Click outside the text box to apply the changes)', 'safelayout-cute-preloader' );
			echo '<div class="sl-pl-custom-icon-div"><input type="url" id="custom_icon" ' .
				 'name="safelayout_preloader_options[custom_icon]" class="sl-pl-image-url" ' .
				 'placeholder="http://www.example.com/myimage.gif" value="' .
				 esc_url( $this->options['custom_icon'], $this->add_data_protocol() ) .
				 '" /><input type="button" class="button" id="sl-pl-custom-media" value="' .
				 esc_html__( 'Media Library', 'safelayout-cute-preloader' ) .
				 '" /><input type="text" name="safelayout_preloader_options[custom_icon_alt]" class="sl-pl-hidden" id="custom_icon_alt" value="' .
				 esc_attr( $this->options['custom_icon_alt'] ) . '" /><input type="number" name="safelayout_preloader_options[custom_icon_width]" ' .
				 'class="sl-pl-hidden" id="custom_icon_width" value="' . esc_attr( $this->options['custom_icon_width'] ) . '" />' .
				 '<input type="number" name="safelayout_preloader_options[custom_icon_height]" class="sl-pl-hidden" ' .
				 'id="custom_icon_height" value="' . esc_attr( $this->options['custom_icon_height'] ) .
				 '" /><span class="description">' . esc_html( $msg ) . '</span></div></div>';
		}

		// Icon preview code
		public function settings_icon_preview_callback() {
			echo '<div id="sl-pl-icon-preview-container">' . esc_html__( 'Preview', 'safelayout-cute-preloader' ) .
				 '<span id="sl-pl-icon-preview-title"></span><br /><div id="sl-pl-icon-preview">' .
				 '<div class="sl-pl-icon-preview-background"></div><div id="sl-pl-icon-box" class="sl-pl-spin-container"></div></div></div>';
		}

		// Icon free color code
		public function settings_icon_free_color_callback() {
			echo '<div class="sl-pl-free-color-container">';
			$this->set_color_code( 'icon_gradient_value', 'icon_free_color_' );
			echo '</div>';
		}

		// echo free version color HTML
		public function set_color_code( $name, $id ) {
			for ( $i = 0; $i < 14 ; $i++ ) {
				echo '<input class="sl-pl-free-color-radio" type="radio" id="' . esc_attr( $id ) . esc_html( $i ) . '" ' .
					 'name="safelayout_preloader_options[' . esc_attr( $name ) . ']" value="' . esc_html( $i ) . '" ' .
					 checked( esc_attr( $this->options[ $name ] ), $i, false ) . ' />' .
					 '<label class="sl-pl-free-color-label" for="'. esc_attr( $id ) . esc_html( $i ) .
					 '" style="background: ' . esc_html( $this->get_color( $i, false ) ) . '"></label>';
			}
		}

		// Icon free effect code
		public function settings_icon_free_effect_callback() {
			echo '<div class="sl-pl-free-color-container">';
			for ( $counter = 0 ; $counter < 4 ; $counter++ ) {
				echo '<input class="sl-pl-effect-radio" type="radio" id="icon_effect_' . esc_html( $counter ) . '" ' .
					 'name="safelayout_preloader_options[icon_effect]" value="' . esc_html( $counter ) . '" ' .
					 checked( esc_attr( $this->options['icon_effect'] ), $counter, false ) . ' />' .
					 '<label class="sl-pl-effect-label" for="icon_effect_' . esc_html( $counter ) . '">';
				if ( $counter === 0 ) {
					echo '<div class="sl-pl-effect-div-text">' . esc_html__( 'No Effect', 'safelayout-cute-preloader' ) . '</div></label>';
				} else {
					echo '<div class="sl-pl-effect-div" style="filter: url(#sl-pl-svg-filter' . esc_html( $counter ) .
						 ');-webkit-filter: url(#sl-pl-svg-filter' . esc_html( $counter ) . ');"></div></label>';
				}
			}
			echo '</div>';

			for ( $counter = 1 ; $counter < 4 ; $counter++ ) {
				$this->get_effects( $counter );
			}
		}

		// Icon size field code
		public function settings_icon_size_callback() {
			echo '<input type="number" id="icon_size" name="safelayout_preloader_options[icon_size]" ' .
				 'min="10" max="1000" step="1" value="' . esc_attr( $this->options['icon_size'] ) .
				 '" data-default-size="' . esc_attr( $this->default_options['icon_size'] ) . '" /> ' .
				 esc_html__( 'px', 'safelayout-cute-preloader' ) . '<br /><br />';
		}

		// Background animation field code
		public function settings_background_anim_callback() {
			$backgrounds = array( 'No', 'fade', 'to-left', 'to-right', 'to-top', 'to-bottom', 'rect',
				'diamond', 'circle', 'ellipse-top', 'ellipse-bottom', 'ellipse-left', 'ellipse-right',
				'tear-vertical', 'tear-horizontal', 'split-vertical', 'split-horizontal', 'linear-left', 'linear-right', );
			$counter = 0;

			echo '<div class="sl-pl-background-container">';
			foreach ( $backgrounds as $background ) {
				echo '<input class="sl-pl-background-radio" type="radio" id="preloader_background_' .
					 esc_html( $counter ) . '" name="safelayout_preloader_options[background_anim]" value="' .
					 esc_html( $background ) . '" ' . checked( esc_attr( $this->options['background_anim'] ), $background, false ) . ' />' .
					 '<label class="sl-pl-background-label" for="preloader_background_' . esc_html( $counter ) . '" title="' . esc_html( $background ) . '">';
				if ( $background === 'No' ) {
					echo '<div class="sl-pl-background-div-text">' . esc_html__( 'No Background', 'safelayout-cute-preloader' ) . '</div></label>';
				} else {
					$class = 'sl-pl-bg-admin';
					if ( $background === 'linear-left' || $background === 'linear-right' ) {
						$class = 'sl-pl-bg-admin-linear';
					}
					echo '<div class="sl-pl-background-div">';
					$this->set_background( $background, $class );
					echo '</div></label>';
				}
				$counter++;
			}

			echo '</div>';
		}

		// Background alpha field code
		public function settings_background_alpha_callback() {
			$alpha = $this->options['background_alpha'];
			echo '<input type="range" id="sl-pl-background-alpha" name="safelayout_preloader_options[background_alpha]" ' .
				 'min="1" max="100" step="1" value="' . esc_attr( $alpha ) . '" />' .
				 '<output id="background-alpha-output" for="sl-pl-background-alpha">' . esc_attr( $alpha ) . '</output> %';
		}

		// Background free color code
		public function settings_background_free_color_callback() {
			echo '<div class="sl-pl-free-color-container">';
			$this->set_color_code( 'background_gradient_value', 'background_free_color_' );
			echo '</div>';
		}

		// Text field code
		public function settings_text_callback() {
			$msg = __( 'Text to display under preloader icon. (Click outside the text box to apply the changes)', 'safelayout-cute-preloader' );
			echo '<input type="text" name="safelayout_preloader_options[text]" class="large-text" value="' .
				 esc_attr( $this->options['text'] ) . '" /><span class="description">' . esc_html( $msg ) . '</span>';
		}

		// Text animation field code
		public function settings_text_anim_callback() {
			$texts = array( 'No', 'bounce', 'glow', 'yoyo', 'spring', 'zoom', 'wave', 'swing', 'shadow', );
			echo '<select name="safelayout_preloader_options[text_anim]" id="text_anim">';

			foreach ( $texts as $text ) {
				echo '<option value="' . esc_html( $text ) . '" ' . selected( esc_attr( $this->options['text_anim'] ), $text, false ) . '>' .
					 esc_html( $text ) . ( $text === 'No' ? ' animation' : '') . '</option>';
			}

			echo '</select>';
		}

		// Text preview code
		public function settings_text_preview_callback() {
			echo '<div class="sl-pl-text-preview-container">' . esc_html__( 'Preview', 'safelayout-cute-preloader' ) .
				 '<span id="sl-pl-text-preview-title"></span><br /><div id="sl-pl-text-preview">' .
				 '<div class="sl-pl-icon-preview-background"></div><div id="sl-pl-text-box" class="sl-pl-spin-container"></div></div></div>';
		}

		// Text color code
		public function settings_text_color_callback() {
			echo '<input type="text" name="safelayout_preloader_options[text_color]" class="sl-pl-text-color" ' .
				 'data-default-color="' . esc_attr( $this->default_options['text_color'] ) . '" value="' .
				 esc_attr( $this->options['text_color'] ) . '" />';
		}

		// Text size field code
		public function settings_text_size_callback() {
			echo '<input type="number" id="text_size" name="safelayout_preloader_options[text_size]" ' .
				 'min="5" max="300" step="1" value="' . esc_attr( $this->options['text_size'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['text_size'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// Text margin top field code
		public function settings_text_margin_callback() {
			echo '<input type="number" id="text_margin_top" name="safelayout_preloader_options[text_margin_top]" ' .
				 'min="-500" max="500" step="1" value="' . esc_attr( $this->options['text_margin_top'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['text_margin_top'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// Brand description code
		public function settings_brand_description_callback() {
			$msg = __( 'You can display your Brand image or logo inside the preloader screen. In this section you can enter the URL of the image and set its properties.', 'safelayout-cute-preloader' );
			echo '<div class="description sl-pl-description">' . esc_html( $msg ) . '</div>';
		}

		// Brand field code
		public function settings_brand_callback() {
			$msg = __( 'You can add your Brand image by entering the URL here. (Click outside the text box to apply the changes)', 'safelayout-cute-preloader' );
			echo '<input type="url" name="safelayout_preloader_options[brand_url]" placeholder="http://www.example.com/myimage.png" id="brand_url" ' .
				 'class="sl-pl-image-url" value="' . esc_url( $this->options['brand_url'], $this->add_data_protocol() ) .
				 '" /><input type="button" class="button" id="sl-pl-brand-media" value="' . esc_html__( 'Media Library', 'safelayout-cute-preloader' ) .
				 '" /><input type="text" name="safelayout_preloader_options[brand_url_alt]" class="sl-pl-hidden" id="brand_url_alt" value="' .
				 esc_attr( $this->options['brand_url_alt'] ) . '" /><span class="description">' . esc_html( $msg ) .
				 '</span><input type="number" name="safelayout_preloader_options[brand_width]" ' .
				 'class="sl-pl-hidden" id="brand_width" value="' . esc_attr( $this->options['brand_width'] ) . '" />' .
				 '<input type="number" name="safelayout_preloader_options[brand_height]" class="sl-pl-hidden" ' .
				 'id="brand_height" value="' . esc_attr( $this->options['brand_height'] ) . '" />';
		}

		// Brand animation field code
		public function settings_brand_anim_callback() {
			$msg = __( 'Wrest X, wrest Y, swirl, sheet, roll and pipe animation types are not suitable for images with a width or height greater than 550 pixels.', 'safelayout-cute-preloader' );
			$anims = array( 'No', 'flash', 'rotate 2D', 'rotate 3D X', 'rotate 3D Y', 'wrest X', 'wrest Y', 'swirl', 'sheet', 'roll', 'pipe', 'bounce', 'yoyo', 'swing', );
			echo '<select name="safelayout_preloader_options[brand_anim]" id="brand_anim">';

			foreach ( $anims as $anim ) {
				echo '<option value="' . esc_html( str_replace( ' ', '-', $anim ) ) . '" ' .
					 selected( esc_attr( $this->options['brand_anim'] ), str_replace( ' ', '-', $anim ), false ) . '>' .
					 esc_html( $anim ) . ( $anim === 'No' ? ' animation' : '') . '</option>';
			}

			echo '</select><span class="sl-pl-hidden" id="sl-pl-brand-size-alert">' . esc_html( $msg ) . '</span>';
		}

		// Brand preview code
		public function settings_brand_preview_callback() {
			echo '<div class="sl-pl-text-preview-container">' . esc_html__( 'Preview', 'safelayout-cute-preloader' ) .
				 '<span id="sl-pl-brand-preview-title"></span><br /><div id="sl-pl-brand-preview">' .
				 '<div class="sl-pl-icon-preview-background"></div><div id="sl-pl-brand-box" class="sl-pl-spin-container"></div></div></div>';
		}

		// Brand position field code
		public function settings_brand_position_callback() {
			$pos = array(
				[ 'top', __('Top of the preloader icon', 'safelayout-cute-preloader' ) ],
				[ 'middle', __('Top of the preloader counter', 'safelayout-cute-preloader' ) ],
				[ 'middle_text', __('Top of the preloader text', 'safelayout-cute-preloader' ) ],
				[ 'bottom', __('Under the preloader text', 'safelayout-cute-preloader' ) ],
			);
			echo '<select name="safelayout_preloader_options[brand_position]" id="brand_position">';

			foreach ( $pos as $p ) {
				echo '<option value="' . esc_html( $p[0] ) . '" ' .
					 selected( esc_attr( $this->options['brand_position'] ), $p[0], false ) . '>' .
					 esc_html( $p[1] ) . '</option>';
			}

			echo '</select>';
		}

		// Brand margin top field code
		public function settings_brand_margin_top_callback() {
			echo '<input type="number" id="brand_margin_top" name="safelayout_preloader_options[brand_margin_top]" ' .
				 'min="-500" max="500" step="1" value="' . esc_attr( $this->options['brand_margin_top'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['brand_margin_top'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// Brand margin bottom field code
		public function settings_brand_margin_bottom_callback() {
			echo '<input type="number" id="brand_margin_bottom" name="safelayout_preloader_options[brand_margin_bottom]" ' .
				 'min="-500" max="500" step="1" value="' . esc_attr( $this->options['brand_margin_bottom'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['brand_margin_bottom'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// enable preloader code
		public function settings_enable_preloader_callback() {
			echo '<input type="checkbox" name="safelayout_preloader_options[enable_preloader]" id="enable_preloader_0" value="enable" ' .
				 checked( esc_attr( $this->options['enable_preloader'] ), 'enable', false ) . ' /><label for="enable_preloader_0"><strong>' .
				 esc_html__( 'Enable Safelayout Cute Preloader', 'safelayout-cute-preloader' ) . '</strong></label>';
		}

		// Display on code
		public function settings_display_on_callback() {
			$temp0 = '<input type="radio" name="safelayout_preloader_options[display_on]" id="preloader_display_on_';
			$temp1 = '<label for="preloader_display_on_';
			
			$disp = $this->options['display_on'];
			echo $temp0 . '0" value="home" ' . checked( esc_attr( $disp ), 'home', false ) . ' />' .
				 $temp1 . '0">' . esc_html__( 'Home page', 'safelayout-cute-preloader' ) . '</label><br />' .

				 $temp0 . '1" value="full" ' . checked( esc_attr( $disp ), 'full', false ) . ' />' .
				 $temp1 . '1">' . esc_html__( 'Full website', 'safelayout-cute-preloader' ) . '</label><br />' .

				 $temp0 . '2" value="posts" ' . checked( esc_attr( $disp ), 'posts', false ) . ' />' .
				 $temp1 . '2">' . esc_html__( 'Posts only', 'safelayout-cute-preloader' ) . '</label><br />' .

				 $temp0 . '3" value="pages" ' . checked( esc_attr( $disp ), 'pages', false ) . ' />' .
				 $temp1 . '3">' . esc_html__( 'Pages only', 'safelayout-cute-preloader' ) . '</label><br />' .

				 $temp0 . '4" value="archive" ' . checked( esc_attr( $disp ), 'archive', false ) . ' />' .
				 $temp1 . '4">' . esc_html__( 'Archive only', 'safelayout-cute-preloader' ) . '</label><br />' .

				 $temp0 . '5" value="search" ' . checked( esc_attr( $disp ), 'search', false ) . ' />' .
				 $temp1 . '5">' . esc_html__( 'Search only', 'safelayout-cute-preloader' ) . '</label><br />' .

				 $temp0 . '6" value="custom-id" ' . checked( esc_attr( $disp ), 'custom-id', false ) . ' />' .
				 $temp1 . '6">' . esc_html__( 'Specific post ( Select from the list )', 'safelayout-cute-preloader' ) . '</label><br />' .
				 '<input type="text" id="specific_IDs" name="safelayout_preloader_options[specific_IDs]" ' .
				 'class="sl-pl-hidden" value="' . esc_attr( $this->options['specific_IDs'] ) .
				 '" /><select class="sl-pl-display-on-select" id="specific_IDs_select" size="6" multiple>';

			$pages = $this->get_page_list();
			foreach ( $pages as $page ) {
				$key = $this->check_list( esc_attr( $this->options['specific_IDs'] ), esc_html( $page[0] ) );
				echo '<option value="' . esc_html( $page[0] ) . ( $key ? '" selected>' : '">' ) . esc_html( $page[1] ) . '</option>';
			}
			echo '</select>';
			echo $temp0 . '7" value="custom-name" ' . checked( esc_attr( $disp ), 'custom-name', false ) . ' />' .
				 $temp1 . '7">' . esc_html__( 'Specific post types ( Select from the list )', 'safelayout-cute-preloader' ) . '</label><br />' .
				 '<input type="text" id="specific_names" name="safelayout_preloader_options[specific_names]" ' .
				 'class="sl-pl-hidden" value="' . esc_attr( $this->options['specific_names'] ) .
				 '" /><select class="sl-pl-display-on-select" id="specific_names_select" size="4" multiple>';

			$posts = get_post_types( array( 'public' => true ) );
			foreach ( $posts as $post ) {
				if ( $post != 'attachment' ) {
					$key = $this->check_list( esc_attr( $this->options['specific_names'] ), esc_html( $post ) );
					echo '<option value="' . esc_html( $post ) . ( $key ? '" selected>' : '">' ) . esc_html( $post ) . '</option>';
				}
			}
			echo '</select><br />';
		}

		// Return true if val is in list
		public function get_page_list() {
			$arr = [];
			$pages = get_pages( array( 'number' => 200 ) );
			if ( is_array( $pages ) ) {
				foreach ($pages as $page) {
					$arr[] = [$page->ID , __( '[Page]', 'safelayout-cute-preloader' ) . ' ' . $page->post_title ];
				}
			}

			$posts = get_posts( array( 'numberposts' => 100 ) );
			if ( is_array($posts) && !empty($posts) && gettype($posts[0]) === 'object') {
				foreach ($posts as $post) {
					$arr[] = [$post->ID , __( '[Post]', 'safelayout-cute-preloader' ) . ' ' . $post->post_title ];
				}
			}

			return $arr;
		}

		// Return true if val is in list
		public function check_list( $list, $val ) {
			if ( trim($list) === '' ) {
				return false;
			}
			$arr = explode( ',', $list );
			$arr = array_map('trim', $arr);
			return in_array( $val, $arr );
		}

		// Minimum time field code
		public function settings_minimum_time_callback() {
			$msg = __( 'The minimum time that, the preloader will be shown, even if the page was loaded. (Enter 0 to disable this feature)', 'safelayout-cute-preloader' );
			echo '<div class="sl-pl-advanced"><input type="number" id="minimum_time" ' .
				 'name="safelayout_preloader_options[minimum_time]" min="0" max="500" ' .
				 'step="1" value="' . esc_attr( $this->options['minimum_time'] ) .
				 '" /> ' . esc_html__( 'Second(s)', 'safelayout-cute-preloader' ) . '<br /><span class="description">' .
				 esc_html( $msg ) . '</span></div>';
		}

		// Maximum time field code
		public function settings_maximum_time_callback() {
			$msg = __( 'After this time the preloader will be hidden even if the page has not been loaded yet. (Enter 0 to disable this feature)', 'safelayout-cute-preloader' );
			echo '<div class="sl-pl-advanced"><input type="number" id="maximum_time" ' .
				 'name="safelayout_preloader_options[maximum_time]" min="0" max="500" ' .
				 'step="1" value="' . esc_attr( $this->options['maximum_time'] ) .
				 '" /> ' . esc_html__( 'Second(s)', 'safelayout-cute-preloader' ) . '<br /><span class="description">' .
				 esc_html( $msg ) . '</span></div>';
		}

		// Close button time field code
		public function settings_close_button_callback() {
			$msg = __( 'After this time the close button will be shown at the top right corner of the screen. (Enter 0 to disable this feature)', 'safelayout-cute-preloader' );
			echo '<div class="sl-pl-advanced"><input type="number" id="close_button" ' .
				 'name="safelayout_preloader_options[close_button]" min="0" max="500" ' .
				 'step="1" value="' . esc_attr( $this->options['close_button'] ) .
				 '" /> ' . esc_html__( 'Second(s)', 'safelayout-cute-preloader' ) . '<br /><span class="description">' .
				 esc_html( $msg ) . '</span></div>';
		}

		// Special preloader description code
		public function settings_special_description_callback() {
			$msg0 = __( 'https://safelayout.com/safelayout-cute-preloader-pro-documentation#how-to-add-different-preloaders', 'safelayout-cute-preloader' );
			$msg1 = __( 'How to add different preloaders to different page/post of your site.', 'safelayout-cute-preloader' );
			echo '<div style="text-align:center;"><a href="' . esc_html( $msg0 ) . '" target="_blank">' . esc_html( $msg1 ) . '</a></div>';
		}

		// Special preloader Show meta box code
		public function settings_special_meta_callback() {
			$msg0 = __( 'Displays a text box in the page/post editor, where you can paste the preloader shortcode.', 'safelayout-cute-preloader' );
			echo '<input type="checkbox" name="safelayout_preloader_options[special_meta]" id="special_meta_0" value="enable" ' .
				 checked( esc_attr( $this->options['special_meta'] ), 'enable', false ) . ' /><label for="special_meta_0"><strong>' .
				 esc_html__( 'Show preloader meta box', 'safelayout-cute-preloader' ) . '</strong></label><br />' .
				 '<span class="description">' . esc_html( $msg0 ) . '</span><br />';
		}

		// Special preloader generator code
		public function settings_special_callback() {
			echo '<a id="special_code_btn_gen" class="button sl-pl-special-button">' .
				 esc_html__( 'Generate preloader Shortcode', 'safelayout-cute-preloader' ) .
				 '</a><a id="special_code_btn_copy" class="button sl-pl-special-button">' .
				 esc_html__( 'Copy to Clipboard', 'safelayout-cute-preloader' ) .
				 '</a><br /><textarea id="special_code_txt" class="large-text" rows="10" readonly></textarea>';
		}

		// Special preloader description1 code
		public function settings_special_description1_callback() {
			$msg0 = __( 'https://safelayout.com/safelayout-cute-preloader-pro-documentation#how-to-set-preloaders-for-type', 'safelayout-cute-preloader' );
			$msg1 = __( 'How to set preloaders for different data types, for example a different preloader for products.', 'safelayout-cute-preloader' );
			echo '<div style="text-align:center;height:70px"><a href="' . esc_html( $msg0 ) . '" target="_blank">' . esc_html( $msg1 ) . '</a></div>';
		}

		// Progress bar shape code
		public function settings_bar_shape_callback() {
			$bars = array( 'No', 'simple-bar', 'border-bar', 'stripe-bar', 'border-stripe-bar', 'anim-stripe-bar', 'anim-border-stripe-bar', );
			$temp0 = array( 'cell-bar', 'border-cell-bar', 'glassy-bar', 'border-glassy-bar', 'bulgy-bar', 'border-bulgy-bar', );
			$bar_shape = in_array( $this->options['bar_shape'], $temp0 ) ? 'anim-border-stripe-bar' : $this->options['bar_shape'];
			$counter = 0;

			echo '<div class="sl-pl-progress-container">';
			foreach ( $bars as $bar ) {
				echo '<input class="sl-pl-progress-radio" type="radio" id="bar_shape_' . esc_html( $counter ) . '" ' .
					 'name="safelayout_preloader_options[bar_shape]" value="' . esc_html( $bar ) . '" ' .
					 checked( esc_attr( $bar_shape ), $bar, false ) . ' />' .
					 '<label class="sl-pl-progress-label" for="bar_shape_' . esc_html( $counter ) . '" title="' . esc_html( $bar ) . '">';
				if ( $bar === 'No' ) {
					echo '<div class="sl-pl-progress-div-text">' . esc_html__( 'No Progress bar', 'safelayout-cute-preloader' ) . '</div></label><br />';
				} else {
					echo '<div class="sl-pl-progress-div">';
					$delay = 'sl-pl-' . $bar . '-delay';

					$this->set_bar_shape( $bar, 'enable', '', '', $delay );
					echo '</div></label>';
				}
				$counter++;
			}
			echo '</div>';
		}

		// Progress bar preview code
		public function settings_bar_preview_callback() {
			echo '<div class="sl-pl-text-preview-container">' . esc_html__( 'Preview', 'safelayout-cute-preloader' ) .
				 '<span id="sl-pl-bar-preview-title"></span><br /><div id="sl-pl-bar-preview">' .
				 '<div class="sl-pl-icon-preview-background"></div>' .
				 '<div class="sl-pl-preview-bar-container"><div class="sl-pl-bar-bg"></div><div id="sl-pl-preview-progress">' .
				 '<div id="sl-pl-preview-progress-view1"><div id="sl-pl-preview-progress-view2"><div class="sl-pl-preview-bar">' .
				 '</div><div class="sl-pl-light-move-bar" id="sl-pl-light-move-selector"></div></div></div></div></div></div></div>' .
				 '<div style="text-align: center;"><a id="sl-pl-play-bar" class="button">' .
				 esc_html__( 'Play progress bar', 'safelayout-cute-preloader' ) . '</a></div>';
		}

		// Progress bar light move effect
		public function settings_bar_light_effect_callback() {
			echo '<input type="checkbox" name="safelayout_preloader_options[bar_light]" id="bar_light_0" value="enable" ' .
				 checked( esc_attr( $this->options['bar_light'] ), 'enable', false ) . ' /><label for="bar_light_0">' .
				 esc_html__( 'Enable Light move effect', 'safelayout-cute-preloader' ) . '</label>';
		}

		// Progress bar position code
		public function settings_bar_position_callback() {
			$pos = array(
				[ 'top', __( 'Top of the screen', 'safelayout-cute-preloader' ) ],
				[ 'bottom', __( 'Bottom of the screen', 'safelayout-cute-preloader' ) ],
				[ 'middle_brand', __( 'Middle ( top of the preloader brand image )', 'safelayout-cute-preloader' ) ],
				[ 'middle_icon', __( 'Middle ( top of the preloader icon )', 'safelayout-cute-preloader' ) ],
				[ 'middle_counter', __( 'Middle ( top of the preloader counter )', 'safelayout-cute-preloader' ) ],
				[ 'middle_text', __( 'Middle ( top of the preloader text )', 'safelayout-cute-preloader' ) ],
				[ 'middle_under_text', __( 'Middle ( under the preloader text )', 'safelayout-cute-preloader' ) ],
			);
			echo '<select name="safelayout_preloader_options[bar_position]" id="bar_position">';

			foreach ( $pos as $p ) {
				echo '<option value="' . esc_html( $p[0] ) . '" ' .
					 selected( esc_attr( $this->options['bar_position'] ), $p[0], false ) . '>' .
					 esc_html( $p[1] ) . '</option>';
			}

			echo '</select>';
		}

		// Progress bar width field code
		public function settings_bar_width_callback() {
			echo '<input type="number" id="bar_width" name="safelayout_preloader_options[bar_width]" ' .
				 'min="1" max="5000" step="1" value="' . esc_attr( $this->options['bar_width'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['bar_width'] ) . '" /> ' .
				 '<select name="safelayout_preloader_options[bar_width_unit]" id="bar_width_unit" style="vertical-align:initial;"><option value="%"' .
				 selected( esc_attr( $this->options['bar_width_unit'] ), '%', false ) . '>%</option><option value="px"' .
				 selected( esc_attr( $this->options['bar_width_unit'] ), 'px', false ) . '>' .
				 esc_html__( 'px', 'safelayout-cute-preloader' ) . '</option></select>';
		}

		// Progress bar height field code
		public function settings_bar_height_callback() {
			echo '<input type="number" id="bar_height" name="safelayout_preloader_options[bar_height]" ' .
				 'min="1" max="5000" step="1" value="' . esc_attr( $this->options['bar_height'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['bar_height'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// Progress bar border radius field code
		public function settings_bar_border_radius_callback() {
			echo '<input type="number" id="bar_border_radius" name="safelayout_preloader_options[bar_border_radius]" ' .
				 'min="0" max="500" step="1" value="' . esc_attr( $this->options['bar_border_radius'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['bar_border_radius'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// Progress bar margin top field code
		public function settings_bar_margin_top_callback() {
			echo '<input type="number" id="bar_margin_top" name="safelayout_preloader_options[bar_margin_top]" ' .
				 'min="-500" max="500" step="1" value="' . esc_attr( $this->options['bar_margin_top'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['bar_margin_top'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// Progress bar margin bottom field code
		public function settings_bar_margin_bottom_callback() {
			echo '<input type="number" id="bar_margin_bottom" name="safelayout_preloader_options[bar_margin_bottom]" ' .
				 'min="-500" max="500" step="1" value="' . esc_attr( $this->options['bar_margin_bottom'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['bar_margin_bottom'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// Progress bar margin left field code
		public function settings_bar_margin_left_callback() {
			echo '<input type="number" id="bar_margin_left" name="safelayout_preloader_options[bar_margin_left]" ' .
				 'min="-1500" max="1500" step="1" value="' . esc_attr( $this->options['bar_margin_left'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['bar_margin_left'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// Progress bar border color code
		public function settings_bar_border_color_callback() {
			echo '<input type="text" name="safelayout_preloader_options[bar_border_color]" class="sl-pl-bar-border-color" ' .
				 'data-default-color="' . esc_attr( $this->default_options['bar_border_color'] ) . '" value="' .
				 esc_attr( $this->options['bar_border_color'] ) . '" /><br />';
		}

		// Progress bar free color code
		public function settings_bar_free_color_callback() {
			echo '<div class="sl-pl-free-color-container">';
			$this->set_color_code( 'bar_gradient_value', 'bar_free_color_' );
			echo '</div>';
		}

		// show counter code
		public function settings_show_counter_callback() {
			echo '<input type="checkbox" name="safelayout_preloader_options[counter]" id="counter_0" value="enable" ' .
				 checked( esc_attr( $this->options['counter'] ), 'enable', false ) . ' /><label for="counter_0">' .
				 esc_html__( 'Show counter', 'safelayout-cute-preloader' ) . '</label>';
		}

		// counter text field code
		public function settings_counter_text_callback() {
			$msg = __( 'Text to display after preloader counter. ( % , %completed , ... )', 'safelayout-cute-preloader' );
			echo '<input type="text" name="safelayout_preloader_options[counter_text]" class="large-text" value="' .
				 esc_attr( $this->options['counter_text'] ) . '" /><span class="description">' . esc_html( $msg ) . '</span>';
		}

		// counter preview code
		public function settings_counter_preview_callback() {
			echo '<div class="sl-pl-text-preview-container" id="sl-pl-counter-preview">' .
				 '<div class="sl-pl-icon-preview-background"></div><div class="sl-pl-spin-container"><div id="sl-pl-counter"></div></div></div>';
		}

		// counter position code
		public function settings_counter_position_callback() {
			$pos = array(
				[ 'default', __( 'Default ( under the preloader icon )', 'safelayout-cute-preloader' ) ],
				[ 'center', __( 'Inside the progress bar (middle)', 'safelayout-cute-preloader' ) ],
				[ 'left', __( 'Inside the progress bar (left)', 'safelayout-cute-preloader' ) ],
				[ 'right', __( 'Inside the progress bar (right)', 'safelayout-cute-preloader' ) ],
			);
			echo '<select name="safelayout_preloader_options[counter_position]" id="counter_position">';

			foreach ( $pos as $p ) {
				echo '<option value="' . esc_html( $p[0] ) . '" ' .
					 selected( esc_attr( $this->options['counter_position'] ), $p[0], false ) . '>' .
					 esc_html( $p[1] ) . '</option>';
			}

			echo '</select>';
		}

		// counter text size field code
		public function settings_counter_size_callback() {
			echo '<input type="number" id="counter_size" name="safelayout_preloader_options[counter_size]" ' .
				 'min="5" max="300" step="1" value="' . esc_attr( $this->options['counter_size'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['counter_size'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// counter margin top field code
		public function settings_counter_margin_top_callback() {
			echo '<input type="number" id="counter_margin_top" name="safelayout_preloader_options[counter_margin_top]" ' .
				 'min="-500" max="500" step="1" value="' . esc_attr( $this->options['counter_margin_top'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['counter_margin_top'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// counter margin bottom field code
		public function settings_counter_margin_bottom_callback() {
			echo '<input type="number" id="counter_margin_bottom" name="safelayout_preloader_options[counter_margin_bottom]" ' .
				 'min="-500" max="500" step="1" value="' . esc_attr( $this->options['counter_margin_bottom'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['counter_margin_bottom'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// counter margin left field code
		public function settings_counter_margin_left_callback() {
			echo '<input type="number" id="counter_margin_left" name="safelayout_preloader_options[counter_margin_left]" ' .
				 'min="-1500" max="1500" step="1" value="' . esc_attr( $this->options['counter_margin_left'] ) . '" data-default-size="' .
				 esc_attr( $this->default_options['counter_margin_left'] ) . '" /> ' . esc_html__( 'px', 'safelayout-cute-preloader' );
		}

		// counter text color code
		public function settings_counter_color_callback() {
			echo '<input type="text" name="safelayout_preloader_options[counter_color]" class="sl-pl-counter-color" ' .
				 'data-default-color="' . esc_attr( $this->default_options['counter_color'] ) . '" value="' .
				 esc_attr( $this->options['counter_color'] ) . '" />';
		}

		// Sanitize options
		public function option_sanitize( $input ) {
			$default_options = safelayout_preloader_get_default_options();
			
			// Restore Defaults
			if ( isset( $_POST["delete"] ) ) {
				return $this->set_HTML_and_CSS_code( $default_options, '' );
			}

			$sanitary_values = array();
			$sanitary_values['version'] = SAFELAYOUT_PRELOADER_VERSION;
			$sanitary_values['ui_tabs_activate'] = sanitize_text_field( $input['ui_tabs_activate'] );

			if ( isset( $input['enable_preloader'] ) ) {
				$sanitary_values['enable_preloader'] = sanitize_text_field( $input['enable_preloader'] );
			} else {
				$sanitary_values['enable_preloader'] = '';
			}

			if ( isset( $input['display_on'] ) ) {
				$sanitary_values['display_on'] = sanitize_text_field( $input['display_on'] );
			}

			if ( isset( $input['specific_IDs'] ) ) {
				$sanitary_values['specific_IDs'] = sanitize_text_field( $input['specific_IDs'] );
			}

			if ( isset( $input['specific_names'] ) ) {
				$sanitary_values['specific_names'] = sanitize_text_field( $input['specific_names'] );
			}

			if ( isset( $input['close_button'] ) ) {
				$sanitary_values['close_button'] = $this->sanitize_numeric(
					$input['close_button'],
					0,
					500,
					$default_options['close_button']
				);
			}

			if ( isset( $input['minimum_time'] ) ) {
				$sanitary_values['minimum_time'] = $this->sanitize_numeric(
					$input['minimum_time'],
					0,
					500,
					$default_options['minimum_time']
				);
			}

			if ( isset( $input['maximum_time'] ) ) {
				$sanitary_values['maximum_time'] = $this->sanitize_numeric(
					$input['maximum_time'],
					0,
					500,
					$default_options['maximum_time']
				);
			}

			if ( isset( $input['background_anim'] ) ) {
				$sanitary_values['background_anim'] = sanitize_text_field( $input['background_anim'] );
			}

			if ( isset( $input['background_gradient_value'] ) ) {
				$sanitary_values['background_gradient_value'] = $this->sanitize_numeric(
					$input['background_gradient_value'],
					0,
					13,
					$default_options['background_gradient_value']
				);
			}

			if ( isset( $input['background_alpha'] ) ) {
				$sanitary_values['background_alpha'] = $this->sanitize_numeric(
					$input['background_alpha'],
					1,
					100,
					$default_options['background_alpha']
				);
			}

			if ( isset( $input['icon'] ) ) {
				$sanitary_values['icon'] = sanitize_text_field( $input['icon'] );
			}

			if ( isset( $input['custom_icon'] ) ) {
				if ( $input['custom_icon_width'] > 0 && $input['custom_icon_height'] > 0 ) {
					$sanitary_values['custom_icon'] = esc_url_raw( $input['custom_icon'], $this->add_data_protocol() );
					$sanitary_values['custom_icon_width'] = sanitize_text_field( $input['custom_icon_width'] );
					$sanitary_values['custom_icon_height'] = sanitize_text_field( $input['custom_icon_height'] );
				}
			}

			if ( isset( $input['custom_icon_alt'] ) ) {
				$sanitary_values['custom_icon_alt'] = sanitize_text_field( $input['custom_icon_alt'] );
			}

			if ( isset( $input['icon_size'] ) ) {
				$sanitary_values['icon_size'] = $this->sanitize_numeric(
					$input['icon_size'],
					10,
					1000,
					$default_options['icon_size']
				);
			}

			if ( isset( $input['icon_gradient_value'] ) ) {
				$sanitary_values['icon_gradient_value'] = $this->sanitize_numeric(
					$input['icon_gradient_value'],
					0,
					13,
					$default_options['icon_gradient_value']
				);
			}

			if ( isset( $input['icon_effect'] ) ) {
				$sanitary_values['icon_effect'] = sanitize_text_field( $input['icon_effect'] );
			}

			if ( isset( $input['text'] ) ) {
				$sanitary_values['text'] = sanitize_text_field( $input['text'] );
			}

			if ( isset( $input['text_anim'] ) ) {
				$sanitary_values['text_anim'] = sanitize_text_field( $input['text_anim'] );
			}

			if ( isset( $input['text_size'] ) ) {
				$sanitary_values['text_size'] = $this->sanitize_numeric(
					$input['text_size'],
					5,
					300,
					$default_options['text_size']
				);
			}

			if ( isset( $input['text_color'] ) ) {
				$sanitary_values['text_color'] = $this->sanitize_color(
					$input['text_color'],
					$default_options['text_color']
				);
			}

			if ( isset( $input['text_margin_top'] ) ) {
				$sanitary_values['text_margin_top'] = $this->sanitize_numeric(
					$input['text_margin_top'],
					-500,
					500,
					$default_options['text_margin_top']
				);
			}

			if ( isset( $input['brand_url'] ) ) {
				if ( $input['brand_width'] > 0 && $input['brand_height'] > 0 ) {
					$sanitary_values['brand_url'] = esc_url_raw( $input['brand_url'], $this->add_data_protocol() );
					$sanitary_values['brand_width'] = sanitize_text_field( $input['brand_width'] );
					$sanitary_values['brand_height'] = sanitize_text_field( $input['brand_height'] );
				}
			}

			if ( isset( $input['brand_url_alt'] ) ) {
				$sanitary_values['brand_url_alt'] = sanitize_text_field( $input['brand_url_alt'] );
			}

			if ( isset( $input['brand_anim'] ) ) {
				$sanitary_values['brand_anim'] = sanitize_text_field( $input['brand_anim'] );
			}

			if ( isset( $input['brand_position'] ) ) {
				$sanitary_values['brand_position'] = sanitize_text_field( $input['brand_position'] );
			}

			if ( isset( $input['brand_margin_top'] ) ) {
				$sanitary_values['brand_margin_top'] = $this->sanitize_numeric(
					$input['brand_margin_top'],
					-500,
					500,
					$default_options['brand_margin_top']
				);
			}

			if ( isset( $input['brand_margin_bottom'] ) ) {
				$sanitary_values['brand_margin_bottom'] = $this->sanitize_numeric(
					$input['brand_margin_bottom'],
					-500,
					500,
					$default_options['brand_margin_bottom']
				);
			}

			if ( isset( $input['bar_shape'] ) ) {
				$sanitary_values['bar_shape'] = sanitize_text_field( $input['bar_shape'] );
			}

			if ( isset( $input['bar_light'] ) ) {
				$sanitary_values['bar_light'] = sanitize_text_field( $input['bar_light'] );
			} else {
				$sanitary_values['bar_light'] = '';
			}

			if ( isset( $input['bar_position'] ) ) {
				$sanitary_values['bar_position'] = sanitize_text_field( $input['bar_position'] );
			}

			if ( isset( $input['bar_width'] ) ) {
				$sanitary_values['bar_width'] = $this->sanitize_numeric(
					$input['bar_width'],
					1,
					5000,
					$default_options['bar_width']
				);
			}

			if ( isset( $input['bar_width_unit'] ) ) {
				$sanitary_values['bar_width_unit'] = sanitize_text_field( $input['bar_width_unit'] );
			}

			if ( isset( $input['bar_height'] ) ) {
				$sanitary_values['bar_height'] = $this->sanitize_numeric(
					$input['bar_height'],
					1,
					5000,
					$default_options['bar_height']
				);
			}

			if ( isset( $input['bar_border_radius'] ) ) {
				$sanitary_values['bar_border_radius'] = $this->sanitize_numeric(
					$input['bar_border_radius'],
					0,
					500,
					$default_options['bar_border_radius']
				);
			}

			if ( isset( $input['bar_margin_top'] ) ) {
				$sanitary_values['bar_margin_top'] = $this->sanitize_numeric(
					$input['bar_margin_top'],
					-500,
					500,
					$default_options['bar_margin_top']
				);
			}

			if ( isset( $input['bar_margin_bottom'] ) ) {
				$sanitary_values['bar_margin_bottom'] = $this->sanitize_numeric(
					$input['bar_margin_bottom'],
					-500,
					500,
					$default_options['bar_margin_bottom']
				);
			}

			if ( isset( $input['bar_margin_left'] ) ) {
				$sanitary_values['bar_margin_left'] = $this->sanitize_numeric(
					$input['bar_margin_left'],
					-1500,
					1500,
					$default_options['bar_margin_left']
				);
			}

			if ( isset( $input['bar_border_color'] ) ) {
				$sanitary_values['bar_border_color'] = $this->sanitize_color(
					$input['bar_border_color'],
					$default_options['bar_border_color']
				);
			}

			if ( isset( $input['bar_gradient_value'] ) ) {
				$sanitary_values['bar_gradient_value'] = $this->sanitize_numeric(
					$input['bar_gradient_value'],
					0,
					13,
					$default_options['bar_gradient_value']
				);
			}

			if ( isset( $input['counter'] ) ) {
				$sanitary_values['counter'] = sanitize_text_field( $input['counter'] );
			} else {
				$sanitary_values['counter'] = '';
			}

			if ( isset( $input['counter_text'] ) ) {
				$sanitary_values['counter_text'] = sanitize_text_field( $input['counter_text'] );
			}

			if ( isset( $input['counter_position'] ) ) {
				$sanitary_values['counter_position'] = sanitize_text_field( $input['counter_position'] );
			}

			if ( isset( $input['counter_size'] ) ) {
				$sanitary_values['counter_size'] = $this->sanitize_numeric(
					$input['counter_size'],
					5,
					300,
					$default_options['counter_size']
				);
			}

			if ( isset( $input['counter_margin_top'] ) ) {
				$sanitary_values['counter_margin_top'] = $this->sanitize_numeric(
					$input['counter_margin_top'],
					-500,
					500,
					$default_options['counter_margin_top']
				);
			}

			if ( isset( $input['counter_margin_bottom'] ) ) {
				$sanitary_values['counter_margin_bottom'] = $this->sanitize_numeric(
					$input['counter_margin_bottom'],
					-500,
					500,
					$default_options['counter_margin_bottom']
				);
			}

			if ( isset( $input['counter_margin_left'] ) ) {
				$sanitary_values['counter_margin_left'] = $this->sanitize_numeric(
					$input['counter_margin_left'],
					-1500,
					1500,
					$default_options['counter_margin_left']
				);
			}

			if ( isset( $input['counter_color'] ) ) {
				$sanitary_values['counter_color'] = $this->sanitize_color(
					$input['counter_color'],
					$default_options['counter_color']
				);
			}

			if ( isset( $input['special_meta'] ) ) {
				$sanitary_values['special_meta'] = sanitize_text_field( $input['special_meta'] );
			} else {
				$sanitary_values['special_meta'] = '';
			}

			return $this->set_HTML_and_CSS_code( $sanitary_values, '' );
		}

		// Add html and CSS code to array
		public function set_HTML_and_CSS_code( $arr, $id ) {
			$options = wp_parse_args( $arr, safelayout_preloader_get_default_options() );

			$icons = array( 'water', 'ball', 'spinner1', 'dots', 'cube2', '3d-grid', 'gear1', 'turn2', 'octagon', 'octagon1', 'bubble2', );
			$arr['icon'] = $options['icon'] = in_array( $options['icon'], $icons ) ? 'bubble' : $options['icon'];
			$bars = array( 'cell-bar', 'border-cell-bar', 'glassy-bar', 'border-glassy-bar', 'bulgy-bar', 'border-bulgy-bar', );
			$arr['bar_shape'] = $options['bar_shape'] = in_array( $options['bar_shape'], $bars ) ? 'anim-border-stripe-bar' : $options['bar_shape'];

			ob_start();
			$this->get_preloader_code( $options );
			$code = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', ob_get_clean() );
			$id = 'safelayout_cute_preloader_escaped_code_' . $id . $options['id'];
			set_transient( $id, $code, DAY_IN_SECONDS ); 
			$arr['code_CSS_HTML'] = addslashes( $code );
			return $arr;
		}

		// Set main code(html)
		public function get_preloader_code( $options ) {
			$bpos = $options['bar_position'];
			$light = $options['bar_light'];
			$shape = $options['bar_shape'];
			$counter = $options['counter'];
			$cpos = $options['counter_position'];

			if ( $options['icon_effect'] ) {
				$this->get_effects( $options['icon_effect'] );
			}

			$this->set_preloader_css( $options );

			echo '<div id="sl-preloader">';
			if ( $shape != 'No' && ( $bpos === 'top' || $bpos === 'bottom' ) ) {
				$this->set_bar_shape( $shape, $light, $counter, $cpos );
			}
			$this->set_background( $options['background_anim'], 'sl-pl-bg' );
			echo '<div id="sl-pl-close-button"><svg class="sl-pl-close-icon" viewbox="0 0 50 50"><path d="M10 7A3 3 0 0 0 7.879 7.879 3 3 0 0 0 7.879 12.12L20.76 25 7.879 37.88A3 3 0 0 0 7.879 42.12 3 3 0 0 0 12.12 42.12L25 29.24 37.88 42.12A3 3 0 0 0 42.12 42.12 3 3 0 0 0 42.12 37.88L29.24 25 42.12 12.12A3 3 0 0 0 42.12 7.879 3 3 0 0 0 37.88 7.879L25 20.76 12.12 7.879A3 3 0 0 0 10 7Z"/></svg></div>' .
				 '<div class="sl-pl-spin-container">';

			if ( $shape != 'No' && $bpos === 'middle_brand' ) {
				$this->set_bar_shape( $shape, $light, $counter, $cpos, '', true );
			}
			$brand = trim( $options['brand_url'] );
			if ( $brand !== '' && $options['brand_position'] === 'top' ) {
				$this->set_brand( $options );
			}

			if ( $shape != 'No' && $bpos === 'middle_icon' ) {
				$this->set_bar_shape( $shape, $light, $counter, $cpos, '', true );
			}

			$grad = '';
			$temp0 = array( 'bubble', 'cycle', 'stream', 'tube', 'wheel', 'turn', 'turn1', 'triple-spinner', );
			if ( $options['icon_gradient_value'] > 4 && in_array( $options['icon'], $temp0 ) ) {
				$grad = $this->get_color( $options['icon_gradient_value'], true );
			}
			$this->set_icon( $options, $options['icon'], $grad );

			if ( $brand !== '' && $options['brand_position'] === 'middle' ) {
				$this->set_brand( $options );
			}
			if ( $shape != 'No' && $bpos === 'middle_counter' ) {
				$this->set_bar_shape( $shape, $light, $counter, $cpos, '', true );
			}
			if ( $counter === 'enable' && ( $cpos === 'default' || $shape === 'No' ) ) {
				echo '<div id="sl-pl-counter">0<span>' . esc_html( $options['counter_text'] ) . '</span></div>';
			}

			if ( $brand !== '' && $options['brand_position'] === 'middle_text' ) {
				$this->set_brand( $options );
			}
			if ( $shape != 'No' && $bpos === 'middle_text' ) {
				$this->set_bar_shape( $shape, $light, $counter, $cpos, '', true );
			}
			$this->set_text( $options['text'], $options['text_anim'] );

			if ( $brand !== '' && $options['brand_position'] === 'bottom' ) {
				$this->set_brand( $options );
			}
			if ( $shape != 'No' && $bpos === 'middle_under_text' ) {
				$this->set_bar_shape( $shape, $light, $counter, $cpos, '', true );
			}
			echo '</div></div>';
		}

		// echo effect
		public function get_effects( $id ) {
			switch ( $id ) {
				case 1:
					echo '<svg class="sl-pl-icon-effect"><filter x="-50%" y="-50%" width="200%" height="200%" id="sl-pl-svg-filter1" color-interpolation-filters="sRGB"><feColorMatrix type="matrix" values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 0 0 0 0 1 0 "/><feGaussianBlur stdDeviation="1"/><feMerge><feMergeNode/><feMergeNode/><feMergeNode in="SourceGraphic"/></feMerge></filter></svg>';
					break;
				case 2:
					echo '<svg class="sl-pl-icon-effect"><filter x="-50%" y="-50%" width="200%" height="200%" id="sl-pl-svg-filter2" color-interpolation-filters="sRGB"><feGaussianBlur stdDeviation="1"/></filter></svg>';
					break;
				case 3:
					echo '<svg class="sl-pl-icon-effect"><filter x="-50%" y="-50%" width="200%" height="200%" id="sl-pl-svg-filter3" color-interpolation-filters="sRGB"><feFlood flood-color="rgb(0,0,0)" flood-opacity="1"/><feComposite in2="SourceGraphic" operator="out"/><feComponentTransfer><feFuncA type="table" tableValues="0 0 0 0 0 1"/></feComponentTransfer><feOffset dx="1" dy="1"/><feGaussianBlur stdDeviation="1 1"/><feMerge><feMergeNode/><feMergeNode/></feMerge><feComposite in2="SourceGraphic" operator="in"/><feMerge><feMergeNode in="SourceGraphic"/><feMergeNode/></feMerge></filter></svg>';
					break;
			}
		}

		// echo text
		public function set_text( $text, $anim ) {
			$text = trim( $text );
			$counter = 0;
			if ( $text !== '' ) {
				if ( $anim === 'No' ) {
					echo '<div class="sl-pl-text">' . esc_html( $text );
				} else {
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-text">';
					for ( $i = 0 , $len = mb_strlen( $text ) ; $i < $len ; $i++ ) {
						$chr = mb_substr( $text, $i, 1 );
						if ( ctype_space( $chr ) ) {
							echo esc_html( $chr );
						} else {
							echo '<span style="animation-delay:' . esc_html( $counter ) .
								 's;-webkit-animation-delay:' . esc_html( $counter ) . 's;">' .
								 esc_html( $chr ) . '</span>';
							$counter += 0.06;
						}
					}
				}
				echo '</div>';
			}
		}

		// echo brand
		public function set_brand( $options ) {
			$alt = trim( $options['brand_url_alt'] );
			$alt = $alt != '' ? $alt : 'Brand Image';
			$width = $options['brand_width'];
			$height = $options['brand_height'];
			switch ( $options['brand_anim'] ) {
				case 'No':
					echo '<div class="sl-pl-brand-container"><img style="aspect-ratio: ' . esc_html( $width ) . ' / ' . esc_html( $height ) .
						 '" width="' . esc_html( $width ) . '" height="' . esc_html( $height ) .
						 '" data-no-lazy="1" class="skip-lazy sl-pl-brand" alt="' . esc_html( $alt ) .
						 '" src="' . esc_url( $options['brand_url'], $this->add_data_protocol() ) . '" /></div>';
					break;
				case 'bounce':
				case 'yoyo':
				case 'swing':
				case 'rotate-2D':
				case 'rotate-3D-X':
				case 'rotate-3D-Y':
				case 'flash':
					echo '<div class="sl-pl-brand-container"><img style="aspect-ratio: ' . esc_html( $width ) . ' / ' . esc_html( $height ) .
						 '" width="' . esc_html( $width ) . '" height="' . esc_html( $height ) .
						 '"  data-no-lazy="1" class="skip-lazy sl-pl-brand" alt="' . esc_html( $alt ) .
						 '" src="' . esc_url( $options['brand_url'], $this->add_data_protocol() ) .
						 '" id="sl-pl-brand-' . esc_html( $options['brand_anim'] ) . '" /></div>';
					break;
				case 'wrest-X':
					$this->set_brand_split( $width, $height, 0, 1, 4 );
					break;
				case 'wrest-Y':
					$this->set_brand_split( $width, $height, 1, 0, 4 );
					break;
				case 'roll':
				case 'pipe':
				case 'swirl':
				case 'sheet':
					$this->set_brand_split( $width, $height, 1, 0, -6 );
					break;
			}
		}

		// echo brand split
		public function set_brand_split( $width, $height, $wKey, $hKey, $delay ) {
			$len = $hKey ? $width : $height;
			echo '<div class="sl-pl-brand-container"><div id="sl-pl-brand-parent" class="sl-pl-brand" style="aspect-ratio:' .
				 esc_html( $width ) . ' / ' . esc_html( $height ) . ';max-width:' . esc_html( $width ) . 'px">';
			for ( $i = 0; $i < $len -1 ; $i++ ) {
				echo '<div class="sl-pl-brand-part" style="position:absolute;background-position:' . esc_html( -$i * $hKey ) . 'px ' .
					 esc_html( -$i * $wKey ) . 'px;top:' . esc_html( $i * $wKey ) . 'px;left:' . esc_html( $i * $hKey ) . 'px;animation-delay:' .
					 esc_html( $i * $delay ) . 'ms;-webkit-animation-delay:' . esc_html( $i * $delay ) . 'ms;"></div>';
			}
			echo '</div></div>';
		}

		// echo icon
		public function set_icon( $options, $anim, $grad ) {
			$exc = array(
				'lineargradient'	=> [ 'id' => 1, 'x1' => 1, 'y1' => 1, 'x2' => 1, 'y2' => 1, ],
				'stop' 				=> [ 'stop-color' => 1, 'offset' => 1 ],
			);
			if ( $grad ) {
				$grad = '<linearGradient id="sl-pl-' . $anim . '-svg-grad01" ' . $grad . '</linearGradient>';
			}
			switch ( $anim ) {
				case 'crawl':
				case '3d-plate':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<span></span></div>';
					break;
				case 'spinner':
				case 'bubble1':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<span></span><span></span></div>';
					break;
				case 'balloons':
				case 'jump':
				case 'infinite':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<span></span><span></span><span></span></div>';
					break;
				case 'window':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><span></span></div><div><span></span></div><div><span></span></div><div><span></span></div></div>';
					break;
				case 'moons':
				case 'blade-vertical':
				case 'blade-vertical1':
				case 'circle':
				case 'flight':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<span></span><span></span><span></span><span></span><span></span></div>';
					break;
				case 'blade-horizontal':
				case 'blade-horizontal1':
				case '3d-bar':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><span></span></div><div><span></span></div><div><span></span></div><div><span></span></div><div><span></span></div></div>';
					break;
				case '3d-square':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><span></span><span></span></div><div><span></span><span></span></div><div><span></span><span></span></div>' .
						 '<div><span></span><span></span></div></div>';
					break;
				case 'gear':
				case 'gear1':
				case 'trail':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>';
					break;
				case 'octagon':
				case 'spinner1':
				case 'ball':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div></div>';
					break;
				case 'octagon1':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><div><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div></div></div>';
					break;
				case 'grid':
				case '3d-grid':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div></div>';
					break;
				case 'fold':
				case 'dots':
				case 'queue':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><span></span><span></span><span></span><span></span></div></div>';
					break;
				case 'planet':
				case 'dive':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><span></span><span></span></div></div>';
					break;
				case 'cube':
				case 'cube2':
				case 'cube1':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><span></span><span></span><span></span><span></span><span></span><span></span></div></div>';
					break;
				case 'bubble2':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<span></span><span></span><div><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div></div>';
					break;
				case 'leap':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<span></span><span></span><span></span><span></span></div>';
					break;
				case 'water':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<div><span></span></div></div>';
					break;
				case 'cycle':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin"><svg viewBox="0 0 39 39">';
					if ( $grad ){
						echo '<defs id="sl-pl-mark">' . wp_kses( $grad, $exc ) .
							 '</defs><path class="sl-pl-svg-color sl-pl-svg-effect" stroke="url(#sl-pl-cycle-svg-grad01)"';
					} else {
						echo '<path class="sl-pl-svg-color sl-pl-svg-effect"';
					}
					echo ' d="M19.5 36.45 C10.1 36.45 2.5 28.85 2.5 19.5 C2.5 10.1 10.1 2.5 19.5 2.5 C28.9 2.45 36.45 10.1 36.45 19.45 C36.45 28.85 28.9 36.4 19.5 36.4"/></svg></div>';
					break;
				case 'bubble':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin"><svg viewBox="0 0 56 56">';
					if ( $grad ){
						echo '<defs id="sl-pl-mark">' . wp_kses( $grad, $exc ) .
							 '</defs><circle class="sl-pl-svg-color sl-pl-svg-effect" stroke="url(#sl-pl-bubble-svg-grad01)" ' .
							 'cx="28" cy="28" r="19"/><circle class="sl-pl-svg-color sl-pl-svg-effect" stroke="url(#sl-pl-bubble-svg-grad01)"';
					} else {
						echo '<circle class="sl-pl-svg-color sl-pl-svg-effect" cx="28" cy="28" r="19"/><circle class="sl-pl-svg-color sl-pl-svg-effect"';
					}
					echo ' cx="28" cy="28" r="19"/></svg></div>';
					break;
				case 'stream':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<svg class="sl-pl-svg-effect" viewBox="0 0 39 39"><defs><linearGradient id="sl-pl-stream-grad01" x1="50%" y1="0%" x2="50%" y2="100%"><stop stop-color="#fff" offset="0" stop-opacity="1"/><stop stop-color="#fff" offset="1" stop-opacity="0.4"/></linearGradient><linearGradient id="sl-pl-stream-grad02" x1="50%" y1="0%" x2="50%" y2="100%"><stop stop-color="#fff" offset="0" stop-opacity="0.1"/><stop stop-color="#fff" offset="1" stop-opacity="0.4"/></linearGradient><mask id="sl-pl-stream-mask0"><rect x="0" y="0" width="19.5" height="39" fill="url(#sl-pl-stream-grad01)"></rect></mask><mask id="sl-pl-stream-mask1"><rect x="19.5" y="0" width="19.5" height="39" fill="url(#sl-pl-stream-grad02)"></rect></mask></defs>';
					if ( $grad ){
						echo '<defs id="sl-pl-mark">' . wp_kses( $grad, $exc ) .
							 '</defs><circle class="sl-pl-svg-color" mask="url(#sl-pl-stream-mask0)" ' .
							 'stroke="url(#sl-pl-stream-svg-grad01)" cx="19.5" cy="19.5" r="16.5"/><circle stroke="url(#sl-pl-stream-svg-grad01)"';
					} else {
						echo '<circle class="sl-pl-svg-color" mask="url(#sl-pl-stream-mask0)" cx="19.5" cy="19.5" r="16.5"/><circle';
					}
					echo ' class="sl-pl-svg-color" mask="url(#sl-pl-stream-mask1)" cx="19.5" cy="19.5" r="16.5"/></svg></div>';
					break;
				case 'tube':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin">' .
						 '<svg viewBox="0 0 66 66"><defs><filter x="-50%" y="-50%" width="200%" height="200%" id="sl-pl-tube-filter"><feGaussianBlur stdDeviation="2"/></filter><linearGradient id="sl-pl-tube-grad" x1="50%" y1="0%" x2="50%" y2="100%"><stop stop-color="#fff" offset="0" stop-opacity="1"/><stop stop-color="#9f9f9f" offset=".7" stop-opacity=".1"/><stop stop-color="#303030" offset="1" stop-opacity="0"/></linearGradient><mask id="sl-pl-tube-mask0"><rect x="10" y="11" width="27" height="45" fill="url(#sl-pl-tube-grad)"></rect></mask></defs>';
					if ( $grad ){
						echo '<defs id="sl-pl-mark">' . wp_kses( $grad, $exc ) .
							 '</defs><circle cx="33" cy="33" r="23"/><circle cx="33" cy="33" r="14"/>' .
							 '<g class="sl-pl-svg-effect" filter="url(#sl-pl-tube-filter)"><path fill="url(#sl-pl-tube-svg-grad01)"';
					} else {
						echo '<circle cx="33" cy="33" r="23"/><circle cx="33" cy="33" r="14"/><g class="sl-pl-svg-effect" filter="url(#sl-pl-tube-filter)"><path';
					}
					echo ' mask="url(#sl-pl-tube-mask0)" class="sl-pl-svg-color" d="M33.1 54.65 C21.15 54.65 11.5 45.1 11.5 33.2 C11.5 21.3 21.15 11.5 33.1 11.5 C35 11.5 36.1 12.85 36.1 14.65 C36.1 16.6 34.25 17.7 32.35 17.7 C24.15 17.7 17.6 24.8 17.6 32.95 C17.6 41.1 24.15 48.3 32.35 48.3 C34.15 48.3 36.05 49.4 36.05 51.2 C36.05 53.1 34.9 54.65 33.05 54.65 Z"/></g></svg></div>';
					break;
				case 'triple-spinner':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin"><svg viewBox="0 0 50 50" class="sl-pl-svg-effect">';
					if ( $grad ){
						echo '<defs id="sl-pl-mark">' . wp_kses( $grad, $exc ) .
							 '</defs><symbol viewBox="0 0 16 16" id="sl-pl-t-s-symbol"><path fill="url(#sl-pl-triple-spinner-svg-grad01)"';
					} else {
						echo '<symbol viewBox="0 0 16 16" id="sl-pl-t-s-symbol"><path';
					}
					echo ' id="sl-pl-t-s-path" class="sl-pl-svg-color" stroke-width="0" d="M1.151 7.002C1.647 4.857 3.277 2.998 5.374 2.298 6.218 2.003 7.125 1.919 8.01 2.001 8.61 2.005 9.08 1.405 8.98.829 8.9.268 8.29-.119 7.742.025 5.411.264 3.217 1.625 2.009 3.64 1.395 4.654 1.028 5.817.969 7.001 1.008 7.077 1.11 7.075 1.151 7.002Z"/></symbol><use xlink:href="#sl-pl-t-s-symbol"/><use xlink:href="#sl-pl-t-s-symbol" id="sl-pl-t-s1"/><use xlink:href="#sl-pl-t-s-symbol" id="sl-pl-t-s2"/></svg></div>';
					break;
				case 'turn':
				case 'turn1':
				case 'turn2':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin"><svg viewBox="0 0 60 60">';
					if ( $grad ){
						echo '<defs id="sl-pl-mark">' . wp_kses( $grad, $exc ) .
							 '</defs><g class="sl-pl-svg-effect"><g><circle cx="30" cy="30" r="18"/><path class="sl-pl-svg-color" stroke="url(#sl-pl-' .
							 esc_html( $anim ) . '-svg-grad01)" d="M 12,30 A18,18 0 0 1 30,12"/><path id="sl-pl-' . esc_html( $anim ) .
							 '-path01" class="sl-pl-svg-color" stroke="url(#sl-pl-' . esc_html( $anim ) . '-svg-grad01)"';
					} else {
						echo '<g class="sl-pl-svg-effect"><g><circle cx="30" cy="30" r="18"/><path class="sl-pl-svg-color" d="M 12,30 A18,18 0 0 1 30,12"/><path id="sl-pl-' .
							 esc_html( $anim ) . '-path01" class="sl-pl-svg-color"';
					}
					echo ' d="M 12,30 A18,18 0 0 1 30,12"/></g></g></svg></div>';
					break;
				case 'wheel':
					echo '<div id="sl-pl-' . esc_html( $anim ) . '" class="sl-pl-spin"><div><svg viewBox="0 0 60 60">';
					if ( $grad ){
						echo '<defs id="sl-pl-mark">' . wp_kses( $grad, $exc ) .
							 '</defs><g class="sl-pl-svg-effect"><path stroke="#f00" d="M 12,30 A18,18 0 0 1 30,12"/><path class="sl-pl-svg-color" ' .
							 'stroke="url(#sl-pl-wheel-svg-grad01)" d="M 30,12 A18,18 0 0 1 48,30"/><path stroke="#00f" d="M 48,30 A18,18 0 0 1 30,48"/>' .
							 '<path stroke="url(#sl-pl-wheel-svg-grad01)"';
					} else {
						echo '<g class="sl-pl-svg-effect"><path stroke="#f00" d="M 12,30 A18,18 0 0 1 30,12"/><path class="sl-pl-svg-color" d="M 30,12 A18,18 0 0 1 48,30"/><path stroke="#00f" d="M 48,30 A18,18 0 0 1 30,48"/><path';
					}
					echo ' class="sl-pl-svg-color" d="M 30,48 A18,18 0 0 1 12,30"/></g></svg></div></div>';
					break;
				case 'Custom':
					$url = trim( $options['custom_icon'] );
					$alt = trim( $options['custom_icon_alt'] );
					$alt = $alt != '' ? $alt : 'Custom Preloader Icon';
					$width = $options['custom_icon_width'];
					$height = $options['custom_icon_height'];
					if ( $url != '' && ( $width <= 0 || $height <= 0 ) ) {
						list( $width, $height ) = getimagesize( $url );
					}
					echo '<div><img style="aspect-ratio: ' . esc_html( $width ) . ' / ' . esc_html( $height ) .
						 '" width="' . esc_html( $width ) . '" height="' . esc_html( $height ) .
						 '" data-no-lazy="1" class="skip-lazy sl-pl-svg-effect sl-pl-custom" alt="' . esc_html( $alt ) . '" src="' .
						 esc_url( $url, $this->add_data_protocol() ) . '"/></div>';
					break;
			}
		}

		// Return gradients array
		public function get_color( $id, $key ) {
			$colors = array( '#fff', '#ffc0cb', '#ffff60', '#0f0', '#f00', '#4285f4', '#101010',
				'linear-gradient(90deg, #c5d06c, #c5d06c 50%, #d2dd72 53%, #d2dd72)',
				'linear-gradient(45deg, #ff0, #008000 50%, #ff0)',
				'linear-gradient(0deg, #ab82bc, #fdea72)',
				'linear-gradient(0deg, #800000, #f00)',
				'linear-gradient(90deg, #8abcfd, #67a5f5 44%, #5197ec 54%, #4087dc)',
				'linear-gradient(90deg, #ff8c59, #ffb37f 24%, #a3bf5f 49%, #7ca63a 75%, #527f32)',
				'linear-gradient(45deg, #000, #803100 49%, #800000 50%, #000)',
				'x1="0" y1="0" x2="1" y2="0"><stop stop-color="#c5d06c" offset="0"/><stop stop-color="#c5d06c" offset="0.5"/><stop stop-color="#d2dd72" offset="0.53"/><stop stop-color="#d2dd72" offset="1"/>',
				'x1="0" y1="0.7" x2="0.7" y2="0"><stop stop-color="#ff0" offset="0"/><stop stop-color="#008000" offset="0.5"/><stop stop-color="#ff0" offset="1"/>',
				'x1="0" y1="1" x2="0" y2="0"><stop stop-color="#ab82bc" offset="0"/><stop stop-color="#fdea72" offset="1"/>',
				'x1="0" y1="1" x2="0" y2="0"><stop stop-color="#800000" offset="0"/><stop stop-color="#f00" offset="1"/>',
				'x1="0" y1="0" x2="1" y2="0"><stop stop-color="#8abcfd" offset="0"/><stop stop-color="#67a5f5" offset="0.44"/><stop stop-color="#5197ec" offset="0.54"/><stop stop-color="#4087dc" offset="1"/>',
				'x1="0" y1="0" x2="1" y2="0"><stop stop-color="#ff8c59" offset="0"/><stop stop-color="#ffb37f" offset="0.24"/><stop stop-color="#a3bf5f" offset="0.49"/><stop stop-color="#7ca63a" offset="0.75"/><stop stop-color="#527f32" offset="1"/>',
				'x1="0" y1="0.7" x2="0.7" y2="0"><stop stop-color="#000" offset="0"/><stop stop-color="#803100" offset="0.49"/><stop stop-color="#800000" offset="0.5"/><stop stop-color="#000" offset="1"/>', );
			return ( $key ) ? $colors[ $id + 7 ] : $colors[ $id ];
		}

		// Return allowed protocols with data
		public function add_data_protocol() {
			$protocols = wp_allowed_protocols();
			$protocols[] = 'data';
			return $protocols;
		}

		// echo background
		public function set_background( $anim, $class ) {
			switch ( $anim ) {
				case 'fade':
				case 'to-left':
				case 'to-right':
				case 'to-top':
				case 'to-bottom':
				case 'ellipse-bottom':
				case 'ellipse-top':
				case 'ellipse-left':
				case 'ellipse-right':
				case 'rect':
				case 'diamond':
				case 'circle':
					echo '<div class="'. esc_html( $class ) . ' sl-pl-bg-' . esc_html( $anim ) . '"></div>';
					break;
				case 'tear-vertical':
				case 'split-horizontal':
					echo '<div class="'. esc_html( $class ) . ' sl-pl-bg-' . esc_html( $anim ) . '-left"></div>' .
						 '<div class="'. esc_html( $class ) . ' sl-pl-bg-' . esc_html( $anim ) . '-right"></div>';
					break;
				case 'tear-horizontal':
				case 'split-vertical':
					echo '<div class="'. esc_html( $class ) . ' sl-pl-bg-' . esc_html( $anim ) . '-top"></div>' .
						 '<div class="'. esc_html( $class ) . ' sl-pl-bg-' . esc_html( $anim ) . '-bottom"></div>';
					break;
				case 'linear-left':
				case 'linear-right':
					echo '<div class="'. esc_html( $class ) . ' sl-pl-bg-' . esc_html( $anim ) .
						 '"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';
					break;
			}
		}

		// echo Progress bar shape
		public function set_bar_shape( $shape, $light, $counter, $pos, $delay = '', $container = false ) {
			if ( $container ) {
				echo '<div id="sl-pl-bar-middle-container">';
			}
			echo '<div class="sl-pl-bar-container" id="sl-pl-' . esc_html( $shape ) . '-container"><div class="sl-pl-bar-bg"></div>' .
				 '<div id="sl-pl-progress"><div id="sl-pl-progress-view1"><div id="sl-pl-progress-view2"><div class="sl-pl-bar" id="sl-pl-' . esc_html( $shape ) . '"></div>';
			if ( $light === 'enable' ) {
				echo '<div class="sl-pl-light-move-bar"' . ( $delay != '' ? ( ' id="' . esc_html( $delay ) . '"' ) : '' ) . '></div>';
			}
			echo '</div></div></div>';
			if ( $counter === 'enable' && $pos != 'default' ) {
				echo '<div class="sl-pl-bar-counter-container"><div id="sl-pl-counter"></div></div>';
			}
			echo '</div>';
			if ( $container ) {
				echo '</div>';
			}
		}

		// Return preloader css
		public function set_preloader_css( $options ) {
			echo '<noscript><style>#sl-preloader{display: none !important;}</style></noscript>' .
				 '<style id="safelayout-cute-preloader-css">';

				require_once SAFELAYOUT_PRELOADER_PATH . 'inc/safelayout-preloader-set-style.php';
				safelayout_preloader_set_style( $options );

			// set background css ( opacity, background )
			if ( $options['background_anim'] != 'No' ) {
				$temp1 = $this->get_color( $options['background_gradient_value'], false );
				if ( $options['background_anim'] === 'linear-left' || $options['background_anim'] === 'linear-right' ) {
					echo '.sl-pl-bg{background: rgba(0,0,0,0) !important;}' .
						 '.sl-pl-bg-' . esc_html( $options['background_anim'] ) . ' div{opacity: ' .
						 ( esc_html( $options['background_alpha'] ) / 100 ) . ';background: ' . esc_html( $temp1 ) . ' !important;}';
				} else {
					echo '.sl-pl-bg{opacity: ' . ( esc_html( $options['background_alpha'] ) / 100 ) . ';' .
						 'background: ' . esc_html( $temp1 ) . ' !important;}';
				}
			}

			// set brand image css
			if ( trim( $options['brand_url'] ) !== '' ) {
				echo '.sl-pl-brand-container{margin-top: ' . esc_html( $options['brand_margin_top'] ) . 'px;' .
					 'margin-bottom: ' . esc_html( $options['brand_margin_bottom'] ) .'px;}';

				$temp0 = array( 'wrest-X', 'wrest-Y', 'roll', 'pipe', 'swirl', 'sheet', );
				if ( in_array( $options['brand_anim'], $temp0 ) ) {
					$key = $options['brand_anim'] === 'wrest-X';
					echo '.sl-pl-brand-part{background-repeat: no-repeat;background-size: cover;' .
						 "background-image: url('" . esc_url( $options['brand_url'], $this->add_data_protocol() ) . "');" .
						 'width: ' . ( $key ? '2px;' : '100%;' ) . 'height: ' . ( ! $key ? '2px;}' : '100%}' );
				}
			}

			// set progress bar css
			$shape = $options['bar_shape'];
			if ( $shape != 'No' ) {
				$gap = 0;
				$temp0 = '';
				$width = $options['bar_width'] . $options['bar_width_unit'];
				if( strpos( $shape, 'border' ) !== false ) {
					$width = 'calc(' . $width . ' - 6px)';
					$gap = 6;
				}
				$margin = 'margin-top: ' . $options['bar_margin_top'] . 'px !important;margin-bottom: ' . $options['bar_margin_bottom'] . 'px !important;';

				if ( $options['bar_position'] === 'top' ) {
					$temp0 = 'top: ' . ( - $options['bar_margin_bottom'] ) . 'px;';
				} else if ( $options['bar_position'] === 'bottom' ) {
					$temp0 = 'top: calc(100% - ' . ( $gap + $options['bar_height'] + $options['bar_margin_bottom'] ) . 'px);';
				} else {
					echo '#sl-pl-bar-middle-container{height: ' . esc_html( $options['bar_height'] + $gap ) . 'px;' . esc_html( $margin ) . '}';
					$margin = '';
				}
				$margin .= 'margin-left: ' . $options['bar_margin_left'] . 'px !important;';
				$temp1 = $this->get_color( $options['bar_gradient_value'], false );
				echo '.sl-pl-bar-container{width: ' . esc_html( $width ) . ';height: ' . esc_html( $options['bar_height'] ) . 'px;' .
					 esc_html( $temp0 ) . 'border-radius: ' . esc_html( $options['bar_border_radius'] ) . 'px;border-color: ' .
					 esc_html( $options['bar_border_color'] ) . ' !important;' . esc_html( $margin ) . '}.sl-pl-bar-bg{' . 
					 'background: ' . esc_html( $temp1 ) . ';}';

				$width = str_replace( '%', 'vw', $width );
				echo '.sl-pl-bar{width: ' . esc_html( $width ) . ';background: ' . esc_html( $temp1 ) . ';}';
			}

			// set counter css
			if ( $options['counter'] === 'enable' ) {
				echo '#sl-pl-counter{font-size: ' . esc_html( $options['counter_size'] ) . 'px !important;' .
					 'color: ' . esc_html( $options['counter_color'] ) . ' !important;' .
					 'margin-top: ' . esc_html( $options['counter_margin_top'] ) . 'px;' .
					 'margin-bottom: ' . esc_html( $options['counter_margin_bottom'] ) . 'px;' .
					 'margin-left: ' . esc_html( $options['counter_margin_left'] ) . 'px;}';

				if ( $options['counter_position'] != 'default' && $shape != 'No' ) {
					echo '#sl-pl-counter{text-align: ' . $options['counter_position'] . ';' .
						 'top: 50%;transform: translateY(-50%);-webkit-transform: translateY(-50%);}';
					}
			}

			// set text css ( size, color, ... )
			if ( trim( $options['text'] ) !== '' ) {
				echo '.sl-pl-text{font-size: ' . esc_html( $options['text_size'] ) . 'px !important;color: ' .
					 esc_html( $options['text_color'] ) . ' !important;margin-top: ' . esc_html( $options['text_margin_top'] ) . 
					 'px;}.sl-pl-text span{font-size: ' . esc_html( $options['text_size'] ) . 'px !important;color: ' .
					 esc_html( $options['text_color'] ) . ' !important;}';
			}

			// set icon css ( size, color, ... )
			if ( $options['icon'] != 'No' ) {
				$size = $options['icon_size'];
				$ico0 = 'width: ' . $size . 'px !important;height: ' . $size . 'px !important;';
				
				// set icon size
				$temp0 = array( 'cycle', 'wheel', '3d-bar', 'blade-horizontal', 'blade-horizontal1',
					'cube', 'cube1', 'leap', 'grid', '3d-square', 'flight', 'dive', );
				if ( in_array( $options['icon'], $temp0 ) ) {
					$ico0 = 'width: 50px !important;height: 50px !important;';
					$scale = $size / 50;
					$ico0 .= 'transform: scale(' . $scale . ');-webkit-transform: scale(' . $scale . ');';
					$ico0 .= 'margin: ' . ( ( $size - 50 ) / 2) . 'px 0;';
				}
				$ico0 = '.sl-pl-spin{' . $ico0 . '}';

				// set icon effect
				$effect = '';
				if ( $options['icon_effect'] ) {
					$temp_id = 'url(#sl-pl-svg-filter' . $options['icon_effect'] . ')';
					$effect = "filter: " . $temp_id . ' !important;-webkit-filter: ' . $temp_id . ' !important;';
				}

				// set icon color
				$temp1 = $this->get_color( $options['icon_gradient_value'], false );
				switch ( $options['icon'] ) {
					case 'cycle':
					case 'bubble':
					case 'stream':
					case 'tube':
					case 'turn':
					case 'turn1':
					case 'wheel':
					case 'triple-spinner':
						if ( $options['icon_gradient_value'] < 7 ) {
							$temp0 = array( 'tube', 'triple-spinner', );
							echo '.sl-pl-svg-color{' . ( in_array( $options['icon'], $temp0 ) ? "fill: " : "stroke: " ) .
							esc_html( $temp1 ) . ' !important;}';
						}
						if ( $effect != '' ) {
							echo '.sl-pl-svg-effect{' . esc_html( $effect ) . '}';
						}
						break;
					case 'Custom':
						$ico0 = '';
						if ( $effect != '' ) {
							echo '.sl-pl-svg-effect{' . esc_html( $effect ) . '}';
						}
					default:
						$temp0 = array( 'crawl', 'gear', 'blade-horizontal', 'blade-horizontal1', );
						if ( $effect != '' && in_array( $options['icon'], $temp0 ) ) {
							$ico0 .= '.sl-pl-spin{' . $effect . '}';
							$effect = '';
						}
						echo '.sl-pl-spin span{background: ' . esc_html( $temp1 ) . ';' . esc_html( $effect ) . '}';
						break;
				}
				echo esc_html( $ico0 );
			}

			echo '</style>';
		}

		// Sanitize color value
		public function sanitize_color( $color, $default ) {
			$color = sanitize_hex_color( $color );
			if ( '' == $color ) {
				$color = $default;
			}
			return $color;
		}

		// Sanitize numeric value
		public function sanitize_numeric( $number, $min, $max, $default ) {
			$number = sanitize_text_field( $number );
			if ( is_numeric( $number ) && $number >= $min && $number <= $max ) {
				$default = $number;
			}
			return $default;
		}

		// Return gradients
		public function get_gradients( $id ) {
			$gradients = array(
				'x1="0" y1="0" x2="0" y2="1"><stop stop-color="#fbe225" offset="0"/><stop stop-color="#efbc22" offset="0.5"/><stop stop-color="#efcd62" offset="0.51"/><stop stop-color="#e2cda0" offset="1"/>',
				'x1="0" y1="0" x2="0" y2="1"><stop stop-color="#ffcba4" offset="0"/><stop stop-color="#ffcba4" offset="0.04"/><stop stop-color="#ff1493" offset="0.32"/><stop stop-color="#f93" offset="0.61"/><stop stop-color="#00bfff" offset="0.9"/><stop stop-color="#00bfff" offset="1"/>',
				'x1="0" y1="0" x2="0" y2="1"><stop stop-color="#4e84ed" offset="0"/><stop stop-color="#88c0ff" offset="0.1"/><stop stop-color="#6ba2f6" offset="0.16"/><stop stop-color="#4f85ed" offset="0.2"/><stop stop-color="#88c0ff" offset="0.3"/><stop stop-color="#4f85ed" offset="0.4"/><stop stop-color="#6ba2f6" offset="0.46"/><stop stop-color="#88c0ff" offset="0.5"/><stop stop-color="#6ba2f6" offset="0.54"/><stop stop-color="#4f85ed" offset="0.6"/><stop stop-color="#6ba2f6" offset="0.64"/><stop stop-color="#88c0ff" offset="0.7"/><stop stop-color="#5086ed" offset="0.79"/><stop stop-color="#87bfff" offset="0.9"/><stop stop-color="#4e84ed" offset="1"/>',
				'x1="0" y1="0" x2="0" y2="1"><stop stop-color="#ed3908" offset="0"/><stop stop-color="#ff6e02" offset="0.19"/><stop stop-color="#ffb601" offset="0.31"/><stop stop-color="#ff0" offset="0.5"/><stop stop-color="#ffb600" offset="0.61"/><stop stop-color="#ff6d00" offset="0.81"/><stop stop-color="#f65304" offset="0.92"/><stop stop-color="#ed3908" offset="1"/>',
				'x1="0" y1="0.7" x2="0.7" y2="0"><stop stop-color="#f00" offset="0"/><stop stop-color="#ff0" offset="0.5"/><stop stop-color="#f00" offset="1"/>',
				'x1="0" y1="0" x2="0" y2="1"><stop stop-color="#78ebff" offset="0"/><stop stop-color="#b8f0c0" offset="0.25"/><stop stop-color="#f5f582" offset="0.5"/><stop stop-color="#fab3ba" offset="0.75"/><stop stop-color="#ff7aee" offset="1"/>',
				'x1="0" y1="0" x2="0" y2="1"><stop stop-color="#fcc200" offset="0"/><stop stop-color="#844e00" offset="0.25"/><stop stop-color="#bf8700" offset="0.27"/><stop stop-color="#fbc100" offset="0.28"/><stop stop-color="#835200" offset="0.48"/><stop stop-color="#bb8701" offset="0.61"/><stop stop-color="#f4bc02" offset="0.68"/><stop stop-color="#845300" offset="0.73"/><stop stop-color="#fac100" offset="0.99"/><stop stop-color="#fac100" offset="1"/>',
				'x1="1" y1="0.4" x2="0" y2="0.6"><stop stop-color="#000" offset="0"/><stop stop-color="#f00000" offset="0.5"/><stop stop-color="#ffcc1a" offset="1"/>',
			);
			return $gradients[ $id ];
		}
	}
	new Safelayout_Preloader_Admin();
}