<?php

namespace LIBRARY;

use WP_CLI;

class WPCLICommands extends \WP_CLI_Command {

	private $library;

	public function __construct() {
		parent::__construct();

		$this->library = BorderlessLibraryImporter::get_instance();

		Helpers::set_demo_import_start_time();

		$this->library->log_file_path = Helpers::get_log_path();
	}

	public function list_predefined() {
		if ( empty( $this->library->import_files ) ) {
			WP_CLI::error( esc_html__( 'There are no predefined demo imports for currently active theme!', 'borderless' ) );
		}

		WP_CLI::success( esc_html__( 'Here are the predefined demo imports:', 'borderless' ) );

		foreach ( $this->library->import_files as $index => $import_file ) {
			WP_CLI::log( sprintf(
				'%d -> %s [content: %s, widgets: %s, customizer: %s, redux: %s]',
				$index,
				$import_file['import_file_name'],
				empty( $import_file['import_file_url'] ) && empty( $import_file['local_import_file'] ) ? 'no' : 'yes',
				empty( $import_file['import_widget_file_url'] ) && empty( $import_file['local_import_widget_file'] ) ? 'no' : 'yes',
				empty( $import_file['import_customizer_file_url'] ) && empty( $import_file['local_import_customizer_file'] ) ? 'no' : 'yes',
				empty( $import_file['import_redux'] ) && empty( $import_file['local_import_redux'] ) ? 'no' : 'yes'
			) );
		}
	}

	public function import( $args, $assoc_args ) {
		if ( ! $this->any_import_options_set( $assoc_args ) ) {
			WP_CLI::error( esc_html__( 'At least one of the possible options should be set! Check them with --help', 'borderless' ) );
		}

		if ( isset( $assoc_args['predefined'] ) ) {
			$this->import_predefined( $assoc_args['predefined'] );
		}

		if ( ! empty( $assoc_args['content'] ) ) {
			$this->import_content( $assoc_args['content'] );
		}

		if ( ! empty( $assoc_args['widgets'] ) ) {
			$this->import_widgets( $assoc_args['widgets'] );
		}

		if ( ! empty( $assoc_args['customizer'] ) ) {
			$this->import_customizer( $assoc_args['customizer'] );
		}
	}

	private function any_import_options_set( $options ) {
		$possible_options = array(
			'content',
			'widgets',
			'customizer',
			'predefined',
		);

		foreach ( $possible_options as $option ) {
			if ( array_key_exists( $option, $options ) ) {
				return true;
			}
		}

		return false;
	}

	private function import_predefined( $predefined_index ) {
		if ( ! is_numeric( $predefined_index ) ) {
			WP_CLI::error( esc_html__( 'The "predefined" parameter should be a number (an index of the LIBRARY predefined demo import)!', 'borderless' ) );
		}

		$predefined_index = absint( $predefined_index );

		if ( ! array_key_exists( $predefined_index, $this->library->import_files ) ) {
			WP_CLI::warning( esc_html__( 'The supplied predefined index does not exist! Please take a look at the available predefined demo imports:', 'borderless' ) );

			$this->list_predefined();

			return false;
		}

		WP_CLI::log( esc_html__( 'Predefined demo import started! All other parameters will be ignored!', 'borderless' ) );

		$selected_files = $this->library->import_files[ $predefined_index ];

		if ( ! empty( $selected_files['import_file_name'] ) ) { 
			WP_CLI::log( sprintf( esc_html__( 'Selected predefined demo import: %s', 'borderless' ), $selected_files['import_file_name'] ) );
		}

		WP_CLI::log( esc_html__( 'Preparing the demo import files...', 'borderless' ) );

		$import_files =	Helpers::download_import_files( $selected_files );

		if ( empty( $import_files ) ) {
			WP_CLI::error( esc_html__( 'Demo import files could not be retrieved!', 'borderless' ) );
		}

		WP_CLI::log( esc_html__( 'Demo import files retrieved successfully!', 'borderless' ) );

		WP_CLI::log( esc_html__( 'Importing...', 'borderless' ) );

		if ( ! empty( $import_files['content'] ) ) {
			$this->do_action( 'library/before_content_import_execution', $import_files, $this->library->import_files, $predefined_index );

			$this->import_content( $import_files['content'] );
		}

		if ( ! empty( $import_files['widgets'] ) ) {
			$this->do_action( 'library/before_widgets_import', $import_files );

			$this->import_widgets( $import_files['widgets'] );
		}

		if ( ! empty( $import_files['customizer'] ) ) {
			$this->import_customizer( $import_files['customizer'] );
		}

		$this->do_action( 'library/after_all_import_execution', $import_files, $this->library->import_files, $predefined_index );

		WP_CLI::log( esc_html__( 'Predefined import finished!', 'borderless' ) );
	}

	private function import_content( $relative_file_path ) {
		$content_import_file_path = realpath( $relative_file_path );

		if ( ! file_exists( $content_import_file_path ) ) {
			WP_CLI::warning( esc_html__( 'Content import file provided does not exist! Skipping this import!', 'borderless' ) );
			return false;
		}

		add_filter( 'library/time_for_one_ajax_call', function() {
			return 3600;
		} );

		WP_CLI::log( esc_html__( 'Importing content (this might take a while)...', 'borderless' ) );

		Helpers::append_to_file( '', $this->library->log_file_path, esc_html__( 'Importing content' , 'borderless' ) );

		$this->library->append_to_frontend_error_messages( $this->library->importer->import_content( $content_import_file_path ) );

		if( empty( $this->library->frontend_error_messages ) ) {
			WP_CLI::success( esc_html__( 'Content import finished!', 'borderless' ) );
		}
		else {
			WP_CLI::warning( esc_html__( 'There were some issues while importing the content!', 'borderless' ) );

			foreach ( $this->library->frontend_error_messages as $line ) {
				WP_CLI::log( $line );
			}

			$this->library->frontend_error_messages = array();
		}
	}

	private function import_widgets( $relative_file_path ) {
		$widgets_import_file_path = realpath( $relative_file_path );

		if ( ! file_exists( $widgets_import_file_path ) ) {
			WP_CLI::warning( esc_html__( 'Widgets import file provided does not exist! Skipping this import!', 'borderless' ) );
			return false;
		}

		WP_CLI::log( esc_html__( 'Importing widgets...', 'borderless' ) );

		WidgetImporter::import( $widgets_import_file_path );

		if( empty( $this->library->frontend_error_messages ) ) {
			WP_CLI::success( esc_html__( 'Widgets imported successfully!', 'borderless' ) );
		}
		else {
			WP_CLI::warning( esc_html__( 'There were some issues while importing widgets!', 'borderless' ) );

			foreach ( $this->library->frontend_error_messages as $line ) {
				WP_CLI::log( $line );
			}

			$this->library->frontend_error_messages = array();
		}
	}

	private function import_customizer( $relative_file_path ) {
		$customizer_import_file_path = realpath( $relative_file_path );

		if ( ! file_exists( $customizer_import_file_path ) ) {
			WP_CLI::warning( esc_html__( 'Customizer import file provided does not exist! Skipping this import!', 'borderless' ) );
			return false;
		}

		WP_CLI::log( esc_html__( 'Importing customizer settings...', 'borderless' ) );

		CustomizerImporter::import( $customizer_import_file_path );

		if( empty( $this->library->frontend_error_messages ) ) {
			WP_CLI::success( esc_html__( 'Customizer settings imported successfully!', 'borderless' ) );
		}
		else {
			WP_CLI::warning( esc_html__( 'There were some issues while importing customizer settings!', 'borderless' ) );

			foreach ( $this->library->frontend_error_messages as $line ) {
				WP_CLI::log( $line );
			}

			$this->library->frontend_error_messages = array();
		}
	}

	private function do_action( $action, $import_files = array(), $all_import_files = array(), $selected_index = null ) {
		if ( false !== Helpers::has_action( $action ) ) { 
			WP_CLI::log( sprintf( esc_html__( 'Executing action: %s ...', 'borderless' ), $action ) );

			ob_start();
				Helpers::do_action( $action, $import_files, $all_import_files, $selected_index );
			$message = ob_get_clean();

			Helpers::append_to_file( $message, $this->library->log_file_path, $action );
		}
	}
}
