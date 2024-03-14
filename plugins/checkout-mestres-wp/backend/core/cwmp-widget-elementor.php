<?php
function register_cwmp_elementor_widget( $widgets_manager ) {
	require_once( __DIR__ . '/widgets/qrcode-pix.php' );
	require_once( __DIR__ . '/widgets/copypast-pix.php' );
	require_once( __DIR__ . '/widgets/button-boleto.php' );
	require_once( __DIR__ . '/widgets/checkout.php' );
	//require_once( __DIR__ . '/widgets/simulador-frete.php' );

	$widgets_manager->register( new \QR_Code_Pix() );
	$widgets_manager->register( new \Copy_Past_Pix() );
	$widgets_manager->register( new \Button_Boleto() );
	$widgets_manager->register( new \Checkout() );
	//$widgets_manager->register( new \Simulador_Frete() );
}
add_action( 'elementor/widgets/register', 'register_cwmp_elementor_widget' );

function cmwp_elementor_addons_create_category($elements_manager) {
    $elements_manager->add_category(
        'cwmp-addons',
        [
            'title' => __('Mestres do WP', 'checkout-mestres-wp'),
            'icon' => 'fa fa-shopping-cart',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'cmwp_elementor_addons_create_category');