<?php
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
		include( 'frontend/core/cwmp-functions.php' );
		include( 'frontend/core/cwmp-set-templates.php' );
		include( 'frontend/core/cwmp-notifications.php' );
		include( 'frontend/core/cwmp-shortcodes.php' );
		include( 'frontend/core/cwmp-hooks.php' );
		if(get_option('cwmp_activate_order_bump')=="S" AND get_option('cwmp_license_cwmwp_active')==true){ include( 'frontend/core/cwmp-order-bump.php' ); }
		if(get_option('cwmp_activate_traffic')=="S" AND get_option('cwmp_license_cwmwp_active')==true){ include( 'frontend/core/cwmp-pixels-facebook.php' ); }
		if(get_option('cwmp_activate_desconto_metodo_pagamento')=="S" AND get_option('cwmp_license_cwmwp_active')==true){ include( 'frontend/core/cwmp-descontos.php' ); }
		if(get_option('cwmp_pmwp_active')=="S" AND get_option('cwmp_license_cwmwp_active')==true){ include( 'frontend/core/cwmp-parcelas.php' ); }
}

