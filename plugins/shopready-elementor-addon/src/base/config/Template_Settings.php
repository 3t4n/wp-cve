<?php

namespace Shop_Ready\base\config;

abstract class Template_Settings {

	public $template = null;

	/**
	 * | preset_tpl |
	 * find elementor template id in config
	 * create template select drop down setting
	 *
	 * @return boolean | int
	 */
	protected function preset_tpl( $name = null ) {

		if ( ! is_null( $name ) ) {
			$this->name = $name;
		}

		if ( is_null( $name ) ) {

			$template = $this->find_template_config( $this->name );
		} else {

			$template = $this->find_template_config( $name );
		}

		if ( isset( $template['id'] ) && is_numeric( $template['id'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Render Elementor Content
	 * elementor page id
	 *
	 * @since 1.0
	 * @return void
	 */
	public function dynamic_template() {

		echo  \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $this->lookup( $this->name ), true);

	}
	/**
	 * | is_tpl_active |
	 * Component Settings
	 * varify template active from Dashboard Settings
	 *
	 * @return boolean | int
	 */
	protected function is_tpl_active( $name = null ) {

		if ( is_null( $name ) ) {

			$template = $this->find_template_config( $this->name );
		} else {

			$template = $this->find_template_config( $name );
		}

		if ( isset( $template['active'] ) && $template['active'] == 1 ) {
			return true;
		}
		// varify template active from Dashboard Settings
		return false;
	}

	/**
	 * @return template config
	 */

	public function find_template_config( $name ) {

		return shop_ready_find_template_by_name( $name );

	}

	/**
	 * @param template_name
	 * @return template_id
	 */
	protected function lookup( $name ) {

		$template = apply_filters( 'shop_ready_template_config_gl', $this->find_template_config( $name ), $name );

		if ( $template && isset( $template['id'] ) ) {

			return $template['id'];
		}

		return $this->fallback_id();

	}

	protected function fallback_id() {

		$latest_template = array(
			'post_type'   => 'elementor_library',
			'post_status' => 'publish',
			'numberposts' => 1,
			'order'       => 'DESC',
			'orderby'     => 'ID',
		);

		$post = get_posts( $latest_template );

		if ( isset( $post[0] ) ) {

			return $post[0]->ID;
		}

		return null;
	}

}