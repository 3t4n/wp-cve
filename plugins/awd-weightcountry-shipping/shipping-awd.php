<?php
/**
 * Plugin Name: AWD Weight/Country Shipping
 * Plugin URI: http://www.andyswebdesign.ie/blog/free-woocommerce-weight-and-country-based-shipping-extension-plugin/
 * Description: Weight and Country based shipping method for Woocommerce.
 * Version: 1.0.1
 * Author: Andy_P
/*  Copyright 2012  andyswebdesign.ie  

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('plugins_loaded', 'init_awd_shipping', 0);

function init_awd_shipping() {

    if ( ! class_exists( 'WC_Shipping_Method' ) ) return;
    
class awd_Shipping extends WC_Shipping_Method {

    function __construct() { 
     
                $this->id 			= 'awd_shipping';
                $this->method_title 		= __( 'AWD Weight/Country', 'woocommerce' );
		
		$this->admin_page_heading 	= __( 'Weight based shipping', 'woocommerce' );
		$this->admin_page_description 	= __( 'Define shipping by weight and country', 'woocommerce' );
              
               
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'sync_countries' ) );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_admin_options' ) );

    	$this->init();
        $this->display_country_groups();        
    }

    /**
     * init function
     */
    function init() {
    
            $this->init_form_fields();
            $this->init_settings();

                $this->enabled		  = $this->settings['enabled'];
                $this->title 		  = $this->settings['title'];
                $this->country_group_no   = $this->settings['country_group_no'];
                $this->sync_countries     = $this->settings['sync_countries'];
                $this->availability       = 'specific';
                $this->countries 	  = $this->settings['countries'];
                $this->type               = 'order';
                $this->tax_status	  = $this->settings['tax_status'];
                $this->fee                = $this->settings['fee'];
                $this->options 		  = isset( $this->settings['options'] ) ? $this->settings['options'] : '';
                $this->options		  = (array) explode( "\n", $this->options );
    }
    
    function init_form_fields() {

    global $woocommerce;

        $this->form_fields = array(
                    'enabled' => array(
                                                    'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
                                                    'type' 			=> 'checkbox',
                                                    'label' 		=> __( 'Enable this shipping method', 'woocommerce' ),
                                                    'default' 		=> 'no',
                                            ),
                    'title' => array(
                                                    'title' 		=> __( 'Method Title', 'woocommerce' ),
                                                    'type' 			=> 'text',
                                                    'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
                                                    'default'		=> __( 'Weight Based Shipping', 'woocommerce' ),
                                            ),

        			'tax_status' => array(
							'title' 		=> __( 'Tax Status', 'woocommerce' ),
							'type' 			=> 'select',
							'description' 	=> '',
							'default' 		=> 'taxable',
							'options'		=> array(
								'taxable' 	=> __( 'Taxable', 'woocommerce' ),
								'none' 		=> __( 'None', 'woocommerce' ),
							),
						),
                           'fee' => array(
                                                    'title' 		=> __( 'Handling Fee', 'woocommerce' ),
                                                    'type' 			=> 'text',
                                                    'description'	=> __( 'Fee excluding tax. Enter an amount, e.g. 2.50. Leave blank to disable.', 'woocommerce' ),
                                                    'default'		=> '',
                                            ),
                       'options' => array(
                                                    'title' 		=> __( 'Shipping Rates', 'woocommerce' ),
                                                    'type' 			=> 'textarea',
                                                    'description'	=> __( 'Set your weight based rates for country groups (one per line). Example: <code>Max weight|Cost|country group number</code>. Example: <code>100|6.95|3</code>.', 'woocommerce' ),
                                                    'default'		=> '',
                                            ),
              'country_group_no' => array(
                                                    'title' 		=> __( 'Number of country groups', 'woocommerce' ),
                                                    'type' 			=> 'text',
                                                    'description'	=> __( 'Number of groups of countries sharing delivery rates (hit "Save changes" button after you have changed this setting).' ),
                                                    'default' 		=> '3',
                                            ),
                'sync_countries' => array(
                                                    'title' 		=> __( 'Add countries to allowed', 'woocommerce' ),
                                                    'type' 			=> 'checkbox',
                                                    'label' 		=> __( 'Countries added to country groups will be automatically added to Allowed Countries 
                                                                                    in <a href="/wp-admin/admin.php?page=woocommerce_settings&tab=general">General settings tab.</a>
                                                                                    This makes sure countries defined in country groups are visible on checkout.
                                                                                    Deleting country from country group will not delete country from Allowed Countries.', 'woocommerce' ),
                                                    'default' 		=> 'no',
                                            ),
                    );  
    }

    /*
    * Displays country group selects in shipping method's options
    */
    function display_country_groups() {

        global $woocommerce;  
    //   echo prp($this->settings['countries1']);
        $number = $this->country_group_no;
        for($counter = 1; $number >= $counter; $counter++) {

            $this->form_fields['countries'.$counter] =  array(
                    'title'     => sprintf(__( 'Country Group %s', 'woocommerce' ), $counter),
                    'type'      => 'multiselect',
                    'class'     => 'chosen_select',
                    'css'       => 'width: 450px;',
                    'default'   => '',
                    'options'   => $woocommerce->countries->countries
            );
        }    
    }

    /*
    * This method is called when shipping is calculated (or re-calculated)
    */  
    function calculate_shipping($package = array()) {

        global $woocommerce;

            $rates      = $this->get_rates_by_countrygroup($this->get_countrygroup($package));
            $weight     = $woocommerce->cart->cart_contents_weight;
            $final_rate = $this->pick_smallest_rate($rates, $weight);
            
            if($final_rate === false) return false;
            
            $taxable    = ($this->tax_status == 'taxable') ? true : false;
            
            
            if($this->fee > 0 && $package['destination']['country']) $final_rate = $final_rate + $this->fee;

                $rate = array(
                'id'        => $this->id,
                'label'     => $this->title,
                'cost'      => $final_rate,
                'taxes'     => '',
                'calc_tax'  => 'per_order'
                );
                
        $this->add_rate( $rate );
    }
    
    /*
    * Retrieves the number of country group for country selected by user on checkout 
    */        
    function get_countrygroup($package = array()) {    

            $counter = 1;

            while(is_array($this->settings['countries'.$counter])) {
                if (in_array($package['destination']['country'], $this->settings['countries'.$counter])) 
                    $country_group = $counter;

                $counter++;
            }
        return $country_group;
    }

    /*
    * Retrieves all rates available for selected country group
    */
    function get_rates_by_countrygroup($country_group = null) {

        $rates = array();
                if ( sizeof( $this->options ) > 0) foreach ( $this->options as $option => $value ) {

                    $rate = preg_split( '~\s*\|\s*~', trim( $value ) );

                    if ( sizeof( $rate ) !== 3 )  {
                        continue;
                    } else {
                        $rates[] = $rate;

                    }
                }

                foreach($rates as $key) {
                    if($key[2] == $country_group) {
                        $countrygroup_rate[] = $key;
                    }
                }
        return $countrygroup_rate;
    }

    /*
    * Picks the right rate from available rates based on cart weight
    */        
    function pick_smallest_rate($rates,$weight) {

    if($weight == 0) return 0; // no shipping for cart without weight

        if( sizeof($rates) > 0) foreach($rates as $key => $value) {

                if($weight <= $value[0]) {
                    $postage[] = $value[1];   
                }
                $postage_all_rates[] = $value[1];
        }

        if(sizeof($postage) > 0) {
            return min($postage);
                } else {
                if (sizeof($postage_all_rates) > 0) return max($postage_all_rates);
                }
        return false;    
    }

    /*
    * Uptades Allowed Countries with countries added to country groups
    */
    function sync_countries() {

        if($this->settings['sync_countries'] == 'yes') {
            $countries = $this->get_cg_countries();
            update_option('woocommerce_specific_allowed_countries', $countries);
        } 
    }
    /*
     * Retrieves countries from country groups and merges them with Allowed Countries array
     */
    function get_cg_countries() {    

                $counter = 1;
                $countries = array();

                    while(is_array($this->settings['countries'.$counter])) {
                        $countries = array_merge($countries, $this->settings['countries'.$counter]);

                    $counter++;
                    }
                    
            $allowed_countries = get_option( 'woocommerce_specific_allowed_countries' );

            if (is_array($allowed_countries)) $countries = array_merge($countries, $allowed_countries);
            $countries = array_unique($countries);
        return $countries;
    }

    function etz($etz) {
        
        if(empty($etz) || !is_numeric($etz)) {
            return 0.00;
        }
    }
    
    public function admin_options() {

    	?>
    	<h3><?php _e('Weight and Country based shipping', 'woocommerce'); ?></h3>
    	<p><?php _e('Lets you calculate shipping based on Country and weight of the cart. Lets you set unlimited weight bands on per country basis and group countries 
            that share same delivery cost/bands. For help and how to use go <a href="http://www.andyswebdesign.ie/blog/free-woocommerce-weight-and-country-based-shipping-extension-plugin/" target="_blank">here</a>', 'woocommerce'); ?></p>
    	<table class="form-table">
    	<?php
    		// Generate the HTML For the settings form.
    		$this->generate_settings_html();
    	?>
		</table><!--/.form-table-->
    	<?php
    }

  } // end awd_Shipping
}

/**
 * Add shipping method to WooCommerce
 **/
function add_awd_shipping( $methods ) {
	$methods[] = 'awd_shipping'; return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'add_awd_shipping' );

?>
