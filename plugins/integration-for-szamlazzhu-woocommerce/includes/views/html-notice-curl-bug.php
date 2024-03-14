<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="notice notice-error wc-szamlazz-notice">
	<button type="button" class="notice-dismiss wc-szamlazz-hide-notice" data-nonce="<?php echo wp_create_nonce( 'wc-szamlazz-hide-notice' )?>" data-notice="curl_bug"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'woocommerce' ); ?></span></button>
	<p><strong>Számlakészítés hiba</strong></p>
	<p><strong>A tárhelyen lévő cURL funkció frissítést igényel!</strong></p>
	<p>A tárhelyen lévő cURL funkció(ezzel kommunikál a számlázz.hu API-val a WooCommerce) az utóbbi napokban frissítve lett a 7.79.0 verzióra, viszont ebben van egy olyan hiba, ami miatt nem készülnek el a számlák. A hibát a 7.79.1-es verzióban már javították.</p>
	<p>Ha saját tárhelyet üzemeltetsz, akkor frissítsd a cURL-t a legújabb verzióra. Ha nem, akkor a tárhelyszolgáltatónak írj légy szíves, ők tudják frissíteni és akkor ismét működni fog a számlakészítés. Bővebb infó a hibáról: <a href="https://github.com/curl/curl/issues/7738">https://github.com/curl/curl/issues/7738</a></p>
	<p><strong>Az automata számlakészítés bizonyos esetekben továbbra is működhet, viszont a WooCommerce-ben nem látod a számlákat, csak a számlázz.hu-n!</strong></p>
</div>
