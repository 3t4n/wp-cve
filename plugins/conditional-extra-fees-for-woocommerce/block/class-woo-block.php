<?php

use Automattic\WooCommerce\StoreApi\StoreApi;
use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema;
//use Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema;
//use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartSchema;
//use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema;

use PISOL\CEFW\ExtraFees;
class pisol_cefw_woo_block{

    private $extend;

    protected static $instance = null;

    const IDENTIFIER = 'pisol_cefw_fees';


    public static function get_instance( ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    protected function __construct(){
        add_action( 'woocommerce_blocks_loaded', [$this, 'loadData']);
        add_action('wp_enqueue_scripts', [$this, 'fillCode']);
    }

    function loadData(){
        if(!class_exists('\Automattic\WooCommerce\StoreApi\StoreApi') || !class_exists('\Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema') || !class_exists('\Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema')) return;
        
        $this->extend = StoreApi::container()->get( ExtendSchema::class );
	    $this->extendData();
        $this->callBack();
    }

    function extendData(){
        $this->extend->register_endpoint_data(
			array(
				'endpoint'        => CartSchema::IDENTIFIER,
				'namespace'       => self::IDENTIFIER,
				'data_callback'   => array( $this, 'optionalFees' ),
				'schema_type'       => ARRAY_A,
			)
		);
    }

    function callBack(){
        woocommerce_store_api_register_update_callback(
            [
              'namespace' => self::IDENTIFIER,
              'callback'  => [$this, 'checkboxChange']
            ]
          );
    }

    function checkboxChange( $data ){
        if($data['checked']){
            \Pi_cefw_Apply_fees::saveFeesInSession($data['id']);
        }else{
            \Pi_cefw_Apply_fees::removeFeesInSession($data['id']);
        }
    }

    function optionalFees(){
        if( !(function_exists('WC') && is_object(WC()->cart)) ) return ['options' => [], 'label' => ''];
        
        $cart = WC()->cart;
        $fees = ExtraFees::matched_optional_fees ( $cart );
        $available_fees = [];
        $label = get_option('pisol_cefw_optional_services','Optional services');
        $main_obj = \Pi_cefw_Apply_fees::get_instance();

        foreach( $fees as $fee){
            $fees_obj = new ExtraFees( $fee->ID );
            $fees_id = $fees_obj->get_id();
            $name = $fees_obj->get_name();
            $title = $fees_obj->get_title();

            $amount = $main_obj->get_fees_amount( $name );
            $amount = html_entity_decode(strip_tags(wc_price($amount)));
            
            $available_fees[] = [
                'id' => $fees_id,
                'name' => $name,
                'checked' => \Pi_cefw_Apply_fees::feesSelectedInSession($fees_id),
                'title' => $title,
                'amount' => $amount
            ];
           
        }
        return ['options' => $available_fees, 'label' => $label];
    }

    function fillCode(){
        $enable_on_cart_page = get_option('pisol_cefw_fees_option_cart', '');

        if((is_cart() && !empty($enable_on_cart_page)) || is_checkout()){
            wp_enqueue_script( 'pisol-cefw-fill-block', plugin_dir_url( __FILE__ ) . 'js/block.js', array( 'wp-plugins', 'wc-blocks-checkout' ), '1.0.0', true );
            wp_enqueue_style( 'pisol-cefw-fill-block', plugin_dir_url( __FILE__ ) . 'css/block.css', array( 'wc-blocks-style' ), '1.0.0' );
        }
    }

}

pisol_cefw_woo_block::get_instance();