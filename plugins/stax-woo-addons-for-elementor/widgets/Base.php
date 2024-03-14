<?php

namespace StaxWoocommerce\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use StaxWoocommerce\StaxWidgets;

/**
 * Class Base
 * @package StaxWoocommerce\Widgets
 */
abstract class Base extends \Elementor\Widget_Base {

	/**
	 * Base constructor.
	 *
	 * @param array $data
	 * @param null $args
	 * @param bool $resources
	 *
	 * @throws \Exception
	 */
	public function __construct( $data = [], $args = null, $resources = true ) {
		parent::__construct( $data, $args );

		if ( $resources ) {
			$this->register_widget_resources();
		}
	}

	/**
	 * Register widget resources (CSS/JS)
	 *
	 * @param array $dependencies
	 */
	public function register_widget_resources( $dependencies = [] ) {
		foreach ( StaxWidgets::instance()->get_widgets( true ) as $folder => $widget ) {
			if ( $widget['slug'] === $this->get_name() ) {
				$suffix = '.min';

				if ( defined( 'STAX_WOO_DEV' ) && STAX_WOO_DEV === true ) {
					$suffix = '';
				}

				$widget_script = STAX_WOO_WIDGET_PATH . $folder . '/component' . $suffix . '.js';
				$widget_style  = STAX_WOO_WIDGET_PATH . $folder . '/component' . $suffix . '.css';

				if ( file_exists( $widget_script ) ) {
					$js_dep = [ 'jquery' ];

					if ( isset( $dependencies['js'] ) && is_array( $dependencies['js'] ) ) {
						$js_dep = $dependencies['js'];
					}

					wp_register_script(
						$this->get_widget_script_handle(),
						STAX_WOO_WIDGET_URL . $folder . '/component' . $suffix . '.js',
						$js_dep,
						STAX_WOO_VERSION,
						true
					);
				}

				if ( file_exists( $widget_style ) ) {
					$css_dep = [];

					if ( isset( $dependencies['css'] ) && is_array( $dependencies['css'] ) ) {
						$css_dep = $dependencies['css'];
					}

					wp_register_style(
						$this->get_widget_style_handle(),
						STAX_WOO_WIDGET_URL . $folder . '/component' . $suffix . '.css',
						$css_dep,
						STAX_WOO_VERSION,
						'all'
					);
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function get_categories() {
		return [ 'stax-woo-elementor' ];
	}

	/**
	 * @return array
	 */
	public function get_script_depends() {
		return [ $this->get_widget_script_handle() ];
	}

	/**
	 * @return array
	 */
	public function get_style_depends() {
		return [ $this->get_widget_style_handle() ];
	}

	/**
	 * Content template
	 */
	protected function _content_template() {
	}

	/**
	 * Enqueue resources (CSS/JS)
	 */
	protected function enqueue_resources() {
		wp_enqueue_script( $this->get_widget_script_handle() );
		wp_print_styles( $this->get_widget_style_handle() );
	}

	/**
	 * @return string
	 */
	protected function get_widget_script_handle() {
		return $this->get_name() . '-script';
	}

	/**
	 * @return string
	 */
	protected function get_widget_style_handle() {
		return $this->get_name() . '-style';
	}

	/**
	 * Product rendered
	 */
	protected function require_extra_classes() {
		require_once STAX_WOO_CORE_PATH . 'woocommerce/CurrentQueryRenderer.php';
		require_once STAX_WOO_CORE_PATH . 'woocommerce/ProductsRenderer.php';
	}

}
