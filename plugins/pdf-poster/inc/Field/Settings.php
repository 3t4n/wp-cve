<?php
namespace PDFPro\Field;

use PDFPro\Helper\Pipe;
use PDFPro\Api\DropboxApi;
use PDFPro\Api\GoogleDriveApi;
use PDFPro\Helper\Functions as Utils;

class Settings{

	private $metabox_prefix = '_fpdf';
	private $option_prefix = 'fpdf_option';
	private $option = null;
	public function register(){
		global $pdfp_bs;
		if (class_exists('\CSF')) {
			

			\CSF::createOptions( $this->option_prefix, array(
				'framework_title' => 'PDF Poster Settings',
				'menu_title'  => 'Settings',
				'menu_slug'   => 'fpdf-settings',
				'menu_type'   => 'submenu',
				'menu_parent' => 'edit.php?post_type=pdfposter',
				'theme' => 'light',
				'show_bar_menu' => false,
			));
			
			$this->shortcode();
			
		}
	}


	public function shortcode(){
		\CSF::createSection($this->option_prefix, array(
			'title' => 'Shortcode',
			'fields' => array(
				array(
					'id' => 'pdfp_gutenberg_enable',
					'type' => 'switcher',
					'title' => 'Enable Gutenberg shortcode generator',
					'default' => get_option('pdfp_gutenberg_enable', false)
				)
			)
		));
	}

	function pdfp_preset($key, $default = false){
		$settings = get_option('fpdf_option');
		return $settings[$key] ?? $default;
	}
}

