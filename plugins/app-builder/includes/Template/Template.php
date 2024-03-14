<?php


/**
 * class Template
 *
 * @link       https://appcheap.io
 * @since      3.0.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Template;

defined( 'ABSPATH' ) || exit;

class Template {

	/**
	 * @var int template id
	 */
	protected int $template_id;

	public function __construct() {
		$this->template_id = (int) get_option( 'app_builder_template_active_id', 0 );
	}

	/**
	 * @return int
	 */
	public function getTemplateId(): int {
		return $this->template_id;
	}

	/**
	 * @param int $template_id
	 */
	public function setTemplateId( int $template_id ): void {
		$this->template_id = $template_id;
	}


	/**
	 * Get template data
	 *
	 * @return array|mixed
	 */
	public function getData() {
		/**
		 * Get post by id
		 */
		$template = get_post( $this->template_id );

		/**
		 * Get at least one template in list
		 */
		if ( ! $template ) {
			$templates = get_posts( array(
				'post_type'   => 'app_builder_template',
				'status'      => 'publish',
				'numberposts' => 1,
			) );
			$template  = count( $templates ) > 0 ? $templates[0] : null;
		}

		return is_null( $template ) ? array() : json_decode( $template->post_content, true );
	}

	/**
	 * Get screen config data
	 *
	 * @param string $screen
	 * @param string $key
	 * @param string $field
	 * @param $default_value
	 *
	 * @return false|mixed
	 */
	public function getScreenData( string $screen, string $key, string $field, $default_value ) {
		$data = $this->getData();

		if ( ! isset( $data['screens'][ $screen ]['widgets'][ $key ]['fields'][ $field ] ) ) {
			return $default_value;
		}

		return $data['screens'][ $screen ]['widgets'][ $key ]['fields'][ $field ];
	}

	/**
	 * Get settings general
	 *
	 * @param string $field
	 * @param $default_value
	 *
	 * @return mixed
	 */
	public function getSettingsGeneral( string $field, $default_value ) {
		$data = $this->getData();

		if ( ! isset( $data['settings']['general']['widgets']['general']['fields'][ $field ] ) ) {
			return $default_value;
		}

		return $data['settings']['general']['widgets']['general']['fields'][ $field ];
	}
}