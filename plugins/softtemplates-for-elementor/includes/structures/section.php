<?php

if ( ! class_exists( 'Soft_template_Core_Structure_Section' ) ) {

	/**
	 * Define Soft_template_Core_Structure_Section class
	 */
	class Soft_template_Core_Structure_Section extends Soft_template_Core_Structure_Base {

		public function get_id() {
			return 'softtemplate_section';
		}

		public function get_single_label() {
			return esc_html__( 'Section', 'soft-template-core' );
		}

		public function get_plural_label() {
			return esc_html__( 'Sections', 'soft-template-core' );
		}

		public function get_sources() {
			return array( 'softtemplate-theme', 'softtemplate-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'Softtemplate_Section_Document',
				'file'  => soft_template_core()->plugin_path( 'includes/document-types/section.php' ),
			);
		}

		/**
		 * Library settings for current structure
		 *
		 * @return void
		 */
		public function library_settings() {

			return array(
				'show_title'    => false,
				'show_keywords' => true,
			);

		}

	}

}
