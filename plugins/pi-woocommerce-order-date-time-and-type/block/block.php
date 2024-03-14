<?php
namespace PISOL\DTT\BLOCK;

use Automattic\WooCommerce\StoreApi\StoreApi;
use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CartItemSchema;

class BlockSupport{

    private $extend;

    protected static $instance = null;

    const IDENTIFIER = 'pisol_dtt_block';


    public static function get_instance( ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    function __construct(){
        add_action( 'wp_enqueue_scripts', [$this, 'front']);
    }


    function front(){
        //check for admin and editor
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }


        if(function_exists('is_checkout') && is_checkout()){
            wp_enqueue_script( 'pisol-dtt-block', plugin_dir_url( __FILE__ ) . 'js/block.js', array( 'wp-plugins', 'wc-blocks-checkout' ), '1.0.0', true );

            wp_enqueue_style( 'pisol-dtt-block', plugin_dir_url( __FILE__ ) . 'css/block.css', array( 'wc-blocks-style' ), '1.0.0' );
        }
    }
}

BlockSupport::get_instance();
