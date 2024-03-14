<?php
if ( ! class_exists( 'WccFee_FlatShippingCity_Method' ) ) {
    class WccFee_FlatShippingCity_Method extends WC_Shipping_Method {
        /**
         * Constructor for your shipping class
         *
         * @access public
         * @return void
         */
        public function __construct() {
            $this->id                 = 'wccfee'; 
            $this->method_title       = __( 'Shipping Rates by City', 'wccfee' );  
            $this->method_description = __( 'You can set your shipping cost by user selected city', 'wccfee' ); 

            // Availability & Countries
            // $this->availability = 'including';
            // $this->countries = array(
            //     'US', // Unites States of America
            //     'CA', // Canada
            //     'DE', // Germany
            //     'GB', // United Kingdom
            //     'IT',   // Italy
            //     'ES', // Spain
            //     'HR',  // Croatia
            //     'PK'
            //     );

            $this->init();

            // print_r($this->settings);
            // die;

            $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
            $this->title =  $this->settings['title'] ?? __( 'Wc City Fee', 'wccfee' );
            $this->qty_multi =  $this->settings['qty_multi'] ?? '';

            add_action('woocommerce_update_options_shipping_methods', array(&$this, 'process_admin_options'));
        }

        /**
         * Init your settings
         *
         * @access public
         * @return void
         */
        function init() {
            // Load the settings API
            $this->init_form_fields(); 
            $this->init_settings(); 

            // Save settings in admin if you have any defined
            add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
        }

        function admin_options() {
            ?>
            <h2><?php _e('Flat Shipping Rate by City','woocommerce'); ?></h2>
            <table class="form-table">
            <?php $this->generate_settings_html(); ?>
            <?php $this->cities_form_fiels(); ?>
            

            </table> <?php
        }

        function getCities(){
            global $wpdb;
            $table = $wpdb->prefix . "wccfee_cities";
            return $wpdb->get_results("SELECT id, city_name, cost FROM $table", OBJECT);
        }

        function cities_form_fiels(){
            global $wpdb;

            if(isset( $_POST['cities'] )){
                $this->update_cities();
            }

            $cities = $this->getCities();

            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="woocommerce_wccfee_cities"><?php _e('Cities', 'wccfee') ?></label>
                </th>
                <td id="wcc_fee_rows">
                    <?php 
                    if(count($cities)) { 
                        foreach($cities as $citi){
                    ?> 
                        <div class="wcc_fee_row">
                        <input type="text" name="cities[<?php echo esc_attr($citi->id) ?>]" value="<?php echo esc_attr($citi->city_name) ?>" class="input-text regular-input" placeholder="<?php _e('City Name', 'wccfee') ?>">
                        <span class="wccfee_currency"><?php echo get_woocommerce_currency_symbol() ?></span>
                        <input type="text" name="cities_fee[<?php echo esc_attr($citi->id) ?>]" value="<?php echo esc_attr($citi->cost) ?>" class="input-text regular-input wccfee_cities_fee" placeholder="<?php _e('0', 'wccfee') ?>">
                        <span class="dashicons dashicons-trash wccfee_delcity" data-id="<?php echo esc_attr($citi->id) ?>"></span>
                        </div>
                    <?php }} else { ?>
                        <div class="wcc_fee_row">
                        <input type="text" name="cities[]" value="" class="input-text regular-input" placeholder="<?php _e('City Name', 'wccfee') ?>">
                        <span class="wccfee_currency"><?php echo get_woocommerce_currency_symbol() ?></span>
                        <input type="text" name="cities_fee[]" value="" class="input-text regular-input wccfee_cities_fee" placeholder="<?php _e('0', 'wccfee') ?>">
                        <span class="dashicons dashicons-trash wccfee_delcity"></span>
                        </div>
                    <?php } ?>
                    
                </td>
            </tr>
            <tr valign="top">
                <th style="padding-top:0"></th>
                <td style="padding-top:0" id="del_citites">
                                            
                    <button class="button-primary wccfee_addcity" type="button"><span class="dashicons dashicons-plus-alt"></span> <?php _e('Add City', 'wccfee') ?></button>

                    
                </td>
            </tr>
            <style>
            .wcc_fee_row { display: flex; margin-bottom: 5px; }
            .wccfee_cities_fee { 
                width:80px !important; 
                margin: 0 6px !important; 
                padding-left: 20px !important; 
            }
            .wccfee_addcity .dashicons { margin: 4px 4px 0 0; }
            .wccfee_delcity:hover { color: red; }
            #wcc_fee_rows { padding-bottom: 5px; }
            .wccfee_delcity {
                margin-top: 4px;
                color: #d54e21;
                cursor: pointer;
            }
            .wccfee_currency {
                width: 0;
                position: relative;
                left: 14px;
                top: 6px;
            }
            </style>
            <?php
        }

        function update_cities(){
            global $wpdb;

            $cities =  array_map( 'sanitize_text_field', $_POST['cities']);
            $fees   = array_map( 'sanitize_text_field', $_POST['cities_fee'] );
            $table  = $wpdb->prefix . "wccfee_cities";

            foreach($cities as $id => $citi){
                $city = [
                    'city_name' => $citi,
                    'cost' => $fees[$id]
                ];
                $check = $wpdb->get_results("SELECT id FROM $table where id = '$id' ORDER BY id ASC", OBJECT);

                if($check)
                $result = $wpdb->update($table, $city, ['id' => $id]);
                else
                $result = $wpdb->insert($table, $city);
            }

            if(isset($_POST['delcity'])){
                $delcity = array_map( 'sanitize_text_field', $_POST['delcity']);
                foreach($delcity as $del){
                    $wpdb->delete( $table, ['id' => $del] );
                }
            }


        }

        /**
         * Define settings field for this shipping
         * @return void 
         */
        function init_form_fields() { 

            $this->form_fields = array(

                'enabled' => array(
                    'title' => __( 'Enable', 'wccfee' ),
                    'type' => 'checkbox',
                    'description' => __( 'Enable this shipping.', 'wccfee' ),
                    'default' => 'yes'
                ),

                'title' => array(
                    'title' => __( 'Title', 'wccfee' ),
                    'type' => 'text',
                    'description' => __( 'Title to be display on site', 'wccfee' ),
                    'default' => __( 'WC City Fee', 'wccfee' )
                ),

                'qty_multi' => array(
                    'title' => __( 'Active', 'wccfee' ),
                    'type' => 'checkbox',
                    'description' => __( 'Price multiply to quantity', 'wccfee' )
                ),

            );

        }

        /**
         * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
         *
         * @access public
         * @param mixed $package
         * @return void
         */
        public function calculate_shipping( $package = array() ) {
            
            $weight = 0;
            $cost = 0;
            $address = $package["destination"]; // country, state, postcode, city, address, address_1, address_2

            // print_r(json_encode($package));
            // die;

            // foreach ( $package['contents'] as $item_id => $values ) 
            // { 
            //     $_product = $values['data']; 
            //     $weight = $weight + $_product->get_weight() * $values['quantity']; 
            // }
            // $weight = wc_get_weight( $weight, 'kg' );

            $cost = $this->getCityFee($address['city']);
            
            // print_r($cost);
            // die;
            
            // print_r($cost);
            // die;
            
            if(isset($cost['cost'])){
                if($this->qty_multi == 'yes'){
                    $qty = WC()->cart->get_cart_contents_count();
                    $cost['cost'] = $cost['cost'] * $qty;
                }
                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title,
                    'cost' => $cost
                );
                $this->add_rate( $rate );
            }
            
        }

        public function getCityFee($city_name){
            global $wpdb;
            $table = $wpdb->prefix . "wccfee_cities";
            return $wpdb->get_row("SELECT cost FROM $table where city_name = '$city_name'", ARRAY_A);
        }
    }
}
