<?php

namespace LIBRARY;

class BorderlessLibraryImporter {
	
	private static $instance;	
	public $importer;	
	public $plugin_installer;	
	private $plugin_page;	
	public $import_files;	
	public $log_file_path;	
	private $selected_index;	
	private $selected_import_files;	
	public $frontend_error_messages = array();	
	private $before_import_executed = false;	
	private $plugin_page_setup = array();	
	private $imported_terms = array();
	
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	protected function __construct() {
		add_action( 'admin_menu', array( $this, 'create_plugin_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_library_upload_manual_import_files', array( $this, 'upload_manual_import_files_callback' ) );
		add_action( 'wp_ajax_library_import_demo_data', array( $this, 'import_demo_data_ajax_callback' ) );
		add_action( 'wp_ajax_library_import_customizer_data', array( $this, 'import_customizer_data_ajax_callback' ) );
		add_action( 'wp_ajax_library_after_import_data', array( $this, 'after_all_import_data_ajax_callback' ) );
		add_action( 'after_setup_theme', array( $this, 'setup_plugin_with_filter_data' ) );
		add_action( 'user_admin_notices', array( $this, 'start_notice_output_capturing' ), 0 );
		add_action( 'admin_notices', array( $this, 'start_notice_output_capturing' ), 0 );
		add_action( 'all_admin_notices', array( $this, 'finish_notice_output_capturing' ), PHP_INT_MAX );
		add_action( 'admin_init', array( $this, 'redirect_from_old_default_admin_page' ) );
		add_action( 'set_object_terms', array( $this, 'add_imported_terms' ), 10, 6 );
	}


	
	private function __clone() {}
	public function __wakeup() {}


	public function create_plugin_page() {
		$this->plugin_page_setup = Helpers::get_plugin_page_setup_data();

		$this->plugin_page = add_submenu_page(
			$this->plugin_page_setup['parent_slug'],
			$this->plugin_page_setup['page_title'],
			$this->plugin_page_setup['menu_title'],
			$this->plugin_page_setup['capability'],
			$this->plugin_page_setup['menu_slug'],
			Helpers::apply_filters( 'library/plugin_page_display_callback_function', array( $this, 'display_plugin_page' ) )
		);

		add_submenu_page(
			'',
			$this->plugin_page_setup['page_title'],
			$this->plugin_page_setup['menu_title'],
			$this->plugin_page_setup['capability'],
			'pt-library'
		);

		register_importer( $this->plugin_page_setup['menu_slug'], $this->plugin_page_setup['page_title'], $this->plugin_page_setup['menu_title'], Helpers::apply_filters( 'library/plugin_page_display_callback_function', array( $this, 'display_plugin_page' ) ) );
	}

	public function display_plugin_page() {

		if ( isset( $_GET['step'] ) && 'install-plugins' === $_GET['step'] ) {
			require_once BORDERLESS__LIBRARY__DIR . 'views/install-plugins.php';

			return;
		}

		if ( isset( $_GET['step'] ) && 'import' === $_GET['step'] ) {
			require_once BORDERLESS__LIBRARY__DIR . 'views/import.php';

			return;
		}

		require_once BORDERLESS__LIBRARY__DIR . 'views/plugin-page.php';
	}

	public function admin_enqueue_scripts( $hook ) {
		if ( $this->plugin_page === $hook || ( 'admin.php' === $hook && $this->plugin_page_setup['menu_slug'] === esc_attr( $_GET['import'] ) ) ) {
			wp_enqueue_script('borderless-library-bootstrap-script', BORDERLESS__SCRIPTS . 'bootstrap.js', array(),  BORDERLESS__VERSION, true);
			wp_enqueue_script('borderless-library-isotope-script', BORDERLESS__LIB . 'isotope.js', array(),  '3.0.6', true);
			wp_enqueue_script('borderless-library-images-loaded-script', BORDERLESS__LIB . 'images-loaded.js', array(),  '5.0.0', true);
			wp_enqueue_script('borderless-library-script', BORDERLESS__SCRIPTS . 'library.js', array(),  BORDERLESS__VERSION, true);

			$theme = wp_get_theme();

			wp_localize_script( 'borderless-library-script', 'library',
				array(
					'ajax_url'         => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'       => wp_create_nonce( 'library-ajax-verification' ),
					'import_files'     => $this->import_files,
					'wp_customize_on'  => Helpers::apply_filters( 'library/enable_wp_customize_save_hooks', false ),
					'theme_screenshot' => $theme->get_screenshot(),
					'missing_plugins'  => $this->plugin_installer->get_missing_plugins(),
					'plugin_url'       => BORDERLESS__LIBRARY__URL,
					'import_url'       => $this->get_plugin_settings_url( [ 'step' => 'import' ] ),
					'texts'            => array(
						'missing_preview_image'    => esc_html__( 'No preview image defined for this import.', 'borderless' ),
						'dialog_title'             => esc_html__( 'Are you sure?', 'borderless' ),
						'dialog_no'                => esc_html__( 'Cancel', 'borderless' ),
						'dialog_yes'               => esc_html__( 'Yes, import!', 'borderless' ),
						'selected_import_title'    => esc_html__( 'Selected demo import:', 'borderless' ),
						'installing'               => esc_html__( 'Installing...', 'borderless' ),
						'importing'                => esc_html__( 'Importing...', 'borderless' ),
						'successful_import'        => esc_html__( 'Successfully Imported!', 'borderless' ),
						'install_plugin'           => esc_html__( 'Install Plugin', 'borderless' ),
						'installed'                => esc_html__( 'Installed', 'borderless' ),
						'import_failed'            => esc_html__( 'Import Failed', 'borderless' ),
						'import_failed_subtitle'   => esc_html__( 'Whoops, there was a problem importing your content.', 'borderless' ),
						'plugin_install_failed'    => esc_html__( 'Looks like some of the plugins failed to install. Please try again. If this issue persists, please manually install the failing plugins and come back to this step to import the theme demo data.', 'borderless' ),
						'content_filetype_warn'    => esc_html__( 'Invalid file type detected! Please select an XML file for the Content Import.', 'borderless' ),
						'widgets_filetype_warn'    => esc_html__( 'Invalid file type detected! Please select a JSON or WIE file for the Widgets Import.', 'borderless' ),
						'customizer_filetype_warn' => esc_html__( 'Invalid file type detected! Please select a DAT file for the Customizer Import.', 'borderless' ),
						'redux_filetype_warn'      => esc_html__( 'Invalid file type detected! Please select a JSON file for the Redux Import.', 'borderless' ),
					),
				)
			);

			wp_enqueue_style('borderless-library-style', BORDERLESS__STYLES.'library.css');
     		wp_enqueue_style('bootstrap', BORDERLESS__STYLES.'bootstrap.css');
      		wp_enqueue_style('bootstrap-icons', BORDERLESS__STYLES.'bootstrap-icons.css');
		}
	}


	public function upload_manual_import_files_callback() {
		Helpers::verify_ajax_call();

		if ( empty( $_FILES ) ) {
			wp_send_json_error( esc_html__( 'Manual import files are missing! Please select the import files and try again.', 'borderless' ) );
		}

		Helpers::set_demo_import_start_time();

		$this->log_file_path = Helpers::get_log_path();
		$this->selected_index = 0;
		$this->selected_import_files = Helpers::process_uploaded_files( $_FILES, $this->log_file_path );
		$this->import_files[ $this->selected_index ]['import_file_name'] = esc_html__( 'Manually uploaded files', 'borderless' );

		Helpers::set_library_import_data_transient( $this->get_current_importer_data() );

		wp_send_json_success();
	}


	public function import_demo_data_ajax_callback() {
		ini_set( 'memory_limit', Helpers::apply_filters( 'library/import_memory_limit', '500M' ) );

		Helpers::verify_ajax_call();

		$use_existing_importer_data = $this->use_existing_importer_data();

		if ( ! $use_existing_importer_data ) {
			Helpers::set_demo_import_start_time();

			$this->log_file_path = Helpers::get_log_path();
			$this->selected_index = empty( $_POST['selected'] ) ? 0 : absint( $_POST['selected'] );

			if ( ! empty( $_FILES ) ) { 
				$this->selected_import_files = Helpers::process_uploaded_files( $_FILES, $this->log_file_path );
				$this->import_files[ $this->selected_index ]['import_file_name'] = esc_html__( 'Manually uploaded files', 'borderless' );
			}
			elseif ( ! empty( $this->import_files[ $this->selected_index ] ) ) { 

				$this->selected_import_files = Helpers::download_import_files( $this->import_files[ $this->selected_index ] );

				if ( is_wp_error( $this->selected_import_files ) ) {
					Helpers::log_error_and_send_ajax_response(
						$this->selected_import_files->get_error_message(),
						$this->log_file_path,
						esc_html__( 'Downloaded files', 'borderless' )
					);
				}

				$log_added = Helpers::append_to_file(
					sprintf( 
						__( 'The import files for: %s were successfully downloaded!', 'borderless' ),
						$this->import_files[ $this->selected_index ]['import_file_name']
					) . Helpers::import_file_info( $this->selected_import_files ),
					$this->log_file_path,
					esc_html__( 'Downloaded files' , 'borderless' )
				);
			}
			else {
				wp_send_json( esc_html__( 'No import files specified!', 'borderless' ) );
			}
		}

		Helpers::set_library_import_data_transient( $this->get_current_importer_data() );

		if ( ! $this->before_import_executed ) {
			$this->before_import_executed = true;

			Helpers::do_action( 'library/before_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index );
		}

		if ( ! empty( $this->selected_import_files['content'] ) ) {
			$this->append_to_frontend_error_messages( $this->importer->import_content( $this->selected_import_files['content'] ) );
		}

		Helpers::do_action( 'library/after_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index );
		Helpers::set_library_import_data_transient( $this->get_current_importer_data() );

		if ( ! empty( $this->selected_import_files['customizer'] ) ) {
			wp_send_json( array( 'status' => 'customizerAJAX' ) );
		}

		if ( false !== Helpers::has_action( 'library/after_all_import_execution' ) ) {
			wp_send_json( array( 'status' => 'afterAllImportAJAX' ) );
		}

		$this->update_terms_count();
		$this->final_response();
	}


	public function import_customizer_data_ajax_callback() {
		Helpers::verify_ajax_call();

		if ( $this->use_existing_importer_data() ) {
			Helpers::do_action( 'library/customizer_import_execution', $this->selected_import_files );
		}

		if ( false !== Helpers::has_action( 'library/after_all_import_execution' ) ) {
			wp_send_json( array( 'status' => 'afterAllImportAJAX' ) );
		}

		$this->final_response();
	}


	public function after_all_import_data_ajax_callback() {
		Helpers::verify_ajax_call();

		if ( $this->use_existing_importer_data() ) {
			Helpers::do_action( 'library/after_all_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index );
		}

		$this->update_terms_count();

		$this->final_response();
	}

	
	private function final_response() {
		delete_transient( 'library_importer_data' );

		$response['title'] = esc_html__( 'Import Complete!', 'borderless' );
		$response['subtitle'] = '<p>' . esc_html__( 'Your Website is ready.', 'borderless' ) . '</p>';
		$response['message'] = '<i class="borderless-library-import__imported-icon bi bi-check-circle-fill"></i>';

		if ( ! empty( $this->frontend_error_messages ) ) {
			$response['subtitle'] = '<p>' . esc_html__( 'Your Website is ready, but some things could not be imported.', 'borderless' ) . '</p>';
			$response['subtitle'] .= sprintf(
				wp_kses(
					__( '<p><a href="%s" target="_blank">View error log</a> for more information.</p>', 'borderless' ),
					array(
						'p'      => [],
						'a'      => [
							'href'   => [],
							'target' => [],
						],
					)
				),
				Helpers::get_log_url( $this->log_file_path )
			);

			$response['message'] = '<div class="notice notice-warning"><p>' . $this->frontend_error_messages_display() . '</p></div>';
		}

		wp_send_json( $response );
	}


	private function use_existing_importer_data() {
		if ( $data = get_transient( 'library_importer_data' ) ) {
			$this->frontend_error_messages = empty( $data['frontend_error_messages'] ) ? array() : $data['frontend_error_messages'];
			$this->log_file_path           = empty( $data['log_file_path'] ) ? '' : $data['log_file_path'];
			$this->selected_index          = empty( $data['selected_index'] ) ? 0 : $data['selected_index'];
			$this->selected_import_files   = empty( $data['selected_import_files'] ) ? array() : $data['selected_import_files'];
			$this->import_files            = empty( $data['import_files'] ) ? array() : $data['import_files'];
			$this->before_import_executed  = empty( $data['before_import_executed'] ) ? false : $data['before_import_executed'];
			$this->imported_terms          = empty( $data['imported_terms'] ) ? [] : $data['imported_terms'];
			$this->importer->set_importer_data( $data );

			return true;
		}
		return false;
	}


	public function get_current_importer_data() {
		return array(
			'frontend_error_messages' => $this->frontend_error_messages,
			'log_file_path'           => $this->log_file_path,
			'selected_index'          => $this->selected_index,
			'selected_import_files'   => $this->selected_import_files,
			'import_files'            => $this->import_files,
			'before_import_executed'  => $this->before_import_executed,
			'imported_terms'          => $this->imported_terms,
		);
	}


	public function get_log_file_path() {
		return $this->log_file_path;
	}


	public function append_to_frontend_error_messages( $text ) {
		$lines = array();

		if ( ! empty( $text ) ) {
			$text = str_replace( '<br>', PHP_EOL, $text );
			$lines = explode( PHP_EOL, $text );
		}

		foreach ( $lines as $line ) {
			if ( ! empty( $line ) && ! in_array( $line , $this->frontend_error_messages ) ) {
				$this->frontend_error_messages[] = $line;
			}
		}
	}


	public function frontend_error_messages_display() {
		$output = '';

		if ( ! empty( $this->frontend_error_messages ) ) {
			foreach ( $this->frontend_error_messages as $line ) {
				$output .= esc_html( $line );
				$output .= '<br>';
			}
		}

		return $output;
	}


	public function setup_plugin_with_filter_data() {
		if ( ! ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) ) {
			return;
		}

		$this->import_files = Helpers::validate_import_file_info( Helpers::apply_filters( 'library/import_files', array() ) );

		$import_actions = new ImportActions();
		$import_actions->register_hooks();

		$importer_options = Helpers::apply_filters( 'library/importer_options', array(
			'fetch_attachments' => true,
		) );

		$logger_options = Helpers::apply_filters( 'library/logger_options', array(
			'logger_min_level' => 'warning',
		) );

		$logger            = new Logger();
		$logger->min_level = $logger_options['logger_min_level'];

		$this->importer = new Importer( $importer_options, $logger );

		$this->plugin_installer = new PluginInstaller();
		$this->plugin_installer->init();
	}

	public function get_plugin_page_setup() {
		return $this->plugin_page_setup;
	}

	public function start_notice_output_capturing() {
		$screen = get_current_screen();

		if ( false === strpos( $screen->base, $this->plugin_page_setup['menu_slug'] ) ) {
			return;
		}

		echo '<div class="library-notices-wrapper js-library-notice-wrapper">';
	}

	public function finish_notice_output_capturing() {
		if ( is_network_admin() ) {
			return;
		}

		$screen = get_current_screen();

		if ( false === strpos( $screen->base, $this->plugin_page_setup['menu_slug'] ) ) {
			return;
		}

		echo '</div><!-- /.library-notices-wrapper -->';
	}

	public function get_plugin_settings_url( $query_parameters = [] ) {
		if ( empty( $this->plugin_page_setup ) ) {
			$this->plugin_page_setup = Helpers::get_plugin_page_setup_data();
		}

		$parameters = array_merge(
			array( 'page' => $this->plugin_page_setup['menu_slug'] ),
			$query_parameters
		);

		$url = menu_page_url( $this->plugin_page_setup['parent_slug'], false );

		if ( empty( $url ) ) {
			$url = self_admin_url( $this->plugin_page_setup['parent_slug'] );
		}

		return add_query_arg( $parameters, $url );
	}

	public function redirect_from_old_default_admin_page() {
		global $pagenow;

		if ( $pagenow == 'themes.php' && isset( $_GET['page'] ) && $_GET['page'] == 'pt-library' ) {
			wp_safe_redirect( $this->get_plugin_settings_url() );
			exit;
		}
	}

	public function add_imported_terms( $object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids ){

		if ( ! isset( $this->imported_terms[ $taxonomy ] ) ) {
			$this->imported_terms[ $taxonomy ] = array();
		}

		$this->imported_terms[ $taxonomy ] = array_unique( array_merge( $this->imported_terms[ $taxonomy ], $tt_ids ) );
	}

	private function update_terms_count() {

		foreach ( $this->imported_terms as $tax => $terms ) {
			wp_update_term_count_now( $terms, $tax );
		}
	}
}
