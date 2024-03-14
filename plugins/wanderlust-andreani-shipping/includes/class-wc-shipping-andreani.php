<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Shipping_Andreani class.
 *
 * @extends WC_Shipping_Method
 */
class WC_Shipping_Andreani extends WC_Shipping_Method {
	private $default_boxes;
	private $found_rates;

	/**
	 * Constructor
	 */
	public function __construct( $instance_id = 0 ) {
		
		$this->id                   = 'andreani_wanderlust';
		$this->instance_id 			 		= absint( $instance_id );
		$this->method_title         = __( 'Andreani Envios', 'woocommerce-shipping-andreani' );
 		$this->method_description   = __( 'Obtain shipping rates dynamically with Andreani API for your orders.', 'woocommerce' );
		$this->default_boxes 				= include( 'data/data-box-sizes.php' );
		$this->supports             = array(
			'shipping-zones',
			'instance-settings',
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
		$this->origin          = apply_filters( 'woocommerce_andreani_origin_postal_code', str_replace( ' ', '', strtoupper( $this->get_option( 'origin' ) ) ) );
		$this->origin_country  = apply_filters( 'woocommerce_andreani_origin_country_code', WC()->countries->get_base_country() );
		$this->api_key    		 = $this->get_option( 'api_key' );
		$this->api_user				 = $this->get_option( 'api_user' );
		$this->api_password		 = $this->get_option( 'api_password' );
		$this->api_nrocuenta   = $this->get_option( 'api_nrocuenta' );
		$this->api_confirmarretiro = $this->get_option( 'api_confirmarretiro' );
		$this->ajuste_precio   = $this->get_option( 'ajuste_precio' );
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
				<p>' . __( 'Argentina tiene que ser el pais de Origen.', 'woocommerce-shipping-andreani' ) . '</p>
			</div>';
		} elseif ( ! $this->origin && $this->enabled == 'yes' ) {
			echo '<div class="error">
				<p>' . __( 'Andreani esta activo, pero no hay Codigo Postal.', 'woocommerce-shipping-andreani' ) . '</p>
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
		$service_sucursal    = isset( $_POST['woocommerce_andreani_wanderlust_modalidad'] ) ? $_POST['woocommerce_andreani_wanderlust_modalidad'] : array();
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
						'woocommerce_andreani_wanderlust_modalidad' =>  $service_sucursal[ $i ] ,  
						'enabled'    => isset( $service_enabled[ $i ] ) ? true : false
					);
				}
			}
 
		}
			
		return $services;
	}

	/**
	 * Get packages - divide the WC package into packages/parcels suitable for a Andreani quote
	 */
	public function get_andreani_packages( $package ) {
		return $this->per_item_shipping( $package );
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
				$this->debug( sprintf( __( 'Product # is virtual. Skipping.', 'woocommerce-shipping-andreani' ), $item_id ), 'error' );
				continue;
			}

			if ( ! $values['data']->get_weight() ) {
				$this->debug( sprintf( __( 'Product # is missing weight. Aborting.', 'woocommerce-shipping-andreani' ), $item_id ), 'error' );
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
		$this->debug( __( 'Andreani modo de depuración está activado - para ocultar estos mensajes, desactive el modo de depuración en los ajustes.', 'woocommerce-shipping-andreani' ) );		
		$andreani_packages   = $this->get_andreani_packages( $package );
 		
		$andreani_package = $andreani_packages[0]['GroupPackageCount'];	

		$dimension_unit = esc_attr( get_option('woocommerce_dimension_unit' ));
 		$weight_unit = esc_attr( get_option('woocommerce_weight_unit' ));
 		$weight_multi = 0;
		$dimension_multi = 0;
 		if ($dimension_unit == 'm') { $dimension_multi =  1;}
 		if ($dimension_unit == 'cm') {  $dimension_multi =  100;}
 		if ($dimension_unit == 'mm') { $dimension_multi =  1000;}
 		if ($weight_unit == 'kg') { $weight_multi =  1;}
 		if ($weight_unit == 'g') {  $weight_multi =  0.001;}
 		$andreani_weightb = 0;
 		$andreani_volumesy = 0;
		foreach ($andreani_packages as $key) {
			$andreani_package = $key['GroupPackageCount'];
	 		$andreani_weight = $key['Weight']['Value'] * $weight_multi;
			$andreani_lenth = $key['Dimensions']['Length'] / $dimension_multi;
			$andreani_width = $key['Dimensions']['Width'] / $dimension_multi;		
			$andreani_height = $key['Dimensions']['Height'] / $dimension_multi;	
			$andreani_amount = $key['InsuredValue']['Amount'];	
			$andreani_weightb += $andreani_weight * $andreani_package;
 			$andreani_volume = $andreani_lenth * $andreani_width * $andreani_height;
			$andreani_volumesy += $andreani_volume * $andreani_package;
			$andreani_volumesy = number_format($andreani_volumesy, 10);	
			$andreani_packageb = 1;	
		}
				
		$seguro = round($package['contents_cost']);

 		foreach($this->services as $services) {
						
			if($services['enabled'] == 1){
				
				$params = array(
						"method" => array(
								 "get_rates" => array(
												'api_user' => $this->api_user,
												'api_password' => $this->api_password,
												'api_confirmarretiro' => $this->api_confirmarretiro,
												'api_key' => $this->api_key,
												'api_nrocuenta' => $this->api_nrocuenta,
												'operativa' => $services['operativa'],
												'peso_total' => $andreani_weightb,
												'volumen_total' => $andreani_volumesy,  
												'cp_origen' => $this->origin,
												'cp_destino' => $package['destination']['postcode'],   
												'n_paquetes' => $andreani_packageb,
												'valor_declarado' => $seguro,   
								 )
						)
				);
 
        		$andreani_response = wp_remote_post( $wp_session['url_andreani'], array(
 					'body'    => $params,
 				) );

 				if ( !is_wp_error( $andreani_response ) ) {
					$andreani_response = json_decode($andreani_response['body']);		
					
	 				if(!empty($andreani_response->error)){ //CHECK FOR ERRORS
						echo '<ul class="woocommerce-error"><li>'. $andreani_response->error .'</li> </ul>';					
						return;
					}
					
					
	 				if (!empty($andreani_response->notice)) {
							if ( !isset($_COOKIE['andreani_notice'])) {
								@setcookie( 'andreani_notice', $andreani_response->notice, time()+3600, "/");  /* expire in 1 hour */
							}
	  				} else {
						@setcookie("andreani_notice", "", time() - 3600, "/");
					}
	        
					
						if($this->ajuste_precio == '0'){
							$ajuste = '1';
						} else if($this->ajuste_precio == '0%'){
							$ajuste = '1';
						} else {
							$ajuste = $this->ajuste_precio;
						}
	 				
					$redondear_total = $this->redondear_total;

					if($andreani_response->results){
						$precio = $andreani_response->results ;
						$precio_base = $andreani_response->results ;
						 
						if($redondear_total=='1'){
							$precio = round($precio, 0, PHP_ROUND_HALF_UP);
						}
					  if($services['woocommerce_andreani_wanderlust_modalidad'] == 'sasp' || $services['woocommerce_andreani_wanderlust_modalidad'] == 'sapp' || $services['woocommerce_andreani_wanderlust_modalidad'] == 'pasp' || $services['woocommerce_andreani_wanderlust_modalidad'] == 'papp'){
								$titulo = $services['service_name'] . ': $' . $precio ;
								$precio = '0';
							} else {
								$titulo = $services['service_name'];
							}
							
							$rate = array(
								'id' => sprintf("%s-%s", $titulo, $services['service_name'] . '-' . $services['woocommerce_andreani_wanderlust_modalidad'] . 'api_nrocuenta' . $this->api_nrocuenta . 'operativa' . $services['operativa'] . 'instance_id' . $this->instance_id ),
								'label' => sprintf("%s", $titulo),
								'cost' => $precio,
								'calc_tax' => 'per_item'
							);			
							if($precio_base!='0'){
								$this->add_rate( $rate );
							}
						
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