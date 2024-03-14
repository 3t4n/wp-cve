<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VI_WPRODUCTBUILDER_F_FrontEnd_Sidebar {
	protected $data;

	public function __construct() {
		/*Register sidebar*/
		add_action( 'woocommerce_product_builder_single_top', array( $this, 'register_sidebar' ), 20 );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
	}

	/**
	 * Register sidebar
	 */
	public function widgets_init() {
		/*Include widget class price filter*/
		if ( is_file( VI_WPRODUCTBUILDER_F_INCLUDES . "class-widget-price-filter.php" ) ) {
			require_once VI_WPRODUCTBUILDER_F_INCLUDES . "class-widget-price-filter.php";
		}
		/*Include widget class rate filter*/
		if ( is_file( VI_WPRODUCTBUILDER_F_INCLUDES . "class-widget-rating-filter.php" ) ) {
			require_once VI_WPRODUCTBUILDER_F_INCLUDES . "class-widget-rating-filter.php";
		}
		/*Include widget class attribute filter*/
		if ( is_file( VI_WPRODUCTBUILDER_F_INCLUDES . "class-widget-layered-nav.php" ) ) {
			require_once VI_WPRODUCTBUILDER_F_INCLUDES . "class-widget-layered-nav.php";
		}
		register_sidebar(
			array(
				'name'          => __( 'WC Product Builder Sidebar', 'woo-product-builder' ),
				'id'            => 'woopb-sidebar',
				'description'   => __( 'Widgets in this area will be shown on product builder page.', 'woo-product-builder' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title woocommerce-product-builder-title">',
				'after_title'   => '</h2>',
			)
		);
		register_widget( 'VI_WPRODUCTBUILDER_F_Widget_Price_Filter' );
		register_widget( 'VI_WPRODUCTBUILDER_F_Widget_Rating_Filter' );
		register_widget( 'VI_WPRODUCTBUILDER_F_Widget_Layered_Nav' );
	}

	/**
	 * Sidebar area
	 */
	public function register_sidebar() {
		if ( is_active_sidebar( 'woopb-sidebar' ) ) { ?>
			<div class="woocommerce-product-builder-sidebar">
				<?php dynamic_sidebar( 'woopb-sidebar' ); ?>
			</div>
		<?php }
	}
}