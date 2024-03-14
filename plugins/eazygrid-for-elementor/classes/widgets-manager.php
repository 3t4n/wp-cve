<?php
namespace EazyGrid\Elementor\Classes;

defined( 'ABSPATH' ) || die();

class Widgets_Manager {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize
	 */
	public function init() {
		add_action( 'elementor/controls/controls_registered', [$this, 'init_controls'] );
		add_action( 'elementor/widgets/widgets_registered', [$this, 'init_widgets'] );
		add_action( 'elementor/elements/categories_registered', [$this, 'add_eazygrind_elementor_widget_category'] );
	}

	public function add_eazygrind_elementor_widget_category( $elements_manager ) {
		$elements_manager->add_category(
			'eazygrid',
			[
				'title' => __( 'EazyGrid', 'eazygrid-elementor' ),
				'icon'  => 'ezicon-eazy-grid-logo',
			]
		);
	}

	public function init_widgets() {
		foreach ( glob( EAZYGRIDELEMENTOR_PATH . 'widgets/*.php' ) as $filename ) {
			$class_name = str_replace( EAZYGRIDELEMENTOR_PATH . 'widgets/', '', $filename );
			$class_name = str_replace( '-', '_', $class_name );
			$class_name = str_replace( '.php', '', $class_name );
			$class_name = "\\EazyGrid\\Elementor\\Widgets\\{$class_name}";
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name() );
		}
	}

	public function init_controls() {
		$image_selector = '\EazyGrid\Elementor\Controls\Image_Selector';
		\Elementor\Plugin::$instance->controls_manager->register_control( $image_selector::TYPE, new $image_selector() );
	}
}
