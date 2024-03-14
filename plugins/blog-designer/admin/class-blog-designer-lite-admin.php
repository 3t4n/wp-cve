<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.solwininfotech.com
 * @since      1.8.2
 *
 * @package    Blog_Designer
 * @subpackage Blog_Designer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blog_Designer
 * @subpackage Blog_Designer/admin
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class Blog_Designer_Lite_Admin {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.8.2
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'bd_enqueue_color_picker' ), 9 );
		add_action( 'admin_enqueue_scripts', array( $this, 'bd_admin_stylesheet' ), 7 );
		add_action( 'admin_menu', array( $this, 'bd_add_menu' ) );
		add_action( 'admin_init', array( $this, 'bd_redirection' ), 1 );
		add_action( 'admin_init', array( $this, 'bd_reg_function' ), 5 );
		add_action( 'admin_init', array( $this, 'bd_session_start' ), 1 );
		add_action( 'admin_head', array( $this, 'bd_subscribe_mail' ), 10 );
		add_action( 'admin_init', array( $this, 'bd_save_settings' ), 10 );
		add_action( 'admin_init', array( $this, 'bd_admin_scripts' ) );
		add_action( 'wp_ajax_nopriv_bd_get_page_link', array( $this, 'bd_get_page_link' ) );
		add_action( 'wp_ajax_bd_get_page_link', array( $this, 'bd_get_page_link' ) );
		add_action( 'wp_ajax_bd_closed_bdboxes', array( $this, 'bd_closed_bdboxes' ) );
		add_action( 'wp_ajax_bd_template_search_result', array( $this, 'bd_template_search_result' ) );
		add_action( 'wp_ajax_bd_create_sample_layout', array( $this, 'bd_create_sample_layout' ) );
		add_action( 'current_screen', array( $this, 'bd_footer' ) );
		add_action( 'init', array( $this, 'bd_fsn_block' ), 12 );
		add_shortcode( 'fsn_blog_designer', array( $this, 'bd_fsn_shortcode' ) );
		add_action( 'admin_init', array( $this, 'bd_unintall_plugins' ) );
		add_action( 'wp_ajax_bd_submit_optin', array( $this, 'bd_submit_optin' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'bd_plugin_links' ) );
	}
	/**
	 * Add menu at admin panel
	 *
	 * @return void
	 */
	public function bd_add_menu() {
		$bd_is_optin = get_option( 'bd_is_optin' );
		if ( 'yes' === $bd_is_optin || 'no' === $bd_is_optin ) {
			add_menu_page( esc_html__( 'Blog Designer', 'blog-designer' ), esc_html__( 'Blog Designer', 'blog-designer' ), 'administrator', 'designer_settings', array( 'Blog_Designer_Lite_Settings', 'bd_main_menu_function' ), BLOGDESIGNER_URL . 'admin/images/blog-designer.png' );

		} else {
			add_menu_page( esc_html__( 'Blog Designer', 'blog-designer' ), esc_html__( 'Blog Designer', 'blog-designer' ), 'administrator', 'designer_welcome_page', array( 'Blog_Designer_Lite_Settings', 'bd_welcome_function' ), BLOGDESIGNER_URL . 'admin/images/blog-designer.png' );
		}
		add_submenu_page( 'designer_settings', esc_html__( 'Blog designer Settings', 'blog-designer' ), esc_html__( 'Blog Designer Settings', 'blog-designer' ), 'manage_options', 'designer_settings', array( $this, 'bd_add_menu' ) );
		add_submenu_page( 'designer_settings', esc_html__( 'Getting Started', 'blog-designer' ), esc_html__( 'Getting Started', 'blog-designer' ), 'manage_options', 'bd_getting_started', 'bd_getting_started' );
	}
	/**
	 * Redirection on welcome page
	 *
	 * @return void
	 */
	public function bd_redirection() {
		if ( is_user_logged_in() ) {
			if ( get_option( 'bd_plugin_do_activation_redirect', false ) ) {
				delete_option( 'bd_plugin_do_activation_redirect' );
				if ( ! isset( $_GET['activate-multi'] ) ) {
					$bd_is_optin = get_option( 'bd_is_optin' );
					if ( 'yes' === $bd_is_optin || 'no' === $bd_is_optin ) {
						wp_safe_redirect( admin_url( 'admin.php?page=bd_getting_started' ) );
						exit();
					} else {
						wp_safe_redirect( admin_url( 'admin.php?page=designer_welcome_page' ) );
						exit();
					}
				}
			}
		}
	}
	/**
	 * Set default value
	 *
	 * @return void
	 */
	public function bd_reg_function() {
		if ( is_user_logged_in() ) {
			$settings = get_option( 'wp_blog_designer_settings' );
			if ( empty( $settings ) ) {
				$settings = array(
					'template_category'          => '',
					'template_tags'              => '',
					'template_authors'           => '',
					'template_name'              => 'classical',
					'template_bgcolor'           => '#ffffff',
					'template_color'             => '#ffffff',
					'template_ftcolor'           => '#2a97ea',
					'template_fthovercolor'      => '#999999',
					'template_titlecolor'        => '#222222',
					'template_titlebackcolor'    => '#ffffff',
					'template_contentcolor'      => '#999999',
					'template_readmorecolor'     => '#cecece',
					'template_readmorebackcolor' => '#2e93ea',
					'template_alterbgcolor'      => '#ffffff',
				);
				update_option( 'posts_per_page', '5' );
				update_option( 'display_sticky', '1' );
				update_option( 'display_category', '0' );
				update_option( 'social_icon_style', '0' );
				update_option( 'rss_use_excerpt', '1' );
				update_option( 'template_alternativebackground', '1' );
				update_option( 'display_tag', '0' );
				update_option( 'display_author', '0' );
				update_option( 'display_date', '0' );
				update_option( 'social_share', '1' );
				update_option( 'facebook_link', '0' );
				update_option( 'twitter_link', '0' );
				update_option( 'linkedin_link', '0' );
				update_option( 'pinterest_link', '0' );
				update_option( 'display_comment_count', '0' );
				update_option( 'excerpt_length', '75' );
				update_option( 'display_html_tags', '0' );
				update_option( 'read_more_on', '2' );
				update_option( 'read_more_text', 'Read More' );
				update_option( 'template_titlefontsize', '35' );
				update_option( 'content_fontsize', '14' );
				update_option( 'wp_blog_designer_settings', $settings );
			}
		}
	}
	/**
	 * Start session if not
	 *
	 * @return void
	 */
	public function bd_session_start() {
		if ( -1 != version_compare( phpversion(), '7.0.0' ) ) {
			if ( session_status() == PHP_SESSION_DISABLED ) {
				session_start( array( 'read_and_close' => true ) );
			}
		} elseif ( -1 != version_compare( phpversion(), '5.4.0' ) ) {
			if ( session_status() == PHP_SESSION_DISABLED ) {
				session_start();
			}
		} else {
			if ( '' == session_id() ) {
				session_start();
			}
		}
	}
	/**
	 * Subscribe email form
	 *
	 * @return void
	 */
	public function bd_subscribe_mail() {
		?>
		<div id="sol_deactivation_widget_cover_bd" style="display:none;">
			<div class="sol_deactivation_widget">
				<h3><?php esc_html_e( 'If you have a moment, please let us know why you are deactivating. We would like to help you in fixing the issue.', 'blog-designer' ); ?></h3>
				<form id="frmDeactivationbd" name="frmDeactivation" method="post" action="">
					<ul class="sol_deactivation_reasons_ul">
						<?php $i = 1; ?>
						<li>
							<input class="sol_deactivation_reasons" checked="checked" name="sol_deactivation_reasons_bd" type="radio" value="<?php echo intval( $i ); ?>" id="bd_reason_<?php echo intval( $i ); ?>">
							<label for="bd_reason_<?php echo intval( $i ); ?>"><?php esc_html_e( 'I am going to upgrade to PRO version', 'blog-designer' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_bd" type="radio" value="<?php echo intval( $i ); ?>" id="bd_reason_<?php echo intval( $i ); ?>">
							<label for="bd_reason_<?php echo intval( $i ); ?>"><?php esc_html_e( 'The plugin suddenly stopped working', 'blog-designer' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_bd" type="radio" value="<?php echo intval( $i ); ?>" id="bd_reason_<?php echo intval( $i ); ?>">
							<label for="bd_reason_<?php echo intval( $i ); ?>"><?php esc_html_e( 'The plugin was not working', 'blog-designer' ); ?></label>
						</li>
						<li class="sol_deactivation_reasons_solution">
							<b>Please check your <a target="_blank" href="<?php echo esc_url( admin_url( 'options-reading.php' ) ); ?>">reading settings</a>. Read our <a href="https://www.solwininfotech.com/knowledgebase/#" target="_blank">knowdgebase</a> for more detail.</b>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_bd" type="radio" value="<?php echo intval( $i ); ?>" id="bd_reason_<?php echo intval( $i ); ?>">
							<label for="bd_reason_<?php echo intval( $i ); ?>"><?php esc_html_e( 'I have configured plugin but not working with my blog page', 'blog-designer' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_bd" type="radio" value="<?php echo intval( $i ); ?>" id="bd_reason_<?php echo intval( $i ); ?>">
							<label for="bd_reason_<?php echo intval( $i ); ?>"><?php esc_html_e( 'Installed & configured well but disturbed my blog page design', 'blog-designer' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_bd" type="radio" value="<?php echo intval( $i ); ?>" id="bd_reason_<?php echo intval( $i ); ?>">
							<label for="bd_reason_<?php echo intval( $i ); ?>"><?php esc_html_e( "My theme's blog page is better than plugin's blog page design", 'blog-designer' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_bd" type="radio" value="<?php echo intval( $i ); ?>" id="bd_reason_<?php echo intval( $i ); ?>">
							<label for="bd_reason_<?php echo intval( $i ); ?>"><?php esc_html_e( 'The plugin broke my site completely', 'blog-designer' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_bd" type="radio" value="<?php echo intval( $i ); ?>" id="bd_reason_<?php echo intval( $i ); ?>">
							<label for="bd_reason_<?php echo intval( $i ); ?>"><?php esc_html_e( 'No any reason', 'blog-designer' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_bd" type="radio" value="<?php echo intval( $i ); ?>" id="bd_reason_<?php echo intval( $i ); ?>">
							<label for="bd_reason_<?php echo intval( $i ); ?>"><?php esc_html_e( 'Other', 'blog-designer' ); ?></label><br/>
							<input style="display:none;width: 90%" value="" type="text" name="sol_deactivation_reason_other_bd" class="sol_deactivation_reason_other_bd" />
						</li>
					</ul>
					<p>
						<input type='checkbox' class='bd_agree' id='bd_agree_gdpr_deactivate' value='1' />
						<label for='bd_agree_gdpr_deactivate' class='bd_agree_gdpr_lbl'><?php echo esc_attr__( 'By clicking this button, you agree with the storage and handling of your data as mentioned above by this website. (GDPR Compliance)', 'blog-designer' ); ?></label>
					</p>
					<a onclick='bd_submit_optin("deactivate")' class="button button-secondary">
						<?php
						esc_html_e( 'Submit', 'blog-designer' );
						echo ' &amp; ';
						esc_html_e( 'Deactivate', 'blog-designer' );
						?>
					</a>
					<input type="submit" name="sbtDeactivationFormClose" id="sbtDeactivationFormClosebd" class="button button-primary" value="<?php esc_html_e( 'Cancel', 'blog-designer' ); ?>" />
					<a href="javascript:void(0)" class="bd-deactivation" aria-label="<?php esc_html_e( 'Deactivate Blog Designer', 'blog-designer' ); ?>">
						<?php
						esc_html_e( 'Skip', 'blog-designer' );
						echo ' &amp; ';
						esc_html_e( 'Deactivate', 'blog-designer' );
						?>
					</a>
				</form>
				<div class="support-ticket-section">
					<h3><?php esc_html_e( 'Would you like to give us a chance to help you?', 'blog-designer' ); ?></h3>
					<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/support-ticket.png'; ?>">
					<a href="<?php echo esc_url( 'http://support.solwininfotech.com/' ); ?>"><?php esc_html_e( 'Create a support ticket', 'blog-designer' ); ?></a>
				</div>
			</div>
		</div>
		<a style="display:none" href="#TB_inline?height=800&inlineId=sol_deactivation_widget_cover_bd" class="thickbox" id="deactivation_thickbox_bd"></a>
		<?php
	}
	/**
	 * Save plugin options
	 *
	 * @return void
	 */
	public function bd_save_settings() {
		if ( is_user_logged_in() && isset( $_POST['blog_ticker_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['blog_ticker_nonce'] ) ), 'blog_ticker_nonce_ac' ) ) {
			if ( isset( $_REQUEST['action'] ) && 'save' === $_REQUEST['action'] && isset( $_REQUEST['updated'] ) && 'true' === $_REQUEST['updated'] ) {
				$settings = $_POST;
				if ( isset( $settings ) && ! empty( $settings ) ) {
					foreach ( $settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} else {
							$settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$settings = is_array( $settings ) ? $settings : maybe_unserialize( $settings );
				$updated  = update_option( 'wp_blog_news_ticker', $settings );
			}
		}
		if ( is_user_logged_in() && isset( $_POST['blog_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['blog_nonce'] ) ), 'blog_nonce_ac' ) ) {
			if ( isset( $_REQUEST['action'] ) && 'save' === $_REQUEST['action'] && isset( $_REQUEST['updated'] ) && 'true' === $_REQUEST['updated'] ) {
				$blog_page_display = '';
				if ( isset( $_POST['blog_page_display'] ) ) {
					$blog_page_display = intval( $_POST['blog_page_display'] );
					update_option( 'blog_page_display', $blog_page_display );
				}
				if ( isset( $_POST['posts_per_page'] ) ) {
					$posts_per_page = intval( $_POST['posts_per_page'] );
					update_option( 'posts_per_page', $posts_per_page );
				}
				if ( isset( $_POST['rss_use_excerpt'] ) ) {
					$rss_use_excerpt = intval( $_POST['rss_use_excerpt'] );
					update_option( 'rss_use_excerpt', $rss_use_excerpt );
				}
				if ( isset( $_POST['display_date'] ) ) {
					$display_date = intval( $_POST['display_date'] );
					update_option( 'display_date', $display_date );
				}
				if ( isset( $_POST['display_author'] ) ) {
					$display_author = intval( $_POST['display_author'] );
					update_option( 'display_author', $display_author );
				}
				if ( isset( $_POST['display_sticky'] ) ) {
					$display_sticky = intval( $_POST['display_sticky'] );
					update_option( 'display_sticky', $display_sticky );
				}
				if ( isset( $_POST['display_category'] ) ) {
					$display_category = intval( $_POST['display_category'] );
					update_option( 'display_category', $display_category );
				}
				if ( isset( $_POST['display_tag'] ) ) {
					$display_tag = intval( $_POST['display_tag'] );
					update_option( 'display_tag', $display_tag );
				}
				if ( isset( $_POST['txtExcerptlength'] ) ) {
					$txt_excerpt_length = intval( $_POST['txtExcerptlength'] );
					update_option( 'excerpt_length', $txt_excerpt_length );
				}
				if ( isset( $_POST['display_html_tags'] ) ) {
					$display_html_tags = intval( $_POST['display_html_tags'] );
					update_option( 'display_html_tags', $display_html_tags );
				} else {
					update_option( 'display_html_tags', 0 );
				}
				if ( isset( $_POST['readmore_on'] ) ) {
					$readmore_on = intval( $_POST['readmore_on'] );
					update_option( 'read_more_on', $readmore_on );
				}
				if ( isset( $_POST['txtReadmoretext'] ) ) {
					$txt_readmore_text = sanitize_text_field( wp_unslash( $_POST['txtReadmoretext'] ) );
					update_option( 'read_more_text', $txt_readmore_text );
				}
				if ( isset( $_POST['template_alternativebackground'] ) ) {
					$template_alternativebackground = sanitize_text_field( wp_unslash( $_POST['template_alternativebackground'] ) );
					update_option( 'template_alternativebackground', $template_alternativebackground );
				}
				if ( isset( $_POST['social_icon_style'] ) ) {
					$social_icon_style = intval( $_POST['social_icon_style'] );
					update_option( 'social_icon_style', $social_icon_style );
				}
				if ( isset( $_POST['social_share'] ) ) {
					$social_share = intval( $_POST['social_share'] );
					update_option( 'social_share', $social_share );
				}
				if ( isset( $_POST['facebook_link'] ) ) {
					$facebook_link = intval( $_POST['facebook_link'] );
					update_option( 'facebook_link', $facebook_link );
				}
				if ( isset( $_POST['twitter_link'] ) ) {
					$twitter_link = intval( $_POST['twitter_link'] );
					update_option( 'twitter_link', $twitter_link );
				}
				if ( isset( $_POST['pinterest_link'] ) ) {
					$pinterest_link = intval( $_POST['pinterest_link'] );
					update_option( 'pinterest_link', $pinterest_link );
				}
				if ( isset( $_POST['linkedin_link'] ) ) {
					$linkedin_link = intval( $_POST['linkedin_link'] );
					update_option( 'linkedin_link', $linkedin_link );
				}
				if ( isset( $_POST['display_comment_count'] ) ) {
					$display_comment_count = intval( $_POST['display_comment_count'] );
					update_option( 'display_comment_count', $display_comment_count );
				}
				if ( isset( $_POST['template_titlefontsize'] ) ) {
					$template_titlefontsize = intval( $_POST['template_titlefontsize'] );
					update_option( 'template_titlefontsize', $template_titlefontsize );
				}
				if ( isset( $_POST['content_fontsize'] ) ) {
					$content_fontsize = intval( $_POST['content_fontsize'] );
					update_option( 'content_fontsize', $content_fontsize );
				}
				if ( isset( $_POST['custom_css'] ) ) {
					update_option( 'custom_css', wp_strip_all_tags( wp_unslash( $_POST['custom_css'] ) ) );
				}
				$templates                 = array();
				$templates['ID']           = $blog_page_display;
				$templates['post_content'] = '[wp_blog_designer]';
				wp_update_post( $templates );
				$settings = $_POST;
				if ( isset( $settings ) && ! empty( $settings ) ) {
					foreach ( $settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} else {
							$settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$settings = is_array( $settings ) ? $settings : maybe_unserialize( $settings );
				$updated  = update_option( 'wp_blog_designer_settings', $settings );
			}
		}
	}
	/**
	 * Enqueue admin side plugin js
	 *
	 * @return void
	 */
	public function bd_admin_scripts() {
		if ( is_user_logged_in() ) {
			wp_enqueue_script( 'jquery' );
		}
	}
	/**
	 * Enqueue colorpicket and chosen
	 *
	 * @param string $hook_suffix value.
	 * @return void
	 */
	public function bd_enqueue_color_picker( $hook_suffix ) {
		// first check that $hook_suffix is appropriate for your admin page.
		if ( isset( $_GET['page'] ) && ( 'designer_settings' === $_GET['page'] || 'bd_getting_started' === $_GET['page'] || 'designer_welcome_page' === $_GET['page'] ) || 'plugins.php' === $hook_suffix ) {
			global $wp_version;
			wp_enqueue_style( array( 'wp-color-picker', 'wp-jquery-ui-dialog' ) );
			if ( function_exists( 'wp_enqueue_code_editor' ) ) {
				wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
			}
			wp_enqueue_script( 'bd-script-handle', plugins_url( 'js/admin_script.js', __FILE__ ), array( 'wp-color-picker', 'jquery-ui-core', 'jquery-ui-dialog' ), '1.0', true );
			wp_localize_script(
				'bd-script-handle',
				'bdlite_js',
				array(
					'wp_version'             => $wp_version,
					'nothing_found'          => esc_html__( 'Oops, nothing found!', 'blog-designer' ),
					'reset_data'             => esc_html__( 'Do you want to reset data?', 'blog-designer' ),
					'choose_blog_template'   => esc_html__( 'Choose the blog template you love', 'blog-designer' ),
					'close'                  => esc_html__( 'Close', 'blog-designer' ),
					'set_blog_template'      => esc_html__( 'Set Blog Template', 'blog-designer' ),
					'default_style_template' => esc_html__( 'Apply default style of this selected template', 'blog-designer' ),
					'no_template_exist'      => esc_html__( 'No template exist for selection', 'blog-designer' ),
					'nonce'                  => wp_create_nonce( 'ajax-nonce' ),
				)
			);
			wp_enqueue_script( 'chosen-handle', plugins_url( 'js/chosen.jquery.js', __FILE__ ), null, '1.8.2', false );
		}
	}
	/**
	 * Enqueue admin panel required css
	 *
	 * @return void
	 */
	public function bd_admin_stylesheet() {
		$screen          = get_current_screen();
		$plugin_data     = get_plugin_data( BLOGDESIGNER_DIR . 'blog-designer.php', $markup = true, $translate = true );
		$current_version = $plugin_data['Version'];
		$old_version     = get_option( 'bd_version' );
		if ( $old_version != $current_version ) {
			update_option( 'is_user_subscribed_cancled', '' );
			update_option( 'bd_version', $current_version );
		}
		if ( ( 'yes' !== get_option( 'is_user_subscribed' ) && 'yes' !== get_option( 'is_user_subscribed_cancled' ) ) || ( 'plugins' === $screen->base ) ) {
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );
		}
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_register_style( 'wp-blog-designer-admin-support-stylesheets', plugins_url( 'css/blog-designer-editor-support.css', __FILE__ ), null, '1.0' );
		wp_enqueue_style( 'wp-blog-designer-admin-support-stylesheets' );
		if ( ( isset( $_GET['page'] ) && ( 'designer_settings' === $_GET['page'] || 'bd_getting_started' === $_GET['page'] || 'designer_welcome_page' === $_GET['page'] ) ) || 'dashboard' === $screen->id || 'plugins' === $screen->id ) {
			$adminstylesheet_url    = plugins_url( 'css/admin.css', __FILE__ );
			$adminrtlstylesheet_url = plugins_url( 'css/admin-rtl.css', __FILE__ );
			$adminstylesheet        = BLOGDESIGNER_DIR . 'admin/css/admin.css';
			if ( file_exists( $adminstylesheet ) ) {
				wp_register_style( 'wp-blog-designer-admin-stylesheets', $adminstylesheet_url, null, '1.0' );
				wp_enqueue_style( 'wp-blog-designer-admin-stylesheets' );
			}
			if ( is_rtl() ) {
				wp_register_style( 'wp-blog-designer-admin-rtl-stylesheets', $adminrtlstylesheet_url, null, '1.0' );
				wp_enqueue_style( 'wp-blog-designer-admin-rtl-stylesheets' );
			}
			$adminstylechosen_url = plugins_url( 'css/chosen.min.css', __FILE__ );
			$adminstylechosen     = BLOGDESIGNER_DIR . 'admin/css/chosen.min.css';
			if ( file_exists( $adminstylechosen ) ) {
				wp_register_style( 'wp-blog-designer-chosen-stylesheets', $adminstylechosen_url, null, '1.0' );
				wp_enqueue_style( 'wp-blog-designer-chosen-stylesheets' );
			}
			if ( isset( $_GET['page'] ) && ( 'designer_settings' === $_GET['page'] ) ) {
				$adminstylearisto_url = plugins_url( 'css/aristo.css', __FILE__ );
				$adminstylearisto     = BLOGDESIGNER_DIR . 'admin/css/aristo.css';
				if ( file_exists( $adminstylearisto ) ) {
					wp_register_style( 'wp-blog-designer-aristo-stylesheets', $adminstylearisto_url, null, '1.0' );
					wp_enqueue_style( 'wp-blog-designer-aristo-stylesheets' );
				}
			}
			$fontawesomeicon_url = plugins_url( 'css/fontawesome-all.min.css', __FILE__ );
			$fontawesomeicon     = BLOGDESIGNER_DIR . 'admin/css/fontawesome-all.min.css';
			if ( file_exists( $fontawesomeicon ) ) {
				wp_register_style( 'wp-blog-designer-fontawesome-stylesheets', $fontawesomeicon_url, null, '1.0' );
				wp_enqueue_style( 'wp-blog-designer-fontawesome-stylesheets' );
			}
		}
	}
	/**
	 * Ajax handler for page link
	 *
	 * @return void
	 */
	public function bd_get_page_link() {
		if ( isset( $_POST['page_id'] ) && isset( $_POST['blog_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['blog_nonce'] ) ), 'blog_nonce_ac' ) ) {
			$page_id = intval( $_POST['page_id'] );
			echo '<a target="_blank" href="' . esc_url( get_permalink( $page_id ) ) . '">' . esc_html__( 'View Blog', 'blog-designer' ) . '</a>';
		}
		exit();
	}
	/**
	 * Ajax handler for Store closed box id
	 *
	 * @return void
	 */
	public function bd_closed_bdboxes() {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$closed = isset( $_POST['closed'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_POST['closed'] ) ) ) : array();
			$page   = isset( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : '';
			$user   = wp_get_current_user();
			if ( sanitize_key( $page ) != $page ) {
				wp_die( 0 );
			}
			if ( ! $user ) {
				wp_die( -1 );
			}
			if ( is_array( $closed ) ) {
				update_user_option( $user->ID, "bdpclosedbdpboxes_$page", $closed, true );
			}
			wp_die( 1 );
		}
	}
	/**
	 * Template search
	 *
	 * @return void
	 */
	public function bd_template_search_result() {
		if ( isset( $_POST['temlate_name'] ) && ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) ) {
			$template_name = sanitize_text_field( wp_unslash( $_POST['temlate_name'] ) );
		}
		$tempate_list = Blog_Designer_Lite_Template::bd_template_list();
		foreach ( $tempate_list as $key => $value ) {
			if ( '' == $template_name ) {
				if ( 'boxy-clean' === $key || 'crayon_slider' === $key || 'classical' === $key || 'lightbreeze' === $key || 'spektrum' === $key || 'evolution' === $key || 'timeline' === $key || 'news' === $key || 'glossary' === $key || 'nicy' === $key || 'sallet_slider' === $key || 'media-grid' === $key || 'blog-carousel' === $key || 'blog-grid-box' === $key || 'ticker' === $key ) {
					$class = 'bd-lite';
				} else {
					$class = 'bp-pro';
				}
				?>
				<div class="bd-template-thumbnail <?php echo esc_attr( $value['class'] . ' ' . $class ); ?>">
					<div class="bd-template-thumbnail-inner">
						<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/layouts/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
						<?php if ( 'bd-lite' === $class ) { ?>
							<div class="bd-hover_overlay">
								<div class="bd-popup-template-name">
									<div class="bd-popum-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer' ); ?></a></div>
									<div class="bd-popup-view"><a href="<?php echo esc_url( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer' ); ?></a></div>
								</div>
							</div>
						<?php } else { ?>
							<div class="bd_overlay"></div>
							<div class="bd-img-hover_overlay">
								<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/pro-tag.png'; ?>" alt="Available in Pro" />
							</div>
							<div class="bd-hover_overlay">
								<div class="bd-popup-template-name">
									<div class="bd-popup-view"><a href="<?php echo esc_url( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer' ); ?></a></div>
								</div>
							</div>
						<?php } ?>
					</div>
					<span class="bd-span-template-name"><?php echo esc_attr( $value['template_name'] ); ?></span>
				</div>
				<?php
			} elseif ( preg_match( '/' . trim( $template_name ) . '/', $key ) ) {
				if ( 'boxy-clean' === $key || 'crayon_slider' === $key || 'classical' === $key || 'lightbreeze' === $key || 'spektrum' === $key || 'evolution' === $key || 'timeline' === $key || 'news' === $key || 'glossary' === $key || 'nicy' === $key || 'sallet_slider' === $key || 'media-grid' === $key || 'blog-carousel' === $key || 'blog-grid-box' === $key || 'ticker' === $key ) {
					$class = 'bd-lite';
				} else {
					$class = 'bp-pro';
				}
				?>
				<div class="bd-template-thumbnail <?php echo esc_attr( $value['class'] . ' ' . $class ); ?>">
					<div class="bd-template-thumbnail-inner">
						<img src="<?php echo esc_url( BLOGDESIGNER_URL . 'admin/images/layouts/' . $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
						<?php if ( 'bd-lite' === $class ) { ?>
							<div class="bd-hover_overlay">
								<div class="bd-popup-template-name">
									<div class="bd-popum-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer' ); ?></a></div>
									<div class="bd-popup-view"><a href="<?php echo esc_url( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer' ); ?></a></div>
								</div>
							</div>
						<?php } else { ?>
							<div class="bd_overlay"></div>
							<div class="bd-img-hover_overlay">
								<img src="<?php echo esc_url( BLOGDESIGNER_URL . 'admin/images/pro-tag.png' ); ?>" alt="Available in Pro" />
							</div>
							<div class="bd-hover_overlay">
								<div class="bd-popup-template-name">
									<div class="bd-popup-view"><a href="<?php echo esc_url( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer' ); ?></a></div>
								</div>
							</div>
						<?php } ?>
					</div>
					<span class="bd-span-template-name"><?php echo esc_attr( $value['template_name'] ); ?></span>
				</div>
				<?php
			}
		}
		exit();
	}
	/**
	 * Create sample layout of blog
	 *
	 * @return void
	 */
	public function bd_create_sample_layout() {
		$page_id      = '';
		$blog_page_id = wp_insert_post(
			array(
				'post_title'   => esc_html__( 'Test Blog Page', 'blog-designer' ),
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => '[wp_blog_designer]',
			)
		);
		if ( $blog_page_id ) {
			$page_id = $blog_page_id;
		}
		update_option( 'blog_page_display', $page_id );
		$post_link = get_permalink( $page_id );
		echo esc_url( $post_link );
		exit;
	}
	/**
	 * Custom Admin Footer
	 *
	 * @return html
	 */
	public function bd_footer() {
		if ( isset( $_GET['page'] ) && ( 'designer_settings' === $_GET['page'] || 'bd_getting_started' === $_GET['page'] ) ) {
			add_filter( 'admin_footer_text', 'bd_remove_footer_admin', 11 );
			/**
			 * Admin side footer text.
			 */
			function bd_remove_footer_admin() {
				ob_start();
				?>
				<p id="footer-left" class="alignleft">
					<?php esc_html_e( 'If you like ', 'blog-designer' ); ?>
					<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-plugins/blog-designer/' ); ?>" target="_blank"><strong><?php esc_attr_e( 'Blog Designer', 'blog-designer' ); ?></strong></a>
					<?php esc_html_e( 'please leave us a', 'blog-designer' ); ?>
					<a class="bdp-rating-link" data-rated="Thanks :)" target="_blank" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/blog-designer/reviews?filter=5#new-post' ); ?>">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</a>
					<?php esc_html_e( 'rating. A huge thank you from Solwin Infotech in advance!', 'blog-designer' ); ?>
				</p>
				<?php
				return ob_get_clean();
			}
		}
	}
	/**
	 * Fusion page builder support
	 *
	 * @return void
	 */
	public function bd_fsn_block() {
		if ( function_exists( 'fsn_map' ) ) {
			fsn_map(
				array(
					'name'          => esc_html__( 'Blog Designer', 'blog-designer' ),
					'shortcode_tag' => 'fsn_blog_designer',
					'description'   => esc_html__( 'To make your blog design more pretty, attractive and colorful.', 'blog-designer' ),
					'icon'          => 'fsn_blog',
				)
			);
		}
	}

	/**
	 * Fusion page builder support shortcode
	 *
	 * @param type $atts atts.
	 * @param type $content content.
	 * @return $output
	 */
	public function bd_fsn_shortcode( $atts, $content ) {
		ob_start();
		?>
		<div class="fsn-bdp <?php echo esc_html( fsn_style_params_class( $atts ) ); ?>">
		<?php echo do_shortcode( '[wp_blog_designer]' ); ?>
		</div>
		<?php
		$output = ob_get_clean();
		return $output;
	}
	/**
	 * Delete Blog designer Data on unintall plugins
	 *
	 * @return void
	 */
	public function bd_unintall_plugins() {
		if ( isset( $_POST['bd_unintall_data_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bd_unintall_data_nonce'] ) ), 'blog_designer_unintall_data' ) ) {
			if ( isset( $_POST['bd_unintall_data'] ) ) {
				update_option( 'bd_unintall_data', 1 );
			} else {
				update_option( 'bd_unintall_data', 0 );
			}
		}
	}
	/**
	 * Return page
	 *
	 * @return $paged
	 */
	public static function bd_paged() {
		if ( isset( $_SERVER['REQUEST_URI'] ) || strstr( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'paged' ) || strstr( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'page' ) ) {
			if ( isset( $_REQUEST['paged'] ) ) {
				$paged = intval( $_REQUEST['paged'] );
			} else {
				if ( isset( $_SERVER['REQUEST_URI'] ) ) {
					$uri = explode( '/', sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
				}
				$uri   = array_reverse( $uri );
				$paged = $uri[1];
			}
		} else {
			$paged = 1;
		}
		/* Pagination issue on home page */
		if ( is_front_page() ) {
			$paged = get_query_var( 'page' ) ? intval( get_query_var( 'page' ) ) : 1;
		} else {
			$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		}
		return $paged;
	}
	/**
	 * Submit optin data
	 *
	 * @return void
	 */
	public function bd_submit_optin() {
		global $wpdb, $wp_version;
		$bd_submit_type = '';
		if ( ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) ) {
			if ( isset( $_POST['email'] ) ) {
				$bd_email = sanitize_email( wp_unslash( $_POST['email'] ) );
			} else {
				$bd_email = get_option( 'admin_url' );
			}
			if ( isset( $_POST['type'] ) ) {
				$bd_submit_type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
			}
			if ( 'submit' === $bd_submit_type ) {
				$status_type   = get_option( 'bd_is_optin' );
				$theme_details = array();
				if ( $wp_version >= 3.4 ) {
					$active_theme                   = wp_get_theme();
					$theme_details['theme_name']    = wp_strip_all_tags( $active_theme->name );
					$theme_details['theme_version'] = wp_strip_all_tags( $active_theme->version );
					$theme_details['author_url']    = wp_strip_all_tags( $active_theme->{'Author URI'} );
				}
				$active_plugins = (array) get_option( 'active_plugins', array() );
				if ( is_multisite() ) {
					$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
				}
				$plugins = array();
				if ( count( $active_plugins ) > 0 ) {
					$get_plugins = array();
					foreach ( $active_plugins as $plugin ) {
						$plugin_data                   = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
						$get_plugins['plugin_name']    = wp_strip_all_tags( $plugin_data['Name'] );
						$get_plugins['plugin_author']  = wp_strip_all_tags( $plugin_data['Author'] );
						$get_plugins['plugin_version'] = wp_strip_all_tags( $plugin_data['Version'] );
						array_push( $plugins, $get_plugins );
					}
				}
				$plugin_data                           = get_plugin_data( BLOGDESIGNER_DIR . 'blog-designer.php', $markup = true, $translate = true );
				$current_version                       = $plugin_data['Version'];
				$plugin_data                           = array();
				$plugin_data['plugin_name']            = 'Blog Designer';
				$plugin_data['plugin_slug']            = 'blog-designer';
				$plugin_data['plugin_version']         = $current_version;
				$plugin_data['plugin_status']          = $status_type;
				$plugin_data['site_url']               = home_url();
				$plugin_data['site_language']          = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
				$current_user                          = wp_get_current_user();
				$f_name                                = $current_user->user_firstname;
				$l_name                                = $current_user->user_lastname;
				$plugin_data['site_user_name']         = esc_attr( $f_name ) . ' ' . esc_attr( $l_name );
				$plugin_data['site_email']             = false != $bd_email ? $bd_email : get_option( 'admin_email' );
				$plugin_data['site_wordpress_version'] = $wp_version;
				$plugin_data['site_php_version']       = esc_attr( phpversion() );
				$plugin_data['site_mysql_version']     = $wpdb->db_version();
				$plugin_data['site_max_input_vars']    = ini_get( 'max_input_vars' );
				$plugin_data['site_php_memory_limit']  = ini_get( 'max_input_vars' );
				$plugin_data['site_operating_system']  = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
				$plugin_data['site_extensions']        = get_loaded_extensions();
				$plugin_data['site_activated_plugins'] = $plugins;
				$plugin_data['site_activated_theme']   = $theme_details;
				$url                                   = 'http://analytics.solwininfotech.com/';
				$response                              = wp_safe_remote_post(
					$url,
					array(
						'method'      => 'POST',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => array(),
						'body'        => array(
							'data'   => maybe_serialize( $plugin_data ),
							'action' => 'plugin_analysis_data',
						),
					)
				);
				update_option( 'bd_is_optin', 'yes' );
			} elseif ( 'cancel' === $bd_submit_type ) {
				update_option( 'bd_is_optin', 'no' );
			} elseif ( 'deactivate' === $bd_submit_type ) {
				$status_type   = get_option( 'bd_is_optin' );
				$theme_details = array();
				if ( $wp_version >= 3.4 ) {
					$active_theme                   = wp_get_theme();
					$theme_details['theme_name']    = wp_strip_all_tags( $active_theme->name );
					$theme_details['theme_version'] = wp_strip_all_tags( $active_theme->version );
					$theme_details['author_url']    = wp_strip_all_tags( $active_theme->{'Author URI'} );
				}
				$active_plugins = (array) get_option( 'active_plugins', array() );
				if ( is_multisite() ) {
					$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
				}
				$plugins = array();
				if ( count( $active_plugins ) > 0 ) {
					$get_plugins = array();
					foreach ( $active_plugins as $plugin ) {
						$plugin_data                   = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
						$get_plugins['plugin_name']    = wp_strip_all_tags( $plugin_data['Name'] );
						$get_plugins['plugin_author']  = wp_strip_all_tags( $plugin_data['Author'] );
						$get_plugins['plugin_version'] = wp_strip_all_tags( $plugin_data['Version'] );
						array_push( $plugins, $get_plugins );
					}
				}
				$plugin_data                             = get_plugin_data( BLOGDESIGNER_DIR . 'blog-designer.php', $markup = true, $translate = true );
				$current_version                         = $plugin_data['Version'];
				$reason_id                               = '';
				$plugin_data['deactivation_option_text'] = '';
				$plugin_data['deactivation_option_text'] = '';
				$plugin_data                             = array();
				$plugin_data['plugin_name']              = 'Blog Designer';
				$plugin_data['plugin_slug']              = 'blog-designer';
				if ( isset( $_POST['selected_option_de'] ) ) {
					$reason_id = sanitize_text_field( wp_unslash( $_POST['selected_option_de'] ) );
				}
				$plugin_data['deactivation_option'] = $reason_id;
				if ( isset( $_POST['selected_option_de_text'] ) ) {
					$plugin_data['deactivation_option_text'] = sanitize_text_field( wp_unslash( $_POST['selected_option_de_text'] ) );
				}
				if ( 9 == $reason_id ) {
					if ( isset( $_POST['selected_option_de_other'] ) ) {
						$plugin_data['deactivation_option_text'] = sanitize_text_field( wp_unslash( $_POST['selected_option_de_other'] ) );
					}
				}
				$plugin_data['plugin_version']         = $current_version;
				$plugin_data['plugin_status']          = $status_type;
				$plugin_data['site_url']               = home_url();
				$plugin_data['site_language']          = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
				$current_user                          = wp_get_current_user();
				$f_name                                = $current_user->user_firstname;
				$l_name                                = $current_user->user_lastname;
				$plugin_data['site_user_name']         = esc_attr( $f_name ) . ' ' . esc_attr( $l_name );
				$plugin_data['site_email']             = false != $bd_email ? $bd_email : get_option( 'admin_email' );
				$plugin_data['site_wordpress_version'] = $wp_version;
				$plugin_data['site_php_version']       = esc_attr( phpversion() );
				$plugin_data['site_mysql_version']     = $wpdb->db_version();
				$plugin_data['site_max_input_vars']    = ini_get( 'max_input_vars' );
				$plugin_data['site_php_memory_limit']  = ini_get( 'max_input_vars' );
				$plugin_data['site_operating_system']  = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
				$plugin_data['site_extensions']        = get_loaded_extensions();
				$plugin_data['site_activated_plugins'] = $plugins;
				$plugin_data['site_activated_theme']   = $theme_details;
				$url                                   = 'http://analytics.solwininfotech.com/';
				$response                              = wp_safe_remote_post(
					$url,
					array(
						'method'      => 'POST',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => array(),
						'body'        => array(
							'data'   => maybe_serialize( $plugin_data ),
							'action' => 'plugin_analysis_data_deactivate',
						),
					)
				);
				update_option( 'bd_is_optin', '' );
			}
		}
		exit();
	}
	/**
	 * Display links
	 *
	 * @param type $links links.
	 */
	public function bd_plugin_links( $links ) {
		$bd_is_optin = get_option( 'bd_is_optin' );
		if ( 'yes' === $bd_is_optin || 'no' === $bd_is_optin ) {
			$start_page = 'designer_settings';
		} else {
			$start_page = 'designer_welcome_page';
		}
		$action_links       = array(
			'settings' => '<a href="' . esc_url( admin_url( "admin.php?page=$start_page" ) ) . '" title="' . esc_attr__( 'View Blog Designer Settings', 'blog-designer' ) . '">' . esc_html__( 'Settings', 'blog-designer' ) . '</a>',
		);
		$links              = array_merge( $action_links, $links );
		$links['documents'] = '<a class="documentation_bd_plugin" target="_blank" href="' . esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer/' ) . '">' . esc_html__( 'Documentation', 'blog-designer' ) . '</a>';
		$links['upgrade']   = '<a target="_blank" href="' . esc_url( 'https://codecanyon.net/item/blog-designer-pro-for-wordpress/17069678?ref=solwin' ) . '" class="bd_upgrade_link">' . esc_html__( 'Upgrade', 'blog-designer' ) . '</a>';
		return $links;
	}
}
new Blog_Designer_Lite_Admin();
