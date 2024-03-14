<?php
function cwmp_html_price($produto){
	if(get_option('parcelas_mwp_juros')==false){ $taxaJurosMensal = 4.99 / 100; }else{ $taxaJurosMensal = get_option('parcelas_mwp_juros')/100; }
	if(get_option('parcelas_mwp_payment_parcelas_sem_juros')==false){ $parcelasSemJuros = 3; }else{ $parcelasSemJuros = get_option('parcelas_mwp_juros'); }
	if(get_option('parcelas_mwp_valor_min')==false){ $valorMinimoParcela = 20; }else{ $valorMinimoParcela = get_option('parcelas_mwp_valor_min'); }
	if(get_option('parcelas_mwp_payment_second_parcels')==false){ $numeroMaxParcelas = 12; }else{ $numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels'); }
	$format = get_option('parcelas_mwp_payment_second_pre');	
	$product = wc_get_product($produto);
	$product_type = $product->get_type();
	$html = "";
	switch ($product_type) {
		case 'simple':
			$regular = $product->get_regular_price();
			if($product->is_on_sale()){ $sale = $product->get_sale_price();	}
			break;
		case 'variable':
			$variations = $product->get_available_variations();
			$lowest_sale_price = null;
			$highest_regular_price = null;
			foreach ($variations as $variation) {
				$variation_obj = wc_get_product($variation['variation_id']);
				$sale_price = $variation_obj->get_sale_price();
				$regular_price = $variation_obj->get_regular_price();
				if ($sale_price !== "" && ($lowest_sale_price === null || $sale_price < $lowest_sale_price)) {
					$lowest_sale_price = $sale_price;
				}
				if ($regular_price !== "" && ($highest_regular_price === null || $regular_price > $highest_regular_price)) {
					$highest_regular_price = $regular_price;
				}
				$regular = $highest_regular_price;
				if($product->is_on_sale()){ $sale = $lowest_sale_price;	}
			}
		break;
		case 'grouped':
			$grouped_products = $product->get_children();
			$total_regular_price = '0';
			$total_sale_price = '0';
			foreach ($grouped_products as $grouped_product_id) {
				$grouped_product = wc_get_product($grouped_product_id);
				$regular_price = $grouped_product->get_regular_price();
				$sale_price = $grouped_product->get_sale_price();
				$regular = bcadd($total_regular_price, $regular_price, 2);
				$sale = bcadd($total_sale_price, ($sale_price !== null) ? $sale_price : $regular_price, 2);
			}
			$regular = $regular;
			if($product->is_on_sale()){ $sale = $sale;	}
		break;
	}
	$html .= "<div class='pmwp_price'>";
	$html .= "<div class='pmwp_view_price'>";
	if(!empty($sale)){
		$html .= "<div class='pmwp_regular_price'>".wc_price($regular)."</div>";
		$html .= "<div class='pmwp_sale_price'>".wc_price($sale)."</div>";
	}else{
		$html .= "<div class='pmwp_sale_price'>".wc_price($regular)."</div>";
	}
	$html .= "</div>";
	$html .= "</div>";
	$html .= "<p>";
	if(!empty($sale)){
		$parcelamento = new CwmpParcelamentoFixo($sale, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
		$parcelasComJurosPossiveis = $parcelamento->calcularNumeroParcelasPossiveis(false);
		if (is_array($parcelasComJurosPossiveis)) {
		} else {
			$parcelasComJurosPossiveis = $parcelamento->calcularNumeroParcelasPossiveis(true);
		}
		$format = str_replace("{{parcels}}",$parcelasComJurosPossiveis['numero'],get_option('parcelas_mwp_payment_second_pre'));
		$format = str_replace("{{value_total}}",wp_strip_all_tags(wc_price($parcelasComJurosPossiveis['valor']*$parcelasComJurosPossiveis['numero'])),$format);
		$format = str_replace("{{parcel}}",wp_strip_all_tags(wc_price($parcelasComJurosPossiveis['valor'])),$format);
		$html .= $format;
	}else{
		$parcelamento = new CwmpParcelamentoFixo($regular, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
		$parcelasComJurosPossiveis = $parcelamento->calcularNumeroParcelasPossiveis(false);
		if (is_array($parcelasComJurosPossiveis)) {
		} else {
			$parcelasComJurosPossiveis = $parcelamento->calcularNumeroParcelasPossiveis(true);
		}
		$format = str_replace("{{parcels}}",$parcelasComJurosPossiveis['numero'],get_option('parcelas_mwp_payment_second_pre'));
		$format = str_replace("{{value_total}}",wp_strip_all_tags(wc_price($parcelasComJurosPossiveis['valor']*$parcelasComJurosPossiveis['numero'])),$format);
		$format = str_replace("{{parcel}}",wp_strip_all_tags(wc_price($parcelasComJurosPossiveis['valor'])),$format);
		$html .= $format;
	}
	$html .= "</p>";
	return $html;
}
function get_parcels_box($product_id){
	if(get_option('parcelas_mwp_juros')==false){ $taxaJurosMensal = 4.99 / 100; }else{ $taxaJurosMensal = get_option('parcelas_mwp_juros')/100; }
	if(get_option('parcelas_mwp_payment_parcelas_sem_juros')==false){ $parcelasSemJuros = 3; }else{ $parcelasSemJuros = get_option('parcelas_mwp_juros'); }
	if(get_option('parcelas_mwp_valor_min')==false){ $valorMinimoParcela = 20; }else{ $valorMinimoParcela = get_option('parcelas_mwp_valor_min'); }
	if(get_option('parcelas_mwp_payment_second_parcels')==false){ $numeroMaxParcelas = 12; }else{ $numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels'); }
	$format = get_option('parcelas_mwp_payment_second_pre');	
	$product = wc_get_product($product_id);
	$product_type = $product->get_type();
	$html = "";
	$html .= "<ul class='pmwp_box_parcels'>";
	switch ($product_type) {
		case 'simple':
			$regular = $product->get_regular_price();
			if($product->is_on_sale()){ $sale = $product->get_sale_price();	}
			break;
		case 'variable':
			$variations = $product->get_available_variations();
			$lowest_sale_price = null;
			$highest_regular_price = null;
			foreach ($variations as $variation) {
				$variation_obj = wc_get_product($variation['variation_id']);
				$sale_price = $variation_obj->get_sale_price();
				$regular_price = $variation_obj->get_regular_price();
				if ($sale_price !== "" && ($lowest_sale_price === null || $sale_price < $lowest_sale_price)) {
					$lowest_sale_price = $sale_price;
				}
				if ($regular_price !== "" && ($highest_regular_price === null || $regular_price > $highest_regular_price)) {
					$highest_regular_price = $regular_price;
				}
				$regular = $highest_regular_price;
				if($product->is_on_sale()){ $sale = $lowest_sale_price;	}
			}
		break;
		case 'grouped':
			$grouped_products = $product->get_children();
			$total_regular_price = '0';
			$total_sale_price = '0';
			foreach ($grouped_products as $grouped_product_id) {
				$grouped_product = wc_get_product($grouped_product_id);
				$regular_price = $grouped_product->get_regular_price();
				$sale_price = $grouped_product->get_sale_price();
				$regular = bcadd($total_regular_price, $regular_price, 2);
				$sale = bcadd($total_sale_price, ($sale_price !== null) ? $sale_price : $regular_price, 2);
			}
			$regular = $regular;
			if($product->is_on_sale()){ $sale = $sale;	}
		break;
	}
	if(!empty($sale)){
		$parcelamento = new CwmpParcelamentoFixo($sale, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
	}else{
		$parcelamento = new CwmpParcelamentoFixo($regular, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
	}
	$resultadoParcelas = $parcelamento->calcularParcelas();
	if (is_array($resultadoParcelas)) {
		foreach ($resultadoParcelas as $parcela) {
			if( $parcela['juros']==0 ){
				$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
			}else{
				$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
			}
		}
	} else {
	}
	$html .= "</ul>";
	echo $html;
}


















/*
function get_parcels_box($product_id){
	if(is_product()){
		$product = wc_get_product($product_id);
		$product_type = $product->get_type();
		$html = "";
		$html .= "<ul class='pmwp_box_parcels'>";
		if(get_option('parcelas_mwp_type_tax')=="fixed"){
			if ($product->is_on_sale()){
				switch ($product_type) {
					case 'simple':
						$valorTotalCompra = $product->get_sale_price();
						$taxaJurosMensal = get_option('parcelas_mwp_juros')/100;
						$parcelasSemJuros = get_option('parcelas_mwp_payment_parcelas_sem_juros');
						$valorMinimoParcela = get_option('parcelas_mwp_valor_min');
						$numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels');
						$parcelamento = new CwmpParcelamentoFixo($valorTotalCompra, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
						$resultadoParcelas = $parcelamento->calcularParcelas();
						if (is_array($resultadoParcelas)) {
							foreach ($resultadoParcelas as $parcela) {
								if( $parcela['juros']==0 ){
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
								}else{
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
								}
							}
						} else {
						}
						break;
					case 'variable':
						$variations = $product->get_available_variations();
						$max_regular_price = null;
						$min_sale_price = null;
						foreach ($variations as $variation) {
							$variation_obj = wc_get_product($variation['variation_id']);
							$regular_price = $variation_obj->get_regular_price();
							$sale_price = $variation_obj->get_sale_price();
							if ($sale_price !== null && ($min_sale_price === null || $sale_price < $min_sale_price)) {
								$min_sale_price = $sale_price;
							}
							if ($max_regular_price === null || $regular_price > $max_regular_price) {
								$max_regular_price = $regular_price;
							}
						}
						$valorTotalCompra = $min_sale_price;
						$taxaJurosMensal = get_option('parcelas_mwp_juros')/100;
						$parcelasSemJuros = get_option('parcelas_mwp_payment_parcelas_sem_juros');
						$valorMinimoParcela = get_option('parcelas_mwp_valor_min');
						$numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels');
						$parcelamento = new CwmpParcelamentoFixo($valorTotalCompra, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
						$resultadoParcelas = $parcelamento->calcularParcelas();
						if (is_array($resultadoParcelas)) {
							foreach ($resultadoParcelas as $parcela) {
								if( $parcela['juros']==0 ){
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
								}else{
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
								}
							}
						} else {
						}
						break;
					case 'grouped':
						$grouped_products = $product->get_children();
						$total_regular_price = '0';
						$total_sale_price = '0';
						foreach ($grouped_products as $grouped_product_id) {
							$grouped_product = wc_get_product($grouped_product_id);
							$regular_price = $grouped_product->get_regular_price();
							$sale_price = $grouped_product->get_sale_price();
							$total_regular_price = bcadd($total_regular_price, $regular_price, 2);
							$total_sale_price = bcadd($total_sale_price, ($sale_price !== null) ? $sale_price : $regular_price, 2);
						}
						$valorTotalCompra = $total_sale_price;
						$taxaJurosMensal = get_option('parcelas_mwp_juros')/100;
						$parcelasSemJuros = get_option('parcelas_mwp_payment_parcelas_sem_juros');
						$valorMinimoParcela = get_option('parcelas_mwp_valor_min');
						$numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels');
						$parcelamento = new CwmpParcelamentoFixo($valorTotalCompra, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
						$resultadoParcelas = $parcelamento->calcularParcelas();
						if (is_array($resultadoParcelas)) {
							foreach ($resultadoParcelas as $parcela) {
								if( $parcela['juros']==0 ){
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
								}else{
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
								}
							}
						} else {
						}
						break;
					default:
						break;
				}
			}else{
				switch ($product_type) {
					case 'simple':
						$regular_price = $product->get_regular_price();
						$valorTotalCompra = $regular_price;
						$taxaJurosMensal = get_option('parcelas_mwp_juros')/100;
						$parcelasSemJuros = get_option('parcelas_mwp_payment_parcelas_sem_juros');
						$valorMinimoParcela = get_option('parcelas_mwp_valor_min');
						$numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels');
						$parcelamento = new CwmpParcelamentoFixo($valorTotalCompra, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
						$resultadoParcelas = $parcelamento->calcularParcelas();
						if (is_array($resultadoParcelas)) {
							foreach ($resultadoParcelas as $parcela) {
								if( $parcela['juros']==0 ){
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
								}else{
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
								}
							}
						} else {
						}
						break;
					case 'variable':
						$variations = $product->get_available_variations();
						$max_price = null;
						$min_price = null;
						foreach ($variations as $variation) {
							$variation_obj = wc_get_product($variation['variation_id']);
							$variation_price = $variation_obj->get_price();
							if ($max_price === null || $variation_price > $max_price) {
								$max_price = $variation_price;
							}
							if ($min_price === null || $variation_price < $min_price) {
								$min_price = $variation_price;
							}
						}
						$valorTotalCompra = $min_price;
						$taxaJurosMensal = get_option('parcelas_mwp_juros')/100;
						$parcelasSemJuros = get_option('parcelas_mwp_payment_parcelas_sem_juros');
						$valorMinimoParcela = get_option('parcelas_mwp_valor_min');
						$numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels');
						$parcelamento = new CwmpParcelamentoFixo($valorTotalCompra, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
						$resultadoParcelas = $parcelamento->calcularParcelas();
						if (is_array($resultadoParcelas)) {
							foreach ($resultadoParcelas as $parcela) {
								if( $parcela['juros']==0 ){
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
								}else{
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
								}
							}
						} else {
						}
						break;
					case 'grouped':
						$grouped_products = $product->get_children();
						$total_regular_price = 0;
						foreach ($grouped_products as $grouped_product_id) {
							$grouped_product = wc_get_product($grouped_product_id);
							$regular_price = $grouped_product->get_regular_price();
							if (is_numeric($regular_price)) {
								$total_regular_price += floatval($regular_price);
							}
						}
						$valorTotalCompra = $total_regular_price;
						$taxaJurosMensal = get_option('parcelas_mwp_juros')/100;
						$parcelasSemJuros = get_option('parcelas_mwp_payment_parcelas_sem_juros');
						$valorMinimoParcela = get_option('parcelas_mwp_valor_min');
						$numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels');
						$parcelamento = new CwmpParcelamentoFixo($valorTotalCompra, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
						$resultadoParcelas = $parcelamento->calcularParcelas();
						if (is_array($resultadoParcelas)) {
							foreach ($resultadoParcelas as $parcela) {
								if( $parcela['juros']==0 ){
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
								}else{
									$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
								}
							}
						} else {
						}
						break;
					default:
						break;
				}
			}
		}else{
			if ($product->is_on_sale()){
				switch ($product_type) {
					case 'simple':
						$valorTotalCompra = $product->get_sale_price();
						$taxasPorMes = array();
						for ($i = 1; $i <= get_option("parcelas_mwp_payment_second_parcels"); $i++){ $taxasPorMes[] = get_option("parcelas_mwp_juros_".$i."_installment")/100; }
						$parcelasSemJuros = get_option('parcelas_mwp_payment_parcelas_sem_juros');
						$valorMinimoParcela = get_option('parcelas_mwp_valor_min');
						$numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels');
						$parcelamento = new CwmpParcelamentoVariavel($valorTotalCompra, $taxasPorMes, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
						$parcelas = $parcelamento->calcularParcelas();
						foreach ($parcelas as $parcela) {
							if( $parcela['juros']==0 ){
								$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
							}else{
								$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
							}
						}
						break;
					case 'variable':
						$variations = $product->get_available_variations();
						$max_regular_price = null;
						$min_sale_price = null;
						foreach ($variations as $variation) {
							$variation_obj = wc_get_product($variation['variation_id']);
							$regular_price = $variation_obj->get_regular_price();
							$sale_price = $variation_obj->get_sale_price();
							if ($sale_price !== null && ($min_sale_price === null || $sale_price < $min_sale_price)) {
								$min_sale_price = $sale_price;
							}
							if ($max_regular_price === null || $regular_price > $max_regular_price) {
								$max_regular_price = $regular_price;
							}
						}
						$valorTotalCompra = $min_sale_price;
						$taxasPorMes = array();
						for ($i = 1; $i <= get_option("parcelas_mwp_payment_second_parcels"); $i++){ $taxasPorMes[] = get_option("parcelas_mwp_juros_".$i."_installment")/100; }
						$parcelasSemJuros = get_option('parcelas_mwp_payment_parcelas_sem_juros');
						$valorMinimoParcela = get_option('parcelas_mwp_valor_min');
						$numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels');
						$parcelamento = new CwmpParcelamentoVariavel($valorTotalCompra, $taxasPorMes, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
						$parcelas = $parcelamento->calcularParcelas();
						foreach ($parcelas as $parcela) {
							if( $parcela['juros']==0 ){
								$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
							}else{
								$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
							}
						}
						break;
					case 'grouped':
						$grouped_products = $product->get_children();
						$total_regular_price = '0';
						$total_sale_price = '0';
						foreach ($grouped_products as $grouped_product_id) {
							$grouped_product = wc_get_product($grouped_product_id);
							$regular_price = $grouped_product->get_regular_price();
							$sale_price = $grouped_product->get_sale_price();
							$total_regular_price = bcadd($total_regular_price, $regular_price, 2);
							$total_sale_price = bcadd($total_sale_price, ($sale_price !== null) ? $sale_price : $regular_price, 2);
						}
						$valorTotalCompra = $total_sale_price;
						$taxasPorMes = array();
						for ($i = 1; $i <= get_option("parcelas_mwp_payment_second_parcels"); $i++){ $taxasPorMes[] = get_option("parcelas_mwp_juros_".$i."_installment")/100; }
						$parcelasSemJuros = get_option('parcelas_mwp_payment_parcelas_sem_juros');
						$valorMinimoParcela = get_option('parcelas_mwp_valor_min');
						$numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels');
						$parcelamento = new CwmpParcelamentoVariavel($valorTotalCompra, $taxasPorMes, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
						$parcelas = $parcelamento->calcularParcelas();
						foreach ($parcelas as $parcela) {
							if( $parcela['juros']==0 ){
								$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_s_juros')))."</li>";
							}else{
								$html .= "<li>".str_replace("{{value}}",wp_strip_all_tags(wc_price(($parcela['valor']))),str_replace("{{parcels}}",$parcela['numero'],get_option('parcelas_mwp_payment_list_format_c_juros')))."</li>";
							}
						}
						break;
					default:
						break;
				}
			}else{
				
			}
		}
		$html .= "</ul>";
		echo $html;
	}
	
}


*/
