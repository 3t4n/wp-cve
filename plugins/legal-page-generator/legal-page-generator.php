<?php
/*
Plugin Name: Legal Page Generator
Description: Generates legal pages for websites, such as: Terms & Condition, Privacy Policy and Disclaimer.
Version: 1.2
Author: Moulik Sheth
Author URL: https://profiles.wordpress.org/dhiraj050
Text Domain: legal-page-generator
License: GLPv2 or Later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') or die();

if ( ! class_exists( 'LegalPageGenerator' ) ) {
	class LegalPageGenerator {
		public $templates_dir = null;
		public $includes_dir = null;
		public $supported_pages = null;
		public $website_data = null;
		public $website_data_validation = null;
		public static $mail_address = 'vakil.dhiraj@gmail.com';

		public function __construct() {
			// Action hooks
			add_action( 'admin_menu', array( $this, 'add_plugin_menues' ) );
			add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
			add_action( 'admin_post_lpg_extra_request', array( $this, 'extra_request' ) );
			add_action( 'update_option', array( $this, 'updated_settings') );
			add_action( 'add_option', array( $this, 'updated_settings') );
			register_activation_hook( __FILE__, array( $this, 'plugin_installed' ) );
			register_uninstall_hook( __FILE__, array( __CLASS__, 'plugin_uninstalled' ) );
			
			// Initialize class variables
			$this->includes_dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR;
			$this->templates_dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
			$this->supported_pages = array(
				'terms_and_conditions' => __( 'Terms & Conditions', 'legal-page-generator' ),
				'privacy_policy' => __( 'Privacy Policy', 'legal-page-generator' ),
				'disclaimer' => __( 'Disclaimer', 'legal-page-generator' )
			);
			$this->website_data = array(
				'company_name' => __( 'Company Name', 'legal-page-generator' ),
				'name' => __( 'Contact Person Name' ),
				'mobile_no' => __( 'Mobile No.', 'legal-page-generator' ),
				'email' => __( 'Email', 'legal-page-generator' ),
				'address' => __( 'Address', 'legal-page-generator' ),
				'services' =>__( 'Services', 'legal-page-generator' ),
				'site_actas' => __( 'Your site act as', 'legal-page-generator' ),
				'designation' => __( 'Designation', 'legal-page-generator' )
			);
			$this->website_data_validation = array(
				'company_name' => array(
					'type' => 'text',
					'pattern' => '[A-Za-z0-9][A-Za-z0-9\s]+[A-Za-z0-9]',
					'maxlength' => 25,
					'message' => __( 'This field must be alpha-numeric, 25 characters at max.', 'legal-page-generator' )
				),
				'name' => array(
					'type' => 'text',
					'pattern' => '[A-Za-z][A-Za-z\s]+[A-Za-z]',
					'maxlength' => 20,
					'message' => __( 'This field must only contain alphabetic characters, 20 chars at max.', 'legal-page-generator' )
				),
				'mobile_no' => array(
					'type' => 'text',
					'pattern' => '\d{10}',
					'length' => 10,
					'message' => __( 'This field must contain 10 numbers', 'legal-page-generator' )
				),
				'email' => array(
					'type' => 'email',
					'message' => __( 'This field must a valid email address', 'legal-page-generator' )
				),
				'address' => array(
					'type' => 'text',
					'pattern' => '[A-Za-z0-9][A-Za-z0-9\s]+[A-Za-z0-9]',
					'maxlength' => 50,
					'message' => __( 'This field must be alpha-numeric, 50 characters at max.', 'legal-page-generator' )
				),
				'services' => array(
					'type' => 'text',
					'pattern' => '[A-Za-z][A-Za-z\s]+[A-Za-z]',
					'length' => 30,
					'message' => __( 'This field must only contain alphabetic characters, 30 characters at max.', 'legal-page-generator' )
				),
				'site_actas' => array(
					'type' => 'text',
					'pattern' => '[A-Za-z][A-Za-z\s]+[A-Za-z]',
					'maxlength' => 30,
					'message' => __( 'This field must only contain alphabetic characters, 30 characters at max.', 'legal-page-generator' )
				),
				'designation' => array(
					'type' => 'text',
					'pattern' => '[A-Za-z][A-Za-z\s]+[A-Za-z]',
					'maxlength' => 30,
					'message' => __( 'This field must only contain alphabetic characters, 30 characters at max.', 'legal-page-generator' )
				)
			);
		}

		public function add_plugin_menues() {
			add_menu_page( __( 'Legal Page Generator', 'legal-page-generator' ), __( 'Legal Page Generator', 'legal-page-generator' ), 'manage_options', 'legal-page-generator', array( $this, 'main_page' ), 'dashicons-admin-page' );
			add_submenu_page( 'legal-page-generator', __( 'Main', 'legal-page-generator' ), __( 'Main', 'legal-page-generator' ), 'manage_options', 'legal-page-generator', array( $this, 'main_page' ) );
			add_submenu_page( 'legal-page-generator', __( 'Manage Pages', 'legal-page-generator' ), __( 'Manage Pages', 'legal-page-generator' ), 'manage_options', 'legal-page-generator-pages', array( $this, 'manage_pages' ) );
			add_submenu_page( 'legal-page-generator', __( 'Custom Request', 'legal-page-generator' ), __( 'Custom Request', 'legal-page-generator' ), 'manage_options', 'legal-page-generator-cr', array( $this, 'custom_request' ) );
		}

		public function main_page() {
			$notices = trim( get_option( 'lpg_admin_notices', '') );
			if ( $notices == '' ) {
				$notices = array();
			} else {
				$notices = json_decode( $notices, true );
				if ( ! $notices ) {
					$notices = array();
				}
			}
			update_option( 'lpg_admin_notices', '');
			require_once( $this->includes_dir . 'main.php' );
		}

		public function manage_pages() {
			if ( ! $this->is_site_info_set() ) {
				require_once( $this->includes_dir . 'configure-first.php' );
			} else {
				if ( isset( $_REQUEST['remove'] ) ) {
					$page_name = sanitize_text_field( $_REQUEST['remove'] );
					if ( array_key_exists( $page_name, $this->supported_pages ) ) {
						$page_id = (int) get_option( 'lpg_' . $page_name . '_page_id', 0 );
						update_option( 'lpg_' . $page_name . '_page_id', '' );
						wp_delete_post( $page_id, true );
					}
				}
				require_once( $this->includes_dir . 'manage-pages.php' );
			}
		}

		public function custom_request() {
			if ( ! $this->is_site_info_set() ) {
				require_once( $this->includes_dir . 'configure-first.php' );
			} else {
				require_once( $this->includes_dir . 'custom-request.php' );
			}
		}

		public function register_plugin_settings() {
			// Existing supported pages IDs
			foreach ( $this->supported_pages as $page_name => $page_title ) {
				register_setting( 'legal_page_generator_optsgroup_1', 'lpg_' . $page_name . '_page_id' );
			}
			// Generator's settings (Company info)
			foreach ( $this->website_data as $field_name => $field_label ) {
				register_setting( 'legal_page_generator_optsgroup_2', 'lpg_' . $field_name );
			}
			register_setting( 'legal_page_generator_optsgroup_2', 'lpg_saving_settings' );
			register_setting( 'legal_page_generator_optsgroup_3', 'lpg_installed' );
			register_setting( 'legal_page_generator_optsgroup_3', 'lpg_admin_notices' );
		}

		public function do_replacements( $content = '' ) {
			$search = array();
			$replace = array();
			foreach ( $this->website_data as $field_name => $field_label ) {
				array_push( $search, '%{' . $field_name . '}' );
				array_push( $replace, get_option( 'lpg_' . $field_name, '-' ) );
			}
			return str_replace( $search, $replace, $content);
		}
		public function generate_pages() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$notices = array();
			foreach( $this->supported_pages as $page_name => $page_title ) {
				$file = $this->templates_dir  . $page_name . '.html';
				if ( ! file_exists( $file ) ) {
					return;
				}
				$page_id = (int) get_option( 'lpg_' . $page_name . '_page_id', 0 );
				$content = file_get_contents( $file );
				$page_content = $this->do_replacements( $content );
				$post_status = get_post_status( $page_id );
				if ( $page_id > 0 && $post_status && $post_status != 'trash' ) {
					$r = wp_update_post( array(
						'ID' => $page_id,
						'post_content' => $page_content,
						'post_title' => $page_title
					), true );
					if ( ! is_wp_error( $r ) ) {
						array_push( $notices, array(
							'type' => 'success',
							'message' => '<strong>' . $page_title . '</strong> ' . __( 'page has been updated', 'legal-page-generator' ) . ' <a href="?page=legal-page-generator-pages">' . __( 'Go to Page Manager', 'legal-page-generator' ) . '</a>'
						) );
					}
				} else {
					$page_id = wp_insert_post( array(
						'post_content' => $page_content,
						'post_title' => $page_title,
						'post_type' => 'page',
						'post_status' => 'publish'
					), true );
					if ( ! is_wp_error( $page_id ) ) {
						array_push( $notices, array(
							'type' => 'success',
							'message' => '<strong>' . $page_title . '</strong> ' . __( 'page has been generated', 'legal-page-generator' ) . ' <a href="?page=legal-page-generator-pages">' . __( 'Go to Page Manager', 'legal-page-generator' ) . '</a>'
						) );
					}
				}
				update_option( 'lpg_' . $page_name . '_page_id', $page_id );
				update_option( 'lpg_admin_notices', json_encode( $notices ) );
			}
			$this->notify_page_generation();
		}

		public function extra_request() {
			$errors = array();
			$sendername = isset( $_POST['sendername'] ) ? sanitize_text_field( $_POST['sendername'] ) : '';
			$senderemail = isset( $_POST['senderemail'] ) ? sanitize_email( $_POST['senderemail'] ) : '';
			$senderphone = isset( $_POST['senderphone'] ) ? sanitize_text_field( $_POST['senderphone'] ) : '';
			if ( $sendername == '' || $senderemail == '' ) {
				array_push( $errors , __( 'Please input your name and email address', 'legal-page-generator' ) );
				goto bottom;
			}
			$subject = __( 'Someone has made a legal documents request', 'legal-page-generator' );
			$headers = array(
				'Content-Type: text/html; charset=UTF-8',
				'Reply-To: ' . $sendername . ' <' . $senderemail . '>'
			);
			$body = '<div><strong>' . __( 'Requester\'s Name', 'legal-page-generator' ) . '</strong>: ' . $sendername . '</div>';
			$body .= '<div><strong>' . __( 'Requester\'s Email Address', 'legal-page-generator' ) . '</strong>: ' . $senderemail . '</div>';
			$body .= '<div><strong>' . __( 'Requester\'s Phone Number', 'legal-page-generator' ) . '</strong>: ' . $senderphone . '</div>';
			$body .= '<p>' . __( 'Find below the requested documents', 'legal-page-generator' ) . '</p>';
			foreach( $_POST as $key => $val ){
				if( ! empty( $val ) && $key != 'sendername' && $key != 'senderemail' && $key != 'senderphone' && $key != 'submitrequest' ) {
					$body .= '<div><strong>' . ucwords( str_replace( '_', ' ', $key ) ) . ':</strong> ';
					if ( gettype( $val ) == 'array' ) {
						$val = implode( ', ', $val );
					}
					$body .= $val . '</div>';
				}
			}
			$body .= '<p>' . __( 'COMPANY INFO', 'legal-page-generator' ) . ':</p>';
			foreach( $this->website_data as $field_name => $field_label ) {
				$body .= '<div><strong>' . $field_label . '</strong>: ' . get_option( 'lpg_' . $field_name, '-' ) . '</div>';
			}
			wp_mail( self::$mail_address, $subject, $body, $headers );
			bottom:
			require_once( $this->includes_dir . 'mail-send.php' );
		}

		public function plugin_installed() {
			$lpg_installed = (int) get_option( 'lpg_installed', 0 );
			if ( $lpg_installed == 0 ) {
				update_option( 'lpg_installed', 1 );
				wp_mail(
					self::$mail_address,
					__( 'Legal Page Geneator - New installation ', 'legal-page-generator' ),
					__( 'Legal Page Generator Plugin was just installed on a website: ', 'legal-page-generator' ) . get_site_url()
				);
			}
		}

		public static function plugin_uninstalled() {
			delete_option( 'lpg_installed' );
			wp_mail(
				self::$mail_address,
				__( 'Legal Page Geneator - Plugin Uninstallation', 'legal-page-generator' ),
				__( 'Legal Page Generator Plugin was just uninstalled on a website: ', 'legal-page-generator' ) . get_site_url()
			);
		}

		public function is_site_info_set( $value = true ) {
			foreach( $this->website_data as $field_name => $field_label ) {
				$setting = get_option( 'lpg_' . $field_name, '' );
				if ( trim( $setting ) == '' ) {
					$value = false;
				}
			}
			return $value;
		}

		public function updated_settings( $option ) {
			if ( $option != 'lpg_saving_settings' ) {
				return;
			}
			$this->generate_pages();
		}
		public function notify_page_generation() {
			$subject = __( 'Someone has generated legal documents', 'legal-page-generator' );
			$headers = array(
				'Content-Type: text/html; charset=UTF-8',
			);
			$body = __( 'Someone has used the "Legal Page Generator" plugin to generate legal pages on their website. Find the details below', 'legal-page-generator' ) . ':';
			$body .= '<p>' . __( 'WEBSITE INFO', 'legal-page-generator' ) . ':</p>';
			$body .= '<div><strong>' . __( 'Website Name', 'legal-page-generator' ) . '</strong>: ' . get_bloginfo( 'name' ) . '</div>';
			$body .= '<div><strong>' . __( 'Website URL', 'legal-page-generator' ) . '</strong>: ' . get_bloginfo( 'url' ) . '</div>';
			$body .= '<p>' . __( 'COMPANY INFO', 'legal-page-generator' ) . ':</p>';
			foreach( $this->website_data as $field_name => $field_label ) {
				$field_value = isset( $_REQUEST['lpg_' . $field_name] ) ? $_REQUEST['lpg_' . $field_name] : '';
				$body .= '<div><strong>' . $field_label . '</strong>: ' . $field_value . '</div>';
			}
			wp_mail( self::$mail_address, $subject, $body, $headers );
		}
	}
}
new LegalPageGenerator();
?>