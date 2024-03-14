<?php
/**
 * Page options
 *
 * @package NovaPosta\Templates\Admin
 *
 * @var array  $tabs       List of available tabs.
 * @var string $url        Admin page url without any additional GET arguments.
 * @var string $active_tab Slug of active tab.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}
?>
<div class="wrap shipping-nova-poshta-for-woocommerce-wrap">
	<div class="shipping-nova-poshta-for-woocommerce-logo">
		<span>by</span>
		<a href="#" target="_blank">
			<img src="<?php echo esc_url( NOVA_POSHTA_URL . 'assets/build/img/logo-colour.svg' ); ?>" alt="">
		</a>
	</div>
	<?php
	if ( count( $tabs ) > 1 ) {
		require NOVA_POSHTA_PATH . 'templates/admin/page-options/tabs.php';
	}

	$tab_label  = $tabs[ $active_tab ];
	$active_tab = str_replace( '-', '_', $active_tab );
	do_action( "shipping_nova_poshta_for_woocommerce_settings_page_{$active_tab}_tab", $tab_label );
	?>
</div>
