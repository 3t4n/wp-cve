<?php
function cwmp_html_price($produto){
	$taxaJurosMensal = array();
	if(get_option('parcelas_mwp_payment_second_parcels')==false){ $numeroMaxParcelas = 12; }else{ $numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels'); }
	for ($i = 1; $i <= $numeroMaxParcelas; $i++){
		if(get_option("parcelas_mwp_juros_".$i."_installment")==false){
			$taxaJurosMensal[] = 2.99/100;
		}else{
			$taxaJurosMensal[] = get_option("parcelas_mwp_juros_".$i."_installment")/100;
		}
	}
	if(get_option('parcelas_mwp_payment_parcelas_sem_juros')==false){ $parcelasSemJuros = 3; }else{ $parcelasSemJuros = get_option('parcelas_mwp_juros'); }
	if(get_option('parcelas_mwp_valor_min')==false){ $valorMinimoParcela = 20; }else{ $valorMinimoParcela = get_option('parcelas_mwp_valor_min'); }
	$format = get_option('parcelas_mwp_payment_second_pre');	
	$product = wc_get_product($produto);
	$product_type = $product->get_type();
	echo "<input type='hidden' class='cwmp_product_id' name='cwmp_product_id' value='".$product->get_id()."' />";
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
		$parcelamento = new CwmpParcelamentoVariavel($sale, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
		$ultimasParcelas = $parcelamento->calcularNumeroParcelasPossiveis();
		if(!empty($ultimasParcelas['com_juros']['numero'])){
			$format = str_replace("{{parcels}}",$ultimasParcelas['com_juros']['numero'],get_option('parcelas_mwp_payment_second_pre'));
			$format = str_replace("{{value_total}}",wp_strip_all_tags(wc_price($ultimasParcelas['com_juros']['valor']*$ultimasParcelas['com_juros']['numero'])),$format);
			$format = str_replace("{{parcel}}",wp_strip_all_tags(wc_price($ultimasParcelas['com_juros']['valor'])),$format);
			
		}else{
			$format = str_replace("{{parcels}}",$ultimasParcelas['sem_juros']['numero'],get_option('parcelas_mwp_payment_second_pre'));
			$format = str_replace("{{value_total}}",wp_strip_all_tags(wc_price($ultimasParcelas['sem_juros']['valor']*$ultimasParcelas['sem_juros']['numero'])),$format);
			$format = str_replace("{{parcel}}",wp_strip_all_tags(wc_price($ultimasParcelas['sem_juros']['valor'])),$format);
		}
	}else{
		$parcelamento = new CwmpParcelamentoVariavel($regular, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
		$parcelasComJurosPossiveis = $parcelamento->calcularNumeroParcelasPossiveis(false);
		$ultimasParcelas = $parcelamento->calcularNumeroParcelasPossiveis();
		if(!empty($ultimasParcelas['com_juros']['numero'])){
			$format = str_replace("{{parcels}}",$ultimasParcelas['com_juros']['numero'],get_option('parcelas_mwp_payment_second_pre'));
			$format = str_replace("{{value_total}}",wp_strip_all_tags(wc_price($ultimasParcelas['com_juros']['valor']*$ultimasParcelas['com_juros']['numero'])),$format);
			$format = str_replace("{{parcel}}",wp_strip_all_tags(wc_price($ultimasParcelas['com_juros']['valor'])),$format);
			
		}else{
			$format = str_replace("{{parcels}}",$ultimasParcelas['sem_juros']['numero'],get_option('parcelas_mwp_payment_second_pre'));
			$format = str_replace("{{value_total}}",wp_strip_all_tags(wc_price($ultimasParcelas['sem_juros']['valor']*$ultimasParcelas['sem_juros']['numero'])),$format);
			$format = str_replace("{{parcel}}",wp_strip_all_tags(wc_price($ultimasParcelas['sem_juros']['valor'])),$format);
		}
	}
	$html .= $format;
	$html .= "</p>";
	return $html;
}


function get_parcels_box($product_id){
	$taxaJurosMensal = array();
	if(get_option('parcelas_mwp_payment_second_parcels')==false){ $numeroMaxParcelas = 12; }else{ $numeroMaxParcelas = get_option('parcelas_mwp_payment_second_parcels'); }
	for ($i = 1; $i <= $numeroMaxParcelas; $i++){
		if(get_option("parcelas_mwp_juros_".$i."_installment")==false){
			$taxaJurosMensal[] = 2.99/100;
		}else{
			$taxaJurosMensal[] = get_option("parcelas_mwp_juros_".$i."_installment")/100;
		}
	}
	if(get_option('parcelas_mwp_payment_parcelas_sem_juros')==false){ $parcelasSemJuros = 3; }else{ $parcelasSemJuros = get_option('parcelas_mwp_juros'); }
	if(get_option('parcelas_mwp_valor_min')==false){ $valorMinimoParcela = 20; }else{ $valorMinimoParcela = get_option('parcelas_mwp_valor_min'); }
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
		$parcelamento = new CwmpParcelamentoVariavel($sale, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
	}else{
		$parcelamento = new CwmpParcelamentoVariavel($regular, $taxaJurosMensal, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas);
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
