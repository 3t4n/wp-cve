<?php
function cwmp_load_admin_css() {
		wp_enqueue_style( 'cwmp_notices', CWMP_PLUGIN_ADMIN_URL.'assets/css/notices.css', array(), wp_rand(111,9999), 'all' );
   	   if(isset($_GET['page'])){
   	   if($_GET['page']=="cwmp_admin_relatorios" OR $_GET['page']=="cwmp_admin_checkout" OR $_GET['page']=="cwmp_admin_template" OR $_GET['page']=="cwmp_admin_vendas" OR $_GET['page']=="cwmp_admin_parcelamento" OR $_GET['page']=="cwmp_admin_descontos" OR $_GET['page']=="cwmp_admin_entrega" OR $_GET['page']=="cwmp_admin_comunicacao" OR $_GET['page']=="cwmp_admin_gatilhos" OR $_GET['page']=="cwmp_admin_trafego" OR $_GET['page']=="cwmp_admin_licensas" OR $_GET['page']=="mwp_administracao" ){
			wp_enqueue_style( 'cwmp_style_admin_checkout', CWMP_PLUGIN_ADMIN_URL.'assets/css/administrador.css', array(), wp_rand(111,9999), 'all' );
			wp_enqueue_style( 'cwmp_style_admin_checkout_iconpicker', CWMP_PLUGIN_ADMIN_URL.'assets/css/fontawesome-iconpicker.min.css', array(), wp_rand(111,9999), 'all' );
			wp_enqueue_style( 'cwmp_style_admin_checkout_font_select', CWMP_PLUGIN_ADMIN_URL.'assets/css/jquery.fontselect.css', array(), wp_rand(111,9999), 'all' );
			wp_register_style( 'select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css', false, '1.0', 'all' );
			wp_register_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_style( 'select2css' );
			wp_enqueue_script( 'select2' );
			wp_enqueue_style( 'cwmp_style_admin_checkout_color', CWMP_PLUGIN_ADMIN_URL.'assets/css/coloris.min.css', array(), wp_rand(111,9999), 'all' );
			wp_enqueue_style( 'cwmp_style_admin_checkout_awesome', 'https://site-assets.fontawesome.com/releases/v6.3.0/css/all.css', array(), wp_rand(111,9999), 'all' );
			wp_enqueue_script( 'cwmp_admin_get_js_bootstrap', CWMP_PLUGIN_ADMIN_URL.'assets/js/bootstrap.min.js', array(), wp_rand(111,9999), 'all' );
			wp_enqueue_script( 'cwmp_admin_get_js_iconpicker', CWMP_PLUGIN_ADMIN_URL.'assets/js/fontawesome-iconpicker.js', array(), wp_rand(111,9999), 'all' );
			wp_enqueue_script( 'cwmp_admin_get_js_font_select', CWMP_PLUGIN_ADMIN_URL.'assets/js/jquery.fontselect.js', array(), wp_rand(111,9999), 'all' );
			wp_enqueue_script( 'cwmp_admin_get_js_color', CWMP_PLUGIN_ADMIN_URL.'assets/js/coloris.min.js', array(), wp_rand(111,9999), 'all' );
			wp_enqueue_script( 'cwmp_admin_functions_js', CWMP_PLUGIN_ADMIN_URL.'assets/js/functions.js', array(), wp_rand(111,9999), 'all' );
		}
		}
		wp_enqueue_script( 'cwmp_admin_get_js', CWMP_PLUGIN_ADMIN_URL.'assets/js/administrador.js', array(), wp_rand(111,9999), 'all' );
}
add_action( 'admin_init', 'cwmp_load_admin_css' );