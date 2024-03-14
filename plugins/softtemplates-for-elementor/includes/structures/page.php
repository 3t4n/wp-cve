<?php

if ( ! class_exists( 'Soft_template_Core_Structure_Page' ) ) {

	/**
	 * Define Soft_template_Core_Structure_Page class
	 */
	class Soft_template_Core_Structure_Page extends Soft_template_Core_Structure_Base {

		public function get_id() {
			return 'softtemplate_page';
		}

		public function get_single_label() {
			return esc_html__( 'Page', 'soft-template-core' );
		}

		public function get_plural_label() {
			return esc_html__( 'Pages', 'soft-template-core' );
		}

		public function get_sources() {
			return array( 'softtemplate-theme', 'softtemplate-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'Softtemplate_Page_Document',
				'file'  => soft_template_core()->plugin_path( 'includes/document-types/page.php' ),
			);
		}

	}

}
