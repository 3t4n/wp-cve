<?php
/*
Plugin Name: Social Pixel
Plugin URI: https://www.labschool.es
Description: Agrega f&aacute;cilmente los pixeles de Facebook, Twitter, Linkedin, Pinterest, TikTok  y/o Google Analytics (Global Tag) a tu sitio web. Adem&aacute;s compatible con Woocommerce en Facebook, Twitter, Pinterest y Google Analytics con eventos est&aacute;ndar y personalizados.
Version: 2.1
Requires at least: 3.0
Tested up to: 5.9
WC requires at least: 3.0
WC tested up to: 6.3
Author: Social Pixel
License: GPLv2
*/

if(!defined('ABSPATH')) exit;
require_once('sp-admin.php');

// ACTIVAR PLUGIN
register_activation_hook( __FILE__, 'soc_pix_install' );
function soc_pix_install() {
	$soc_pix_options = array(
    	'fb_re'					=> '1',	
		'fb_cu'					=> 'EUR',
		'fb_tx'					=> '0',
    	'fb_id' 				=> '',
		'fb_in' 				=> '0',
		'fb_vp'					=> '0',
		'fb_ac'					=> '0',
		'fb_ic'					=> '0',
		'fb_pc'					=> '0',
		'fb_vs'					=> '0',
		'fb_vc'					=> '0',
		'fb_vt'					=> '0',
		'tw_id' 				=> '',
		'tw_in' 				=> '0',
		'tw_vc' 				=> '0',
		'tw_ac' 				=> '0',
		'tw_ic' 				=> '0',
		'tw_pc' 				=> '0',
		'in_id' 				=> '',
		'in_in' 				=> '0',
		'pn_id' 				=> '',
		'pn_in' 				=> '0',
		'pn_vc' 				=> '0',
		'pn_ac' 				=> '0',
		'pn_ic' 				=> '0',
		'pn_pc' 				=> '0',	
		'tt_id' 				=> '',
		'tt_in' 				=> '0',
		'tt_vc' 				=> '0',
		'tt_ac' 				=> '0',
		'tt_ic' 				=> '0',
		'tt_pc' 				=> '0',		
		'ga_id' 				=> '',
		'ga_in' 				=> '0',
		'ga_ds' 				=> '0',
		'ga_ip' 				=> '0',
		'ga_ln' 				=> '0',
		'ga_vi' 				=> '0',
		'ga_ac' 				=> '0',
		'ga_bc' 				=> '0',
		'ga_cp' 				=> '0',
		'ga_pc' 				=> '0',
	);
	if(!get_option('soc_pix_options')) {
		update_option('soc_pix_options', $soc_pix_options);
	}
}

// INICIAR PLUGIN
add_action('plugins_loaded', 'soc_pix_setup');
function soc_pix_setup() {
	add_action('wp_head', 'soc_pix_header', 102);
}

function soc_pix_header() {
    $options 	= get_option('soc_pix_options');
	$fb_id 		= strip_tags($options['fb_id']);
	$fb_in		= $options['fb_in'];
	$fb_vp		= $options['fb_vp'];
	$fb_ac		= $options['fb_ac'];
	$fb_ic		= $options['fb_ic'];
	$fb_pc		= $options['fb_pc'];
	$fb_vs		= $options['fb_vs'];
	$fb_vc		= $options['fb_vc'];
	$fb_vt		= $options['fb_vt'];
	$tw_id 		= strip_tags($options['tw_id']);
	$tw_in		= $options['tw_in'];
	$tw_vc		= $options['tw_vc'];
	$tw_ac		= $options['tw_ac'];
	$tw_ic		= $options['tw_ic'];
	$tw_pc		= $options['tw_pc'];
	$in_id 		= strip_tags($options['in_id']);
	$in_in		= $options['in_in'];
	$pn_id 		= strip_tags($options['pn_id']);
	$pn_in		= $options['pn_in'];
	$pn_vc		= $options['pn_vc'];
	$pn_ac		= $options['pn_ac'];
	$pn_ic		= $options['pn_ic'];
	$pn_pc		= $options['pn_pc'];
	$tt_id	 	= strip_tags($options['tt_id']);
	$tt_in 		= $options['tt_in'];
	$tt_vc 		= $options['tt_vc'];
	$tt_ac 		= $options['tt_ac'];
	$tt_ic 		= $options['tt_ic'];
	$tt_pc		= $options['tt_pc'];
	$ga_id 		= strip_tags($options['ga_id']);
	$ga_in		= $options['ga_in'];
	$ga_ds 		= isset($options['ga_ds']) && $options['ga_ds'] ? "true" : "false";
	$ga_ip	 	= isset($options['ga_ip']) && $options['ga_ip'] ? "true" : "false";
	$ga_ln	 	= isset($options['ga_ln']) && $options['ga_ln'] ? "true" : "false";
	$ga_vi	 	= $options['ga_vi'];
	$ga_ac	 	= $options['ga_ac'];
	$ga_bc	 	= $options['ga_bc'];
	$ga_cp	 	= $options['ga_cp'];
	$ga_pc	 	= $options['ga_pc'];
	
	// FACEBOOK PAGEVIEW
    if ($fb_in == '1') { 
		?> 
		<!-- Facebook Pixel by Social Pixel -->
		<script>
		!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
		document,'script','https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '<?php echo $fb_id; ?>');
		fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $fb_id; ?>&ev=PageView&noscript=1"/></noscript>
		<?php
    }
    
	// FACEBOOK VIEWCONTENT
	if($fb_in == '1' && $fb_vp == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'fb_viewcontent');
		function fb_viewcontent() {
			global $product;
			if(is_product()){
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$product_name 			= $product->get_title();
				$product_categories 	= get_the_terms($product->get_id(), 'product_cat');
				$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
				$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $product->get_id() : $product->get_sku();
				$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(wc_get_price_including_tax($product),2,'.','') : number_format(wc_get_price_excluding_tax($product),2,'.','');
				?>
				<!-- Facebook ViewContent event -->
				<script>
				fbq('track', 'ViewContent', {
					content_type: 'product', 
					content_name: '<?php echo $product_name; ?>',
					content_category: '<?php echo $product_category; ?>', 
					content_ids: ['<?php echo $product_id ?>'],
					value: <?php echo $product_price; ?>, 
					currency:'<?php echo $currency;?>'
				});
				</script>
				<?php
			}
		}
	}
	
	// FACEBOOK VIEWSHOP
	if($fb_in == '1' &&  $fb_vs == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'fb_viewshop');
		function fb_viewshop() {			
			global $wp_query;
			if(is_shop()){
				$options 				= get_option('soc_pix_options');
				$products 				= array_values(array_map(function($item) {return wc_get_product($item->ID);}, $wp_query->get_posts()));
				foreach ($products as $product) {		
					$content_sku[] 		= "'".$product->get_sku()."'";
					$content_id[] 		= "'".$product->get_id()."'";
				}
				$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? implode(', ',$content_id) : implode(', ',$content_sku);	
				?>
				<!-- Facebook ViewShop custom event -->
				<script>
				fbq('track', 'ViewShop', {
					content_type: 'product', 
					content_ids: ['<?php echo $product_id ?>']
				});
				</script>
				<?php
			}
		}
	}
	
	// FACEBOOK VIEWCATEGORY
	if($fb_in == '1' && $fb_vc == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'fb_viewcategory');
		function fb_viewcategory(){			
			global $wp_query;
			if(is_product_category()){
				$options 				= get_option('soc_pix_options');
				$products 				= array_values(array_map(function($item) {return wc_get_product($item->ID);}, $wp_query->get_posts()));
				foreach ($products as $product) {		
					$content_sku[] 		= "'".$product->get_sku()."'";
					$content_id[] 		= "'".$product->get_id()."'";
				}
				$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? implode(', ',$content_id) : implode(', ',$content_sku);	
				?>
				<!-- Facebook ViewCategory custom event -->
				<script>
				fbq('track', 'ViewCategory', {
					content_type: 'product', 
					content_category: '<?php echo single_cat_title(); ?>',
					content_ids: ['<?php echo $product_id ?>']
				});
				</script>
				<?php
			}
		}
	}
	
	// FACEBOOK VIEWTAG
	if($fb_in == '1' && $fb_vt == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'fb_viewtag');
		function fb_viewtag() {			
			global $wp_query;
			if(is_product_tag()){
				$options 				= get_option('soc_pix_options');
				$products 				= array_values(array_map(function($item) {return wc_get_product($item->ID);}, $wp_query->get_posts()));
				foreach ($products as $product) {		
					$content_sku[] 		= "'".$product->get_sku()."'";
					$content_id[] 		= "'".$product->get_id()."'";
				}
				$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? implode(', ',$content_id) : implode(', ',$content_sku);	
				?>
				<!-- Facebook ViewTag custom event -->
				<script>
				fbq('track', 'ViewTag', {
					content_type: 'product', 
					content_category: '<?php echo single_tag_title(); ?>',
					content_ids: ['<?php echo $product_id ?>']
				});
				</script>
				<?php
			}
		}
	}
	
	// FACEBOOK ADDTOCART
	if ($fb_in == '1' && $fb_ac == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'fb_addtocart');
		function fb_addtocart() {
			if(is_cart() && WC()->cart->get_cart_contents_count() > 0){	
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>
				<!-- Facebook AddToCart event -->   
				<script>
				fbq('track', 'AddToCart', {
					content_type: 'product',
					currency:'<?php echo $currency;?>',
					order_quantity: <?php echo $cart_count_items; ?>,
					value: <?php echo $cart_total; ?>,
					contents: [
						<?php
						if($cart_items) foreach ($cart_items as $item){			
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
						?>
						{
						content_name: '<?php echo $product_name; ?>',
						content_category: '<?php echo $product_category; ?>', 
						content_ids: '<?php echo $product_id; ?>',
						content_price: <?php echo $product_price; ?>,
						quantity: '<?php echo $product_quantity; ?>'
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}
	
	// FACEBOOK INITIATECHECKOUT
	if ($fb_in == '1' && $fb_ic == '1' && class_exists('woocommerce')) { 
		add_action('woocommerce_after_checkout_form', 'fb_initiatecheckout');
		function fb_initiatecheckout() {
			if(is_checkout() && WC()->cart->get_cart_contents_count() > 0){	
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>		
				<!-- Facebook InitiateCheckout event -->
				<script>
				fbq('track', 'InitiateCheckout', {
					content_type: 'product',
					currency:'<?php echo $currency;?>',
					order_quantity: <?php echo $cart_count_items; ?>,
					value: <?php echo $cart_total; ?>,
					contents: [
						<?php
						if($cart_items) foreach ($cart_items as $item){			
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
						?>
						{
						content_name: '<?php echo $product_name; ?>',
						content_category: '<?php echo $product_category; ?>', 
						content_ids: '<?php echo $product_id; ?>',
						content_price: <?php echo $product_price; ?>,
						quantity: '<?php echo $product_quantity; ?>'
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}
	
	// FACEBOOK PURCHASE
	if($fb_in == '1' && $fb_pc == '1' && class_exists('woocommerce')) { 
		add_action('woocommerce_thankyou', 'fb_purchase');
		function fb_purchase($order_id){
			$options 				= get_option('soc_pix_options');
			$currency				= get_woocommerce_currency();
			$order 					= new WC_Order($order_id);
			$order_items 			= $order->get_items();		
			$order_count_items		= $order->get_item_count();
			$order_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($order->get_total(),2,'.','') : number_format($order->get_total() - $order->get_total_tax(),2,'.','');
			?>
			<!-- Facebook Purchase event -->
			<script>
			fbq('track', 'Purchase', {
				content_type: 'product',
				currency:'<?php echo $currency;?>',
				order_id: '<?php echo $order_id; ?>',
				order_quantity: <?php echo $order_count_items; ?>,
				value: <?php echo $order_total; ?>,
				contents: [
					<?php
					if($order_items) foreach ($order_items as $order_item => $item){ 
						$product_name			= $item['name'];
						$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
						$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');		
						$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
						$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
						$product_quantity		= $item['qty'];
					?>
					{
					content_name: '<?php echo $product_name; ?>',
					content_category: '<?php echo $product_category; ?>', 
					content_ids: '<?php echo $product_id; ?>',
					content_price: <?php echo $product_price; ?>,
					quantity: '<?php echo $product_quantity; ?>'
					},
					<?php } ?>
				]
			});
			</script> 
			<?php
		}
	}
	
	// TWITTER PAGEVIEW
    if($tw_in == '1') { 
		?>
		<!-- Twitter Pixel by Social Pixel -->
		<script>
		!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
		},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',
		a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
		twq('init','<?php echo $tw_id; ?>');
		<?php
		if(class_exists('woocommerce')){
			if($tw_vc == '1' && is_product()){
				echo null;
			} elseif($tw_ac == '1' && is_cart() && WC()->cart->get_cart_contents_count() > 0){
				echo null;
			} elseif($tw_ic == '1' && is_checkout() && WC()->cart->get_cart_contents_count() > 0){
				echo null;
			} elseif($tw_pc == '1' && is_wc_endpoint_url('order-received')){
				echo null;
			} else {
				echo "twq('track','PageView');";
			}
		}
		?>
		</script>
		<?php
    }
    
    // TWITTER VIEWCONTENT
	if($tw_in == '1' && $tw_vc == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'tw_viewcontent');
		function tw_viewcontent() {
			global $product;
			if(is_product()){
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $product->get_id() : $product->get_sku();
				$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(wc_get_price_including_tax($product),2,'.','') : number_format(wc_get_price_excluding_tax($product),2,'.','');
				$product_name 			= $product->get_title();
				$product_categories 	= get_the_terms($product->get_id(), 'product_cat');
				$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
				?>			
				<!-- Twitter ViewContent event -->
				<script>
				twq('track', 'ViewContent', {
					content_type: 'product', 
					content_name: '<?php echo $product_name; ?>',
					content_category: '<?php echo $product_category; ?>', 
					content_ids: ['<?php echo $product_id; ?>'],
					value: <?php echo $product_price; ?>, 
					currency:'<?php echo $currency;?>'
				});
				</script>
				<?php
			}
		}
	}
    
   // TWITTER ADDTOCART
	if ($tw_in == '1' && $tw_ac == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'tw_addtocart');
		function tw_addtocart() {
			if(is_cart() && WC()->cart->get_cart_contents_count() > 0){	
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>
				<!-- Twitter AddToCart event -->
				<script>
				twq('track', 'AddToCart', {
					content_type: 'product',
					currency:'<?php echo $currency;?>',
					order_quantity: <?php echo $cart_count_items; ?>,
					value: <?php echo $cart_total; ?>,
					contents: [
						<?php
						if($cart_items) foreach ($cart_items as $item){			
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						?>
						{
						content_name: '<?php echo $product_name; ?>',
						content_category: '<?php echo $product_category; ?>',
						content_ids: '<?php echo $product_id; ?>',
						content_price: <?php echo $product_price; ?>,
						quantity: '<?php echo $product_quantity; ?>'
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}
    
   // TWITTER INITIATECHECKOUT
	if ($tw_in == '1' && $tw_ic == '1' && class_exists('woocommerce')) { 
		add_action('woocommerce_after_checkout_form', 'tw_initiatecheckout');
		function tw_initiatecheckout() {
			if(is_checkout() && WC()->cart->get_cart_contents_count() > 0){
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>	
				<!-- Twitter InitiateCheckout event -->
				<script>
				twq('track', 'InitiateCheckout', {
					content_type: 'product',
					currency:'<?php echo $currency;?>',
					order_quantity: <?php echo $cart_count_items; ?>,
					value: <?php echo $cart_total; ?>,
					contents: [
						<?php
						if($cart_items) foreach ($cart_items as $item){
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						?>
						{
						content_name: '<?php echo $product_name; ?>',
						content_category: '<?php echo $product_category; ?>', 
						content_ids: '<?php echo $product_id; ?>',
						content_price: <?php echo $product_price; ?>,
						quantity: '<?php echo $product_quantity; ?>'
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}
	
	// TWITTER PURCHASE
	if($tw_in == '1' && $tw_pc == '1' && class_exists('woocommerce')){ 
		add_action('woocommerce_thankyou', 'tw_purchase');
		function tw_purchase($order_id){
			$options 				= get_option('soc_pix_options');
			$currency				= get_woocommerce_currency();
			$order 					= new WC_Order( $order_id );
			$order_items 			= $order->get_items();		
			$order_count_items		= $order->get_item_count();
			$order_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($order->get_total(),2,'.','') : number_format($order->get_total() - $order->get_total_tax(),2,'.','');
			?>
			<!-- Twitter Purchase event -->
			<script>
			twq('track', 'Purchase', {
				content_type: 'product',
				currency:'<?php echo $currency;?>',
				order_id: '<?php echo $order_id; ?>',
				order_quantity: <?php echo $order_count_items; ?>,
				value: <?php echo $order_total; ?>,
				contents: [
					<?php
					if($order_items) foreach ($order_items as $order_item => $item){ 
						$product_name			= $item['name'];
						$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
						$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');		
						$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
						$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
						$product_quantity		= $item['qty'];
					?>
					{
					content_name: '<?php echo $product_name; ?>',
					content_category: '<?php echo $product_category; ?>', 
					content_ids: '<?php echo $product_id; ?>',
					content_price: <?php echo $product_price; ?>,
					quantity: '<?php echo $product_quantity; ?>'
					},
					<?php } ?>
				]
			});
			</script> 
			<?php
		}
	}
	
	// LINKEDIN PAGEVIEW
    if ($in_in == '1'){ 
		?>
		<!-- Linkedin Pixel by Social Pixel -->
		<script>
		_linkedin_partner_id = "<?php echo $in_id; ?>";
		window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
		window._linkedin_data_partner_ids.push(_linkedin_partner_id);
		</script><script type="text/javascript">
		(function(){var s = document.getElementsByTagName("script")[0];
		var b = document.createElement("script");
		b.type = "text/javascript";b.async = true;
		b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
		s.parentNode.insertBefore(b, s);})();
		</script>
		<noscript><img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=<?php echo $in_id; ?>&fmt=gif" /></noscript>
		<?php
    }
	
	// PINTEREST PAGEVIEW
	if ($pn_in == '1') { 
		?>
		<!-- Pinterest Pixel by Social Pixel -->
		<script>
		!function(e){if(!window.pintrk){window.pintrk=function(){window.pintrk.queue.push(
		Array.prototype.slice.call(arguments))};var
		n=window.pintrk;n.queue=[],n.version="3.0";var
		t=document.createElement("script");t.async=!0,t.src=e;var
		r=document.getElementsByTagName("script")[0];r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");
		pintrk('load', '<?php echo $pn_id; ?>');
		pintrk('page');
		</script>
		<noscript><img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=<?php echo $pn_id; ?>&noscript=1" /></noscript>
		<?php
	}
	
	// PINTEREST PAGEVISIT
	if($pn_in == '1' && $pn_vc == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'pn_pagevisit');
		function pn_pagevisit(){
			global $product;
			if(is_product()){
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $product->get_id() : $product->get_sku();
				$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(wc_get_price_including_tax($product),2,'.','') : number_format(wc_get_price_excluding_tax($product),2,'.','');
				$product_name 			= $product->get_title();
				$product_categories 	= get_the_terms($product->get_id(), 'product_cat');
				$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
				?>
				<!-- Pinterest PageVisit event -->
				<script>
				pintrk('track', 'pagevisit', {
					currency:'<?php echo $currency;?>',
					line_items: [{
						product_name: '<?php echo $product_name; ?>',
						product_category: '<?php echo $product_category; ?>', 
						product_id: ['<?php echo $product_id; ?>'],
						product_price: <?php echo $product_price; ?> 
					}] 
				});
				</script>
				<?php
			}
		}
	}
	
	// PINTEREST ADDTOCART
	if ($pn_in == '1' && $pn_ac == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'pn_addtocart');
		function pn_addtocart(){
			if(is_cart() && WC()->cart->get_cart_contents_count() > 0){	
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>
				<!-- Pinterest AddToCart event -->
				<script>
				pintrk('track', 'addtocart', {
					currency:'<?php echo $currency;?>',
					order_quantity: <?php echo $cart_count_items; ?>,
					value: <?php echo $cart_total; ?>,
					line_items: [
						<?php
						if($cart_items) foreach ($cart_items as $item){			
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						?>
						{
						product_name: '<?php echo $product_name; ?>',
						product_category: '<?php echo $product_category; ?>',
						product_id: '<?php echo $product_id;  ?>',
						product_price: <?php echo $product_price; ?>,
						product_quantity: '<?php echo $product_quantity; ?>'
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}
	
	// PINTEREST INITIATECHECKOUT
	if ($pn_in == '1' && $pn_ic == '1' && class_exists('woocommerce')) { 
		add_action('woocommerce_after_checkout_form', 'pn_initiatecheckout');
		function pn_initiatecheckout(){
			if(is_checkout() && WC()->cart->get_cart_contents_count() > 0){	
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>
				<!-- Pinterest InitiateCheckout event -->
				<script>
				pintrk('track', 'initiatecheckout', {
					currency:'<?php echo $currency;?>',
					order_quantity: <?php echo $cart_count_items; ?>,
					value: <?php echo $cart_total; ?>,
					line_items: [
						<?php
						if($cart_items) foreach ($cart_items as $item){			
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						?>
						{
						product_name: '<?php echo $product_name; ?>',
						product_category: '<?php echo $product_category; ?>',
						product_id: '<?php echo $product_id;  ?>',
						product_price: <?php echo $product_price; ?>,
						product_quantity: '<?php echo $product_quantity; ?>'
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}
	
	// PINTEREST CHECKOUT
	if ($pn_in == '1' && $pn_pc == '1' && class_exists('woocommerce')) { 
		add_action('woocommerce_thankyou', 'pn_checkout');
		function pn_checkout($order_id){
			$options 				= get_option('soc_pix_options');
			$currency				= get_woocommerce_currency();
			$order 					= new WC_Order( $order_id );
			$order_items 			= $order->get_items();		
			$order_count_items		= $order->get_item_count();
			$order_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($order->get_total(),2,'.','') : number_format($order->get_total() - $order->get_total_tax(),2,'.','');
			?>
			<!-- Pinterest Checkout event -->
			<script>
			pintrk('track', 'checkout', {
				currency:'<?php echo $currency;?>',
				order_id: '<?php echo $order_id; ?>',
				order_quantity: <?php echo $order_count_items; ?>,
				value: <?php echo $order_total; ?>,
				line_items: [
					<?php
					if($order_items) foreach ($order_items as $order_item => $item){
						$product_name			= $item['name'];
						$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
						$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');		
						$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
						$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
						$product_quantity		= $item['qty'];
					?>
					{
					product_name: '<?php echo $product_name; ?>',
					product_category: '<?php echo $product_category; ?>',
					product_id: '<?php echo $product_id; ?>',
					product_price: <?php echo $product_price; ?>,
					product_quantity: '<?php echo $product_quantity; ?>'
					},
					<?php } ?>
				]
			});
			</script> 
		<?php
		}
	}
	
	// TIKTOK PAGEVIEW
	if($tt_in == '1') { 
		?>	
		<!-- TikTok Pixel by Social Pixel -->
		<script>
		!function (w, d, t) {
		  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
		  ttq.load('<?php echo $tt_id; ?>');
		  ttq.page();
		}(window, document, 'ttq');
		</script>
		<?php
	}
		
	// TIKTOK VIEWCONTENT
	if($tt_in == '1' && $tt_vc == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'tt_viewcontent');
		function tt_viewcontent(){
			global $product;
			if(is_product()){
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $product->get_id() : $product->get_sku();
				$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(wc_get_price_including_tax($product),2,'.','') : number_format(wc_get_price_excluding_tax($product),2,'.','');
				$product_name 			= $product->get_title();
				$product_categories 	= get_the_terms($product->get_id(), 'product_cat');
				$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
				?>
				<!-- TikTok ViewContent event -->
				<script>
				ttq.track('ViewContent', {
					content_name: '<?php echo $product_name; ?>',
					content_category: '<?php echo $product_category; ?>', 
					content_type: 'product', 
					value: <?php echo $product_price; ?>,
					content_id: '<?php echo $product_id; ?>',
					currency: '<?php echo $currency;?>'
				});
				</script>
				<?php
			}
		}
	}

	// TIKTOK ADDTOCART
	if ($tt_in == '1' && $tt_ac == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'tt_addtocart');
		function tt_addtocart(){
			if(is_cart() && WC()->cart->get_cart_contents_count() > 0){	
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>	
				<!-- TikTok AddToCart event -->	
				<script>
				ttq.track('AddToCart', {
					currency:'<?php echo $currency;?>',
					value: <?php echo $cart_total; ?>,
					quantity: <?php echo $cart_count_items; ?>,
					contents: [
						<?php
						if($cart_items) foreach ($cart_items as $item){			
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						?>
						{
						content_name: '<?php echo $product_name; ?>',
						content_category: '<?php echo $product_category; ?>',
						content_type: 'product', 
						content_id: '<?php echo $product_id; ?>',
						price: <?php echo $product_price; ?>,
						quantity: <?php echo $product_quantity; ?>,
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}

	// TIKTOK INITIATECHECKOUT
	if($tt_in == '1' && $tt_ic == '1' && class_exists('woocommerce')) { 
		add_action('woocommerce_after_checkout_form', 'tt_initiatecheckout');
		function tt_initiatecheckout(){
			if(is_checkout() && WC()->cart->get_cart_contents_count() > 0){	
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>	
				<!-- TikTok InitiateCheckout event -->
				<script>
				ttq.track('InitiateCheckout', {
					currency:'<?php echo $currency;?>',
					value: <?php echo $cart_total; ?>,
					quantity: <?php echo $cart_count_items; ?>,
					contents: [
						<?php
						if($cart_items) foreach ($cart_items as $item){			
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						?>
						{
						content_name: '<?php echo $product_name; ?>',
						content_category: '<?php echo $product_category; ?>', 
						content_type: 'product', 
						content_id: '<?php echo $product_id; ?>',
						price: <?php echo $product_price; ?>,
						quantity: <?php echo $product_quantity; ?>,
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}

	// TIKTOK PLACEANORDER
	if ($tt_in == '1' && $tt_pc == '1' && class_exists('woocommerce')) { 
		add_action('woocommerce_thankyou', 'tt_placeanorder');
		function tt_placeanorder($order_id){
			$options 					= get_option('soc_pix_options');
			$currency					= get_woocommerce_currency();
			$order 						= new WC_Order( $order_id );
			$order_items 				= $order->get_items();		
			$order_count_items			= $order->get_item_count();
			$order_total 				= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($order->get_total(),2,'.','') : number_format($order->get_total() - $order->get_total_tax(),2,'.','');
			?>
			<!-- TikTok PlaceAnOrder event -->
			<script>
			ttq.track('PlaceAnOrder', {
				currency:'<?php echo $currency;?>',
				value: <?php echo $order_total; ?>,
				quantity: <?php echo $order_count_items; ?>,
				contents: [
					<?php
					if($order_items) foreach ($order_items as $order_item => $item){
						$product_name			= $item['name'];
						$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
						$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');		
						$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
						$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
						$product_quantity		= $item['qty'];
					?>
					{
					content_name: '<?php echo $product_name; ?>',
					content_category: '<?php echo $product_category; ?>', 
					content_type: 'product', 
					content_id: '<?php echo $product_id; ?>',
					price: <?php echo $product_price; ?>,
					quantity: <?php echo $product_quantity; ?>,
					},
					<?php } ?>
				]
			});
			</script> 
			<?php
		}
	}
	
	// GOOGLE ANALYTICS PAGEVIEW
    if ($ga_in == '1') { 
		?>
		<!-- Google Analytics Pixel by Social Pixel -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_id;?>"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		<?php echo "gtag('config', '$ga_id', {'allow_display_features':$ga_ds, 'anonymize_ip':$ga_ip, 'link_attribution':$ga_ln});"; ?> 
		</script>
		<?php
    }
    
     // GOOGLE ANALYTICS VIEW_ITEM
	if($ga_in == '1' && $ga_vi == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'ga_view_item');
		function ga_view_item() {
			global $product;
			if(is_product()){
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$product_name 			= $product->get_title();
				$product_categories 	= get_the_terms($product->get_id(), 'product_cat');
				$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
				$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $product->get_id() : $product->get_sku();
				$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(wc_get_price_including_tax($product),2,'.','') : number_format(wc_get_price_excluding_tax($product),2,'.','');
				?>
				<!-- Google Analytics View Item event -->
				<script>
				gtag('event', 'view_item', {
					'items': [{
						'name': '<?php echo $product_name; ?>',
						'category': '<?php echo $product_category; ?>',
						'id': '<?php echo $product_id; ?>',
						'price': '<?php echo $product_price; ?>' 
					}] 
				});
				</script>
				<?php
			}
		}
	}
	
	// GOOGLE ANALYTICS ADD_TO_CART
	if($ga_in == '1' && $ga_ac == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'ga_add_to_cart');
		function ga_add_to_cart() {
			global $product;
			if(is_product()){
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$product_name 			= $product->get_title();
				$product_categories 	= get_the_terms($product->get_id(), 'product_cat');
				$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
				$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $product->get_id() : $product->get_sku();
				$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(wc_get_price_including_tax($product),2,'.','') : number_format(wc_get_price_excluding_tax($product),2,'.','');
				?>
				<!-- Google Analytics Add To Cart event -->
				<script>
				jQuery(document).ready(function($) {
					$('.single_add_to_cart_button').click(function() {
						gtag('event', 'add_to_cart', {
							'value': <?php echo $product_price; ?>,
							'currency':'<?php echo $currency;?>',
							'items': [{
								'name': '<?php echo $product_name; ?>',
								'category': '<?php echo $product_category; ?>',
								'id': '<?php echo $product_id; ?>',
								'price': '<?php echo $product_price; ?>' 
							}] 
						});
					});
				});
				</script>
				<?php
			}
		}
	}
	    
    // GOOGLE ANALYTICS BEGIN_CHECKOUT
	if ($ga_in == '1' && $ga_bc == '1' && class_exists('woocommerce')) { 
		add_action('wp_footer', 'ga_begin_checkout');
		function ga_begin_checkout() {
			if(is_cart() && WC()->cart->get_cart_contents_count() > 0){	
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total				= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>
				<!-- Google Analytics Begin Checkout event -->    
				<script>
				gtag('event', 'begin_checkout', {
					'value': <?php echo $cart_total; ?>,
					'currency':'<?php echo $currency;?>',
					'items': [
						<?php
						if($cart_items) foreach ($cart_items as $item){
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						?>
						{
						'name': '<?php echo $product_name; ?>',
						'category': '<?php echo $product_category; ?>',
						'id': '<?php echo $product_id;  ?>',
						'price': '<?php echo $product_price; ?>',
						'quantity': '<?php echo $product_quantity; ?>'
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}
	
	// GOOGLE ANALYTICS CHECKOUT_PROGRESS
	if ($ga_in == '1' && $ga_cp == '1' && class_exists('woocommerce')) { 
		add_action('woocommerce_after_checkout_form', 'ga_checkout_progress');
		function ga_checkout_progress() {
			if(is_checkout() && WC()->cart->get_cart_contents_count() > 0){	
				$options 				= get_option('soc_pix_options');
				$currency				= get_woocommerce_currency();
				$cart_items				= WC()->cart->cart_contents;
				$cart_count_items		= WC()->cart->get_cart_contents_count();
				$cart_total				= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format(WC()->cart->subtotal,2,'.','') : number_format(WC()->cart->subtotal_ex_tax,2,'.','');
				?>		
				<!-- Google Analytics Checkout Progress event -->    
				<script>
				gtag('event', 'checkout_progress', {
					'value': <?php echo $cart_total; ?>,
					'currency':'<?php echo $currency;?>',
					'items': [
						<?php
						if($cart_items) foreach ($cart_items as $item){
							$product_name			= $item['data']->get_name();
							$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
							$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');
							$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
							$product_quantity		= $item['quantity'];
							$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
							$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						?>
						{
						'name': '<?php echo $product_name; ?>',
						'category': '<?php echo $product_category; ?>',
						'id': '<?php echo $product_id;  ?>',
						'price': '<?php echo $product_price; ?>',
						'quantity': '<?php echo $product_quantity; ?>'
						},
						<?php } ?>
					]
				});
				</script> 
				<?php
			}
		}
	}
	
    // GOOGLE ANALYTICS PURCHASE
	if ($ga_in == '1' && $ga_pc == '1' && class_exists('woocommerce')) { 
		add_action('woocommerce_thankyou', 'ga_purchase');
		function ga_purchase($order_id) {
			$options 				= get_option('soc_pix_options');
			$currency				= get_woocommerce_currency();
			$order 					= new WC_Order($order_id);
			$order_items 			= $order->get_items();		
			$order_count_items		= $order->get_item_count();
			$order_total 			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($order->get_total(),2,'.','') : number_format($order->get_total() - $order->get_total_tax(),2,'.','');
			$order_total_tax		= number_format($order->get_total_tax(),2,'.','');
			$shipping_total 		= number_format($order->get_shipping_total(),2,'.','');
			?>
			<!-- Google Analytics Purchase event -->    
			<script>
			gtag('event', 'purchase', {
				'transaction_id': '<?php echo $order_id; ?>',
				'value': <?php echo $order_total; ?>,
				'currency':'<?php echo $currency;?>',
				'tax': <?php echo $order_total_tax; ?>,
				'shipping': <?php echo $shipping_total; ?>,
				'items': [
					<?php
					if($order_items) foreach ($order_items as $order_item => $item){
						$product_name			= $item['name'];
						$product_categories 	= get_the_terms($item['product_id'], 'product_cat');
						$product_category		= (isset($product_categories) && $product_categories) ? $product_categories[0]->name : esc_html('Ninguna','social-pixel');		
						$product_sku			= (wc_get_product($item['variation_id'])) ? wc_get_product($item['variation_id'])->get_sku() : wc_get_product($item['product_id'])->get_sku();
						$product_id				= (isset($options['fb_re']) && $options['fb_re']) ? $item['product_id'] : $product_sku;
						$product_price			= (isset($options['fb_tx']) && $options['fb_tx']) ? number_format($item['line_subtotal'] + $item['line_subtotal_tax'],2,'.','') : number_format($item['line_subtotal'],2,'.','');
						$product_quantity		= $item['qty'];
					?>
					{
					'name': '<?php echo $product_name; ?>',
					'category': '<?php echo $product_category; ?>',
					'id': '<?php echo $product_id;  ?>',
					'price': '<?php echo $product_price; ?>',
					'quantity': '<?php echo $product_quantity; ?>'
					},
					<?php } ?>
				]
			});
			</script> 
			<?php
		}
	}  
}