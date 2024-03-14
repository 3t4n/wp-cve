<?php
	global $wp_session;
	if (isset($_COOKIE['oca_notice'])) {
			$wp_session['oca_notice'] = $_COOKIE['oca_notice'];
			add_action( 'admin_notices', 'oca_admin_notice' );
	}

	add_action('wp_ajax_check_sucursales', 'check_sucursales', 1);
	add_action('wp_ajax_nopriv_check_sucursales', 'check_sucursales', 1);

	add_action('wp_ajax_check_admision', 'check_admision', 1);
	add_action('wp_ajax_nopriv_check_admision', 'check_admision', 1);

	function check_sucursales() {
		global $wp_session;
		
		if (isset($_POST['post_code'])) {
			
			$params = array(
						"method" => array(
								 "get_centros_destino" => array(
												'cuit' => $_POST['cuit'],
												'operativa' => $_POST['operativa'],
												'cp_destino' => $_POST['post_code'],   
								 )
						)
				);
									
			 $ch = curl_init();
			 curl_setopt_array($ch,	
														array(	
															CURLOPT_TIMEOUT	=> 30,
															CURLOPT_POST => TRUE,
															CURLOPT_POSTFIELDS => http_build_query($params),
															CURLOPT_URL => $wp_session['url_oca'],
															CURLOPT_RETURNTRANSFER => TRUE,
															CURLOPT_FOLLOWLOCATION	=> TRUE
														)
													);

			 	$oca_response = curl_exec ($ch);			
        $oca_response = json_decode($oca_response);
 				echo '<select id="pv_centro_oca_estandar" name="pv_centro_oca_estandar">';
			
				$listado_oca = array();
			
				foreach($oca_response->results as $sucursales){
					$idCentroImposicion = $sucursales->sucursales->IdCentroImposicion;

					if(empty($idCentroImposicion)){
						$idCentroImposicion = $sucursales->sucursales->IdSucursalOCA;
						$sucursales_finales = $sucursales->sucursales->Descripcion;
					} else {
						$sucursales_finales = $sucursales->sucursales->Sucursal;
					}
										
					$listado_oca[] = $sucursales->sucursales;
 					echo '<option value="'. $idCentroImposicion.'">'. $sucursales_finales . ' - ' . $sucursales->sucursales->Calle . ' - ' . $sucursales->sucursales->Numero . ' - ' . $sucursales->sucursales->Localidad . '</option>';
				}
			
				echo '</select>';
				
				$wp_session['listado_oca'] = $listado_oca;
							 
 			die();
		}
	}

	function check_admision() {
		global $wp_session;
		
		if (isset($_POST['post_code'])) {
			
			$params = array(
						"method" => array(
								 "get_centros_admision" => array(
												'cuit' => $_POST['cuit'],
												'operativa' => $_POST['operativa'],
												'cp_admision' => $_POST['post_code'],   
								 )
						)
				);
									
			 $ch = curl_init();
			 curl_setopt_array($ch,	
														array(	
															CURLOPT_TIMEOUT	=> 30,
															CURLOPT_POST => TRUE,
															CURLOPT_POSTFIELDS => http_build_query($params),
															CURLOPT_URL => $wp_session['url_oca'],
															CURLOPT_RETURNTRANSFER => TRUE,
															CURLOPT_FOLLOWLOCATION	=> TRUE
														)
													);

			 	$oca_response = curl_exec ($ch);			
        $oca_response = json_decode($oca_response);
 				echo '<select id="pv_centro_oca_estandar" name="pv_centro_oca_estandar">';
			
				$listado_oca = array();
			
				foreach($oca_response->results as $sucursales){
					$idCentroImposicion = $sucursales->sucursales->IdCentroImposicion;

					if(empty($idCentroImposicion)){
						$idCentroImposicion = $sucursales->sucursales->IdSucursalOCA;
						$sucursales_finales = $sucursales->sucursales->Descripcion;
					} else {
						$sucursales_finales = $sucursales->sucursales->Sucursal;
					}
					$listado_oca[] = $sucursales->sucursales;
 					echo '<option value="'. $idCentroImposicion.'">'. $sucursales_finales . ' - ' . $sucursales->sucursales->Calle . ' - ' . $sucursales->sucursales->Numero . ' - ' . $sucursales->sucursales->Localidad . '</option>';
				}
			
				echo '</select>';
			
				$wp_session['listado_oca'] = $listado_oca;
			
 				if (!empty($oca_response->notice)) {
						if ( !isset($_COOKIE['oca_notice'])) {
							setcookie( 'oca_notice', $oca_response->notice, time()+3600);  /* expire in 1 hour */
						}
  			} else {
					setcookie("oca_notice", "", time() - 3600);
				}
			
 			die();
		}
	}


  add_action( 'wp_footer', 'only_numbers_ocas');
	function only_numbers_ocas(){ 
		if ( is_checkout() ) { ?>
 			<script type="text/javascript">
 				jQuery(document).ready(function () {  
        jQuery('#order_sucursal_main').insertAfter( jQuery( '.woocommerce-checkout-review-order-table' ) );
				jQuery('#calc_shipping_postcode').attr({ maxLength : 4 });
				jQuery('#billing_postcode').attr({ maxLength : 4 });
				jQuery('#shipping_postcode').attr({ maxLength : 4 });

		          jQuery("#calc_shipping_postcode").keypress(function (e) {
		          if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		          	return false;
		          }
		          });
		          jQuery("#billing_postcode").keypress(function (e) { 
		          if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) { 
		          return false;
		          }
		          });
		          jQuery("#shipping_postcode").keypress(function (e) {  
		          if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		          return false;
		          }
		          });
					
							 		
						jQuery('#billing_postcode').focusout(function () {
				    	if (jQuery('#ship-to-different-address-checkbox').is(':checked')) {
				    		var state = jQuery('#shipping_state').val();
				    		var post_code = jQuery('#shipping_postcode').val();
				    	} else {
				    		var state = jQuery('#billing_postcode').val();
				    		var post_code = jQuery('#billing_postcode').val();
				    	}
				    	
						
 							var selectedMethod = jQuery('input:checked', '#shipping_method').attr('id');
							var selectedMethodb = jQuery( "#order_review .shipping .shipping_method option:selected" ).val();
							if (selectedMethod == null) {
									if(selectedMethodb != null){
										selectedMethod = selectedMethodb;
									} else {
										return false;
									}
							}	 					
							var order_sucursal = 'ok';
							var operativa = selectedMethod.substr(selectedMethod.indexOf("operativa") + 9)
							var cuit = selectedMethod.substr(selectedMethod.indexOf("cuit") + 4)
     					var cuit_ok = cuit.substr(0, 13);
 							if (selectedMethod.indexOf("-sas") >= 0 || selectedMethod.indexOf("-sasp") >= 0 || selectedMethod.indexOf("-pasp") >= 0 || selectedMethod.indexOf("-pas") >= 0) {
							jQuery("#order_sucursal_main_result").fadeOut(100);
							jQuery("#order_sucursal_main_result_cargando").fadeIn(100);	
				    	jQuery.ajax({
				    		type: 'POST',
				    		cache: false,
				    		url: wc_checkout_params.ajax_url,
				    		data: {
 									action: 'check_sucursales',
									post_code: post_code,
									order_sucursal: order_sucursal,
									operativa: operativa,
									cuit: cuit_ok,								
				    		},
				    		success: function(data, textStatus, XMLHttpRequest){
											jQuery("#order_sucursal_main_result").fadeIn(100);
 											jQuery("#order_sucursal_main_result_cargando").fadeOut(100);	
											jQuery("#order_sucursal_main_result").html('');
											jQuery("#order_sucursal_main_result").append(data);
									
 											var selectList = jQuery('#pv_centro_oca_estandar option');
											var arr = selectList.map(function(_, o) { return { t: jQuery(o).text(), v: o.value }; }).get();
											arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
											selectList.each(function(i, o) {
												o.value = arr[i].v;
												jQuery(o).text(arr[i].t);
											});
											jQuery('#pv_centro_oca_estandar').html(selectList);
											jQuery("#pv_centro_oca_estandar").prepend("<option value='0' selected='selected'>Sucursales Disponibles</option>");
									
										},
										error: function(MLHttpRequest, textStatus, errorThrown){alert(errorThrown);}
									});
				    	return false;		
							}
				    });		
					
				});

				function toggleCustomBox() {
 				        var selectedMethod = jQuery('input:checked', '#shipping_method').attr('id');
								var selectedMethodb = jQuery( "#order_review .shipping .shipping_method option:selected" ).val();
								if (selectedMethod == null) {
									if(selectedMethodb != null){
										selectedMethod = selectedMethodb;
									} else {
										return false;
									}
								}	                  
									//sas, sasp, pasp, pas
                if (selectedMethod.indexOf("-sas") >= 0 || selectedMethod.indexOf("-sasp") >= 0 || selectedMethod.indexOf("-pasp") >= 0 || selectedMethod.indexOf("-pas") >= 0) {
									
                  jQuery('#order_sucursal_main').show();
									jQuery('#order_sucursal_main').insertAfter( jQuery('.shop_table') );

									if (jQuery('#ship-to-different-address-checkbox').is(':checked')) {
										var state = jQuery('#shipping_state').val();
										var post_code = jQuery('#shipping_postcode').val();
									} else {
										var state = jQuery('#billing_postcode').val();
										var post_code = jQuery('#billing_postcode').val();
									}
 									
									var order_sucursal = 'ok';
									var operativa = selectedMethod.substr(selectedMethod.indexOf("operativa") + 9)
									var cuit = selectedMethod.substr(selectedMethod.indexOf("cuit") + 4)
     							var cuit_ok = cuit.substr(0, 13);
									jQuery("#order_sucursal_main_result").fadeOut(100);
									jQuery("#order_sucursal_main_result_cargando").fadeIn(100);	
									jQuery.ajax({
										type: 'POST',
										cache: false,
										url: wc_checkout_params.ajax_url,
										data: {
											action: 'check_sucursales',
											post_code: post_code,
											order_sucursal: order_sucursal,
											operativa: operativa,
											cuit: cuit_ok,
										},
										success: function(data, textStatus, XMLHttpRequest){
													jQuery("#order_sucursal_main_result").fadeIn(100);
													jQuery("#order_sucursal_main_result_cargando").fadeOut(100);	
													jQuery("#order_sucursal_main_result").html('');
													jQuery("#order_sucursal_main_result").append(data);
											
	 											var selectList = jQuery('#pv_centro_oca_estandar option');
												var arr = selectList.map(function(_, o) { return { t: jQuery(o).text(), v: o.value }; }).get();
												arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
												selectList.each(function(i, o) {
													o.value = arr[i].v;
													jQuery(o).text(arr[i].t);
												});
												jQuery('#pv_centro_oca_estandar').html(selectList);
												jQuery("#pv_centro_oca_estandar").prepend("<option value='0' selected='selected'>Sucursales Disponibles</option>");										
											
												},
												error: function(MLHttpRequest, textStatus, errorThrown){alert(errorThrown);}
											});
									return false;					

                } else {
                  jQuery('#order_sucursal_main').hide();  
                }
				}; //ends toggleCustomBox

				jQuery(document).ready(toggleCustomBox);
				jQuery(document).on('change', '#shipping_method input:radio', toggleCustomBox);
 				jQuery(document).on('change', '#order_review .shipping .shipping_method', toggleCustomBox);

 						 
			</script>

			<style type="text/css">
         #order_sucursal_main h3 {
            text-align: left;
            padding: 5px 0 5px 115px;
        }
				.oca-logo {
					position: absolute;
    			margin: 0px;
				}
			</style>
		<?php }
	}	//ends only_numbers_ocas

  /**
	 * Add the field to the checkout
	 */
	add_action( 'woocommerce_after_order_notes', 'order_sucursal_main' );
	function order_sucursal_main( $checkout ) {
		global $woocommerce, $wp_session;
	
 		$items = $woocommerce->cart->cart_contents;
    
 		foreach($items as $item){
			$user_id = $item['data']->post->post_author;
 		}
 
	  $wp_session['user_id'] = $user_id;
 		echo '<input type="hidden" value="'. $user_id .'" id="user_id_vendor" name="user_id_vendor" />';

	  echo '<div id="order_sucursal_main" style="display:none; margin-bottom: 50px;"><img class="oca-logo" src="'. plugins_url( 'img/suc-oca.png', __FILE__ ) . '"><h3>' . __('Sucursal OCA') . '</h3>';
    	echo '<small>Si seleccionaste retirar por sucursal, elegí tu sucursal en el listado.</small>';
      echo '<div id="order_sucursal_main_result_cargando">Cargando Sucursales...';echo '</div>';
 			echo '<div id="order_sucursal_main_result" style="display:none;">Cargando Sucursales...';echo '</div>';
    echo '</div>';
	
 	}


	 /**
	 * Process the checkout
	 */
	add_action('woocommerce_checkout_process', 'checkout_field_oca_process');
	function checkout_field_oca_process() {
			global $woocommerce, $wp_session;
		
			$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
			$chosen_shipping = $chosen_methods[0]; 
			$wp_session['chosen_shipping'] = $chosen_shipping;
			if (strpos($chosen_shipping, '-saspcuit') !== false || strpos($chosen_shipping, '-paspcuit') !== false || strpos($chosen_shipping, '-pascuit') !== false || strpos($chosen_shipping, '-sascuit') !== false) {
				if (empty($_POST['pv_centro_oca_estandar']) )
									wc_add_notice( __( 'Por favor, seleccionar sucursal de retiro.' ), 'error' ); 
			}
	}

	 /**
	 * Update the order meta with field value
	 */
	add_action( 'woocommerce_checkout_update_order_meta', 'order_sucursal_main_update_order_meta_oca', 10);
	function order_sucursal_main_update_order_meta_oca( $order_id ) {
		global $wp_session;
	 	session_start();
 	    if ( ! empty( $_POST['pv_centro_oca_estandar'] ) ) {
				
				update_post_meta( $order_id, 'sucursal_pv_centro_oca_estandar', $_POST['pv_centro_oca_estandar'] );
				
				foreach($wp_session['listado_oca'] as $opciones){
					if($_POST['pv_centro_oca_estandar'] == $opciones->IdCentroImposicion){
						$opciones = json_encode($opciones);
						update_post_meta( $order_id, 'sucursal_oca_c', $opciones );
					}				
 				}
	    }
			$chosen_shipping = json_encode($wp_session['chosen_shipping'] );

 
 			update_post_meta( $order_id, 'chosen_shipping', $chosen_shipping );
 	}

	 /**
	 * Show info at order
	 */
	add_action('add_meta_boxes', 'woocommerce_oca_box_add_box');

	function woocommerce_oca_box_add_box() {
		add_meta_box( 'woocommerce-oca-box', __( 'OCA Express Pack', 'woocommerce-oca' ), 'woocommerce_oca_box_create_box_content', 'shop_order', 'side', 'default' );
	}
	function woocommerce_oca_box_create_box_content() {
		global $post;
			$site_url = get_site_url();
		  $order = wc_get_order( $post->ID );
			$shipping = $order->get_items( 'shipping' );
		
		  $sucursal_oca_c = get_post_meta($post->ID, 'sucursal_oca_c', true);
			
			echo '<div class="oca-single">';
			echo '<strong>Operativa</strong></br>';
			foreach($shipping as $method){
				echo $method['name'];
			}
			if(!empty($sucursal_oca_c)){
				$oca_response = json_decode($sucursal_oca_c);			
 				echo '</br></br><strong>Dirección</strong></br>'; 
				echo $oca_response->Calle . ' ' . $oca_response->Numero . '</br>';
				echo $oca_response->Localidad . ' - ' . $oca_response->Provincia . '</br>';
				echo '<strong>Tel.</strong> ' . $oca_response->Telefono . '</br>';
				echo '<strong>Sucursal.</strong> ' . $oca_response->Sucursal;
			}
			echo '</div>';
		
	   
		
		if (empty($oca_shipping_label_tracking)){ ?>

			<style type="text/css">
				#generar-oca {
					background: #643494;
					color: white;
					width: 100%;
					text-align: center;
					height: 40px;
					padding: 0px;
					line-height: 37px;
					margin-top: 20px;
				}
			</style>

			<div id="generar-oca" class="button" data-id="<?php echo $post->ID; ?>">Generar Etiqueta - VERSION PREMIUM</div>
			<div id="editar-oca" style="display:none;" class="button" data-id="<?php echo $post->ID; ?>">Editar Datos</div>

			<div class="oca-single-label"> </div>	
			 
		<?php } 
	}


 

	function oca_admin_notice() {
		global $wp_session;
 
			?>
			<div class="notice error my-acf-notice is-dismissible" >
					<p><?php print_r($wp_session['oca_notice'] ); ?></p>
			</div>

			<?php
	}


?>