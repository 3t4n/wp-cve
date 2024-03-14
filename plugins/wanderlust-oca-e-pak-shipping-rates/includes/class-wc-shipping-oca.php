<?php
error_reporting(0);

session_start();

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Shipping_Oca class.
 *
 * @extends WC_Shipping_Method
 */
class WC_Shipping_Oca extends WC_Shipping_Method {
	private $default_boxes;
	private $found_rates;

	/**
	 * Constructor
	 */
	public function __construct( $instance_id = 0 ) {
		
		$this->id                   = 'oca_wanderlust';
		$this->instance_id 			 		= absint( $instance_id );
		$this->method_title         = __( 'OCA Express Pak', 'woocommerce-shipping-oca' );
 		$this->method_description   = __( 'Obtain shipping rates dynamically via the OCA API for your orders.', 'woocommerce' );
		$this->default_boxes 				= include( 'data/data-box-sizes.php' );
		$this->supports             = array(
			'shipping-zones',
			'instance-settings',
			//'instance-settings-modal',
		);

		$this->init();
		
 		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

	}

	/**
	 * init function.
	 */
	public function init() {
		// Load the settings.
		$this->init_form_fields = include( 'data/data-settings.php' );
		$this->init_settings();
		$this->instance_form_fields = include( 'data/data-settings.php' );
	 
		// Define user set variables
		$this->title           = $this->get_option( 'title', $this->method_title );
		$this->origin          = apply_filters( 'woocommerce_oca_origin_postal_code', str_replace( ' ', '', strtoupper( $this->get_option( 'origin' ) ) ) );
		$this->origin_country  = apply_filters( 'woocommerce_oca_origin_country_code', WC()->countries->get_base_country() );
		$this->api_key    		 = $this->get_option( 'api_key' );
		$this->origin_contacto = $this->get_option( 'origin_contacto' );
		$this->origin_email		 = $this->get_option( 'origin_email' );
		$this->origin_calle		 = $this->get_option( 'origin_calle' );
		$this->origin_numero	 = $this->get_option( 'origin_numero' );
		$this->origin_piso		 = $this->get_option( 'origin_piso' );
		$this->origin_depto		 = $this->get_option( 'origin_depto' );
		$this->origin_localidad			 = $this->get_option( 'origin_localidad' );
		$this->origin_provincia			 = $this->get_option( 'origin_provincia' );
		$this->origin_observaciones	 = $this->get_option( 'origin_observaciones' );
		$this->api_user				 = $this->get_option( 'api_user' );
		$this->api_password		 = $this->get_option( 'api_password' );
		$this->api_nrocuenta   = $this->get_option( 'api_nrocuenta' );
		$this->cuit_number     = $this->get_option( 'cuit_number' );
		$this->ajuste_precio   = $this->get_option( 'ajuste_precio' );
		$this->envio_gratis   = $this->get_option( 'envio_gratis' );
		$this->tipo_servicio   = $this->get_option( 'tipo_servicio' );
		$this->debug           = ( $bool = $this->get_option( 'debug' ) ) && $bool == 'yes' ? true : false;
 		$this->services        = $this->get_option( 'services', array( ));
		$this->mercado_pago    = ( $bool = $this->get_option( 'mercado_pago' ) ) && $bool == 'yes' ? true : false;
		$this->redondear_total = ( $bool = $this->get_option( 'redondear_total' ) ) && $bool == 'yes' ? true : false;
	}

	/**
	 * Output a message
	 */
	public function debug( $message, $type = 'notice' ) {
		if ( $this->debug ) {
			wc_add_notice( $message, $type );
		}
	}

	/**
	 * environment_check function.
	 */
	private function environment_check() {
		if ( ! in_array( WC()->countries->get_base_country(), array( 'AR' ) ) ) {
			echo '<div class="error">
				<p>' . __( 'Argentina tiene que ser el pais de Origen.', 'woocommerce-shipping-oca' ) . '</p>
			</div>';
		} elseif ( ! $this->origin && $this->enabled == 'yes' ) {
			echo '<div class="error">
				<p>' . __( 'OCA esta activo, pero no hay Codigo Postal.', 'woocommerce-shipping-oca' ) . '</p>
			</div>';
		}
	}

	/**
	 * admin_options function.
	 */
	public function admin_options() {
		// Check users environment supports this method
		$this->environment_check();

		// Show settings
		parent::admin_options();
	}


	/**
	 * generate_box_packing_html function.
	*/
	public function generate_service_html() {
		ob_start();
		include( 'data/services.php' );
		return ob_get_clean();
	}

	
	/**
	 * validate_box_packing_field function.
	 *
	 * @param mixed $key
	*/
		public function validate_service_field( $key ) {
						
 		$service_name     = isset( $_POST['service_name'] ) ? $_POST['service_name'] : array();
		$service_operativa     = isset( $_POST['service_operativa'] ) ? $_POST['service_operativa'] : array();
		$service_sucursal    = isset( $_POST['woocommerce_oca_wanderlust_modalidad'] ) ? $_POST['woocommerce_oca_wanderlust_modalidad'] : array();
		$service_enabled    = isset( $_POST['service_enabled'] ) ? $_POST['service_enabled'] : array();
			  	
		$services = array();

		if ( ! empty( $service_operativa ) && sizeof( $service_operativa ) > 0 ) {
			for ( $i = 0; $i <= max( array_keys( $service_operativa ) ); $i ++ ) {

				if ( ! isset( $service_operativa[ $i ] ) )
					continue;
		
				if ( $service_operativa[ $i ] ) {
  					$services[] = array(
 						'service_name'     =>  $service_name[ $i ],
						'operativa'     => floatval( $service_operativa[ $i ] ),
						'woocommerce_oca_wanderlust_modalidad' =>  $service_sucursal[ $i ] ,  
						'enabled'    => isset( $service_enabled[ $i ] ) ? true : false
					);
				}
			}
 
		}
			
		return $services;
	}

	/**
	 * Get packages - divide the WC package into packages/parcels suitable for a OCA quote
	 */
	public function get_oca_packages( $package ) {
		switch ( $this->packing_method ) {
			case 'box_packing' :
				return $this->box_shipping( $package );
			break;
			case 'per_item' :
			default :
				return $this->per_item_shipping( $package );
			break;
		}
	}

	/**
	 * per_item_shipping function.
	 *
	 * @access private
	 * @param mixed $package
	 * @return array
	 */
	private function per_item_shipping( $package ) {
		$to_ship  = array();
		$group_id = 1;

		// Get weight of order
		foreach ( $package['contents'] as $item_id => $values ) {

			if ( ! $values['data']->needs_shipping() ) {
				$this->debug( sprintf( __( 'Product # is virtual. Skipping.', 'woocommerce-shipping-oca' ), $item_id ), 'error' );
				continue;
			}

			if ( ! $values['data']->get_weight() ) {
				$this->debug( sprintf( __( 'Product # is missing weight. Aborting.', 'woocommerce-shipping-oca' ), $item_id ), 'error' );
				return;
			}

			$group = array();

			$group = array(
				'GroupNumber'       => $group_id,
				'GroupPackageCount' => $values['quantity'],
				'Weight' => array(
					'Value' => $values['data']->get_weight(),
					'Units' => 'KG'
				),
				'packed_products' => array( $values['data'] )
			);

			if ( $values['data']->get_length() && $values['data']->get_height() && $values['data']->get_width() ) {

				$dimensions = array( $values['data']->get_length(), $values['data']->get_width(), $values['data']->get_height() );

				sort( $dimensions );

				$group['Dimensions'] = array(
					'Length' => $values['data']->get_length(),
					'Width'  => $values['data']->get_width(),
					'Height' => $values['data']->get_height(),
					'Units'  => 'CM'
				);
			}

			$group['InsuredValue'] = array(
				'Amount'   => round( $values['data']->get_price() ),
				'Currency' => get_woocommerce_currency()
			);

			$to_ship[] = $group;

			$group_id++;
		}

		return $to_ship;
	}



	/**
	 * calculate_shipping function.
	 *
	 * @param mixed $package
	 */
	public function calculate_shipping( $package = array() ) {
		global $wp_session;

		// Debugging
		$this->debug( __( 'OCA Express Pak modo de depuración está activado - para ocultar estos mensajes, desactive el modo de depuración en los ajustes.', 'woocommerce-shipping-oca' ) );		
		
		// Get requests
		$oca_packages   = $this->get_oca_packages( $package );
				 		
		$oca_package = $oca_packages[0]['GroupPackageCount'];	
		
		$dimension_unit = esc_attr( get_option('woocommerce_dimension_unit' ));
 		$weight_unit = esc_attr( get_option('woocommerce_weight_unit' ));
 		$weight_multi = 0;
		$dimension_multi = 0;
 		if ($dimension_unit == 'm') { $dimension_multi =  1;}
 		if ($dimension_unit == 'cm') {  $dimension_multi =  100;}
 		if ($dimension_unit == 'mm') { $dimension_multi =  1000;}
 		if ($weight_unit == 'kg') { $weight_multi =  1;}
 		if ($weight_unit == 'g') {  $weight_multi =  0.001;}
						
		foreach ($oca_packages as $key) {
			$oca_package = $key['GroupPackageCount'];
	 		$oca_weight = $key['Weight']['Value'] * $weight_multi;
			$oca_lenth = $key['Dimensions']['Length'] / $dimension_multi;
			$oca_width = $key['Dimensions']['Width'] / $dimension_multi;		
			$oca_height = $key['Dimensions']['Height'] / $dimension_multi;	
			$oca_amount = $key['InsuredValue']['Amount'];	
			$oca_weightb += $oca_weight * $oca_package;
 			$oca_volume = $oca_lenth * $oca_width * $oca_height;
			$oca_volumesy += $oca_volume * $oca_package;
			$oca_volumesy = number_format($oca_volumesy, 10);	
			$oca_packageb = 1;	
		}
		
 
		$seguro = round($package['contents_cost']);

	 	$mercado_pago = $this->mercado_pago;		
 		if($mercado_pago =='1'){
			add_filter( 'woocommerce_cart_shipping_method_full_label', 'remove_local_pickup_free_label', 10, 2 );
			function remove_local_pickup_free_label($full_label, $method){
				$full_label = str_replace("(Gratis)","",$full_label);
			return $full_label;
			}
			add_filter( 'woocommerce_cart_shipping_method_full_label', 'remove_local_pickup_free_label_en', 10, 2 );
			function remove_local_pickup_free_label_en($full_label, $method){
				$full_label = str_replace("(Free)","",$full_label);
			return $full_label;
			}		
		}			
								
		foreach($this->services as $services) {
						
			if($services['enabled'] == 1){
				
				$params = array(
						"method" => array(
								 "get_rates" => array(
												'api_key' => $this->api_key,
												'cuit' => $this->cuit_number,
												'operativa' => $services['operativa'],
												'peso_total' => $oca_weightb,
												'volumen_total' => $oca_volumesy,  
												'cp_origen' => $this->origin,
												'cp_destino' => $package['destination']['postcode'],   
												'n_paquetes' => $oca_packageb,
												'valor_declarado' => $seguro,   
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
  
				
 				if(!empty($oca_response->error)){ //CHECK FOR ERRORS
					echo '<ul class="woocommerce-error"><li>'. $oca_response->error .'</li> </ul>';					
					return;
				}
				
				
 				if (!empty($oca_response->notice)) {
						if ( !isset($_COOKIE['oca_notice'])) {
							setcookie( 'oca_notice', $oca_response->notice, time()+3600, "/");  /* expire in 1 hour */
						}
  			} else {
					setcookie("oca_notice", "", time() - 3600, "/");
				}
        
				
					if($this->ajuste_precio == '0'){
						$ajuste = '1';
					} else if($this->ajuste_precio == '0%'){
						$ajuste = '1';
					} else {
						$ajuste = $this->ajuste_precio;
					}
 				
				$redondear_total = $this->redondear_total;

				$porcentaje = (int)$oca_response->results[0]->Total * (int)$ajuste / 100;
				
				$precio = $oca_response->results[0]->Total + $porcentaje;
				$precio_base = $oca_response->results[0]->Total + $porcentaje;
				 
				if($redondear_total=='1'){
					$precio = round($precio, 0, PHP_ROUND_HALF_UP);
				}
				
				if($seguro >= $this->envio_gratis){
					$precio = 0;
				}
											
				if($mercado_pago =='1'){
 					$precio = number_format($precio, 2);
					$titulo = $services['service_name'] . ': $' . $precio ;
					$precio = '0';
					$rate = array(
						'id' => sprintf("%s-%s", $titulo, $services['service_name'] . '-' . $services['woocommerce_oca_wanderlust_modalidad'] . 'cuit' . $this->cuit_number . 'operativa' . $services['operativa'] ),
						'label' => sprintf("%s", $titulo),
						'cost' => $precio,
						'calc_tax' => 'per_item'
					);	
 					$this->add_rate( $rate );
				} else {
				  if($services['woocommerce_oca_wanderlust_modalidad'] == 'sasp' || $services['woocommerce_oca_wanderlust_modalidad'] == 'sapp' || $services['woocommerce_oca_wanderlust_modalidad'] == 'pasp' || $services['woocommerce_oca_wanderlust_modalidad'] == 'papp'){
						$titulo = $services['service_name'] . ': $' . $precio ;
						$precio = '0';
					} else {
						$titulo = $services['service_name'];
					}
					
					$rate = array(
						'id' => sprintf("%s-%s", $titulo, $services['service_name'] . '-' . $services['woocommerce_oca_wanderlust_modalidad'] . 'cuit' . $this->cuit_number . 'operativa' . $services['operativa'] ),
						'label' => sprintf("%s", $titulo),
						'cost' => $precio,
						'calc_tax' => 'per_item',
						'package' => $package,
					);			
					if($precio_base!='0'){
						$this->add_rate( $rate );
					}
				}		
			}
			
		}	
 	 
 	}

	/**
	 * sort_rates function.
	 **/
	public function sort_rates( $a, $b ) {
		if ( $a['sort'] == $b['sort'] ) return 0;
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
	}
}