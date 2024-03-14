<?php

namespace LIBRARY;

class Importer {
	private $importer;
	private $microtime;
	public $logger;
	private $library;
	public function __construct( $importer_options = array(), $logger = null ) {
		$this->include_required_files();
		$this->importer = new WXRImporter( $importer_options );

		$this->logger = $logger;
		if ( ! empty( $this->logger ) ) {
			$this->set_logger( $this->logger );
		}

		$this->library = BorderlessLibraryImporter::get_instance();
	}


	private function include_required_files() {
		if ( ! class_exists( '\WP_Importer' ) ) {
			require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}
	}


	public function import( $data_file ) {
		$this->importer->import( $data_file );
	}


	public function set_logger( $logger ) {
		$this->importer->set_logger( $logger );
	}


	public function get_importer_data() {
		return $this->importer->get_importer_data();
	}


	public function set_importer_data( $data ) {
		$this->importer->set_importer_data( $data );
	}


	public function import_content( $import_file_path ) {
		$this->microtime = microtime( true );

		if ( strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) === false ) {
			set_time_limit( Helpers::apply_filters( 'library/set_time_limit_for_demo_data_import', 300 ) );
		}

		add_filter( 'wxr_importer.pre_process.user', '__return_false' );

		add_filter( 'wxr_importer.pre_process.post', array( $this, 'new_ajax_request_maybe' ) );

		if ( ! Helpers::apply_filters( 'library/regenerate_thumbnails_in_content_import', true ) ) {
			add_filter( 'intermediate_image_sizes_advanced', '__return_null' );
		}

		if ( ! empty( $import_file_path ) ) {
			ob_start();
				$this->import( $import_file_path );
			$message = ob_get_clean();
		}

		return $this->logger->error_output;
	}


	public function new_ajax_request_maybe( $data ) {
		$time = microtime( true ) - $this->microtime;

		if ( $time > Helpers::apply_filters( 'library/time_for_one_ajax_call', 25 ) ) {
			$response = array(
				'status'  => 'newAJAX',
				'message' => 'Time for new AJAX request!: ' . $time,
			);

			$message = ob_get_clean();

			if ( ! empty( $message ) ) {
				$this->library->append_to_frontend_error_messages( $message );
			}

			$log_added = Helpers::append_to_file(
				__( 'New AJAX call!' , 'borderless' ) . PHP_EOL . $message,
				$this->library->get_log_file_path(),
				''
			);

			$this->set_current_importer_data();

			wp_send_json( $response );
		}

		$current_user_obj    = wp_get_current_user();
		$data['post_author'] = $current_user_obj->user_login;

		return $data;
	}


	private function set_current_importer_data() {
		$data = array_merge( $this->library->get_current_importer_data(), $this->get_importer_data() );

		Helpers::set_library_import_data_transient( $data );
	}
}
