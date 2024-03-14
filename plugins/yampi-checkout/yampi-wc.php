<?php
/*
* Plugin Name: Yampi Checkout
* Plugin URI: https://www.yampi.com.br/checkout
* Description: Aumenta suas conversões com o Checkout transparente da Yampi.
* Version: 1.0.4
* Author: Yampi
* Author URI: https://www.yampi.com.br
*
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

class Yampi_WC 
{
	public function __construct() 
	{
		if (!class_exists('WooCommerce')) {
			return;
		}
		
		add_action('woocommerce_before_cart', [$this, 'add_cart_script']);
		add_action('woocommerce_before_checkout_form', [$this, 'add_checkout_script']);
		add_action('rest_api_init', [$this, 'api']);
	}

	/**
	 * api
	 * Custom API handler.
	 */
	public function api()
	{
		register_rest_route('yampi-checkout/v1', '/orders', [
			'methods' => 'GET',
			'callback' => function ($data) {
				$orders = wc_get_orders([
					'transaction_id' => sanitize_text_field($_GET['transaction_id']),
				]);
				
				return [
					'exists' => count($orders) > 0,
				];
			}
		]);
	}
	
	/**
	* add_checkout_script
	* Put the Yampi Snippet on WC template.
	*
	* @access        public
	* @return        void
	*/
	public function add_checkout_script() 
	{
		$this->script(true);
	}
	
	public function add_cart_script()
	{
		$this->script();
	}
	
	public function script($isCheckout = false)
	{
		?>
		<style>
		.yampi-loader {display: none; position: fixed; width: 100%; height: 100%; background: #fff; left: 0; top: 0; z-index:99999}
		.yampi-loading{position:fixed;overflow:show;margin:auto;top:0;left:0;bottom:0;right:0;width:50px;height:50px}.yampi-loading:before{content:'';display:block;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(255,255,255,.5)}.yampi-loading:not(:required){font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0}.yampi-loading:not(:required):after{content:'';display:block;font-size:10px;width:50px;height:50px;margin-top:-.5em;border:5px solid #999;border-radius:100%;border-bottom-color:transparent;-webkit-animation:spinner 1s linear 0s infinite;animation:spinner 1s linear 0s infinite}@-webkit-keyframes spinner{0%{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}@-moz-keyframes spinner{0%{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}@-o-keyframes spinner{0%{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes spinner{0%{-webkit-transform:rotate(0);-moz-transform:rotate(0);-ms-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-moz-transform:rotate(360deg);-ms-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}
		</style>
		
		<div class="yampi-loader">
		<div class="yampi-loading"></div>
		</div>
		
		<script type='text/javascript'>
			window.Yampi = {
				page: <?php echo $isCheckout ? '"checkout"' : '"cart"'; ?>,
				merchant_url: "<?php echo esc_html($_SERVER['HTTP_HOST']); ?>",
				cart: <?php echo $this->format_cart(); ?>
			};
			
			(function() {
				var ch = document.createElement('script'); ch.type = 'text/javascript'; ch.async = true;
				ch.src = 'https://api.dooki.com.br/v2/public/woocommerce/script';
				var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(ch, x);
			})();
		</script>
		<?php
	}
	
	/**
	* format_cart
	* 
	* Format cart payload.
	*
	* @access        public
	* @return        string
	*/
	public function format_cart() 
	{
		$cartData = WC()->cart->get_cart();
		$cart = [];
		
		foreach ($cartData as $key => $item) {
			$cart['items'][] = [
				'variant_id' => $item['variation_id'] ? $item['variation_id'] : $item['product_id'],
				'quantity' => $item['quantity'],
			];
		}
		
		return json_encode($cart);
	}
	
}

/**
* Load Yampi
*/
function yampi_plugins_loaded() {
	new Yampi_WC();
}

add_action('plugins_loaded', 'yampi_plugins_loaded');