<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://miniorange.com/
 * @since      1.1.1
 *
 * @package    Media_Restriction
 * @subpackage Media_Restriction/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Media_Restriction
 * @subpackage Media_Restriction/admin
 * @author     test <test@test.com>
 */
require_once 'partials/media-restriction-admin-display.php';
require_once 'class-miniorange-media-restriction-customer.php';
require_once 'partials/class-mo-media-restriction-admin-feedback.php';
/**
 * This class handled the admin menu and initiate the plugin.
 */
class Media_Restriction_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.1
	 * @param string $plugin_name  The name of this plugin.
	 * @param string $version  The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		update_option( 'host_name', 'https://login.xecurify.com' );
		add_action( 'init', array( $this, 'mo_media_restriction_validate' ) );
		if ( get_option( 'mo_enable_media_restriction' ) === false ) {
			update_option( 'mo_enable_media_restriction', 1 );
		}
	}

	/**
	 * Show restricted folder path
	 *
	 * @param mixed $redirect_url redirection url.
	 * @return void
	 */
	public function mo_media_show_file_or_folder( $redirect_url ) {
		$file_path = ABSPATH . DIRECTORY_SEPARATOR . $redirect_url;
		$file_url  = site_url() . '/' . $redirect_url;
		if ( file_exists( $file_path ) ) {
			if ( is_dir( $file_path ) ) {
				$dh = opendir( $file_path );
				if ( $dh ) {
					// reading the contents of the directory.
					$file = readdir( $dh );
					while ( false !== $file ) {
						if ( '..' !== $file && '.' !== $file ) {
							echo "<a href='" . esc_attr( $file_url ) . '/' . esc_attr( $file ) . "'>" . esc_attr( $file ) . '</a><br>';
						}
					}
					closedir( $dh );
				}
				exit;
			} else {
				header( 'content-type: ' . mime_content_type( $file_path ) );
				echo esc_attr( wp_remote_get( $file_path ) );
				exit;
			}
		} else {
			wp_die( 'No such file exist' );
		}
	}

	/**
	 * Media restriction validate.
	 *
	 * @return mixed
	 */
	public function mo_media_restriction_validate() {
		if ( isset( $_GET['mo_media_restrict_request'] ) && '1' === sanitize_text_field( wp_unslash( $_GET['mo_media_restrict_request'] ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are not fetching data on form submission.
			if ( is_user_logged_in() === false ) {
				$restrict_option = get_option( 'mo_mr_redirect_to' );
				if ( '403-forbidden-page' === $restrict_option ) {
					header( 'HTTP/1.0 403 Forbidden' );
					echo 'Access forbidden!';
				} else {
					$redirect_to = get_permalink( get_page_by_path( $restrict_option ) );
					wp_safe_redirect( $redirect_to );
				}
				exit;
			} else {
				$redirect_url = isset( $_GET['redirect_to'] ) ? sanitize_text_field( wp_unslash( $_GET['redirect_to'] ) ) : site_url(); //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
				$this->mo_media_show_file_or_folder( $redirect_url );
			}
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.1.1
	 * @param string $page page.
	 * @return mixed
	 */
	public function enqueue_styles( $page ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Media_Restriction_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Media_Restriction_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( 'toplevel_page_mo_media_restrict' !== $page ) {
			return;
		}
		if ( isset( $_REQUEST['page'] ) && 'mo_media_restrict' === sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce recommendation because we are fetching data from URL directly and not form submission.
			wp_enqueue_style( 'mo_media_admin_bootstrap_style', plugins_url( 'css/bootstrap.min.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'mo_media_admin_font_awesome_style', plugins_url( 'css/font-awesome.min.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'mo_media_admin_media_phone_style', plugins_url( 'css/phone.min.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'mo_media_admin_media_settings_style', plugins_url( 'css/media-restriction-admin.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'mo_media_admin_settings_style', plugins_url( 'css/style.min.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'mo_media_admin_table_style', plugins_url( 'css/jquery.dataTables.min.css', __FILE__ ), array(), $this->version );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.1.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Media_Restriction_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Media_Restriction_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( isset( $_REQUEST['page'] ) && 'mo_media_restrict' === sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce recommendation because we are fetching data from URL directly and not form submission.
			wp_enqueue_script( 'mo_media_admin_media_settings_script', plugins_url( 'js/media-restriction-admin.min.js', __FILE__ ), array(), $this->version, false );
			wp_enqueue_script( 'mo_media_admin_media_phone_script', plugins_url( 'js/phone.js', __FILE__ ), array(), $this->version, false );
			wp_enqueue_script( 'mo_media_admin_custom_settings_script', plugins_url( 'js/custom.min.js', __FILE__ ), array(), $this->version, false );
			wp_enqueue_script( 'mo_media_admin_table_script', plugins_url( 'js/jquery.dataTables.min.js', __FILE__ ), array(), $this->version, false );
			wp_enqueue_script( 'mo_media_admin_fontawesome_script', plugins_url( 'js/fontawesome.js', __FILE__ ), array(), $this->version, false );
		}
	}

	/**
	 * On Post field check values are empty or not
	 *
	 * @since    1.1.1
	 * @param string $value  The version of this plugin.
	 */
	private function mo_media_restriction_check_empty_or_null( $value ) {
		if ( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Remove rules in htaccess file
	 *
	 * @since    1.1.1
	 */
	private function mo_media_restrict_remove_rules() {
		$home_path     = get_home_path();
		$htaccess_file = $home_path . '.htaccess';

		if ( file_exists( $htaccess_file ) && is_writable( $home_path ) && is_writeable( $htaccess_file ) ) {
			insert_with_markers( $htaccess_file, 'MINIORANGE MEDIA RESTRICTION', array() );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Write rules in htaccess file
	 *
	 * @since    1.1.1
	 */
	private function mo_media_restrict_write_rules() {
		global $wp_rewrite;
		$home_path      = get_home_path();
		$htaccess_file  = $home_path . '.htaccess';
		$permalink_type = get_option( 'permalink_structure' );
		if ( file_exists( $htaccess_file ) && is_writable( $home_path ) && is_writeable( $htaccess_file ) ) {
			$htaccess_file_backup = $home_path . '.htaccess-backup';
			if ( ! file_exists( $htaccess_file_backup ) ) {
				copy( $htaccess_file, $home_path . '.htaccess-backup' );
			}
			if ( empty( $permalink_type ) ) {
				$wp_rewrite->set_permalink_structure( '/%year%/%monthnum%/%day%/%postname%/' );
				flush_rewrite_rules();
			}
		} else {
			if ( is_writable( $home_path ) ) {
				$wp_rewrite->set_permalink_structure( '/%category%/%postname%/' );
				flush_rewrite_rules();
				copy( $htaccess_file, $home_path . '.htaccess-backup' );
			} else {
				return false;
			}
		}

		$mo_media_restriction_file_types = get_option( 'mo_media_restriction_file_types' );
		if ( empty( $mo_media_restriction_file_types ) ) {
			$mo_media_restriction_file_types = 'png|jpg|gif|pdf|doc';
		}
		$restrict_option = get_option( 'mo_mr_restrict_option' );
		if ( empty( $restrict_option ) ) {
			$restrict_option = 'display-custom-page';
		}
		$redirect_to = get_option( 'mo_mr_redirect_to' );
		if ( empty( $redirect_to ) ) {
			$redirect_to = '403-forbidden-page';
		}

		$rules  = 'RewriteCond %{REQUEST_FILENAME} ^.*(' . $mo_media_restriction_file_types . ")$ [OR]\n";
		$rules .= 'RewriteCond %{REQUEST_URI} protectedfiles ';
		$rules .= "\n";
		$rules .= "RewriteCond %{HTTP_COOKIE} !^.*wordpress_logged_in.*$ [NC]\n";

		$choose_server = get_option( 'mo_media_restriction_choose_server', 'apache' );

		if ( 'godaddy' === $choose_server ) {
			$rules .= 'RewriteRule ^(.*)$ ./?mo_media_restrict_request=1&redirect_to=$1 [R=302,NC]';
		} else {
			if ( 'display-custom-page' === $restrict_option ) {
				if ( '403-forbidden-page' === $redirect_to ) {
					$rules .= 'RewriteRule . - [R=403,L]';
				} else {
					$rules .= 'RewriteRule . ./' . $redirect_to . ' [R=302,NC]';
				}
			}
		}

		if ( ! $this->mo_media_restrict_remove_rules() ) {
			return false;
		}

		return insert_with_markers( $htaccess_file, 'MINIORANGE MEDIA RESTRICTION', $rules );
	}

	/**
	 * Success Message
	 *
	 * @return void
	 */
	public function mo_media_restriction_success_message() {
		$class   = 'error';
		$message = get_option( 'mo_media_restriction_message' );
		echo "<div class='" . esc_attr( $class ) . "'> <p>" . esc_attr( $message ) . '</p></div>';
	}

	/**
	 * Error Message
	 *
	 * @return void
	 */
	public function mo_media_restriction_error_message() {
		$class   = 'updated';
		$message = get_option( 'mo_media_restriction_message' );
		echo "<div class='" . esc_attr( $class ) . "'><p>" . esc_attr( $message ) . '</p></div>';
	}

	/**
	 * Success message print
	 *
	 * @return void
	 */
	private function mo_media_restriction_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_media_restriction_success_message' ) );
		add_action( 'admin_notices', array( $this, 'mo_media_restriction_error_message' ) );
	}

	/**
	 * Error message print
	 *
	 * @return void
	 */
	private function mo_media_restriction_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'mo_media_restriction_error_message' ) );
		add_action( 'admin_notices', array( $this, 'mo_media_restriction_success_message' ) );
	}

	/**
	 * Post request of support form.
	 *
	 * @return mixed
	 */
	public function mo_media_restrict_support() {
		if ( isset( $_POST['option'] ) ) {
			if ( current_user_can( 'administrator' ) ) {
				if ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_feedback' && isset( $_REQUEST['mo_media_restriction_feedback_fields'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_feedback_fields'] ) ), 'mo_media_restriction_feedback_form' ) ) {
					$user                      = wp_get_current_user();
					$message                   = 'Plugin Deactivated:';
					$deactivate_reason         = array_key_exists( 'mo_media_restriction_deactivate_reason_radio', $_POST ) ? sanitize_textarea_field( wp_unslash( $_POST['mo_media_restriction_deactivate_reason_radio'] ) ) : false;
					$deactivate_reason_message = array_key_exists( 'mo_media_restriction_query_feedback', $_POST ) ? sanitize_textarea_field( wp_unslash( $_POST['mo_media_restriction_query_feedback'] ) ) : false;
					if ( $deactivate_reason ) {
						$message .= $deactivate_reason;
						if ( isset( $deactivate_reason_message ) ) {
							$message .= ':' . $deactivate_reason_message;
						}
						$email = is_bool( get_option( 'mo_media_restriction_admin_email' ) ) ? '' : get_option( 'mo_media_restriction_admin_email' );
						if ( '' === $email ) {
							$email = $user->user_email;
						}
						$phone = get_option( 'mo_media_restriction_admin_phone' );
						// only reason.
						$feedback_reasons = new Miniorange_Media_Restriction_Customer();
						$submited         = $feedback_reasons->mo_media_restriction_send_email_alert( $email, $phone, $message, 'Feedback: WordPress Prevent Files / Folders Access' );

						$path = plugin_dir_path( dirname( __FILE__ ) ) . 'media-restriction.php';
						deactivate_plugins( $path );
						if ( false === $submited ) {
							update_option( 'mo_media_restriction_message', 'Your query could not be submitted. Please try again.' );
							$this->mo_media_restriction_show_error_message();
						} else {
							update_option( 'mo_media_restriction_message', 'Thanks for getting in touch! We shall get back to you shortly.' );
							$this->mo_media_restriction_show_success_message();
						}
					} else {
						update_option( 'mo_media_restriction_message', 'Please Select one of the reasons ,if your reason is not mentioned please select Other Reasons' );
						$this->mo_media_restriction_show_error_message();
					}
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_skip_feedback' && isset( $_REQUEST['mo_media_restriction_skip_feedback_form_fields'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_skip_feedback_form_fields'] ) ), 'mo_media_restriction_skip_feedback_form' ) ) {
					$path = plugin_dir_path( dirname( __FILE__ ) ) . 'media-restriction.php';
					deactivate_plugins( $path );
					update_option( 'mo_media_restriction_message', 'Plugin deactivated successfully' );
					$this->mo_media_restriction_show_success_message();
				}
			}
		}
	}

	/**
	 * Post data request handle for media restriction on admin side
	 *
	 * @since    1.1.1
	 */
	public function mo_media_restrict_page() {
		if ( isset( $_POST['option'] ) ) {
			if ( current_user_can( 'administrator' ) ) {
				if ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_enable_media_restriction' && isset( $_REQUEST['mo_media_restriction_enable_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_enable_field'] ) ), 'mo_media_restriction_enable_form' ) ) {
					update_option( 'mo_enable_media_restriction', isset( $_POST['mo_enable_media_restriction'] ) ? intval( $_POST['mo_enable_media_restriction'] ) : 0 );
					if ( get_option( 'mo_enable_media_restriction' ) ) {
						$upload_dir = wp_upload_dir();
						if ( $upload_dir && isset( $upload_dir['basedir'] ) ) {
							$base_upload_dir = $upload_dir['basedir'];
							$protectedfiles  = $base_upload_dir . DIRECTORY_SEPARATOR . 'protectedfiles';

							if ( ! file_exists( $protectedfiles ) && ! is_dir( $protectedfiles ) ) {
								wp_mkdir_p( $protectedfiles );
							}
						}
					} else {
						if ( ! $this->mo_media_restrict_remove_rules() ) {
							echo "<div class='mo_media_restriction_error_box'><b>Directory doesn\'t have write permissions.</b></div>";
						}
					}
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_file_types' && isset( $_REQUEST['mo_media_restriction_file_configuration_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_file_configuration_field'] ) ), 'mo_media_restriction_file_configuration_form' ) ) {
					$mo_media_restriction_file_types = isset( $_POST['mo_media_restriction_file_types'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mo_media_restriction_file_types'] ) ) : 0;
					$mo_media_restriction_show_rules = isset( $_POST['mo_media_restriction_show_rules'] ) ? intval( $_POST['mo_media_restriction_show_rules'] ) : 0;
					if ( empty( $mo_media_restriction_file_types ) ) {
						echo '<div class="mo_media_restriction_error_box"><b>File extension is required.</b></div>';
					} else {
						if ( is_string( $mo_media_restriction_file_types ) ) {
							$mo_media_restriction_file_types = str_replace( '{"value":"', '', $mo_media_restriction_file_types );
							$mo_media_restriction_file_types = str_replace( '"}', '', $mo_media_restriction_file_types );
							$mo_media_restriction_file_types = str_replace( '[', '', $mo_media_restriction_file_types );
							$mo_media_restriction_file_types = str_replace( ']', '', $mo_media_restriction_file_types );
							$mo_media_restriction_file_types = explode( ',', $mo_media_restriction_file_types );
							$string                          = $mo_media_restriction_file_types[0];
							$count                           = count( $mo_media_restriction_file_types );
							for ( $i = 1;$i < $count;$i++ ) {
								$string = $string . '|' . $mo_media_restriction_file_types[ $i ];
							}
							$mo_media_restriction_file_types = $string;
						}
						update_option( 'mo_media_restriction_file_types', $mo_media_restriction_file_types );
						update_option( 'mo_media_restriction_show_rules', $mo_media_restriction_show_rules );
						if ( 0 === $mo_media_restriction_show_rules ) {
							if ( get_option( 'mo_enable_media_restriction' ) ) {
								if ( $this->mo_media_restrict_write_rules() ) {
									echo "<div class='mo_media_restriction_success_box'><b>Settings saved successfully.</b></div>";
								} else {
									echo "<div class='mo_media_restriction_error_box'><b>Please give write permissions.</b></div>";
								}
							} else {
								echo "<div class='mo_media_restriction_error_box'><b>Enable media restriction for logged in user first.</b></div>";
							}
						} else {
							update_option( 'mo_media_restriction_show_rules', 2 );
						}
					}
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_enable' && isset( $_REQUEST['mo_media_restriction_enable_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_enable_field'] ) ), 'mo_media_restriction_media_restriction_enable_form' ) ) {
					$mo_media_restriction_show_rules = isset( $_POST['mo_media_restriction_show_rules'] ) ? intval( $_POST['mo_media_restriction_show_rules'] ) : 0;
					if ( isset( $_POST['mo_mr_restrict_option'] ) ) {
							$restrict_option = sanitize_textarea_field( wp_unslash( $_POST['mo_mr_restrict_option'] ) );
							update_option( 'mo_mr_restrict_option', $restrict_option );

						if ( 'display-custom-page' === $restrict_option ) {
							if ( isset( $_POST['mo_media_redirect_to_display_page'] ) ) {
								$redirect_to = sanitize_textarea_field( wp_unslash( $_POST['mo_media_redirect_to_display_page'] ) );
								update_option( 'mo_mr_redirect_to', $redirect_to );
							}
						}
					}

						$choose_server = isset( $_POST['choose_server'] ) ? sanitize_textarea_field( wp_unslash( $_POST['choose_server'] ) ) : 'apache';
						update_option( 'mo_media_restriction_choose_server', $choose_server );
					if ( 0 === $mo_media_restriction_show_rules ) {
						if ( get_option( 'mo_enable_media_restriction' ) ) {
							if ( $this->mo_media_restrict_write_rules() ) {
								echo "<div class='mo_media_restriction_success_box'><b>Settings saved successfully.</b></div>";
							} else {
								echo "<div class='mo_media_restriction_error_box'><b>Please give write permissions.</b></div>";
							}
						} else {
							echo "<div class='mo_media_restriction_error_box'><b>Enable media restriction for logged in user first.</b></div>";
						}
					} else {
						update_option( 'mo_media_restriction_show_rules', 2 );
					}
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_file_upload' && isset( $_REQUEST['mo_media_restriction_file_upload_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_file_upload_field'] ) ), 'mo_media_restriction_file_upload_form' ) ) {
					$filename            = isset( $_FILES['fileToUpload']['name'] ) ? sanitize_file_name( wp_unslash( $_FILES['fileToUpload']['name'] ) ) : '';
					$extension_lowercase = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
					$whitelist           = array( 'pdf', 'png', 'jpg', 'doc', 'gif' );
					if ( ! $this->mo_media_restriction_check_empty_or_null( $filename ) && ! validate_file( $filename ) && in_array( $extension_lowercase, $whitelist, true ) ) {
						$upload_dir = wp_upload_dir();
						if ( $upload_dir && isset( $upload_dir['basedir'] ) ) {
							$base_upload_dir = $upload_dir['basedir'];
							$protectedfiles  = $base_upload_dir . DIRECTORY_SEPARATOR . 'protectedfiles';
							if ( false !== $upload_dir['error'] ) {
								echo "<div class='mo_media_restriction_error_box'><b style='color:red'>" . esc_attr( $upload_dir['error'] ) . '</b></div>';
							} else {
								if ( ! file_exists( $protectedfiles ) && ! is_dir( $protectedfiles ) ) {
									wp_mkdir_p( $protectedfiles, 0775, true );
								}
								$target_file = $protectedfiles . DIRECTORY_SEPARATOR . basename( $filename );
								if ( isset( $_FILES['fileToUpload']['tmp_name'] ) && ! empty( $_FILES['fileToUpload']['tmp_name'] ) ) {
									// The 'tmp_name' index exists and is not empty, so we can use it safely.
									move_uploaded_file( sanitize_text_field( $_FILES['fileToUpload']['tmp_name'] ), $target_file ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Here we are setting a folder path based on slashes so please ignore ulslash.
									echo "<div class='mo_media_restriction_success_box'><b>File uploaded successfully.</b></div>";
								} else {
									echo "<div class='mo_media_restriction_error_box'><b>Error uploading the file.</b></div>";
								}
							}
						} else {
							echo "<div class='mo_media_restriction_error_box'><b>Directory doesn\'t exist.</b></div>";
						}
					} else {
						echo "<div class='mo_media_restriction_error_box'><b>Invalid file name.</b></div>";
					}
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_contact_us' && isset( $_REQUEST['mo_media_restriction_contact_us_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_contact_us_field'] ) ), 'mo_media_restriction_contact_us_form' ) ) {
					// contact us.
					if ( isset( $_POST['mo_media_restriction_contact_us_email'] ) ) {
						$email = sanitize_email( wp_unslash( $_POST['mo_media_restriction_contact_us_email'] ) );
					}
					if ( isset( $_POST['mo_media_restriction_contact_us_phone'] ) ) {
						$phone = '+ ' . preg_replace( '/[^0-9]/', '', sanitize_textarea_field( wp_unslash( $_POST['mo_media_restriction_contact_us_phone'] ) ) );
					}
					if ( isset( $_POST['mo_media_restriction_contact_us_query'] ) ) {
						$query = sanitize_textarea_field( wp_unslash( $_POST['mo_media_restriction_contact_us_query'] ) );
					}
					if ( $this->mo_media_restriction_check_empty_or_null( $email ) || $this->mo_media_restriction_check_empty_or_null( $query ) ) {
						echo '<br><b style=color:#ef2020d1>Please fill up Email and Query fields to submit your query.</b>';
					} else {
						$customer = new Miniorange_Media_Restriction_Customer();
						$submited = $customer->submit_contact_us( $email, $phone, $query );
						if ( false === $submited ) {
							echo '<div class="mo_media_restriction_error_box"><span class="icon-box-2"><img class="icon-image-2" src="' . esc_attr( plugin_dir_url( __FILE__ ) ) . '/images/mail.png" alt="error" height="20px" width = "20px"></span>
							<b>Unable to connect to Internet. Please try again.</b></div>';
						} else {
							echo '<div class="mo_media_restriction_success_box"><span class="icon-box-2"><img class="icon-image-2" src="' . esc_attr( plugin_dir_url( __FILE__ ) ) . '/images/tick.png" alt="success" height="20px" width = "20px"></span>
							<b>Thanks for your inquiry! We shall get back to you shortly.</b></div>';
						}
					}
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_delete_file' && isset( $_REQUEST['mo_media_restriction_delete_file_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_delete_file_field'] ) ), 'mo_media_restriction_delete_file_form' ) ) {
					$filename = isset( $_POST['mo_media_restrict_filename'] ) ? sanitize_file_name( wp_unslash( $_POST['mo_media_restrict_filename'] ) ) : '';
					if ( $this->mo_media_restriction_check_empty_or_null( $filename ) || 'none' === $filename ) {
						echo '<div class="mo_media_restriction_error_box"><b>Please select a file to submit your query.</b></div>';
					} else {
						if ( ! validate_file( $filename ) ) {
							$upload_dir = wp_upload_dir();
							if ( $upload_dir && isset( $upload_dir['basedir'] ) ) {
								$base_upload_dir = $upload_dir['basedir'];
								$protectedfiles  = $base_upload_dir . DIRECTORY_SEPARATOR . 'protectedfiles';
								if ( file_exists( $protectedfiles ) ) {
									if ( file_exists( $protectedfiles . DIRECTORY_SEPARATOR . $filename ) ) {
										wp_delete_file( $protectedfiles . DIRECTORY_SEPARATOR . $filename );
										echo '<div class="mo_media_restriction_success_box"><b>File deleted successfully.</b></div>';
									} else {
										echo '<div class="mo_media_restriction_error_box"><b>File doesn\'t exist.</b></div>';
									}
								} else {
									echo '<div class="mo_media_restriction_error_box"><b>Protected directory doesn\'t exist.</b></div>';
								}
							} else {
								echo '<div class="mo_media_restriction_error_box"><b>Upload directory doesn\'t exist.</b></div>';
							}
						} else {
							echo '<div class="mo_media_restriction_error_box"><b>Invalid file.</b></div>';
						}
					}
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_register_customer' && isset( $_REQUEST['mo_media_restriction_register_customer_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_register_customer_field'] ) ), 'mo_media_restriction_register_customer_form' ) ) {
					// validation and sanitization.
					$email            = '';
					$phone            = '';
					$password         = '';
					$confirm_password = '';
					$fname            = '';
					$lname            = '';
					$company          = '';
					if ( $this->mo_media_restriction_check_empty_or_null( sanitize_text_field( $_POST['mo_media_restriction_admin_email'] ) ) || $this->mo_media_restriction_check_empty_or_null( $_POST['mo_media_restriction_password'] ) || $this->mo_media_restriction_check_empty_or_null( $_POST['mo_media_restriction_confirm_password'] ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- As we are not storing password in the database, so we can ignore sanitization. Preventing use of sanitization in password will lead to removal of special characters.
						echo '<div class="mo_media_restriction_error_box"><b>All the fields are required. Please enter valid entries.</b></div>';
					} elseif ( strlen( $_POST['mo_media_restriction_password'] ) < 8 || strlen( $_POST['mo_media_restriction_confirm_password'] ) < 8 ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- As we are not storing password in the database, so we can ignore sanitization. Preventing use of sanitization in password will lead to removal of special characters.
						echo '<div class="mo_media_restriction_error_box"><b>Choose a password with minimum length 8.</b></div>';
					} else {
						$email            = ! empty( $_POST['mo_media_restriction_admin_email'] ) ? sanitize_email( wp_unslash( $_POST['mo_media_restriction_admin_email'] ) ) : '';
						$phone            = '';
						$password         = wp_unslash( $_POST['mo_media_restriction_password'] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization. Preventing use of sanitization in password will lead to removal of special characters.
						$confirm_password = wp_unslash( $_POST['mo_media_restriction_confirm_password'] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization. Preventing use of sanitization in password will lead to removal of special characters.
						$fname            = '';
						$lname            = '';
						$company          = '';
						update_option( 'mo_media_restriction_admin_email', $email );
						update_option( 'mo_media_restriction_admin_phone', $phone );
						update_option( 'mo_media_restriction_admin_fname', $fname );
						update_option( 'mo_media_restriction_admin_lname', $lname );
						update_option( 'mo_media_restriction_admin_company', $company );

						if ( 0 === strcmp( $password, $confirm_password ) ) {
							$customer = new Miniorange_Media_Restriction_Customer();
							$email    = get_option( 'mo_media_restriction_admin_email' );
							$content  = json_decode( $customer->check_customer(), true );

							if ( 0 === strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND' ) ) {
								$response = json_decode( $customer->create_customer( $password ), true );

								if ( 0 !== strcasecmp( $response['status'], 'SUCCESS' ) ) {
									echo '<div class="mo_media_restriction_error_box"><b>Failed to create customer. Try again.</b></div>';
								} else {
									echo '<div class="mo_media_restriction_success_box"><b>' . esc_attr( $response['message'] ) . '.</b></div>';
									update_option( 'mo_media_restriction_new_user', 'login' );
								}
							} elseif ( 0 === strcasecmp( $content['status'], 'SUCCESS' ) ) {
								update_option( 'mo_media_restriction_new_user', 'login' );
								echo '<div class="mo_media_restriction_error_box"><b>Account already exist. Please Login.</b></div>';
							} elseif ( is_null( $content ) ) {
								echo '<div class="mo_media_restriction_error_box"><b>Failed to create customer. Try again.</b></div>';
							} else {
								echo '<div class="mo_media_restriction_error_box"><b>' . esc_attr( $content['message'] ) . '.</b></div>';
							}
						} else {
							echo '<div class="mo_media_restriction_error_box"><b>Passwords do not match.</b></div>';
						}
					}
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_login_customer' && isset( $_REQUEST['mo_media_restriction_login_customer_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_login_customer_field'] ) ), 'mo_media_restriction_login_customer_form' ) ) {
					// validation and sanitization.
					$email    = '';
					$password = '';
					if ( $this->mo_media_restriction_check_empty_or_null( sanitize_text_field( $_POST['mo_media_restriction_admin_email'] ) ) || $this->mo_media_restriction_check_empty_or_null( $_POST['mo_media_restriction_password'] ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization. Preventing use of sanitization in password will lead to removal of special characters.
						echo '<div class="mo_media_restriction_error_box"><b>All the fields are required. Please enter valid entries.</b></div>';
					} else {
						$email    = sanitize_email( wp_unslash( $_POST['mo_media_restriction_admin_email'] ) );
						$password = wp_unslash( $_POST['mo_media_restriction_password'] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- As we are not storing password in the database, so we can ignore sanitization. Preventing use of sanitization in password will lead to removal of special characters.

						update_option( 'mo_media_restriction_admin_email', $email );
						$customer     = new Miniorange_Media_Restriction_Customer();
						$content      = $customer->get_customer_key( $password );
						$customer_key = json_decode( $content, true );
						if ( json_last_error() === JSON_ERROR_NONE && isset( $customer_key['status'] ) && 'SUCCESS' === $customer_key['status'] ) {
							update_option( 'mo_media_restriction_admin_customer_key', $customer_key['id'] );
							update_option( 'mo_media_restriction_admin_api_key', $customer_key['apiKey'] );
							update_option( 'customer_token', $customer_key['token'] );
							update_option( 'mo_media_restriction_admin_phone', isset( $customer_key['phone'] ) ? $customer_key['phone'] : '' );
							delete_option( 'password' );
							update_option( 'mo_media_restriction_new_user', 'account-setup' );
							echo '<div class="mo_media_restriction_success_box"><b>Customer retrieved successfully.</b></div>';
						} else {
							echo '<div class="mo_media_restriction_error_box"><b>Invalid username or password. Please try again.</b></div>';
						}
					}
				} elseif ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_r_demo_request_form' && isset( $_REQUEST['mo_media_r_demo_request_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_r_demo_request_field'] ) ), 'mo_media_r_demo_request_form' ) ) {
					$email     = ! empty( $_POST['mo_auto_create_demosite_email'] ) ? sanitize_email( wp_unslash( $_POST['mo_auto_create_demosite_email'] ) ) : '';
					$demo_plan = ! empty( $_POST['mo_auto_create_demosite_demo_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_auto_create_demosite_demo_plan'] ) ) : '';
					$query     = ! empty( $_POST['mo_auto_create_demosite_usecase'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_auto_create_demosite_usecase'] ) ) : '';
					if ( $this->mo_media_restriction_check_empty_or_null( $email ) || $this->mo_media_restriction_check_empty_or_null( $demo_plan ) || $this->mo_media_restriction_check_empty_or_null( $query ) ) {
						echo '<div class="mo_media_restriction_error_box"><b>All the fields are required. Please enter valid entries.</b></div>';
					} else {
						$customer = new Miniorange_Media_Restriction_Customer();
						$submited = $customer->mo_api_auth_send_demo_alert( $email, $demo_plan, $query, 'Trial: WordPress Prevent Files / Folders Access - ' . $email );
						if ( false === $submited ) {
							echo '<div class="mo_media_restriction_error_box"><b>Your query could not be submitted. Please try again.</b></div>';
						} else {
							echo '<div class="mo_media_restriction_success_box"><b>Thanks for getting in touch! We shall get back to you shortly.</b></div>';
						}
					}
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_change_to_login' && isset( $_REQUEST['mo_media_restriction_change_to_login_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_change_to_login_field'] ) ), 'mo_media_restriction_change_to_login' ) ) {
					// validation and sanitization.
					delete_option( 'mo_media_restriction_admin_customer_key' );
					delete_option( 'mo_media_restriction_admin_api_key' );
					delete_option( 'customer_token' );
					delete_option( 'mo_media_restriction_admin_phone' );
					delete_option( 'mo_media_restriction_admin_email' );
					update_option( 'mo_media_restriction_new_user', 'login' );
				} elseif ( sanitize_textarea_field( wp_unslash( $_POST['option'] ) ) === 'mo_media_restriction_change_to_register' && isset( $_REQUEST['mo_media_restriction_change_to_register_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_media_restriction_change_to_register_field'] ) ), 'mo_media_restriction_change_to_register' ) ) {
					// validation and sanitization.
					delete_option( 'mo_media_restriction_admin_customer_key' );
					delete_option( 'mo_media_restriction_admin_api_key' );
					delete_option( 'customer_token' );
					delete_option( 'mo_media_restriction_admin_phone' );
					delete_option( 'mo_media_restriction_admin_email' );
					update_option( 'mo_media_restriction_new_user', 'register' );
				} else {
					echo '<div class="mo_media_restriction_error_box"><b>Something went wrong please try again later.</b></div>';
				}
			}
		}

		mo_media_restrict_page_ui();
	}

	/**
	 * Nginx rules
	 */
	public function mo_media_restrict_write_nginx_rules() {
		$rule  = '';
		$rule .= 'location ~ .*(/protectedfiles) {<br>';
		$rule .= '&nbsp&nbsp&nbspreturn 301 $scheme://$http_host/?mo_media_restrict_request=1&redirect_to=$request_uri;<br>';
		$rule .= '&nbsp&nbsp&nbsp}<br>';
		return $rule;
	}

}
