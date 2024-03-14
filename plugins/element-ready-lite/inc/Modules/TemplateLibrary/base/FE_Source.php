<?php

namespace Element_Ready\Modules\TemplateLibrary\base;

use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Base;

class FE_Source extends Source_Base {

	/**
	 * Get remote template ID.
	 *
	 * Retrieve the remote template ID.
	 *
	 * @since 1.14.5
	 * @access public
	 *
	 * @return string The remote template ID.
	 */
	public function get_id() {
		return 'firefly';
	}

	/**
	 * Get remote template title.
	 *
	 * Retrieve the remote template title.
	 *
	 * @since 1.14.5
	 * @access public
	 *
	 * @return string The remote template title.
	 */
	public function get_title() {
		return 'FireFly';
	}

	/**
	 * Register remote template data.
	 *
	 * Used to register custom template data like a post type, a taxonomy or any
	 * other data.
	 *
	 * @since 1.14.5
	 * @access public
	 */
	public function register_data() {}

	/**
	 * Get remote templates.
	 *
	 * Retrieve remote templates from http://quomodosoft.com servers.
	 *
	 * @since 1.14.5
	 * @access public
	 *
	 * @param array $args Optional. Nou used in remote source.
	 *
	 * @return array Remote templates.
	 */
	public function get_items( $args = [] ) {

		$library_data = Templates_Lib::get_library_data();
		$status = apply_filters( 'element_ready/template/service/status', 200 );

		$templates = [];

		if ( ! empty( $library_data['templates'] ) ) {

			foreach ( $library_data['templates'] as $template_data ) {
				$data = $this->prepare_template( $template_data );
				$data['proStatus'] = $status;
				$templates[] = $data;
			}

		}

		return $templates;
	}

	/**
	 * Get remote template.
	 *
	 * Retrieve a single remote template from http://quomodosoft.com/ servers.
	 *
	 * @since 1.14.5
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return array Remote template.
	 */
	public function get_item( $template_id ) {

		$templates = $this->get_items();
		return $templates[ $template_id ];
	}

	/**
	 * Save remote template.
	 *
	 * Remote template from http://quomodosoft.com/ servers cannot be saved on the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.14.5
	 * @access public
	 *
	 * @param array $template_data Remote template data.
	 *
	 * @return \WP_Error
	 */
	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a remote source' );
	}

	/**
	 * Update remote template.
	 *
	 * Remote template from http://quomodosoft.com/ servers cannot be updated on the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.14.5
	 * @access public
	 *
	 * @param array $new_data New template data.
	 *
	 * @return \WP_Error
	 */
	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a remote source' );
	}

	/**
	 * Delete remote template.
	 *
	 * Remote template from http://quomodosoft.com/ servers cannot be deleted from the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.14.5
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return \WP_Error
	 */
	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a remote source' );
	}

	/**
	 * Export remote template.
	 *
	 * Remote template from http://quomodosoft.com/ servers cannot be exported from the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.14.5
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return \WP_Error
	 */
	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a remote source' );
	}

	/**
	 * Get remote template data.
	 *
	 * Retrieve the data of a single remote template from http://quomodosoft.com/ servers.
	 *
	 * @since 1.14.5
	 * @access public
	 *
	 * @param array  $args    Custom template arguments.
	 * @param string $context Optional. The context. Default is `display`.
	 *
	 * @return array|\WP_Error Remote Template data.
	 */
	public function get_data( array $args, $context = 'display' ) {
		
		$data = Templates_Lib::get_template_content( $args['template_id'] );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$data = (array) $data;

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );
		
		$post_id = $args['editor_post_id'];
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		return $data;
	}

	public function get_custom_data($data,$post_id){

		
		// After the upload complete, set the elementor upload state back to false
		Plugin::$instance->uploads_manager->set_elementor_upload_state( true );
		
		$data['content'] = $this->replace_elements_ids( $data['content'] );
		
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );
	
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		Plugin::$instance->uploads_manager->set_elementor_upload_state( false );
		
		return $data;
	}

	/**
	 * Prepare template.
	 *
	 * Prepare template data.
	 *
	 * @since 1.6.0
	 * @access private
	 *
	 * @param array $template_data Collection of template data.
	 * @return array Collection of template data.
	 */
	private function prepare_template( array $template_data ) {
		
		$favorite_templates = $this->get_user_meta( 'favorites' );
      
		return [
			'template_id' => $template_data['id'],
			'source'      => $this->get_id(),
			'type'        => esc_html($template_data['type']),
			'subtype'     => esc_html($template_data['subtype']),
			'title'       => esc_html($template_data['title']),
			'thumbnail'   => esc_html($template_data['thumbnail']),
			'date'        => esc_html($template_data['tmpl_created']),
			'author'      => esc_html($template_data['author']),
			'tags'        => implode(',', $template_data['tags'] ),
			'isPro'       => esc_attr($this->template_is_pro($template_data['is_pro'])),
			'url'         => esc_attr($template_data['url']),
			'favorite'    => ! empty( $favorite_templates[ $template_data['id'] ] ),
		];
	}

	public function template_is_pro($data = true){
	  
	   $resu = $data == 'yes' ? true : false;
	   return apply_filters( 'element_ready_pro_template_status', $resu );
	}
}