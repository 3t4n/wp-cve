<?php
// class for custom tab shipping by city woocommerce settings
if ( ! class_exists( 'WP_Class_Cmetric_Sbcfw_Setting' ) ) :

class WP_Class_Cmetric_Sbcfw_Setting  {

    /**
     * Setup settings class
     *
     * @since  1.0
     */
      public function __construct() {
      
        $this->id    = 'sbcfw';
        $this->label = __( 'Advance Shipping Zone', 'cmetric-sbcfw' );
        
              /* common hooks for custom setting WooCommerce */
             
             add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
              add_action( 'woocommerce_settings_tabs_sbcfw_setting_zone_city', __CLASS__ . '::settings_tab' );
              add_action( 'woocommerce_update_options_sbcfw_setting_zone_city', __CLASS__ . '::update_settings' );
         
         
              if('yes' === get_option( 'wc_sbcfw_shipping_cities_section_enabled' )) :
                    add_filter( 'woocommerce_shipping_calculator_enable_city', array( $this, 'enable_city_shipping_calculator' ), 99, 0 );    
                    add_filter('woocommerce_package_rates', array( $this , 'woocommerce_package_rates_func' ), 10, 2 );
              endif;
                  

      }
    
                
        public static function add_settings_tab( $settings_tabs ) {
            $settings_tabs['sbcfw_setting_zone_city'] = __( 'Advance Shipping Zone', 'woocommerce-sbcfw-setting-zone-city' );
            return $settings_tabs;
        }
                  

        /**
         * Uses the WooCommerce admin fields API to output settings via woocommerce_admin_fields() function.
         *
         * @uses woocommerce_admin_fields()
         * @uses self::get_settings()
         */
        public static function settings_tab() {
            woocommerce_admin_fields( self::get_settings() );
        }

        /**
         * Uses the WooCommerce options API to save settings via woocommerce_update_options() function.
         *
         * @uses woocommerce_update_options()
         * @uses self::get_settings()
         */
        public static function update_settings() {
            woocommerce_update_options( self::get_settings() );            

        }

        /**
         * Get all the settings for this plugin for woocommerce_admin_fields() function.
         *
         * @return array Array of settings for woocommerce_admin_fields() function.
         */
        public static function get_settings() {

            $data_store = WC_Data_Store::load( 'shipping-zone' );
            $raw_zones  = $data_store->get_zones();
      
            $settings['section_title'] = array(
                    'name'     => __( 'Shipping Zone by city', 'woocommerce-sbcfw-setting-zone-city' ),
                    'type'     => 'title',
                    'desc'     => '',
                    'id'       => 'wc_sbcfw_setting_zone_city_section_title'
            );

            $settings['wc_sbcfw_shipping_cities_section_enabled']    = array(
                    'title'    => __( 'Enable Shipping by Cities', 'wc-sbcfw-shipping-city-zone-section-enabled' ),
                    'desc'     => '<strong>' . __( 'Enable section', 'wc-sbcfw-shipping-city-zone-section-enabled' ) . '</strong>',
                    'id'       => 'wc_sbcfw_shipping_cities_section_enabled',
                    'default'  => '0',
                    'type'     => 'checkbox',
                );
            

            foreach ($raw_zones as $raw_zone) {

                 $zone                                = new WC_Shipping_Zone( $raw_zone );
                //$zones[ $zone->get_id() ]            = $zone->get_data();
                $zones[ $zone->get_id() ]['zone_id'] = $zone->get_id();
                $zones[ $zone->get_id() ]['zone_name'] = $raw_zone->zone_name;
                $zones[ $zone->get_id() ]['formatted_zone_location'] = $zone->get_formatted_location();
                $zones[ $zone->get_id() ]['shipping_methods']        = $zone->get_shipping_methods();

                foreach ($zones[ $zone->get_id() ]['shipping_methods'] as  $methodvalue) {
                   

                        $settings['wc_sbcfw_shipping_city_zone_'.$methodvalue->id.'_instance_'.$methodvalue->instance_id] = array(
                                'name' => __( $zones[ $zone->get_id() ]['zone_name'].' '.$methodvalue->method_title.' :: '. $methodvalue->title, 'wc-sbcfw-shipping-city-zone' ),
                                'type' => 'textarea',
                                'desc' => __( 'Enter each cities per line for this Zone', 'wc-sbcfw-shipping-city-zone'),
                                'id'   => 'wc_sbcfw_shipping_city_zone_'.$methodvalue->id.'_instance_'.$methodvalue->instance_id,
                                'class' => 'setting_city_zone'
                        );
                       
                 }
            }
              $settings['section_end'] = array(
                   'type' => 'sectionend',
                     'id' => 'wc_sbcfw_setting_zone_city_section_end'
            );
            
            return apply_filters( 'wc_sbcfw_setting_zone_city_settings', $settings );
        }


        /* Enable shipping by city calculation on checkout */
    public function enable_city_shipping_calculator() {
      return ( 'yes' === get_option( 'wc_sbcfw_shipping_cities_section_enabled' ) );
    }

    // add the action 

  
        /* Filter on calculate shipping method on checkout  */       
        public function woocommerce_package_rates_func( $rates, $package ) {

           

                $shipping_city = $package['destination']['city'];  // get shipping city field value      

                $new_rates = array();                                
                $accepted_cities = array();
                $cnt = 0;  $newrate = 0; 
                $newrateid='';  $newratelabel ='';  $ship_id='';            
                $new_match_zone_arr = array();

                $data_store = WC_Data_Store::load( 'shipping-zone' );
                $raw_zones  = $data_store->get_zones();
                                             
                foreach ($raw_zones as $raw_zone) {
     
                $zone                                                = new WC_Shipping_Zone( $raw_zone );
                //$zones[ $zone->get_id() ]                          = $zone->get_data();
                $zones[ $zone->get_id() ]['zone_id']                 = $zone->get_id();
                $zones[ $zone->get_id() ]['zone_name']               = $raw_zone->zone_name;
                $zones[ $zone->get_id() ]['formatted_zone_location'] = $zone->get_formatted_location();
                $zones[ $zone->get_id() ]['shipping_methods']        = $zone->get_shipping_methods();
                $i=0;
                
                foreach ($zones[ $zone->get_id() ]['shipping_methods'] as  $methodvalue) {
                          

                     $accepted_cities  = get_option('wc_sbcfw_shipping_city_zone_'.$methodvalue->id.'_instance_'.$methodvalue->instance_id);
                     
                    if(!empty( $accepted_cities )){

                           $accepted_cities = array_map( 'strtoupper', array_map( 'trim', explode( PHP_EOL, $accepted_cities ) ) );
                           $accepted_cities = array_map( 'trim', $accepted_cities );
                           $accepted_cities = array_map( 'strtoupper', $accepted_cities );
                           
                            if(in_array( strtoupper( $shipping_city ), $accepted_cities ))
                            {
                            if("free_shipping" === $methodvalue->id)    
                            {
                                $has_coupon         = false;
                                $has_met_min_amount = false;

                                if ( in_array( $methodvalue->requires, array( 'coupon', 'either', 'both' ), true ) ) {
                                    $coupons = WC()->cart->get_coupons();

                                    if ( $coupons ) {
                                        foreach ( $coupons as $code => $coupon ) {
                                            if ( $coupon->is_valid() && $coupon->get_free_shipping() ) {
                                                $has_coupon = true;
                                                break;
                                            }
                                        }
                                    }
                                }

                                if ( in_array( $methodvalue->requires, array( 'min_amount', 'either', 'both' ), true ) ) {
                                    $total = WC()->cart->get_displayed_subtotal();

                                    if ( WC()->cart->display_prices_including_tax() ) {
                                        $total = $total - WC()->cart->get_discount_tax();
                                    }

                                    if ( 'no' === $methodvalue->ignore_discounts ) {
                                        $total = $total - WC()->cart->get_discount_total();
                                    }

                                    $total = round( $total, wc_get_price_decimals() );

                                    if ( $total >= $methodvalue->min_amount ) {
                                        $has_met_min_amount = true;
                                    }
                                }

                                switch ( $methodvalue->requires ) {
                                    case 'min_amount':
                                        $is_available = $has_met_min_amount;
                                        break;
                                    case 'coupon':
                                        $is_available = $has_coupon;
                                        break;
                                    case 'both':
                                        $is_available = $has_met_min_amount && $has_coupon;
                                        break;
                                    case 'either':
                                        $is_available = $has_met_min_amount || $has_coupon;
                                        break;
                                    default:
                                        $is_available = true;
                                        break;
                                }
                                  if($is_available){
                                        $ship_id = $methodvalue->instance_id;
                                        $newrate =  $methodvalue->cost;
                                        $newrateid =  $methodvalue->id.':'.$ship_id;
                                        $newratelabel = $methodvalue->title;
                                          

                                         /* set new rate array if match shipping city value in shipping method */ 
                                         $new_match_zone_arr[$methodvalue->id] = array(
                                            'id'     => $newrateid,
                                            'method_id'   => $methodvalue->id,
                                            'instance_id' => $ship_id,
                                            'label'       => $newratelabel,
                                            'cost'      => $newrate
                                             );
                                         $i++;
                                  }  

                            }else{

                                $ship_id = $methodvalue->instance_id;
                                $newrate =  $methodvalue->cost;
                                $newrateid =  $methodvalue->id.':'.$ship_id;
                                $newratelabel = $methodvalue->title;
                                  

                                 /* set new rate array if match shipping city value in shipping method */ 
                                 $new_match_zone_arr[$methodvalue->id] = array(
                                    'id'     => $newrateid,
                                    'method_id'   => $methodvalue->id,
                                    'instance_id' => $ship_id,
                                    'label'       => $newratelabel,
                                    'cost'      => $newrate
                                     );
                                 $i++;
                            }
                           

                              
                                                 
                            }else{                          
                                                           
                                $cnt++;
                            } 
                    }else{
                        
                       //  unset( $rates[ $rate_key ] ); 
                       $cnt++;          
                    } 
                  
                }
                      
        }
          
        if(count($new_match_zone_arr)>0){

            unset($rates);
            $rates = array();
            
            foreach ($new_match_zone_arr as $ratekey => $finalrate){

                        $new_cost= $new_match_zone_arr[$ratekey]['cost'];
                           
                            if ( ! ( $new_cost > 0 ) ) {      $label = ': ' . wc_price(0);  }else{
                                 $label ='';
                            }  

                        $new_id = $new_match_zone_arr[$ratekey]['id'];
                        $new_label = $new_match_zone_arr[$ratekey]['label'].$label;
                        $new_instance_id = $new_match_zone_arr[$ratekey]['instance_id'];

                $addrate = new WC_Shipping_Rate($new_id, $new_label, $new_cost, 0,$new_instance_id );

                $rates[$new_id] = $addrate; // set new rates of array in current package
                
            }
        }else{
			  unset($rates);
            $rates = array();
		}   
          
        return $rates;                          
       
    }
}
endif;
?>