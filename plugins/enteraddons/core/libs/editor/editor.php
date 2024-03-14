<?php 
namespace Enteraddons\Editor;

/**
 * Enteraddons editor class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Enteraddons_Editor {
	
	private static $instance = null;

	function __construct() {

		$this->includeFiles();

		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ), 1);
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'editor_preview_style' ) );
		add_action( 'elementor/editor/footer', array( $this, 'footer_scripts' ) );
		add_action( 'wp_ajax_editor_library_data', array( $this, 'library_data' ) );
		add_action( 'wp_ajax_nopriv_editor_library_data', array( $this, 'library_data' ) );

		// init import class
		new Import();

	}
	private function callAPI() {
		return new \Enteraddons\Classes\API();
	} 
	public static function getInstance() {

		if( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function includeFiles() {
		require_once( ENTERADDONS_DIR_CORE.'libs/editor/inc/class-source-base.php' );
		require_once( ENTERADDONS_DIR_CORE.'libs/editor/inc/class-import.php' );
	}

	public function editor_scripts() {
		wp_enqueue_style(
			'enteraddons-icons', 
			ENTERADDONS_DIR_ADMIN_ASSETS. 'css/enteraddons-icons.css', 
			array(), 
			'1.0.0',
			false
		);
		wp_enqueue_style(
			'enteraddons-editor', 
			ENTERADDONS_DIR_CORE_URL. 'libs/editor/css/editor.css', 
			array(), 
			'1.0.0',
			false
		);
		wp_enqueue_script(
			'vue-js',
			ENTERADDONS_DIR_CORE_URL.'libs/editor/js/vue.js', 
			array(),
			'3.2.36',
			false
		);
		wp_enqueue_script(
			'enteraddons-editor-script', 
			ENTERADDONS_DIR_CORE_URL. 'libs/editor/js/editor.js', 
			array( 'jquery', 'vue-js', 'elementor-editor' ), 
			'1.0',
			true
		);
		wp_localize_script(
			'enteraddons-editor-script',
			'enteraddonsGeteDitorData',
			array( 
				'api_source'   => ENTERADDONS_API_SOURCE, 
				'enter_nonce'   => wp_create_nonce( 'enteraddons-fig' ), 
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'version_type' => \Enteraddons\Classes\Helper::versionType()
			)
		);

	}
	public function editor_preview_style() {
		wp_enqueue_style(
			'enteraddons-editor-preview', 
			ENTERADDONS_DIR_CORE_URL. 'libs/editor/css/preview.css', 
			array(), 
			'1.0',
			false
		);
	}

	public function footer_scripts() {

		$folder = ENTERADDONS_DIR_CORE.'libs/editor/view';
		$files = list_files( $folder );
		if( !empty( $files ) ) {
			foreach( $files as $file ) {
				$name = wp_basename( $file, '.php' );
				ob_start();
				include_once( $folder.'/'.$name.'.php' );
				echo '<script id="enteraddons-'.esc_attr( $name ).'" type="text/html">'.ob_get_clean().'</script>';
			}
		}

	}

	public function library_data() {

		if ( ! current_user_can( 'edit_posts' ) || ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'enteraddons-fig' ) ) ) {
			die();
		}

		// get tab id
		if( isset( $_POST['id'] ) ) {
			$tabId = sanitize_text_field( $_POST['id'] );
		}

		$api = $this->callAPI();
		$endpoint = "templates-library/".$tabId;
		$remoteUrl = $this->get_remote_url( esc_html( $endpoint ) );
		$response = $api->getRemote( $remoteUrl );
		//
		if( !empty( $response['body'] ) ) {
			echo apply_filters( 'enteraddons_library_data', $response['body'] );
		}

		exit;
	}

	public function get_remote_url( $endpoint ) {
		$api = $this->callAPI();
		return $api->get_api_url( esc_html( $endpoint ) );
	}

}

Enteraddons_Editor::getInstance();