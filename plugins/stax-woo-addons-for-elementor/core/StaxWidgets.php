<?php

namespace StaxWoocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Plugin;

/**
 * Class StaxWidgets
 * @package StaxWoocommerce
 */
class StaxWidgets {

	/**
	 * @var null
	 */
	public static $instance;

	/**
	 * @return StaxWidgets|null
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * StaxWidgets constructor.
	 */
	public function __construct() {
		if ( Utils::woocommerce_is_active() ) {
			add_action( 'elementor/elements/categories_registered', [ $this, 'register_elementor_category' ] );
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_css' ] );
		}
	}

	/**
	 * Get widgets
	 *
	 * @param bool $active
	 * @param bool $withStatus
	 *
	 * @return array
	 */
	public function get_widgets( $active = false, $withStatus = false ) {
		$widgets = [];

		$widgets['products'] = [
			'scope' => 'Products',
			'name'  => 'Products',
			'slug'  => 'stax-woo-products'
		];

		$widgets['product-images'] = [
			'scope' => 'ProductImages',
			'name'  => 'Product Images',
			'slug'  => 'stax-woo-product-images'
		];

		// Remove disabled widgets
		if ( $active && ! $withStatus ) {
			$disabled_widgets = get_option( '_stax_woocommerce_disabled_widgets', [] );
			foreach ( $widgets as $k => $widget ) {
				if ( isset( $disabled_widgets[ $widget['slug'] ] ) ) {
					unset( $widgets[ $k ] );
				}
			}
		}

		if ( $withStatus ) {
			$disabled_widgets = get_option( '_stax_woocommerce_disabled_widgets', [] );
			foreach ( $widgets as $k => $widget ) {
				if ( isset( $disabled_widgets[ $widget['slug'] ] ) ) {
					$widgets[ $k ]['status'] = false;
				} else {
					$widgets[ $k ]['status'] = true;
				}
			}
		}

		return $widgets;
	}

	/**
	 * Register Elementor widgets
	 */
	public function register_widgets() {
		// get our own widgets up and running:
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( Widget_Base::class ) && class_exists( Plugin::class ) && is_callable( Plugin::class, 'instance' ) ) {
			$elementor = \Elementor\Plugin::instance();

			if ( isset( $elementor->widgets_manager ) && method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
				// Require Base class for widgets
				require_once STAX_WOO_PATH . '/widgets/Base.php';

				$elements = $this->get_widgets( true );
				foreach ( $elements as $folder => $element ) {
					if ( $widget_file = $this->get_widget_file( $folder ) ) {

						require_once $widget_file;
						$class_name = '\StaxWoocommerce\Widgets\\' . $element['scope'] . '\Component';
						$elementor->widgets_manager->register_widget_type( new $class_name );
					}
				}
			}
		}
	}

	/**
	 * Register new Elementor category
	 */
	public function register_elementor_category() {
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( Widget_Base::class ) && class_exists( Plugin::class ) && is_callable( Plugin::class, 'instance' ) ) {
			\Elementor\Plugin::instance()->elements_manager->add_category(
				'stax-woo-elementor',
				[
					'title' => 'Stax Woocommerce Elements',
					'icon'  => 'fa fa-plug'
				]
			);
		}
	}

	/**
	 * Get widget file path
	 *
	 * @param $folder
	 *
	 * @return bool|string
	 */
	public function get_widget_file( $folder ) {
		$template_file = STAX_WOO_WIDGET_PATH . $folder . '/Component.php';

		if ( $template_file && is_readable( $template_file ) ) {
			return $template_file;
		}

		return false;
	}

	/**
	 * Enqueue Elementor Editor CSS
	 */
	public function editor_css() {
		$this->enqueue_icons();

		wp_enqueue_style(
			'stax-elementor-panel-label-style',
			STAX_WOO_ASSETS_URL . 'css/label.css',
			null,
			STAX_WOO_VERSION
		);
	}

	public function enqueue_icons() {
		wp_enqueue_style(
			'stax-elementor-panel-style',
			STAX_WOO_ASSETS_URL . 'css/editor.css',
			null,
			STAX_WOO_VERSION
		);
	}

}

StaxWidgets::instance();
