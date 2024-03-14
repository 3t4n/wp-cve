<?php
add_action( 'wp_ajax_cwmp_template_pre', 'cwmp_template_pre' );
add_action( 'wp_ajax_nopriv_cwmp_template_pre', 'cwmp_template_pre' );
function cwmp_template_pre(){
	switch ($_POST['color']) {
		case 'padrao':
			update_option('cwmp_checkout_background','#eeeeee');
			update_option('cwmp_checkout_box_background','#ffffff');
			update_option('cwmp_checkout_primary_color','#000000');
			update_option('cwmp_checkout_secundary_color','#000000');
			update_option('cwmp_checkout_secundary_color_contrast','#ffffff');
			update_option('cwmp_checkout_input_background','#e2e2e2');
			update_option('cwmp_checkout_input_color','#000000');
			update_option('cwmp_checkout_input_hover_background','#969696');
			update_option('cwmp_checkout_input_hover_color','#000000');
			update_option('cwmp_checkout_button_background','#3fc583');
			update_option('cwmp_checkout_button_color','#ffffff');
			update_option('cwmp_checkout_button_hover_background','#3fc583');
			update_option('cwmp_checkout_button_hover_color','#ffffff');
			update_option('cwmp_checkout_success_background','#f9fdf7');
			update_option('cwmp_checkout_success_color','#36b376');
			update_option('cwmp_checkout_box_icon_dados_pessoais','fas fa-user-alt');
			update_option('cwmp_checkout_box_icon_entrega','fas fa-truck');
			update_option('cwmp_checkout_box_icon_frete','fas fa-shipping-fast');
			update_option('cwmp_checkout_box_icon_pagamento','fas fa-credit-card');
			update_option('cwmp_checkout_button_icon_dados_pessoais','fas fa-check');
			update_option('cwmp_checkout_button_icon_entrega','fas fa-check');
			update_option('cwmp_checkout_button_icon_frete','fas fa-check');
			update_option('cwmp_checkout_button_icon_pagamento','fas fa-lock');
			break;
		case 'blue':
			update_option('cwmp_checkout_background','#eeeeee');
			update_option('cwmp_checkout_box_background','#ffffff');
			update_option('cwmp_checkout_primary_color','#067bc2');
			update_option('cwmp_checkout_secundary_color','#067bc2');
			update_option('cwmp_checkout_secundary_color_contrast','#ffffff');
			update_option('cwmp_checkout_input_background','#e2e2e2');
			update_option('cwmp_checkout_input_color','#000000');
			update_option('cwmp_checkout_input_hover_background','#969696');
			update_option('cwmp_checkout_input_hover_color','#000000');
			update_option('cwmp_checkout_button_background','#067bc2');
			update_option('cwmp_checkout_button_color','#ffffff');
			update_option('cwmp_checkout_button_hover_background','#067bc2');
			update_option('cwmp_checkout_button_hover_color','#ffffff');
			update_option('cwmp_checkout_success_background','#bddeee');
			update_option('cwmp_checkout_success_color','#067bc2');
			update_option('cwmp_checkout_box_icon_dados_pessoais','fas fa-user-alt');
			update_option('cwmp_checkout_box_icon_entrega','fas fa-truck');
			update_option('cwmp_checkout_box_icon_frete','fas fa-shipping-fast');
			update_option('cwmp_checkout_box_icon_pagamento','fas fa-credit-card');
			update_option('cwmp_checkout_button_icon_dados_pessoais','fas fa-check');
			update_option('cwmp_checkout_button_icon_entrega','fas fa-check');
			update_option('cwmp_checkout_button_icon_frete','fas fa-check');
			update_option('cwmp_checkout_button_icon_pagamento','fas fa-lock');
			break;
		case 'red':
		
			update_option('cwmp_checkout_background','#eeeeee');
			update_option('cwmp_checkout_box_background','#ffffff');
			update_option('cwmp_checkout_primary_color','#e50303');
			update_option('cwmp_checkout_secundary_color','#e50303');
			update_option('cwmp_checkout_secundary_color_contrast','#ffffff');
			update_option('cwmp_checkout_input_background','#e2e2e2');
			update_option('cwmp_checkout_input_color','#000000');
			update_option('cwmp_checkout_input_hover_background','#969696');
			update_option('cwmp_checkout_input_hover_color','#000000');
			update_option('cwmp_checkout_button_background','#e50303');
			update_option('cwmp_checkout_button_color','#ffffff');
			update_option('cwmp_checkout_button_hover_background','#e50303');
			update_option('cwmp_checkout_button_hover_color','#ffffff');
			update_option('cwmp_checkout_success_background','#ffc1c1');
			update_option('cwmp_checkout_success_color','#e50303');
			update_option('cwmp_checkout_box_icon_dados_pessoais','fas fa-user-alt');
			update_option('cwmp_checkout_box_icon_entrega','fas fa-truck');
			update_option('cwmp_checkout_box_icon_frete','fas fa-shipping-fast');
			update_option('cwmp_checkout_box_icon_pagamento','fas fa-credit-card');
			update_option('cwmp_checkout_button_icon_dados_pessoais','fas fa-check');
			update_option('cwmp_checkout_button_icon_entrega','fas fa-check');
			update_option('cwmp_checkout_button_icon_frete','fas fa-check');
			update_option('cwmp_checkout_button_icon_pagamento','fas fa-lock');
			break;
		case 'green':
			update_option('cwmp_checkout_background','#eeeeee');
			update_option('cwmp_checkout_box_background','#ffffff');
			update_option('cwmp_checkout_primary_color','#47c93b');
			update_option('cwmp_checkout_secundary_color','#47c93b');
			update_option('cwmp_checkout_secundary_color_contrast','#ffffff');
			update_option('cwmp_checkout_input_background','#e2e2e2');
			update_option('cwmp_checkout_input_color','#000000');
			update_option('cwmp_checkout_input_hover_background','#969696');
			update_option('cwmp_checkout_input_hover_color','#000000');
			update_option('cwmp_checkout_button_background','#47c93b');
			update_option('cwmp_checkout_button_color','#ffffff');
			update_option('cwmp_checkout_button_hover_background','#47c93b');
			update_option('cwmp_checkout_button_hover_color','#ffffff');
			update_option('cwmp_checkout_success_background','#b2efab');
			update_option('cwmp_checkout_success_color','#47c93b');
			update_option('cwmp_checkout_box_icon_dados_pessoais','fas fa-user-alt');
			update_option('cwmp_checkout_box_icon_entrega','fas fa-truck');
			update_option('cwmp_checkout_box_icon_frete','fas fa-shipping-fast');
			update_option('cwmp_checkout_box_icon_pagamento','fas fa-credit-card');
			update_option('cwmp_checkout_button_icon_dados_pessoais','fas fa-check');
			update_option('cwmp_checkout_button_icon_entrega','fas fa-check');
			update_option('cwmp_checkout_button_icon_frete','fas fa-check');
			update_option('cwmp_checkout_button_icon_pagamento','fas fa-lock');
			break;
	}
	wp_die();
}



if(get_option("cwmp_field_country")=="1"){ update_option('cwmp_field_country', 'S'); }
if(get_option("cwmp_field_country")!="S" AND get_option("cwmp_field_country")!="N" ){ update_option('cwmp_field_country', 'N'); }
if(get_option("cwmp_optional_present")=="1"){ update_option('cwmp_optional_present', 'S'); }
if(get_option("cwmp_optional_present")!="S" AND get_option("cwmp_optional_present")!="N" ){ update_option('cwmp_optional_present', 'N'); }
if(get_option("cwmp_view_error")=="1"){ update_option('cwmp_view_error', 'S'); }
if(get_option("cwmp_view_error")!="S" AND get_option("cwmp_view_error")!="N" ){ update_option('cwmp_view_error', 'N'); }
if(get_option("cwmp_ignore_cart")=="1"){ update_option('cwmp_ignore_cart', 'S'); }
if(get_option("cwmp_ignore_cart")!="S" AND get_option("cwmp_ignore_cart")!="N" ){ update_option('cwmp_ignore_cart', 'N'); }
if(get_option("cwmp_open_resumo")=="1"){ update_option('cwmp_open_resumo', 'S'); }
if(get_option("cwmp_open_resumo")!="S" AND get_option("cwmp_open_resumo")!="N" ){ update_option('cwmp_open_resumo', 'N'); }
if(get_option("cwmp_view_remove_whatsapp")=="1"){ update_option('cwmp_view_remove_whatsapp', 'S'); }
if(get_option("cwmp_view_remove_whatsapp")!="S" AND get_option("cwmp_view_remove_whatsapp")!="N" ){ update_option('cwmp_view_remove_whatsapp', 'N'); }
if(get_option("cwmp_view_active_address")=="1"){ update_option('cwmp_view_active_address', 'S'); }
if(get_option("cwmp_view_active_address")!="S" AND get_option("cwmp_view_active_address")!="N" ){ update_option('cwmp_view_active_address', 'N'); }
if(get_option("cwmp_view_active_obs")=="1"){ update_option('cwmp_view_active_obs', 'S'); }
if(get_option("cwmp_view_active_obs")!="S" AND get_option("cwmp_view_active_obs")!="N" ){ update_option('cwmp_view_active_obs', 'N'); }
if(get_option("cwmp_safe_environment")=="1"){ update_option('cwmp_safe_environment', 'S'); }
if(get_option("cwmp_safe_environment")!="S" AND get_option("cwmp_safe_environment")!="N" ){ update_option('cwmp_safe_environment', 'N'); }

if(get_option('cwmp_checkout_background')==''){ update_option('cwmp_checkout_background','#eeeeee'); }
if(get_option('cwmp_checkout_box_background')==''){ update_option('cwmp_checkout_box_background','#ffffff'); }
if(get_option('cwmp_checkout_primary_color')==''){ update_option('cwmp_checkout_primary_color','#000000'); }
if(get_option('cwmp_checkout_secundary_color')==''){ update_option('cwmp_checkout_secundary_color','#000000'); }
if(get_option('cwmp_checkout_secundary_color_contrast')==''){ update_option('cwmp_checkout_secundary_color_contrast','#ffffff'); }
if(get_option('cwmp_checkout_input_background')==''){ update_option('cwmp_checkout_input_background','#e2e2e2'); }
if(get_option('cwmp_checkout_input_color')==''){ update_option('cwmp_checkout_input_color','#000000'); }
if(get_option('cwmp_checkout_input_hover_background')==''){ update_option('cwmp_checkout_input_hover_background','#969696'); }
if(get_option('cwmp_checkout_input_hover_color')==''){ update_option('cwmp_checkout_input_hover_color','#000000'); }
if(get_option('cwmp_checkout_button_background')==''){ update_option('cwmp_checkout_button_background','#3fc583'); }
if(get_option('cwmp_checkout_button_color')==''){ update_option('cwmp_checkout_button_color','#ffffff'); }
if(get_option('cwmp_checkout_button_hover_background')==''){ update_option('cwmp_checkout_button_hover_background','#3fc583'); }
if(get_option('cwmp_checkout_button_hover_color')==''){ update_option('cwmp_checkout_button_hover_color','#ffffff'); }
if(get_option('cwmp_checkout_success_background')==''){ update_option('cwmp_checkout_success_background','#f9fdf7'); }
if(get_option('cwmp_checkout_success_color')==''){ update_option('cwmp_checkout_success_color','#36b376'); }
if(get_option('cwmp_checkout_box_icon_dados_pessoais')==''){ update_option('cwmp_checkout_box_icon_dados_pessoais','fas fa-user-alt'); }
if(get_option('cwmp_checkout_box_icon_entrega')==''){ update_option('cwmp_checkout_box_icon_entrega','fas fa-truck'); }
if(get_option('cwmp_checkout_box_icon_frete')==''){ update_option('cwmp_checkout_box_icon_frete','fas fa-shipping-fast'); }
if(get_option('cwmp_checkout_box_icon_pagamento')==''){ update_option('cwmp_checkout_box_icon_pagamento','fas fa-credit-card'); }
if(get_option('cwmp_checkout_button_icon_dados_pessoais')==''){ update_option('cwmp_checkout_button_icon_dados_pessoais','fas fa-check'); }
if(get_option('cwmp_checkout_button_icon_entrega')==''){ update_option('cwmp_checkout_button_icon_entrega','fas fa-check'); }
if(get_option('cwmp_checkout_button_icon_frete')==''){ update_option('cwmp_checkout_button_icon_frete','fas fa-check'); }
if(get_option('cwmp_checkout_button_icon_pagamento')==''){ update_option('cwmp_checkout_button_icon_pagamento','fas fa-lock'); }
