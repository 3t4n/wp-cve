<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://payamito.com/
 * @since      1.0.0
 * @package    Payamito
 * @subpackage Payamito/admin
 */

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Payamito
 * @subpackage Payamito/admin
 * @author     payamito <payamito@gmail.com>
 */
if ( ! class_exists( "Payamito_Admin" ) ) {
	class Payamito_Admin
	{

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version     The version of this plugin.
		 *
		 * @since    1.0.0
		 */
		public function __construct( $plugin_name, $version )
		{
			$this->plugin_name = $plugin_name;
			$this->version     = $version;

			add_action( 'plugins_loaded', [ $this, 'load_admin_pages' ] );
			add_action( 'admin_menu', [ $this, 'log_submenu' ], 999 );
			add_action( 'wp_ajax_nopriv_payamito_export', [ $this, 'export' ] );
			add_action( 'wp_ajax_payamito_export', [ $this, 'export' ] );
			add_action( 'wp_ajax_init_ajax', [ $this, 'payamito_init_ajax' ] );
		}

		public function export()
		{
			if ( ! isset( $_POST['nonce'] ) || wp_verify_nonce( $_POST['nonce'] ) ) {
				die();
			}

			$status   = isset( $_POST['status'] ) && $_POST['status'] == 'all' ? null : sanitize_text_field( $_POST['status'] );
			$reciever = @empty( trim( $_POST['reciever'] ) ) ? null : sanitize_text_field( $_POST['reciever'] );
			$limit    = @empty( trim( $_POST['limit'] ) ) ? null : sanitize_text_field( $_POST['limit'] );
			$method   = isset( $_POST['method'] ) && $_POST['method'] == 'all' ? null : sanitize_text_field( $_POST['method'] );
			$slug     = isset( $_POST['operative'] ) && $_POST['operative'] == 'all' ? null : sanitize_text_field( $_POST['operative'] );
			$where    = [ 'status' => $status, 'reciever' => $reciever, 'method' => $method, 'slug' => $slug ];
			if ( ! is_numeric( $limit ) || $limit > 999999 || $limit < 0 ) {
				$limit = 100;
			}
			if ( ! isset( $_POST['columns'] ) ) {
				$col = [ "*" ];
			} else {
				$col = array_map( "sanitize_text_field", $_POST['columns'] );
			}

			$result = Payamito_DB::select( Payamito_DB::table_name(), $where, $limit, $col );
			if ( count( $result ) == 0 ) {
				wp_send_json( [
					'download' => false,
					'messege'  => __( "There is no records", 'payamito' ),
					'payamito',
				] );

				die;
			}

			$header = Payamito_Sent_List_Table::XLSX_set_header( "payamito_sms", $col );
			Payamito_Sent_List_Table::XLSXWriter( $result, $header );

			wp_send_json( [
				'file_name' => Payamito_Sent_List_Table::$file_name,
				'download'  => true,
				'messege'   => __( "Downloading", 'payamito' ),
				'payamito',
			] );
			die;
		}

		public function log_submenu()
		{
			add_submenu_page( 'payamito', __( 'Payamito logs', 'payamito' ), __( 'Logs', 'payamito' ), 'manage_options', 'payamito_logs', [
				&$this,
				'render_table',
			] );
		}

		public function render_table()
		{
			$table = new Payamito_Sent();

			$table->table();
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles()
		{
			/**
			 * This function is provided for demonstration purposes only.
			 * An instance of this class should be passed to the run() function
			 * defined in Payamito_Loader as all of the hooks are defined
			 * in that particular class.
			 * The Payamito_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			$page = isset( $_GET['page'] ) && in_array( $_GET['page'], [ 'payamito', 'payamito_logs' ] ) ? true : false;
			if ( $page === true ) {
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/payamito-admin.css', [], $this->version, 'all' );

				if ( is_rtl() ) {
					$custom_css = ".kianfr-nav-normal+.kianfr-content{margin-left: 0px !important;}";
					wp_add_inline_style( $this->plugin_name, $custom_css );
				}
			}
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts()
		{
			/**
			 * This function is provided for demonstration purposes only.
			 * An instance of this class should be passed to the run() function
			 * defined in Payamito_Loader as all of the hooks are defined
			 * in that particular class.
			 * The Payamito_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			$page = isset( $_GET['page'] ) && in_array( $_GET['page'], [ 'payamito', 'payamito_logs' ] ) ? true : false;
			if ( $page === true ) {
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/payamito-admin.js', [ 'jquery' ], $this->version, false );

				wp_enqueue_script( "payamito-init-js", plugin_dir_url( __FILE__ ) . "js/payamito-init.js", [ 'jquery' ], $this->version, true );

				wp_localize_script( 'payamito-init-js', 'payanitoObject', [
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'logsUrl' => admin_url( 'admin.php?page=payamito_logs' ),
				] );
			}
		}

		/**
		 * @since    1.0.0
		 */
		public function load_admin_pages()
		{
			require_once PAYAMITO_ADMIN . 'settings/register_setting.php';
			require_once PAYAMITO_ADMIN . 'settings/summary-box.php';

			\payamito\admin\register_setting::instance();
		}

		public function payamito_init_ajax()
		{
			$method = sanitize_text_field( $_GET["method"] );
			switch ( $method ) {
				case "statistics":
					$crediet = payamito_code_to_message( payamito_get_crediet() );
					if ( is_numeric( $crediet ) ) {
						$crediet = round( $crediet );
					}
					$statistics = $this->send_statistics( [ - 1, 0, 7, 30 ] );
					wp_send_json( [ 'statistics' => $statistics, 'crediet' => $crediet ] );
					break;
				case "connect":
					$crediet = payamito_code_to_message( payamito_get_crediet() );
					$title   = __( "Connection test", "payamito" );
					if ( is_numeric( $crediet ) ) {
						$m      = __( "connection", "payamito" );
						$type   = "success";
						$status = '1';
					} else {
						$m = $crediet;;
						$type   = "error";
						$status = '0';
					}

					wp_send_json( [
						'connect' => $m,
						'status'  => $status,
						"title"   => $title,
						"type"    => $type,
						"btn"     => __( "OK", "payamito" ),
					] );
					break;
			}
		}

		public function send_statistics( array $days )
		{
			global $wpdb;
			$result       = [];
			$table_name   = $wpdb->prefix . 'payamito_sms';
			$data         = array_column( Payamito_DB::select( $table_name, [ 'status' => 1 ], null, [ 'date' ], null ), 'date' );
			$data         = array_map( function ( $string ) {
				return substr( $string, 0, 10 );
			}, $data );
			$current_time = current_time( 'timestamp', 0 );
			foreach ( $days as $day ) {
				if ( is_array( $data ) && is_countable( $data ) && count( $data ) > 0 ) {
					switch ( $day ) {
						case - 1:
							//all sends count
							$result[ $day ] = count( $data );
							break;
						case 0:
							$today= date( 'Y-m-d', strtotime( '0day', $current_time ) );
							$result[ $day ] = count( array_filter( $data, function ( $value ) use ( $today ) {
								return $value == $today;
							} ) );
							break;
						default:
							$x       = date( 'Y-m-d', strtotime( '-' . $day . 'day', $current_time ) );
							$counter = 0;
							foreach ( $data as $value ) {
								if ( $value >= $x ) {
									$counter ++;
								}
							}
							$result[ $day ] = $counter;
					}
				} else {
					$result[ $day ] = 0;
				}
			}

			return $result;
		}
	}
}
