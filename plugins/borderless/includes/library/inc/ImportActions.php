<?php

namespace LIBRARY;

class ImportActions {
	public function register_hooks() {
		add_action( 'library/before_content_import_execution', array( $this, 'before_content_import_action' ), 10, 3 );

		add_action( 'library/after_content_import_execution', array( $this, 'before_widget_import_action' ), 10, 3 );
		add_action( 'library/after_content_import_execution', array( $this, 'widgets_import' ), 20, 3 );
		add_action( 'library/after_content_import_execution', array( $this, 'redux_import' ), 30, 3 );

		add_action( 'library/customizer_import_execution', array( $this, 'customizer_import' ), 10, 1 );

		add_action( 'library/after_all_import_execution', array( $this, 'after_import_action' ), 10, 3 );

		if ( Helpers::apply_filters( 'library/enable_custom_menu_widget_ids_fix', true ) ) {
			add_action( 'library/widget_settings_array', array( $this, 'fix_custom_menu_widget_ids' ) );
		}
	}


	public function fix_custom_menu_widget_ids( $widget ) {
		if ( ! array_key_exists( 'nav_menu', $widget ) || empty( $widget['nav_menu'] ) || ! is_int( $widget['nav_menu'] ) ) {
			return $widget;
		}

		$library                = BorderlessLibraryImporter::get_instance();
		$content_import_data = $library->importer->get_importer_data();
		$term_ids            = $content_import_data['mapping']['term_id'];

		$widget['nav_menu'] = $term_ids[ $widget['nav_menu'] ];

		return $widget;
	}


	public function widgets_import( $selected_import_files, $import_files, $selected_index ) {
		if ( ! empty( $selected_import_files['widgets'] ) ) {
			WidgetImporter::import( $selected_import_files['widgets'] );
		}
	}


	public function redux_import( $selected_import_files, $import_files, $selected_index ) {
		if ( ! empty( $selected_import_files['redux'] ) ) {
			ReduxImporter::import( $selected_import_files['redux'] );
		}
	}


	public function customizer_import( $selected_import_files ) {
		if ( ! empty( $selected_import_files['customizer'] ) ) {
			CustomizerImporter::import( $selected_import_files['customizer'] );
		}
	}


	public function before_content_import_action( $selected_import_files, $import_files, $selected_index ) {
		$this->do_import_action( 'library/before_content_import', $import_files[ $selected_index ] );
	}


	public function before_widget_import_action( $selected_import_files, $import_files, $selected_index ) {
		$this->do_import_action( 'library/before_widgets_import', $import_files[ $selected_index ] );
	}


	public function after_import_action( $selected_import_files, $import_files, $selected_index ) {
		$this->do_import_action( 'library/after_import', $import_files[ $selected_index ] );
	}


	private function do_import_action( $action, $selected_import ) {
		if ( false !== Helpers::has_action( $action ) ) {
			$library          = BorderlessLibraryImporter::get_instance();
			$log_file_path = $library->get_log_file_path();

			ob_start();
				Helpers::do_action( $action, $selected_import );
			$message = ob_get_clean();
			$log_added = Helpers::append_to_file(
				$message,
				$log_file_path,
				$action
			);
		}
	}
}
