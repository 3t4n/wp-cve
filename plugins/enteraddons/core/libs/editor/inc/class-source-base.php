<?php
namespace Enteraddons\Editor;
/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */
use Elementor\TemplateLibrary\Source_Base;
class Library_Source extends Source_Base {

	public function get_id() {
		return 'enteraddons-template-library-manager';
	}

	public function get_title() {
		return esc_html__( 'EnterAddons Template Library Manager', 'enteraddons' );
	}

	public function register_data() {}

	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', esc_html__( 'Cannot save template to a EnterAddons Library manager', 'enteraddons' ) );
	}

	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', esc_html__( 'Cannot update template to a EnterAddons Library manager', 'enteraddons' ) );
	}

	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', esc_html__( 'Cannot delete template from a EnterAddons Library manager', 'enteraddons' ) );
	}

	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', esc_html__( 'Cannot export template from a EnterAddons Library manager', 'enteraddons' ) );
	}

	public function get_items( $args = array() ) {
		return array();
	}

	public function get_item( $template_id ) {
		$templates = $this->get_items();
		return $templates[ $template_id ];
	}

	public function get_data( $getdata, $context = 'display' ) {
		
		if( empty( $getdata[0] ) || empty( $getdata[0]['content'] ) ) {
			throw new \Exception( __( 'Template does not have any content', 'enteraddons' ) );
		}

		$data = $getdata[0];

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id  = $getdata[1];
		$document = \Elementor\Plugin::instance()->documents->get( $post_id );

		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		return $data;
	}
}