<?php
/**
* Plugin Name: PayJustNow for WooCommerce
* Plugin URI: https://payjustnow.com/how-it-works
* Description: Accept payments for WooCommerce using the PayJustNow service
* Version: 2.3
* Author: PayJustNow (Pty) Ltd.
* Author URI: https://payjustnow.com
* Developer: WickedWeb
* Developer e-mail: tertius@wickedweb.co.za
*
* Requires at least: 5.6
* Tested up to: 6.1.1
*
* Copyright: © 2020 PayJustNow (Pty) Ltd.
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Required functions
add_action( 'plugins_loaded', 'woocommerce_payjustnow_init', 0 );

function woocommerce_payjustnow_init()
	{
	if ( !class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}
	require_once plugin_basename( 'classes/payjustnow.class.php' );
	add_filter( 'woocommerce_payment_gateways', 'woocommerce_payjustnow_add_gateway' );
}

function woocommerce_payjustnow_add_gateway( $methods )
	{
	$methods[] = 'WC_Gateway_PayJustNow';
	return $methods;
}

// FILTER - Change order button text and disable if order amount less than 300 or ID/Key not available
add_filter( 'woocommerce_available_payment_gateways', 'woocommerce_available_payment_gateways' );
function woocommerce_available_payment_gateways( $available_gateways ) {
	
	$payjustnow_settings = get_option('woocommerce_payjustnow_settings');
	$payjustnow_order_text = $payjustnow_settings['order_text'];

    if (! is_checkout() ) return $available_gateways;  // stop doing anything if we're not on checkout page.
    if (array_key_exists('payjustnow',$available_gateways)) {
         $available_gateways['payjustnow']->order_button_text = __($payjustnow_order_text, 'woocommerce' );
    }

	 global $woocommerce;
	 
	// Early exit if any 1 of the products in the cart is a WooCommerce Subscription type product:
	$pjnwoosubcheck = false;
	foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
		$product = $cart_item['data'];
		
		if( class_exists( 'WC_Subscriptions_Product' ) && WC_Subscriptions_Product::is_subscription( $product ) ) {
		$pjnwoosubcheck = true;
		break;
		}
		
	}		
	if ( $pjnwoosubcheck ) {
		unset($available_gateways['payjustnow']);
	} 
	 
	 if(($payjustnow_settings['pjn_username']=='') || ($payjustnow_settings['pjn_password']=='') ) {
		unset($available_gateways['payjustnow']);
	 }

    return $available_gateways;
}

// FUNCTION - Frontend show on single product page
function woo_payjustnow_frontend()
	{
	
	// Early exit if frontend is disabled in settings, check if settings are set:
	$payjustnow_settings = get_option('woocommerce_payjustnow_settings');
	
	if( !isset( $payjustnow_settings['show_on_single_product'] ) or !isset( $payjustnow_settings['usedarktheme'] ) ){ return; }
	
	$payjustnow_frontend = $payjustnow_settings['show_on_single_product'];
	$payjustnow_dark_theme = $payjustnow_settings['usedarktheme'];
	$payjustnow_order_number = $payjustnow_settings['useordernumber'];

	if ( $payjustnow_frontend != 'yes' ) { return; }
	
	if ( $payjustnow_dark_theme == 'yes' ) { 
		$payjustnow_logo = 'payjustnow_logo_dark_theme.png';
		} else {
		$payjustnow_logo = 'payjustnow_logo_light_theme.png';
		}
	
	global $product;
	
	// Early exit if product is a WooCommerce Subscription type product:	
	if( class_exists( 'WC_Subscriptions_Product' ) && WC_Subscriptions_Product::is_subscription( $product ) ) {
		return;
	}
	
	// Early exit if product has no price:	
	$noprice = $product->get_price();
	if (!$noprice) {
		return;
	}

	$payjustnowprice = wc_get_price_including_tax( $product );
	$payjustnowone = $payjustnowprice/3;
	$payjustnowone = ceil($payjustnowone*100)/100;


	//Variable product data saved for updating amount when selection is made
    if ( $product->is_type('variable') ) {
        $variations_data =[];
        foreach($product->get_available_variations() as $variation ) {
			$varprice = $variation['display_price']/3;
			$varprice = ceil($varprice*100)/100;
			$variations_data[$variation['variation_id']] = wc_price($varprice);
        }
        ?>
        <script>
        jQuery(function($) {
			$('#vprodtextupd').text('from');
            var jsonData = <?php echo json_encode($variations_data); ?>,
                inputVID = 'input.variation_id';
            $('input.variation_id').change( function(){
                if( '' != $(inputVID).val() ) {
                    var vid = $(inputVID).val(),
                    vprice = '';
                    $.each( jsonData, function( index, price ) {
                        if( index == vid ) {
                            vprice = price;
                        }
                    });
                    $('#vprodtextupd').text('of');
                    $('#vprodpriceupd').html(vprice);          
                }
            });
        });
        </script>
        <?php
    }

	echo '
	<p class="pjnsinglepagetext"><a href="https://payjustnow.com/register/overview" alt="Sign up with PayJustNow" title="Sign up with PayJustNow"  target="_blank"><img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/'.$payjustnow_logo.'" class="pjnsinglepagelogo"></a>
	Pay over <span class="pjnsinglepagetextzero">3 EQUAL zero-interest</span>
	instalments <span id="vprodtextupd">of</span> <span id="vprodpriceupd">'.wc_price($payjustnowone).'</span> with <span  class="pjnsinglepagetextpjn">PayJustNow.</span><br>	
	<a href="#payjustnowinfo">Find out how...</a></p>
	
	<style>
	.pjnsinglepagelogo {
	width:120px;height:auto;float:right;margin-left:20px;
	}
	.pjnsinglepagetextzero, .pjnsinglepagetextpjn {
	font-weight:bold;font-style:italic;
	}
	</style>

	<div class="remodal" data-remodal-id="payjustnowinfo" >
	<button data-remodal-action="close" class="remodal-close"></button>
		<div style="display: block;width: 140px;height: 140px;background: #14204a;text-align: center;align-items: center;border-radius: 50%;    -moz-border-radius: 50%; -webkit-border-radius: 50%; float: left;clear: both;">
		<a href="https://payjustnow.com/register/overview" alt="Sign up with PayJustNow" title="Sign up with PayJustNow"  target="_blank"><img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/payjustnow_logo_dark_theme.png" style="height: auto;max-width: 120px;max-height: 100%;padding: 40px 0 0 20px;"></a>
		</div>
	
	<div style="max-width:500px;padding:15px 10px 0 0;float:right;text-align:left;">
	<p style="font-family:DM Sans;margin: 0 0 1.2rem;font-weight:bold;line-height:1.8rem;font-size:1.3rem;color:#14204a;">PayJustNow is a simple, easy-to-use payment system.</p>
	
	<p style="font-family:DM Sans;margin: 0 0 1rem;font-weight:bold;color:#14204a;line-height:1.2rem;font-size:0.9rem;">Here’s how it works:</p>
	
		<p style="font-family:DM Sans;margin: 0 0 1rem;line-height:1.2rem;font-size:0.9rem;color: #62667e;">PayJustNow allows you to pay for your purchase over 3 equal, zero interest instalments. You’ll pay one instalment at the time of purchase, the next at the beginning of the following month and the last one a month thereafter.<br>
		
		<span style="color:#ff5c75;font-weight:bold;">#zerointerest</span>
		</p>
		
	</div>
	<div style=clear:both;></div>
		
		<div style="width:25%;padding:0 10px;float:left;text-align:left;min-width:150px;min-height:180px;">
		<img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/VectorStep1.png" style="width:auto;height:auto;margin-bottom: 1rem;">
		<p style="font-family:DM Sans;margin: 0 0 1rem;line-height:1rem;font-size:0.75rem;"><span style="color:#ff5c75;font-weight:bold;">Step 1:</span></p>
		<p style="font-family:DM Sans;margin: 0 0 1rem;line-height:1rem;font-size:0.75rem;color: #62667e;">Browse your favourite online stores and proceed to check-out.</p>
		</div>


		<div style="width:25%;padding:0 10px;float:left;text-align:left;min-width:150px;min-height:180px;">
		<img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/VectorStep2.png" style="width:auto;height:auto;margin-bottom: 1rem;">
		<p style="font-family:DM Sans;margin: 0 0 1rem;line-height:1rem;font-size:0.75rem;"><span style="color:#ff5c75;font-weight:bold;">Step 2:</span></p>
		<p style="font-family:DM Sans;margin: 0 0 1rem;line-height:1rem;font-size:0.75rem;color: #62667e;">Choose PayJustNow as your payment method.</p>
		</div>


		<div style="width:25%;padding:0 10px;float:left;text-align:left;min-width:150px;min-height:180px;">
		<img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/VectorStep3.png" style="width:auto;height:auto;margin-bottom: 1rem;">
		<p style="font-family:DM Sans;margin: 0 0 1rem;line-height:1rem;font-size:0.75rem;"><span style="color:#ff5c75;font-weight:bold;">Step 3:</span></p>
		<p style="font-family:DM Sans;margin: 0 0 1rem;line-height:1rem;font-size:0.75rem;color: #62667e;">Create your account as easily as if your eyes were shut (though we’d recommend you keep them open).</p>
		</div>


		<div style="width:25%;padding:0 10px;float:left;text-align:left;min-width:150px;min-height:180px;">
		<img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/VectorStep4.png" style="width:auto;height:auto;margin-bottom: 1rem;">
		<p style="font-family:DM Sans;margin: 0 0 1rem;line-height:1rem;font-size:0.75rem;"><span style="color:#ff5c75;font-weight:bold;">Step 4:</span></p>
		<p style="font-family:DM Sans;margin: 0 0 1rem;line-height:1rem;font-size:0.75rem;color: #62667e;">Complete your purchase and whoop for joy!</p>
		</div>
		
	<div style=clear:both;></div>
	
	<div style="padding:0 20px 0 0;float:left;text-align:left;border: 1px #e5e8ef solid;border-radius:5px;border-left:3px #14204a solid;width: 100%;">
	
		<div style="padding:0 20px 0 0;float:left;text-align:left;">
		<p style="font-family:DM Sans;margin: 1rem 0 1rem 10px;font-weight:bold;color:#14204a;line-height:1.2rem;font-size:0.9rem;">Here’s what you’ll need:</p>
		</div>
		<div style=clear:both;></div>
		<div style="padding:0 0 0 10px;float:left;text-align:left;">
		<p style="font-family:DM Sans;line-height:1rem;font-size:0.75rem;margin-bottom:0.4em;color: #62667e;"><img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/VectorTick.png" style="width:auto;height:auto;display: inline;">&nbsp;
		A valid RSA ID document</p>
		</div>

		<div style="padding:0 0 0 10px;float:left;text-align:left;">
		<p style="font-family:DM Sans;line-height:1rem;font-size:0.75rem;margin-bottom:0.4em;color: #62667e;"><img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/VectorTick.png" style="width:auto;height:auto;display: inline;">&nbsp;
		To be over 18 years old</p>
		</div>


		<div style="padding:0 0 0 10px;float:left;text-align:left;">
		<p style="font-family:DM Sans;line-height:1rem;font-size:0.75rem;margin-bottom:0.4em;color: #62667e;"><img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/VectorTick.png" style="width:auto;height:auto;display: inline;">&nbsp;
		An email address</p>
		</div>	
		
		<div style="padding:0 0 0 10px;float:left;text-align:left;">
		<p style="font-family:DM Sans;line-height:1rem;font-size:0.75rem;margin-bottom:1em;color: #62667e;"><img alt="" src="'.plugin_dir_url( __FILE__ ).'assets/images/VectorTick.png" style="width:auto;height:auto;display: inline;">&nbsp;
		A SA Bank issued debit or credit card</p>
		</div>	

	</div>	
	
	<div style=clear:both;></div>
	
		<div style="padding:0;float:right;text-align:right;margin:0;">
		<a class="remodal-btn-grad" style="font-family:DM Sans;line-height:1.2rem;font-size:0.9rem;color: #fff;"  href="https://payjustnow.com/" target="_blank" rel="noopener">Learn more</a>
		</div>
	
	</div>';		
}

// FUNCTION - Show the PJN payment option after the cart total
function payjustnow_woocommerce_after_cart_totals()
	{ 
		
	global $woocommerce;
	
	// Early exit if any 1 of the products in the cart is a WooCommerce Subscription type product:
	$pjnwoosubcheck = false;
	foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
		$product = $cart_item['data'];
		if( class_exists( 'WC_Subscriptions_Product' ) && WC_Subscriptions_Product::is_subscription( $product ) ) {
		$pjnwoosubcheck = true;
		break;
		}
	}		
	if ( $pjnwoosubcheck ) {
		return;
	}	
	
	$ordertotal = $woocommerce->cart->total;	
		$payjustnowone = $ordertotal/3;
		$payjustnowone = ceil($payjustnowone*100)/100;
		echo '<p class="pjncartpagetext">With <span class="pjncartpagetextpjn">PayJustNow</span> your Cart Total will be split into 3 easy equal instalments of '.wc_price($payjustnowone).'.</p><style>.pjncartpagetextpjn {font-weight:bold;font-style:italic;}</style>';
}; 

// ACTION - Show the PJN payment option after the cart total
$payjustnow_settings = get_option('woocommerce_payjustnow_settings');

if(isset( $payjustnow_settings['show_after_cart_total'] )){
	$payjustnow_show_after_cart_total = $payjustnow_settings['show_after_cart_total'];
	if ( $payjustnow_show_after_cart_total == 'yes' ) { 
		add_action( 'woocommerce_after_cart_totals', 'payjustnow_woocommerce_after_cart_totals', 10, 0 );
	}  
}

// ACTION - Frontend show on single product page
add_action( 'woocommerce_single_product_summary','woo_payjustnow_frontend',25);

// FUNCTION - Load the scripts needed for the modal window - Frontend show on single product page
// (http://vodkabears.github.io/remodal/ - Made by Ilya Makarov - Under MIT License)
function woo_payjustnow_scripts()
	{
	if (is_singular("product")) {
		wp_enqueue_style("payjustnow-jquery-modal-css", plugin_dir_url( __FILE__ ) .  'assets/remodal/remodal.css?v=118');
		wp_enqueue_style("payjustnow-jquery-modal-css-default-theme", plugin_dir_url( __FILE__ ) .  'assets/remodal/remodal-default-theme.css?v=118');
		wp_enqueue_style("payjustnow-google-fonts", "https://fonts.googleapis.com/css?family=DM+Sans&display=swap", false);
		wp_enqueue_script("payjustnow-jquery-modal-js", plugin_dir_url(__FILE__) . 'assets/remodal/remodal.min.js?v=118');
	}
}

// ACTION - Load the scripts needed for the modal window - Frontend show on single product page
add_action( 'wp_enqueue_scripts', 'woo_payjustnow_scripts', 50 );
?>
