<?php



class CwmpDescontos {
    // Função para aplicar desconto por método de pagamento
    public function aplicarDescontoMetodoPagamento($metodo, $desconto, $tipo, $label) {
		if (is_admin() && !defined('DOING_AJAX')) return;
		if (did_action('woocommerce_before_calculate_totals') >= 2) return;
		if (WC()->session->get('chosen_payment_method') === $metodo) {
			if($tipo=="percent"){
			$desconto = WC()->cart->subtotal * $desconto;
			}else{
			$desconto = (int)$desconto;
			}
			WC()->cart->add_fee(__($label, 'woocommerce'), -$desconto);
		}
    }
    // Função para aplicar desconto por método de entrega
    public function aplicarDescontoMetodoEntrega($metodo, $desconto, $tipo, $label) {
		if (is_admin() && !defined('DOING_AJAX')) return;
		if (did_action('woocommerce_before_calculate_totals') >= 2) return;
		$chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
		foreach ($chosen_shipping_methods as $elemento) {
			if (strpos($elemento, $metodo) === 0) {
				$metodo = $elemento;
				break;
			}
		}
		if (in_array($metodo, $chosen_shipping_methods)) {
			if($tipo=="percent"){
			$desconto = WC()->cart->get_shipping_total() * $desconto;
			}else{
			$desconto = (int)$desconto;
			}
			
			WC()->cart->add_fee(__($label, 'woocommerce'), -$desconto);
		}
    }
    // Função para aplicar desconto por quantidade de itens
    public function aplicarDescontoQuantidadeItens($produto, $min, $max, $desconto, $tipo, $label) {
		$produto_id = $produto; // Substitua 123 pelo ID do seu produto
		$quantidade_minima = $min;
		$quantidade_maxima = $max;
		$quantidade_total = 0;
		foreach (WC()->cart->get_cart() as $item) {
			if ($item['product_id'] == $produto_id) {
				$quantidade_total += $item['quantity'];
			}
		}
		if ($quantidade_total >= $quantidade_minima && $quantidade_total <= $quantidade_maxima) {
			$subtotal = WC()->cart->subtotal;
			if($tipo=="percent"){
			$desconto =  $subtotal * $desconto;
			}else{
			$desconto = (int)$desconto;
			}
			WC()->cart->add_fee($label, -$desconto);
		}
    }
    // Função para aplicar desconto por valor total da compra
    public function aplicarDescontoValorTotalCompra($valorMinimo, $desconto, $tipo, $label) {
		if (WC()->cart->subtotal >= $valorMinimo) {
			if($tipo=="percent"){
			$desconto = WC()->cart->subtotal * $desconto;
			}else{
			$desconto = (int)$desconto;
			}
			WC()->cart->add_fee($label, -$desconto);
		}
    }
    // Função para aplicar desconto por categorias específicas
    public function aplicarDescontoCategoriasEspecificas($categoria, $desconto, $tipo, $label) {
		$categoria_id = $categoria; // Substitua 123 pelo ID da categoria desejada
		$desconto_porcentagem = $desconto; // 10% de desconto
		$total_desconto = 0;
		foreach (WC()->cart->get_cart() as $item) {
			$produto = $item['data'];
			if (has_term($categoria_id, 'product_cat', $produto->get_id())) {
				if($tipo=="percent"){
					$desconto_item = $produto->get_price() * $desconto_porcentagem * $item['quantity'];
				}else{
					$desconto_item = $desconto_porcentagem * $item['quantity'];
				}
					$total_desconto += $desconto_item;
			}
		}
		if ($total_desconto > 0) {
			WC()->cart->add_fee($label, -$total_desconto);
		}
    }
}
add_action( 'woocommerce_cart_calculate_fees','pmwp_add_discount_payment_method', 20, 1 );
function pmwp_add_discount_payment_method( $cart_object ) {
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results("SELECT * FROM {$table_prefix}cwmp_discounts WHERE tipo = '1'");
	if ($result) {
		for ($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$id = $row->id;
			$label = $row->label;
			$tipo = $row->tipo;
			$metodo = $row->metodo;
			if($row->discoutType=="percent"){
				$desconto = $row->discoutValue/100;
			}else{
				$desconto = $row->discoutValue;
			}
			$descontos = new CwmpDescontos();
			$descontos->aplicarDescontoMetodoPagamento($metodo, $desconto, $row->discoutType, $label);
		}
	} else {
	}
}
add_action( 'woocommerce_cart_calculate_fees','pmwp_add_discount_shipping_method', 20, 1 );
function pmwp_add_discount_shipping_method( $cart_object ) {
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results("SELECT * FROM {$table_prefix}cwmp_discounts WHERE tipo = '2'");
	if ($result) {
		for ($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$id = $row->id;
			$label = $row->label;
			$tipo = $row->tipo;
			$metodo = $row->metodo;
			if($row->discoutType=="percent"){
				$desconto = $row->discoutValue/100;
			}else{
				$desconto = $row->discoutValue;
			}
			$descontos = new CwmpDescontos();
			$descontos->aplicarDescontoMetodoEntrega($metodo, $desconto, $row->discoutType, $label);
		}
	} else {
	}
}
add_action( 'woocommerce_cart_calculate_fees','pmwp_add_discount_product_qtd', 20, 1 );
function pmwp_add_discount_product_qtd( $cart_object ) {
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results("SELECT * FROM {$table_prefix}cwmp_discounts WHERE tipo = '3'");
	if ($result) {
		for ($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$label = $row->label;
			$produto = $row->metodo;
			$min = $row->minQtd;
			$max = $row->maxQtd;
			if($row->discoutType=="percent"){
				$desconto = $row->discoutValue/100;
			}else{
				$desconto = $row->discoutValue;
			}
			$descontos = new CwmpDescontos();
			$descontos->aplicarDescontoQuantidadeItens($produto, $min, $max, $desconto, $row->discoutType, $label);
		}
	} else {
	}
}
add_action( 'woocommerce_cart_calculate_fees','pmwp_add_discount_total_cart', 20, 1 );
function pmwp_add_discount_total_cart( $cart_object ) {
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results("SELECT * FROM {$table_prefix}cwmp_discounts WHERE tipo = '4'");
	if ($result) {
		for ($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$label = $row->label;
			$valorMinimo = $row->valueMax;
			if($row->discoutType=="percent"){
				$desconto = $row->discoutValue/100;
			}else{
				$desconto = $row->discoutValue;
			}
			$descontos = new CwmpDescontos();
			$descontos->aplicarDescontoValorTotalCompra($valorMinimo, $desconto, $row->discoutType, $label);
			
		}
	} else {
	}
}
add_action( 'woocommerce_cart_calculate_fees','pmwp_add_discount_categorie', 20, 1 );
function pmwp_add_discount_categorie( $cart_object ) {
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results("SELECT * FROM {$table_prefix}cwmp_discounts WHERE tipo = '5'");
	if ($result) {
		for ($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$label = $row->label;
			$categoria = $row->category;
			if($row->discoutType=="percent"){
				$desconto = $row->discoutValue/100;
			}else{
				$desconto = $row->discoutValue;
			}
			$descontos = new CwmpDescontos();
			$descontos->aplicarDescontoCategoriasEspecificas($categoria, $desconto, $row->discoutType, $label);
			
		}
	} else {
	}
}