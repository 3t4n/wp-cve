<?php
function cwmpAdminCreateForms($args){
	global $wpdb, $table_prefix;
	$html = '';
	$url = '';
	foreach($args as $args){
		foreach($args as $box){
		if(!empty($box['typeThankyou'])){
			if(is_array($box['typeThankyou'])){
				foreach(cwmpArrayPaymentMethods() as $valor){
					$html .= '<div class="mwp-box">';
						$html .= '<div class="col-1">';
						if(isset($valor['label'])){
							$html .= '<h3>'.$valor['label'].'</h3>';
						}
						if(isset($valor['value'])){
						$html .= '<p>#'.$valor['value'].'</p>';
						}
						$html .= '</div>';
						$html .= '<div class="col-2">';
						$html .= '<p>';
						$html .= '<strong>'.__('Pending payment', 'checkout-mestres-wp').'</strong>';
						$html .= '<span>'.__('It is mandatory to choose custom thank you page for pending payments.', 'checkout-mestres-wp').'</span>';
						$html .= '<select name="cwmp_thankyou_page_pending_'.str_replace("-","_",$valor['value']).'" class="input-150 select2-offscreen" id="cwmp_thankyou_page_pending_'.str_replace("-","_",$valor['value']).'" tabindex="-1" title="">';
						$pages = cwmpArrayPages();
						foreach($pages as $page){
							if(get_option('cwmp_thankyou_page_pending_'.str_replace("-","_",$valor['value']))==$page['value']){ $selected="selected"; }else{ $selected=""; }
							$html .= '<option value="'.$page['value'].'" '.$selected.'>'.$page['label'].'</option>';
						}
						$html .= '</select>';
						$url .= 'cwmp_thankyou_page_pending_'.str_replace("-","_",$valor['value']).",";
						$html .= '</p>';
						$html .= '<p>';
						$html .= '<strong>'.__('Success', 'checkout-mestres-wp').'</strong>';
						$html .= '<span>'.__('It is mandatory to choose custom thank you page for paid orders.', 'checkout-mestres-wp').'</span>';
						$html .= '<select name="cwmp_thankyou_page_aproved_'.str_replace("-","_",$valor['value']).'" class="input-150 select2-offscreen" id="cwmp_thankyou_page_aproved_'.str_replace("-","_",$valor['value']).'" tabindex="-1" title="">';
						$pages = cwmpArrayPages();
						foreach($pages as $page){
							if(get_option('cwmp_thankyou_page_aproved_'.str_replace("-","_",$valor['value']))==$page['value']){ $selected="selected"; }else{ $selected=""; }
							$html .= '<option value="'.$page['value'].'" '.$selected.'>'.$page['label'].'</option>';
						}
						$html .= '</select>';
						$url .= 'cwmp_thankyou_page_aproved_'.str_replace("-","_",$valor['value']).",";
						$html .= '</p>';
						$html .= '</div>';
					$html .= '</div>';
				}

			}
		}else{
			if(!empty($box['bd'])){
				$id = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : '';
				$result = $wpdb->get_results("SELECT * FROM ".$table_prefix ."". $box['bd']." WHERE id = ".$id."");
				$html .='<input type="hidden" name="id" id="id" size="45" value="'.$_GET['id'].'"  />';
			}
			$html .= '<div class="mwp-box">';
				$html .= '<div class="col-1">';
				if(isset($box['title'])){
				$html .= '<h3>'.$box['title'].'</h3>';
				}
				if(isset($box['description'])){
				$html .= '<p>'.$box['description'].'</p>';
				}
				if(!empty($box['button']['url'])){ $html .= '<a href="'.$box['button']['url'].'" class="action">'.$box['button']['label'].'</a>'; }
				if(!empty($box['help'])){ $html .= '<a href="'.$box['help'].'" target="blank">'.__( 'Help? See the documentation', 'checkout-mestres-wp').'</a>'; }
				$html .= '</div>';
				$html .= '<div class="col-2">';
				if(isset($box['args'])){
				foreach($box['args'] as $option){
					$html .= '<p>';
					if(is_array($option['type'])){
						$html .= '<p ';
						if(isset($option['value']['class'])){ $html .= 'id="'.$option['value']['class'].'" '; }
						$html .= '>';
						$html .= '<strong>'.$option['label'].'</strong>';
						$html .= '<span>'.$option['info'].'</span>';
						foreach($option['type'] as $fields){
							$html .= '<input ';
							if(isset($fields['type'])){ $html .= 'type="'.$fields['type'].'"'; }
							if(isset($fields['value']['name'])){ $html .= ' name="'.$fields['value']['name'].'"'; }
							if(isset($fields['value']['class'])){ $html .= ' class="array '.$fields['value']['class'].'"'; }
							if(isset($fields['value']['placeholder'])){ $html .= ' placeholder="'.$fields['value']['placeholder'].'"'; }
							if(isset($fields['value']['step'])){ $html .= ' step="0.01"'; }
							$html .= ' value="'.$value.'" />';
							if(isset($fields['value'])){ $url .= $fields['value']['name'].','; }
						}
						$html .= '</p>';
					}else{
						if($option['type']=="icon"){
							$html .= '<p ';
							if(isset($option['value']['class'])){ $html .= 'id="'.$option['value']['class'].'" '; }
							$html .= '>';
							$html .= '<strong>'.$option['value']['label'].'</strong>';
							$html .= '<span>'.$option['value']['info'].'</span>';
							$html .= '<div class="input-group">';
							$html .= '<input data-placement="bottomRight" class="icp demo2" name="'.$option['value']['name'].'" value="'.esc_html(get_option($option['value']['name'])).'" type="hidden"/>';
							$html .= '<span class="input-group-addon"></span>';
							$html .= '</div>';
							$html .= '</p>';
							$url .= $option['value']['name'].',';
						}elseif($option['type']=="datetime"){
							if(!empty($box['bd'])){ $value=$result[0]->{$option['value']['row']}; }else{ $value=esc_html(get_option($option['value']['name'])); }
							$html .= '<p ';
							if(isset($option['value']['class'])){ $html .= 'id="'.$option['value']['class'].'" '; }
							$html .= '>';
							if(isset($option['value']['label'])){
							$html .= '<strong>'.$option['value']['label'].'</strong>';
							}
							if(isset($option['value']['info'])){
							$html .= '<span>'.$option['value']['info'].'</span>';
							}
							$html .= '<input type="datetime-local" ';
							if(isset($option['value']['name'])){
							$html .= 'name="'.$option['value']['name'].'" ';
							}
							if(isset($option['value']['class'])){
							$html .= 'class="'.$option['value']['class'].'" ';
							}
							if(isset($option['value']['placeholder'])){
							$html .= 'placeholder="'.$option['value']['placeholder'].'" ';
							}
							$html .= 'value="'.$value.'" ';
							$html .= '/>';
							$html .= '</p>';
							$url .= $option['value']['name'].',';
						}elseif($option['type']=="checkbox"){
							$html .= '<p class="'.$option['value']['name'].'">';
							$html .= '<strong>'.$option['title'].'</strong>';
							$html .= '<span>'.$option['description'].'</span>';
							foreach($option['options'] as $rows){
								if(!empty($box['bd'])){
									if($rows['value']==$result[0]->{$option['row']}){ $selected = "selected"; }else{ $selected = ""; }
								}else{
									if($rows['value']==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
								}
								$html .= '<label for="'.$rows['value'].'"><input type="checkbox" name="'.$rows['value'].'" value="'.$rows['value'].'" id="'.$rows['value'].'" '.$selected.' />'.$rows['label'].'</label>';
								$url .= $rows['value'].',';
							}
							$html .= '</p>';
							
						}elseif($option['type']=="select"){
							$html .= '<p ';
							if(isset($option['class'])){ $html .= 'id="'.$option['class'].'" '; }
							$html .= '>';
							$html .= '<strong>'.$option['title'].'</strong>';
							$html .= '<span>'.$option['description'].'</span>';
							$html .= '<select ';
							if(isset($option['id'])){
							$html .= 'name="'.$option['id'].'" ';
							}
							if(isset($option['class'])){
							$html .= 'class="'.$option['class'].'" ';
							}
							$html .= '/>';
							foreach($option['options'] as $rows){
								if(!empty($box['bd'])){
									if($rows['value']==$result[0]->{$option['row']}){ $selected = "selected"; }else{ $selected = ""; }
								}else{
									if($rows['value']==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
								}
								$html .= '<option value="'.$rows['value'].'" '.$selected.'>'.$rows['label'].'</option>';
							}
							$html .= '</select />';
							$html .= '</p>';
							if(isset($option['id'])){
							$url .= $option['id'].',';
							}
						}elseif($option['type']=="multiple"){
							$html .= '<p ';
							if(isset($option['value']['class'])){ $html .= 'id="'.$option['class'].'" '; }
							$html .= '>';
							$html .= '<strong>'.$option['title'].'</strong>';
							$html .= '<span>'.$option['description'].'</span>';
							$html .= '<select ';
							if(isset($option['id'])){
							$html .= 'name="'.$option['id'].'[]" ';
							}
							if(isset($option['class'])){
							$html .= 'class="'.$option['class'].'" ';
							}
							$html .= 'multiple />';
							foreach($option['options'] as $rows){
								if(!empty($box['bd'])){
									if($rows['value']==$result[0]->{$option['row']}){
										$selected = "selected"; }else{ $selected = ""; }
								}else{
									if($rows['value']==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
								}
								$html .= '<option value="'.$rows['value'].'" '.$selected.'>'.$rows['label'].'</option>';
							}
							$html .= '</select />';
							$html .= '</p>';
							if(isset($option['value']['name'])){
							$url .= $option['value']['name'].',';
							}
						}elseif($option['type']=="textarea"){
							if(!empty($box['bd'])){ $value=$result[0]->{$option['value']['row']}; }else{ $value=esc_html(get_option($option['value']['name'])); }
							$html .= '<p ';
							if(isset($option['value']['class'])){ $html .= 'id="'.$option['value']['class'].'"  '; }
							$html .= '>';
							$html .= '<strong>'.$option['value']['label'].'</strong>';
							$html .= '<span>'.$option['value']['info'].'</span>';
							$html .= '<textarea ';
							if(isset($option['value']['name'])){
							$html .= 'name="'.$option['value']['name'].'" ';
							}
							if(isset($option['value']['class'])){
							$html .= 'class="'.$option['value']['class'].'" ';
							}
							if(isset($option['value']['placeholder'])){
							$html .= 'placeholder="'.$option['value']['placeholder'].'"';
							}
							$html .= '>'.str_replace("\'","'",str_replace('\"','"',$value)).'</textarea>';
							$html .= '</p>';
							if(isset($option['value']['name'])){
							$url .= $option['value']['name'].',';
							}
						}elseif($option['type']=="payment_method"){
							$html .= '<p ';
							if(isset($option['value']['class'])){ $html .= 'id="'.$option['value']['class'].'"  '; }
							$html .= '>';
							$html .= '<strong>'.$option['value']['label'].'</strong>';
							$html .= '<span>'.$option['value']['info'].'</span>';
							$html .= '<select name="'.$option['value']['name'].'" id="'.$option['value']['name'].'">';
								$wc_gateways      = new WC_Payment_Gateways();
								$payment_gateways = $wc_gateways->payment_gateways();
								foreach( $payment_gateways as $gateway_id => $gateway ){
								if($gateway->enabled=="yes"){
									if(!empty($box['bd'])){
										echo "AAAAAAA: ".$gateway->id;
										if($gateway->id==$result[0]->{$option['name']}){
											$selected = "selected";
										}else{
											$selected = "";
										}
									}else{
										print_r($option);
										if(isset($option['id'])){
										if($gateway->id==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
										}
									}
									$html .= '<option value="'.$gateway->id.'" '.$selected.'>';
									$html .= $gateway->title.' ('.$gateway->id.')';
									$html .= '</option>';
								}
								}
							$html .= '</select>';
							$html .= '</p>';
							$url .= $option['value']['name'].',';
						}elseif($option['type']=="allProducts"){
							$html .= '<p ';
							if(isset($option['value']['class'])){ $html .= 'id="'.$option['value']['class'].'" '; }
							$html .= '>';
							$html .= '<strong>'.$option['value']['label'].'</strong>';
							$html .= '<span>'.$option['value']['info'].'</span>';
							$html .= '<select name="'.$option['value']['name'].'" id="'.$option['value']['name'].'">';
								$args     = array( 'post_type'      => array('product', 'product_variation'), 'posts_per_page' => -1 );
								$products = get_posts( $args );
								foreach($products as $product){
									if(!empty($box['bd'])){
										if($product->ID==$result[0]->{$option['row']}){
											$selected = "selected"; }else{ $selected = ""; }
									}else{
										if($rows['value']==get_option($option['id'])){ $selected = "selected"; }else{ $selected = ""; }
									}
									//echo $result[0]->{$option['row']}."<br/>";
									$html .= '<option value="'.str_replace('-', '_', $product->ID).'" '.$selected.'>'.$product->post_title.'</option>';
								}
							$html .= '</select>';
							$html .= '</p>';
							$url .= $option['value']['name'].',';
						}elseif($option['type']=="listPayments"){
								$wc_gateways      = new WC_Payment_Gateways();
								$payment_gateways = $wc_gateways->payment_gateways();
								foreach( $payment_gateways as $gateway_id => $gateway ){
								if($gateway->enabled=="yes"){
									$html .= '<p class="'.$option['value']['name'].'">';
									$html .= '<strong>'.$gateway->title.' ('.$gateway->id.')</strong>';
									$html .= '<input type="number" name="parcelas_mwp_desconto_status_'.$gateway->id.'" value="'.get_option("parcelas_mwp_desconto_status_".$gateway->id).'" />';
									$html .= '</p>';
									$url .= "parcelas_mwp_desconto_status_".$gateway->id.",";
								}
								}
						}else{
							if(!empty($box['bd'])){ $value=$result[0]->{$option['value']['row']}; }else{ $value=esc_html(get_option($option['value']['name'])); }
							$html .= '<p ';
							if(isset($option['value']['class'])){ $html .= 'id="'.$option['value']['class'].'" '; }
							$html .= '>';
							if(isset($option['value']['label'])){
							$html .= '<strong>'.$option['value']['label'].'</strong>';
							}
							if(isset($option['value']['info'])){
							$html .= '<span>'.$option['value']['info'].'</span>';
							}
							$html .= '<input ';
							if(isset($option['type'])){ $html .= 'type="'.$option['type'].'" '; }
							if(isset($option['value']['name'])){ $html .= 'name="'.$option['value']['name'].'" '; }
							if(isset($option['value']['class'])){ $html .= 'class="'.$option['value']['class'].'" '; }
							if(isset($option['value']['placeholder'])){ $html .= 'placeholder="'.$option['value']['placeholder'].'" '; }
							if(isset($option['value']['step'])){ $html .= ' step="any"'; }
							$html .= 'value="'.$value.'" />';
							$html .= '</p>';
							$url .= $option['value']['name'].',';
						}
					}
				}
				}
				$html .= '</div>';
			$html .= '</div>';
			}
		}
	$html .= '<input type="submit" name="Submit" class="mwpbuttonupdatesection" id="mwpbuttonupdatesection"';
	if(isset($box['formButton'])){
	$html .= 'value="'.$box['formButton'].'"';
	}else{
	$html .= 'value="'.__( 'Update', 'checkout-mestres-wp').'"';
	}
	$html .= '/>';
	//if(isset($box['action'])){
	//if($box['action']!="externo"){
	$html .= '<input type="hidden" name="action" value="update" />';
	$html .= '<input type="hidden" name="page_options" value="'.substr($url,0,-1).'" />';
	//}
	//}
	
	}
	
	echo $html;
}