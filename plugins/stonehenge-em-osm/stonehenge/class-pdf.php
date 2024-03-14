<?php
if( !defined('ABSPATH') ) exit;

if( !class_exists('Stonehenge_PDF')) :
Class Stonehenge_PDF extends Stonehenge_Mailer {


	#===============================================
	public function pdf_file_name( $title ) {
		$file_name = $this->slugify($title).'.pdf';
		return $file_name;
	}


	#===============================================
	public function pdf_file_path( $dir ) {
		$file_path	= ((object) wp_upload_dir())->basedir . "/stonehenge/{$dir}/";

		// Create the directory if needed.
		if( !is_dir($file_path) ) {
			wp_mkdir_p($file_path);
			chmod( "$file_path", 0755 );
		}
		$file_path = apply_filters('stonehenge_pdf_file_path', $file_path);
		return $file_path;
	}


	#===============================================
	public function pdf_temp_dir() {
		$directory = ((object) wp_upload_dir())->basedir . '/temp/';

		// Create the directory if needed.
		if( !is_dir($directory) ) {
			wp_mkdir_p($directory);
			chmod( "$directory", 0755 );
		}
		$directory = apply_filters('stonehenge_pdf_temp_dir', $directory);
		return $directory;
	}


	#===============================================
	public function pdf_font_folders() {
		$default_config = (new Mpdf\Config\ConfigVariables())->getDefaults();
		$default_dir	= $default_config['fontDir'];
		$custom_dir		= $this->pdf_custom_font_folder();
		$folders 		= array_merge($default_dir, (array) $custom_dir);
		return $folders;
	}


	#===============================================
	public function pdf_custom_font_folder() {
		$custom_dir	= ((object) wp_upload_dir())->basedir . '/stonehenge/mpdf-fonts/';

		// Create the directory if needed.
		if( !is_dir($custom_dir) ) {
			wp_mkdir_p($custom_dir);
			chmod( "$custom_dir", 0755 );
		}

		$custom_dir = apply_filters('stonehenge_pdf_fonts_folder', $custom_dir);
		return $custom_dir;
	}


	#===============================================
	public function pdf_fonts() {
		$default_config = (new Mpdf\Config\FontVariables())->getDefaults();
		$fonts 			= $default_config['fontdata'];
		$fonts 			= apply_filters('stonehenge_mpdf_fonts', $fonts); // Deprecated.
		$fonts 			= apply_filters('stonehenge_pdf_fonts', $fonts);
		$fonts 			= array_change_key_case($fonts, CASE_LOWER);
		return $fonts;
	}


} // End class.
endif;
