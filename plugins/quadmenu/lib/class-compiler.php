<?php

namespace QuadLayers\QuadMenu;

use QuadLayers\QuadMenu\Plugin;
use QuadLayers\QuadMenu\Redux;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Compiler Class ex QuadMenu_Compiler
 */
class Compiler {

	public $redux = '';
	public $args  = array();
	public static $instance;

	public function __construct() {
		add_filter( 'quadmenu_global_js_data', array( $this, 'js_data' ) );

		add_action( 'init', array( $this, 'activation' ) );
		add_action( 'redux/page/' . QUADMENU_DB_OPTIONS . '/enqueue', array( $this, 'enqueue' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/ajax_save/response', array( $this, 'developer_variables' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/ajax_save/response', array( $this, 'compile_variables' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/compiler', array( $this, 'compiler' ), 5, 3 );
		add_action( 'wp_ajax_quadmenu_compiler_save', array( $this, 'compiler_save' ) );
		add_action( 'wp_ajax_nopriv_quadmenu_compiler_save', array( $this, 'compiler_save' ) );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function activation() {
		if ( ! get_transient( '_quadmenu_activation' ) ) {
			return;
		}

		self::do_compiler( true );
	}

	function js_data( $data ) {

		global $quadmenu;

		$data['debug'] = QUADMENU_DEV;

		if ( $compiler = $this->run_compiler() ) {
			$data['variables'] = self::less_variables( $quadmenu );
			$data['compiler']  = $compiler;
		}

		$data['files']   = apply_filters( 'quadmenu_compiler_files', array() );
		$data['nonce']   = wp_create_nonce( 'quadmenu' );
		$data['ajaxurl'] = admin_url( 'admin-ajax.php' );

		return $data;
	}

	public function enqueue() {
		$compiler = include QUADMENU_PLUGIN_DIR . 'build/compiler/index.asset.php';

		wp_enqueue_script( 'quadmenu-compiler', QUADMENU_PLUGIN_URL . 'build/compiler/index.js', $compiler['dependencies'], $compiler['version'], true );
		wp_localize_script( 'quadmenu-compiler', 'quadmenu', apply_filters( 'quadmenu_global_js_data', array() ) );
	}

	function developer_variables( $return_array ) {

		if ( is_array( $return_array ) ) {
			$return_array['options'] = apply_filters( 'quadmenu_developer_options', $return_array['options'] );
		}

		return $return_array;
	}

	function compile_variables( $return_array ) {

		if ( is_array( $return_array ) ) {
			$return_array['variables'] = self::less_variables( $return_array['options'] );
		}

		return $return_array;
	}

	public function compiler_save() {
		if ( ! check_ajax_referer( 'quadmenu', 'nonce', false ) ) {
			Plugin::send_json_error( esc_html__( 'Please reload page.', 'quadmenu' ) );
		}

		$return_array = array( 'status' => 'error' );

		if ( ! isset( $_REQUEST['output']['imports'][0] ) ) {
			Redux::add_notification( 'red', esc_html__( 'Imports is undefined.', 'quadmenu' ) );
			wp_die();
		}

		if ( ! isset( $_REQUEST['output']['css'] ) ) {
			Redux::add_notification( 'red', esc_html__( 'CSS is undefined.', 'quadmenu' ) );
			wp_die();
		}

		$file_ext = pathinfo( $_REQUEST['output']['imports'][0], PATHINFO_EXTENSION );

		if ( ! in_array( $file_ext, array( 'less', 'css' ) ) ) {
			wp_die( 'Cheating?' );
		}

		$return_array['status'] = 'success';

		$file_name = str_replace( ".{$file_ext}", '.css', basename( $_REQUEST['output']['imports'][0] ) );

		try {
			$this->save_file( $file_name, QUADMENU_UPLOAD_DIR, stripslashes( $_REQUEST['output']['css'] ) );
		} catch ( Exception $e ) {
			$return_array['status'] = $e->getMessage();
		}

		ob_start();

		Redux::notification_bar();

		$notification_bar = ob_get_contents();

		ob_end_clean();

		$return_array['notification_bar'] = $notification_bar;

		self::do_compiler( false );

		echo json_encode( $return_array );

		wp_die();
	}

	public static function do_compiler( $run = true ) {

		if ( $run ) {
			update_option( '_quadmenu_compiler', $run );
		} else {
			delete_option( '_quadmenu_compiler' );
		}
	}

	public function run_compiler() {
		return (int) get_option( '_quadmenu_compiler', false );
	}

	public function compiler( $options, $css, $changed ) {

		self::do_compiler( true );

		Redux::add_notification( 'yellow', sprintf( esc_html__( 'Some style options have been changed. Your stylesheet will be compiled to reflect changes. %s.', 'quadmenu' ), esc_html__( 'Please wait', 'quadmenu' ) ) );
	}

	public function save_file( $name = false, $dir = false, $content = false ) {

		if ( ! $name || ! $dir || ! $content ) {
			return;
		}

		if ( ! class_exists( '\\ReduxFrameworkInstances' ) ) {
			Redux::add_notification( 'error', esc_html__( 'ReduxFramework is not installed', 'quadmenu' ) );
			return;
		}

		$this->redux = \ReduxFrameworkInstances::get_instance( QUADMENU_DB_OPTIONS );

		// Check if file exists ------------------------------------------------.
		$is_file = is_file( trailingslashit( $dir ) . $name );

		// Create the folder ---------------------------------------------------.
		if ( ! is_dir( $dir ) ) {
			$this->redux->filesystem->execute( 'mkdir', $dir );
			Redux::add_notification( 'yellow', sprintf( esc_html__( 'Folder created : %1$s', 'quadmenu' ), $dir ) );
		}

		// Write file ----------------------------------------------------------.
		if ( $this->redux->filesystem->execute( 'put_contents', trailingslashit( $dir ) . $name, array( 'content' => $content ) ) ) {
			Redux::add_notification( 'green', sprintf( esc_html__( 'File has been %2$s : %1$s', 'quadmenu' ), trailingslashit( $dir ) . $name, $is_file ? esc_html__( 'updated', 'quadmenu' ) : esc_html__( 'created', 'quadmenu' ) ) );
			return;
		}

		Redux::add_notification( 'error', sprintf( esc_html__( 'File cant\'t been created : %1$s', 'quadmenu' ), trailingslashit( $dir ) . $name ) );
	}

	public static function less_variables( &$data, $header = '' ) {

		$html = array(); // Themes::less_themes();

		if ( ! is_array( $data ) ) {
			return $data;
		}

		if ( isset( $data['styles'] ) ) {
			unset( $data['styles'] );
		}

		if ( isset( $data['styles_normalize'] ) ) {
			unset( $data['styles_normalize'] );
		}

		if ( isset( $data['styles_widgets'] ) ) {
			unset( $data['styles_widgets'] );
		}

		if ( isset( $data['styles_pscrollbar'] ) ) {
			unset( $data['styles_pscrollbar'] );
		}

		if ( isset( $data['styles_owlcarousel'] ) ) {
			unset( $data['styles_owlcarousel'] );
		}

		// if (isset($data['styles_icons'])) {
		// unset($data['styles_icons']);
		// }

		if ( isset( $data['viewport'] ) ) {
			unset( $data['viewport'] );
		}

		if ( isset( $data['css'] ) ) {
			unset( $data['css'] );
		}

		if ( isset( $data['social'] ) ) {
			unset( $data['social'] );
		}

		if ( isset( $data['errors'] ) ) {
			unset( $data['errors'] );
		}

		if ( isset( $data['warnings'] ) ) {
			unset( $data['warnings'] );
		}

		if ( isset( $data['notification_bar'] ) ) {
			unset( $data['notification_bar'] );
		}

		foreach ( $data as $key => &$val ) {

			$value = ( $key != 'font-options' ) ? $val : '';

			$value = ( filter_var( $value, FILTER_VALIDATE_URL ) ) ? "'{$value}'" : $value;

			if ( is_array( $value ) ) {
				$html = array_merge( $html, self::less_variables( $value, "{$key}_" ) );
			} elseif ( $value != '' ) {
				$html[ "@{$header}{$key}" ] = $value;
			} else {
				$html[ "@{$header}{$key}" ] = 0;
			}
		}

		return $html;
	}

	function redux_compiler( $return_array = array() ) {

		global $quadmenu;

		if ( is_array( $return_array ) ) {
			$return_array['options']   = apply_filters( 'quadmenu_developer_options', $quadmenu );
			$return_array['variables'] = self::less_variables( $return_array['options'] );
		}

		return $return_array;
	}
}

