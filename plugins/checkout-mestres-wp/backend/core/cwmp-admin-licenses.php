<?php

add_action( 'wp_ajax_cmwp_get_plugins_licensa', 'cmwp_get_plugins_licensa' );
add_action( 'wp_ajax_nopriv_cmwp_get_plugins_licensa', 'cmwp_get_plugins_licensa' );
function cmwp_get_plugins_licensa(){
	$menu_nonce = wp_create_nonce('menu_nonce');
    if (isset($_GET['page']) && $_GET['page'] === 'cwmp_admin_menu') {
        // Nonce válido, execute as ações necessárias
        if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'menu_nonce')) {
            echo '<div class="notice notice-success is-dismissible"><p>Ação realizada com sucesso!</p></div>';
        } else {
            // Nonce inválido, redirecionar ou lidar com a situação de não autorizado
            echo '<div class="notice notice-error is-dismissible"><p>Erro: Nonce inválido ou ausente.</p></div>';
            return;
        }
    }
	$return_licensa = wp_remote_get("https://www.mestresdowp.com.br/checkout/api-messages-for-elementor.php?email=".$_POST['email']."&product=".$_POST['product']."&url=".$_POST['url']."&tipo=".$_POST['tipo']."", array('headers' => array('Content-Type' => 'application/json')));
	$licensa_body = wp_remote_retrieve_body($return_licensa);
	if($licensa_body==true){
		update_option('cwmp_license_cwmwp_tipo',$_POST['tipo']);
		update_option('cwmp_license_cwmwp_active','true');
		update_option('cwmp_license_cwmwp_email',$_POST['email']);
		update_option('cwmp_license_cwmwp_pirata','');
		update_option('cwmp_license_pmwp_active','true');
		update_option('cwmp_license_pmwp_email',$_POST['email']);
		update_option('cwmp_license_lpw_active','true');
		update_option('cwmp_license_lpw_email',$_POST['email']);
		update_option('cwmp_license_dashmwp_active','true');
		update_option('cwmp_license_dashmwp_email',$_POST['email']);
	}else{
		update_option('cwmp_license_cwmwp_tipo','');
		update_option('cwmp_license_cwmwp_active','');
		update_option('cwmp_license_cwmwp_email','');
		update_option('cwmp_license_cwmwp_pirata','');
		update_option('cwmp_license_pmwp_active','');
		update_option('cwmp_license_pmwp_email','');
		update_option('cwmp_license_lpw_active','');
		update_option('cwmp_license_lpw_email','');
		update_option('cwmp_license_dashmwp_active','');
		update_option('cwmp_license_dashmwp_email','');
	}
	wp_die();
}
add_action( 'wp_ajax_cmwp_get_plugins_licensa_remove', 'cmwp_get_plugins_licensa_remove' );
add_action( 'wp_ajax_nopriv_cmwp_get_plugins_licensa_remove', 'cmwp_get_plugins_licensa_remove' );
function cmwp_get_plugins_licensa_remove(){
	$menu_nonce = wp_create_nonce('menu_nonce');
    if (isset($_GET['page']) && $_GET['page'] === 'cwmp_admin_menu') {
        // Nonce válido, execute as ações necessárias
        if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'menu_nonce')) {
            echo '<div class="notice notice-success is-dismissible"><p>Ação realizada com sucesso!</p></div>';
        } else {
            // Nonce inválido, redirecionar ou lidar com a situação de não autorizado
            echo '<div class="notice notice-error is-dismissible"><p>Erro: Nonce inválido ou ausente.</p></div>';
            return;
        }
    }
	$return_licensa = wp_remote_get("https://www.mestresdowp.com.br/checkout/api-messages-for-elementor.php?email=".$_POST['email']."&product=".$_POST['product']."&url=".$_POST['url']."&tipo=".$_POST['tipo']."&action=remove", array('headers' => array('Content-Type' => 'application/json')));
	$licensa_body = wp_remote_retrieve_body($return_licensa);
	if($licensa_body==true){
		update_option('cwmp_license_cwmwp_tipo','');
		update_option('cwmp_license_cwmwp_active','');
		update_option('cwmp_license_cwmwp_email','');
		update_option('cwmp_license_cwmwp_pirata','');
		update_option('cwmp_license_pmwp_active','');
		update_option('cwmp_license_pmwp_email','');
		update_option('cwmp_license_lpw_active','');
		update_option('cwmp_license_lpw_email','');
		update_option('cwmp_license_dashmwp_active','');
		update_option('cwmp_license_dashmwp_email','');
	}else{}
	wp_die();
}
function cwmp_get_sites(){
	if($_SERVER['PHP_SELF']=="/wp-admin/plugins.php" OR $_SERVER['PHP_SELF']=="/wp-admin/update-core.php" OR $_SERVER['PHP_SELF']=="/wp-admin/index.php"){
		$data = array(
			'site' => get_bloginfo('url') ,
			'status' => get_option('cwmp_license_cwmwp_active'),
			'versao' => CWMP_VERSAO,
			'tipo' => get_option('cwmp_license_cwmwp_tipo'),
			'email' => get_option('cwmp_license_cwmwp_email')
		);
		$send = wp_remote_post('https://www.mestresdowp.com.br/checkout/get_sites.php', array(
			'method' => 'POST',
			'body' => $data
		));
		$body = wp_remote_retrieve_body($send);
		if($body=="PIRATA"){
			update_option('cwmp_license_cwmwp_active','');
			update_option('cwmp_license_cwmwp_email','');
			update_option('cwmp_license_cwmwp_pirata','');
			update_option('cwmp_license_pmwp_active','');
			update_option('cwmp_license_pmwp_email','');
			update_option('cwmp_license_lpw_active','');
			update_option('cwmp_license_lpw_email','');
			update_option('cwmp_license_dashmwp_active','');
			update_option('cwmp_license_dashmwp_email','');
			update_option('cwmp_license_cwmwp_tipo','');
		}
	}
}
add_action("admin_init", "cwmp_get_sites");