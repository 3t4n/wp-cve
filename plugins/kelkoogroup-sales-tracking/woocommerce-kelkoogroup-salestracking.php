<?php
/**
 * Plugin Name:       Kelkoogroup Sales Tracking
 * Description:       Plugin to contain Kelkoogroup sales tracking customisation for Woocommerce
 * Plugin URI:        https://github.com/KelkooGroup/woocommerce-kelkoogroup-salestracking
 * Version:           1.0.10
 * Author:            Kelkoo Group
 * Author URI:        https://www.kelkoogroup.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 3.0.0
 * Tested up to:      6.2
 *
 * @package Kelkoogroup_SalesTracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Kelkoogroup_SalesTracking Class
 *
 * @class Kelkoogroup_SalesTracking
 * @version	1.0.10
 * @since 1.0.0
 * @package	Kelkoogroup_SalesTracking
 */
final class Kelkoogroup_SalesTracking {

	/**
	 * Set up the plugin
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'kelkoogroup_salestracking_setup' ), -1 );
		require_once( 'inc/functions.php' );
		require_once( 'admin/class-kelkoogroup-salestracking-admin.php');
	}

     /**
      * Setup all the things
      */
    public function kelkoogroup_salestracking_setup() {
            add_action( 'admin_menu', 'kelkoogroup_salestracking_add_admin_menu' );
            add_action( 'admin_init', 'kelkoogroup_salestracking_settings_init' );
            add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'kelkoogroup_action_links' );
            add_action('woocommerce_thankyou', array(&$this, 'kelkoogroup_salestracking_woocommerce_thankyou'), -10);
    }


    public function kelkoogroup_salestracking_woocommerce_thankyou($orderId) {
    if( class_exists( 'WC_Order' ) ) {
        $order=new WC_Order($orderId);
        if ( $order ) :
            $options = get_option( 'kelkoogroup_salestracking_settings' );
            $productsKelkoo=array();
            $items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ));
            foreach ( $items as $item ) {
                $product = json_decode($item->get_product());
                $productKelkoo=array('productname'=>$product->name,
               'productid'=>$product->id,
               'quantity'=>$item->get_quantity(),
               'price'=>$product->price);
                array_push($productsKelkoo,$productKelkoo);
            }
         ?>
         <script type="text/javascript">
             _kkstrack = {
	      <?php if ($options['kelkoogroup_salestracking_multicomid'] == FALSE) { ?>
	       merchantInfo: [{ country:"<?php echo esc_js( $options['kelkoogroup_salestracking_country'] );?>", merchantId:"<?php echo esc_js( $options['kelkoogroup_salestracking_comid'] );?>" }],
              <?php } else { ?>
               merchantInfo: [<?php echo wp_strip_all_tags( $options['kelkoogroup_salestracking_multicomid'] );?>],
              <?php } ?>
	       orderValue: '<?php echo esc_js( $order ->get_total());?>',
               orderId: '<?php echo esc_js( $order ->get_order_number());?>',
               basket: <?php echo wp_strip_all_tags( json_encode($productsKelkoo) );?>
            };
             (function() {
               var s = document.createElement('script');
               s.type = 'text/javascript';
               s.async = true;
               s.src = 'https://s.kk-resources.com/ks.js';
               var x = document.getElementsByTagName('script')[0];
               x.parentNode.insertBefore(s, x);
             })();
          </script>
         <?php endif;
         }
    }


} // End Class

/**
 * The 'main' function
 *
 * @return void
 */
function kelkoogroup_salestracking_main() {
	new Kelkoogroup_SalesTracking();
}

/**
 * Initialise the plugin
 */
add_action( 'plugins_loaded', 'kelkoogroup_salestracking_main' );
