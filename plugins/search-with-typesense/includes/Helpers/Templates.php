<?php


namespace Codemanas\Typesense\Helpers;

class Templates {
	public string $theme_folder = 'search-with-typesense';
	public string $template_dir = CODEMANAS_TYPESENSE_ROOT_DIR_PATH . '/templates/';
	public static ?Templates $instance = null;

	/**
	 * @return Templates|null
	 */
	public static function getInstance(): ?Templates {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_template_dir( $template_dir = '' ) {
		return empty( $template_dir ) ? $this->template_dir : $template_dir;
	}

	public function include_file( $file = '', $args = array(), $require_once = false ) {
		$templatePath = apply_filters( 'cm_typesense_locate_template', locate_template( $this->theme_folder . '/' . $file ), $file, $args );
		if ( $templatePath ) {
			load_template( $templatePath, $require_once, $args );
		} else {
			$file_path = $this->get_template_dir() . $file;
			if ( file_exists( $file_path ) ) {
				load_template( $file_path, $require_once, $args );
			}
		}
	}
}

