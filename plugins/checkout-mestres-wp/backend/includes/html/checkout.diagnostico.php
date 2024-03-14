<?php
	$args = array();
	
	if ( ! is_plugin_active( 'woocommerce-extra-checkout-fields-for-brazil/woocommerce-extra-checkout-fields-for-brazil.php' ) ) {
		$args['box'][1]['title'] = __( 'Brazilian Market', 'checkout-mestres-wp');
		$args['box'][1]['description'] = __( 'Você precisa ativar o plugin Brazilian Market.', 'checkout-mestres-wp');
		$args['box'][1]['button']['class'] = 'red';
	}else{
		$args['box'][101]['title'] = __( 'Brazilian Market', 'checkout-mestres-wp');
		$args['box'][101]['description'] = __( 'Parabéns, você está com o plugin Brazilian Market ativo!', 'checkout-mestres-wp');
		$args['box'][101]['button']['class'] = 'green';

		$cwmp_brazilian = get_option('wcbcf_settings');
		if(empty($cwmp_brazilian['maskedinput'])){
			$args['box'][2]['title'] = __( 'Mascarás', 'checkout-mestres-wp');
			$args['box'][2]['description'] = __( 'Você precisa ativar as mascaras no plugin Brazilian Market', 'checkout-mestres-wp');
			$args['box'][2]['button']['class'] = 'red';
		}else{
			$args['box'][102]['title'] = __( 'Mascarás', 'checkout-mestres-wp');
			$args['box'][102]['description'] = __( 'Parabéns, você ativou corretamente as mascaras no plugin Brazilian Market', 'checkout-mestres-wp');
			$args['box'][102]['button']['class'] = 'green';
		}
	}
	
	echo cwmpAdminCreateDiagnostic($args);
	
	
	include "config.copyright.php";


?>