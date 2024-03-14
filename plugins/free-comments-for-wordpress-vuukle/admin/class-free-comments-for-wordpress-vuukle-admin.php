<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/admin
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      1.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/admin
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 */
class Free_Comments_For_Wordpress_Vuukle_Admin {

	/**
	 * Plugin base name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $plugin_name Plugin base name for folder and main file
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin all needed properties in one place
	 *
	 * @since  5.0
	 * @access protected
	 * @var    array $attributes The array containing main attributes of the plugin.
	 */
	protected $attributes;

	/**
	 * Main settings option name
	 *
	 * @since  5.0
	 * @access protected
	 * @var    string $settings_name Main settings option_name
	 */
	protected $settings_name;

	/**
	 * Main settings option name
	 *
	 * @since  5.0
	 * @access protected
	 * @var    string $settings_name Main settings option_name
	 */
	protected $app_id_setting_name;

	/**
	 * Property for storing Vuukle App Id
	 *
	 * @since  5.0
	 * @access protected
	 * @var    string $app_id App Id
	 */
	protected $app_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array $attributes The array containing main attributes of the plugin.
	 */
	public function __construct( $attributes ) {
		$this->attributes          = $attributes;
		$this->plugin_name         = $this->attributes['name'];
		$this->version             = $this->attributes['version'];
		$this->settings_name       = $this->attributes['settings_name'];
		$this->app_id_setting_name = $this->attributes['app_id_setting_name'];
	}

	/**
	 * Quick register App
	 */
	public function tryQuickRegister() {
		/**
		 * Check if we are on our page
		 * Proceed quick app registration process in case not done yet
		 * Alongside retrieving/setting app_id assign it to the main property
		 */
		if ( ! empty( $_GET['page'] ) && $_GET['page'] == $this->plugin_name ) {
			$app_id = get_option( $this->app_id_setting_name );
			if ( empty( $app_id ) ) {
				$app_id = Free_Comments_For_Wordpress_Vuukle_Helper::quickRegister( $this->app_id_setting_name, $this->attributes['log_dir'] );
			}
			if ( ! empty( $app_id ) ) {
				$this->app_id = $app_id;
			}
		}
	}

	/**
	 * Start session. Mostly for organizing proper error messaging
	 *
	 * @since  1.0.0
	 */
	public function startSession() {
		if ( empty( session_id() ) && is_admin() && get_admin_page_parent() == $this->attributes['name'] ) {
			session_start();
		}
	}

	/**
	 * Check plugin using date
	 *
	 * @since  1.0.0
	 */
	public function checkOneMonthPassed() {
		if ( get_option( 'hide_vuukle_admin_notice' ) !== '1' ) {
			$is_our          = ! empty( $_GET['page'] ) && $_GET['page'] == $this->plugin_name;
			$date_activation = get_option( 'Activated_Vuukle_Plugin_Date' );
			$date_activation = strtotime( '+4 weeks', strtotime( $date_activation ) );
			$current_date    = strtotime( gmdate( 'Y-m-d H:i:s' ) );
			if ( $current_date >= $date_activation && $is_our ) {
				add_action( 'admin_notices', array( $this, 'one_month_notice' ) );
			}
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function enqueueStyles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Free_Comments_For_Wordpress_Vuukle_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Free_Comments_For_Wordpress_Vuukle_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'vuukle_admin_font_awesome', 'https://use.fontawesome.com/releases/v5.2.0/css/all.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/free-comments-for-wordpress-vuukle-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function enqueueScripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Free_Comments_For_Wordpress_Vuukle_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Free_Comments_For_Wordpress_Vuukle_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$admin_dir_url = $this->attributes['admin_dir_url'];
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( $this->plugin_name, $admin_dir_url . 'js/free-comments-for-wordpress-vuukle-admin.js', array( 'jquery' ), $this->version );
		wp_localize_script( $this->plugin_name, 'fcfwv_admin_vars', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce()
		] );
	}

	/**
	 * This function creates a page in the admin dashboard.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function adminMenu() {
		$page = add_menu_page( 'Vuukle Settings', 'Vuukle', 'moderate_comments', $this->plugin_name, array(
			$this,
			'adminPage'
		), $this->attributes['admin_dir_url'] . 'images/icon@2.png' );
		add_action( 'admin_print_styles-' . $page, array( $this, 'enqueueStyles' ) );
		add_action( 'admin_print_scripts-' . $page, array( $this, 'enqueueScripts' ) );
	}

	/**
	 * This function  create an admin page.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function adminPage() {
		$tab              = ! empty( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'tab1';
		$url              = esc_url_raw( remove_query_arg( array( 'tab' ) ) );
		$app_id           = ! empty( $this->app_id ) ? $this->app_id : get_option( $this->app_id_setting_name );
		$default_settings = Free_Comments_For_Wordpress_Vuukle_Helper::getDefaultSettings();
		$settings         = get_option( $this->settings_name );
		$settings         = ! is_array( $settings ) ? $default_settings : array_replace_recursive( $default_settings, $settings );
		include_once $this->attributes['admin_dir_path'] . 'partials/' . $this->attributes['name'] . '-admin-display.php';
	}

	/**
	 * Get error/success message value
	 *
	 * @param $type
	 *
	 * @return mixed
	 * @since  1.0.0.
	 */
	public function get_message( $type ) {
		return $_SESSION['fcfwv_admin'][ $type ];
	}

	/**
	 * Get error/success message existence
	 *
	 * @param $type
	 *
	 * @return bool
	 * @since  1.0.0.
	 */
	public function check_message_existence( $type ) {
		return ! empty( $_SESSION['fcfwv_admin'] ) && ! empty( $_SESSION['fcfwv_admin'][ $type ] );
	}

	/**
	 * Remove message from sessions
	 *
	 * @since  1.0.0.
	 */
	public function unset_sessions() {
		unset( $_SESSION['fcfwv_admin'] );
		session_write_close();
	}

	/**
	 * Redirect upon result (success, error)
	 *
	 * @param $referer
	 * @param $key
	 * @param $value
	 */
	public function redirect_upon_result( $referer, $key, $value ) {
		if ( empty( session_id() ) ) {
			session_start();
		}
		if ( ! empty( $key ) && ! empty( $value ) ) {
			if ( empty( $_SESSION['fcfwv_admin'] ) ) {
				$_SESSION['fcfwv_admin'] = [];
			}
			$_SESSION['fcfwv_admin'][ $key ] = $value;
		}
		session_write_close();
		wp_safe_redirect( $referer );
		exit();
	}

	/**
	 * For enabling.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function enableCoupleConfigs() {
		// Check admin referer
		if ( check_admin_referer( 'vuukleEnableFunctionAction', 'vuukleEnableFunctionNonce' ) ) {
			$option                 = get_option( $this->settings_name );
			$emote                  = ! empty( $_POST['emote'] ) ? esc_sql( sanitize_text_field( $_POST['emote'] ) ) : null;
			$share                  = ! empty( $_POST['share'] ) ? esc_sql( sanitize_text_field( $_POST['share'] ) ) : null;
			$enabled_comments       = ! empty( $_POST['enabled_comments'] ) ? esc_sql( sanitize_text_field( $_POST['enabled_comments'] ) ) : null;
			$web_push_notifications = ! empty( $_POST['web_push_notifications'] ) ? esc_sql( sanitize_text_field( $_POST['web_push_notifications'] ) ) : 'null';
			if ( $emote === '1' ) {
				$option['emote'] = 'true';
			} else {
				$option['emote'] = 'false';
			}
			if ( $share === '1' ) {
				$option['share'] = '1';
			} else {
				$option['share'] = '0';
			}
			if ( $enabled_comments === '1' ) {
				$option['enabled_comments'] = 'true';
			} else {
				$option['enabled_comments'] = 'false';
			}
			if ( $web_push_notifications === 'on' ) {
				$option['web_push_notifications'] = 'on';
			} else {
				$option['web_push_notifications'] = 'off';
            }
            // Web push notification related rest todos
			$this->fieldRelated_web_push_notifications($option['web_push_notifications']);
			// Also try call to refresh API key, or get API key
			update_option( $this->settings_name, $option );
			// Bad solution , but will solve the issue regarding fetching key upon activation
			Free_Comments_For_Wordpress_Vuukle_Helper::quickRegister( $this->app_id_setting_name, $this->attributes['log_dir'] );
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->attributes['name'] ) );
			exit;
		}
	}

	/**
	 * Save settings
	 *
	 * @since 4.0
	 */
	public function saveSettings() {
		// Define vars
		$referer        = wp_get_referer();
		$parsed_referer = wp_parse_url( $referer );
		$tab            = ! empty( $_POST['tab'] ) ? sanitize_key( $_POST['tab'] ) : '';
		// Modify referer url
		if ( ! empty( $tab ) && ! empty( $parsed_referer['query'] ) ) {
			parse_str( $parsed_referer['query'], $query_arr );
			$query_arr['tab']        = $tab;
			$parsed_referer['query'] = http_build_query( $query_arr );
			$referer                 = $parsed_referer['path'] . '?' . $parsed_referer['query'];
		}
		$nonce = ! empty( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : null;
		// Check nonce
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, $this->settings_name ) ) {
			// Invalid or empty nonce
			$this->redirect_upon_result( $referer, 'error', __( 'Invalid nonce' ) );
		}
		// Check AppId
		if ( ! empty( $_POST['AppId'] ) ) {
			$app_id = esc_sql( sanitize_text_field( $_POST['AppId'] ) );
			if ( empty( $app_id ) || $_POST['AppId'] !== $app_id ) {
				$this->redirect_upon_result( $referer, 'error', __( 'Invalid value for AppId' ) );
			}
			update_option( $this->app_id_setting_name, $app_id );
		}
		// Check and validate the rest data
		$data_received    = [];
		$default_settings = Free_Comments_For_Wordpress_Vuukle_Helper::getDefaultSettings();
		foreach ( $default_settings as $key => $setting ) {
			if ( isset( $_POST[ $key ] ) ) {
				// Two scenarios: 1. Field is string or 2. Field is array
				// Second one is connected with field named "text"
				// Most probably that field will be removed from user interface, or completely
				if ( is_array( $_POST[ $key ] ) && is_array( $default_settings[ $key ] ) ) {
					// Field is array
					$data_received[ $key ] = [];
					foreach ( $default_settings[ $key ] as $sub_key => $sub_value ) {
						if ( $key === 'text' && $sub_key === 'timeAgo' ) {
							continue;
						}
						if ( isset( $_POST[ $key ][ $sub_key ] ) ) {
							$data_received[ $key ][ $sub_key ] = esc_sql( sanitize_text_field( $_POST[ $key ][ $sub_key ] ) );
						} else {
							$data_received[ $key ][ $sub_key ] = $default_settings[ $key ][ $sub_key ];
						}
					}
				} else {
					// Field is string
					// Check additional logic
					$data_received[ $key ] = esc_sql( sanitize_text_field( $_POST[ $key ] ) );
					// Another additional todos, in case there are some todos left.
					if ( method_exists( $this, 'fieldRelated_' . $key ) ) {
						$this->{'fieldRelated_' . $key}( $data_received[ $key ] );
					}
				}
			} else {
				// Field is not set
				// According to previous save logic, lets check for couple fields default value
				switch ( $key ) {
					case 'share_type':
					case 'share_type_vertical':
					case 'share_position':
					case 'share_position2':
						$data_received[ $key ] = '';
						break;
					case 'non_article_pages':
					case 'web_push_notifications':
					case 'embed_emotes_amp':
						$data_received[ $key ] = 'off';
						break;
					case 'checkboxTextEnabled':
						$data_received[ $key ] = false;
						break;
				}
				// Another additional todos, in case there are some todos left.
				if ( method_exists( $this, 'fieldRelated_' . $key ) ) {
					$this->{'fieldRelated_' . $key}( $data_received[ $key ] );
				}
			}
		}
		// Some logic
		if ( $data_received['enable_h_v'] === 'yes' ) {
			$data_received['div_class_powerbar'] = '';
		}
		if ( $data_received['div_class'] === '' && $data_received['embed_comments'] === '3' ) {
			$data_received['embed_comments'] = '1';
		}
		if ( $data_received['div_id'] === '' && $data_received['embed_comments'] === '4' ) {
			$data_received['embed_comments'] = '1';
		}
		// Final save
		$ok = update_option( $this->settings_name, $data_received );
		if ( $ok === false ) {
			// Error
			$this->redirect_upon_result( $referer, 'warning', __( 'Seems there is no change made.' ) );
		}
		// Success
		$this->redirect_upon_result( $referer, 'success', __( 'Successfully saved' ) );

	}

	/**
	 * Web push notification related
	 *
	 * @param  string  $value  on or off
	 */
	public function fieldRelated_web_push_notifications( $value ) {
		if ( $value === 'on' || $value === 'off' ) {
			// Remove from root
			if ( file_exists( get_home_path() . 'firebase-messaging-sw.js' ) ) {
				@unlink( get_home_path() . 'firebase-messaging-sw.js' );
			}
		}
		if ( $value === 'on' ) {
			// Move to root
			$existingFilePath = $this->attributes['public_dir_path'] . '/js/firebase-messaging-sw.js';
			if ( file_exists( $existingFilePath ) ) {
				$existingFileContent = file_get_contents( $existingFilePath );
				$newFile             = fopen( get_home_path() . 'firebase-messaging-sw.js', 'w' );
				fwrite( $newFile, $existingFileContent );
				fclose( $newFile );
			}
		}
	}

	/**
	 * Reset settings
	 *
	 * @since 4.0
	 */
	public function resetSettings() {
		// Define vars
		$referer = wp_get_referer();
		$nonce   = ! empty( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : null;
		// Check nonce
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, $this->settings_name ) ) {
			// Invalid or empty nonce
			$this->redirect_upon_result( $referer, 'error', __( 'Invalid nonce' ) );
		}
		$default_settings = Free_Comments_For_Wordpress_Vuukle_Helper::getDefaultSettings();
		// Final save
		$ok = update_option( $this->settings_name, $default_settings );
		if ( $ok === false ) {
			// Error
			$this->redirect_upon_result( $referer, 'warning', __( 'Seems there is no change made.' ) );
		}
        // Some additional todos connected with each field
		foreach ( $default_settings as $key => $default_setting ) {
			// Another additional todos, in case there are some todos left.
			if ( method_exists( $this, 'fieldRelated_' . $key ) ) {
				$this->{'fieldRelated_' . $key}( $default_setting );
			}
        }
		// Success
		$this->redirect_upon_result( $referer, 'success', __( 'Successfully saved' ) );
	}

	/**
	 * For deactivation.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function deactivateAction() {
		$nonce = ! empty( $_POST['_wpnonce'] ) ? sanitize_key( $_POST['_wpnonce'] ) : '';
		if ( wp_verify_nonce( $nonce ) ) {
			$post = $_POST;
			if ( $post['vuukle_deactivate_function'] === 'confirm' ) {
				deactivate_plugins( array( $this->plugin_name . '/' . $this->plugin_name . '.php' ) );
				delete_option( 'Activated_Vuukle_Plugin_Date' );
				delete_option( 'hide_vuukle_admin_notice' );
				if ( ! empty( $post['answer-deactivate-vuukle'] ) ) {
					$subject = 'Deactivate Vuukle plugin on the site ' . site_url();
					$message = '<strong>Type answer:</strong> ' . esc_html( stripslashes( $post['answer-deactivate-vuukle'] ) );
					if ( ! empty( $post['other-answer-deactivate-vuukle'] ) && 'Other' === $post['answer-deactivate-vuukle'] ) {
						$message .= '<br><strong>Text answer:</strong> ' . esc_html( stripslashes( $post['other-answer-deactivate-vuukle'] ) );
					}
					$headers = array(
						'From: ' . get_option( 'blogname' ) . ' <' . get_option( 'admin_email' ) . '>',
						'content-type: text/html',
					);
					wp_mail( 'support@vuukle.com', $subject, $message, $headers );
				}
			}
		}
		wp_safe_redirect( admin_url( 'plugins.php' ) );
		exit;
	}

	/**
	 * Creating activation modal.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function activationModal() {
		if ( '1' === get_option( 'Activated_Vuukle_Plugin' ) ) {
			delete_option( 'Activated_Vuukle_Plugin' );
			include $this->attributes['admin_dir_path'] . 'partials/' . $this->attributes['name'] . '-activate-modal.php';
		}
	}

	/**
	 * Creating deactivation modal.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function deactivationModal() {
		$screen = get_current_screen();
		if ( 'plugins' === $screen->base ) {
			include $this->attributes['admin_dir_path'] . 'partials/' . $this->attributes['name'] . '-deactivate-modal.php';
		}
	}

	/**
	 * This function creates admin notice.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function one_month_notice() {
		?>
        <div class="notice notice-success is-dismissible vuukle-notice">
            <p>
                <img style="float:right; width:180px;" class="logo"
                     src="<?php echo $this->attributes['admin_dir_url']; ?>images/vuukle-logo.svg"/>
                <strong>
                    Hello there! You’ve been using Vuukle widgets on your site for over a month now. If it sucked, you
                    know what
                    to do but if it was a great experience - please rate it 5-stars and spread the positive vibes by
                    leaving us
                    a comment.<br>We thank you for choosing us and helping us become a better product. Email us on <a
                            href="mailto:support@vuukle.com">support@vuukle.com</a> if you have any suggestions.
                </strong>
            </p>
            <div style="clear: both"></div>
            <ul>
                <li>
                    <a target="_blank" href="https://wordpress.org/plugins/free-comments-for-wordpress-vuukle/#reviews">
                        <strong>Post a review</strong>
                    </a>
                </li>
                <li>
                    <a href="#" class="pum2-dismiss">
                        <strong>Remind me later</strong>
                    </a>
                </li>
                <li>
                    <a href="#" class="pum2-dismiss">
                        <strong>Already did</strong>
                    </a>
                </li>
            </ul>
        </div>
        <script>
            function doAjaxRequest(type, data, url) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                };
                xmlhttp.open(type, url, true);
                if (data)
                    xmlhttp.send(data);
                else
                    xmlhttp.send();
            }

            document.addEventListener('click', function (event) {
                if (event.target.matches('.pum2-dismiss')) {
                    document.querySelector('.vuukle-notice button.notice-dismiss')?.click();
                } else if (event.target.matches('.vuukle-notice button.notice-dismiss')) {

                }
            });
        </script>
		<?php
	}

}


