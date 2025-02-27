<?php
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
function custom_shop_order_column($columns){
	$reordered_columns = array();
	foreach( $columns as $key => $column){
		$reordered_columns[$key] = $column;
		if( $key ==  'order_status' ){
			$reordered_columns['cwmp-security'] = "Segurança";
			$reordered_columns['cwmp-whatsapp'] = "WhatsApp";
			$reordered_columns['cwmp-view-rastreio'] = "Rastreio";
		}
	}
	return $reordered_columns;
}
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );
function custom_orders_list_column_content( $column, $post_id ){
	$cwmp_order_info = wc_get_order($post_id);
	switch ( $column )    {
		case 'cwmp-security' :
			$pontos = 0;
			$str = $cwmp_order_info->get_billing_email();
			$search = $cwmp_order_info->get_billing_first_name();
			$valor = $cwmp_order_info->get_total();
			if(preg_match("/{$search}/i", $str)){}else{$pontos = $pontos+1;}
			if( preg_match('/[0-9]/', $str) ){$pontos = $pontos+1;}else{}
			if($valor>="500"){$pontos = $pontos+1;}else{}
			if($pontos<=1){
			echo "<mark class='order-status status-processing tips'><span>Seguro</span></mark>";
			}elseif($pontos==2){
			echo "<mark class='order-status status-on-hold tips'><span>Suspeita</span></mark>";
			}elseif($pontos==3){
			echo "<mark class='order-status status-failed tips'><span>Inseguro</span></mark>";
			}else{}
		break;
			case 'cwmp-whatsapp' :
			echo '<a href="https://api.whatsapp.com/send?phone=55'.preg_replace('/[^0-9]/', '', $cwmp_order_info->get_billing_phone()).'" target="blank"><svg width="30" height="31" viewBox="0 0 256 259" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g clip-path="url(#clip0_126_2)">
			<path d="M67.663 221.823L71.8479 223.916C89.288 234.379 108.819 239.262 128.351 239.262C189.736 239.262 239.96 189.038 239.96 127.653C239.96 98.356 228.101 69.756 207.175 48.829C186.248 27.902 158.345 16.044 128.351 16.044C66.966 16.044 16.741 66.268 17.439 128.351C17.439 149.277 23.717 169.507 34.18 186.945L36.97 191.131L25.81 232.287L67.663 221.823Z" fill="#00E676"/>
			<path d="M219.033 37.668C195.316 13.254 162.531 0 129.048 0C57.898 0 0.698 57.897 1.395 128.35C1.395 150.672 7.673 172.297 18.137 191.828L0 258.096L67.663 240.657C86.497 251.121 107.423 256.004 128.351 256.004C198.804 256.004 256.004 198.106 256.004 127.654C256.004 93.473 242.75 61.385 219.034 37.668H219.033ZM129.048 234.38C110.214 234.38 91.38 229.498 75.336 219.732L71.151 217.639L30.693 228.102L41.156 188.342L38.366 184.156C7.673 134.63 22.322 69.058 72.546 38.365C122.77 7.673 187.643 22.322 218.336 72.546C249.028 122.77 234.379 187.643 184.156 218.336C168.111 228.799 148.58 234.379 129.048 234.379V234.38ZM190.433 156.952L182.76 153.464C182.76 153.464 171.6 148.581 164.624 145.093C163.926 145.093 163.229 144.395 162.531 144.395C160.438 144.395 159.043 145.093 157.648 145.791C157.648 145.791 156.951 146.488 147.185 157.649C146.487 159.044 145.092 159.742 143.697 159.742H142.999C142.302 159.742 140.907 159.044 140.209 158.347L136.721 156.952C129.048 153.464 122.073 149.278 116.492 143.698C115.097 142.303 113.004 140.908 111.609 139.513C106.726 134.63 101.843 129.049 98.356 122.771L97.658 121.376C96.961 120.678 96.961 119.981 96.263 118.586C96.263 117.191 96.263 115.796 96.961 115.098C96.961 115.098 99.751 111.61 101.843 109.518C103.239 108.122 103.936 106.03 105.331 104.635C106.726 102.542 107.424 99.752 106.726 97.659C106.029 94.171 97.658 75.337 95.566 71.152C94.17 69.059 92.776 68.362 90.683 67.664H83.01C81.614 67.664 80.22 68.362 78.824 68.362L78.126 69.059C76.731 69.757 75.336 71.152 73.941 71.849C72.546 73.245 71.848 74.639 70.453 76.035C65.57 82.313 62.78 89.986 62.78 97.659C62.78 103.239 64.175 108.82 66.268 113.703L66.966 115.796C73.244 129.049 81.614 140.908 92.776 151.371L95.566 154.161C97.658 156.254 99.751 157.649 101.146 159.741C115.795 172.298 132.536 181.366 151.37 186.249C153.463 186.946 156.253 186.946 158.346 187.644H165.321C168.809 187.644 172.994 186.249 175.785 184.854C177.877 183.459 179.272 183.459 180.667 182.064L182.063 180.668C183.458 179.273 184.853 178.576 186.248 177.181C187.643 175.786 189.038 174.391 189.736 172.995C191.131 170.205 191.828 166.717 192.526 163.23V158.347C192.526 158.347 191.828 157.649 190.433 156.952V156.952Z" fill="white"/>
			</g>
			<defs>
			<clipPath id="clip0_126_2">
			<rect width="256" height="259" fill="white"/>
			</clipPath>
			</defs>
			</svg></a>
			';
		break;
		case 'cwmp-view-rastreio' :
			global $wpdb;
			global $table_prefix;
			$transportadora_id = get_post_meta($cwmp_order_info->get_ID(), '_cwmp_codigo_transportadora_slug', true);
			$get_campanha = $wpdb->get_results("SELECT * FROM ".$table_prefix."cwmp_transportadoras WHERE id LIKE ".$transportadora_id."");
			if(get_post_meta($cwmp_order_info->get_id(), '_cwmp_codigo_rastreio_slug', true)){
			echo "<a href='".str_replace("{{track}}", get_post_meta($cwmp_order_info->get_id(), '_cwmp_codigo_rastreio_slug', true),$get_campanha[0]->estrutura)."' target='blank'>".str_replace("{track}", get_post_meta($cwmp_order_info->get_id(), '_cwmp_codigo_rastreio_slug', true),$get_campanha[0]->estrutura)."</a>";
			}
		break;
	}
}
