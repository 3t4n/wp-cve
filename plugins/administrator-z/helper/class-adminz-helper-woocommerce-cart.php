<?php 
namespace Adminz\Helper;
use WC_Form_Handler;
use WC_AJAX;

class ADMINZ_Helper_Woocommerce_Cart{
	function __construct($admz_woo) {
		if ($admz_woo->check_option('adminz_woocommerce_ajax_add_to_cart_single_product',false,"on")){			
			add_action( 'wp_footer', [$this,'ace_product_page_ajax_add_to_cart_js'] );
			add_action( 'wc_ajax_ace_add_to_cart', [$this,'ace_ajax_add_to_cart_handler'] );
			add_action( 'wc_ajax_nopriv_ace_add_to_cart', [$this,'ace_ajax_add_to_cart_handler'] );	
			add_filter( 'woocommerce_add_to_cart_fragments', [$this,'ace_ajax_add_to_cart_add_fragments'] );
		}

		$add_to_cart_text = ADMINZ_Helper_Language::get_pll_string('adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_text]');
		if($add_to_cart_text){			
			$this->change_add_to_cart_text($add_to_cart_text);
		}

		if ($admz_woo->check_option('adminz_woocommerce_remove_add_to_cart_button',false,"on")){			
			$this->hide_add_to_cart_btn();
		}

		if ($admz_woo->check_option('adminz_woocommerce_ajax_add_to_cart_redirect_checkout',false,"on")){			
			$this->redirect_checkout();
		}
		if ($admz_woo->check_option('adminz_woocommerce_remove_quanity',false,true)){					
			$this->remove_quantity();
		}

		$buynow_text = ADMINZ_Helper_Language::get_pll_string('adminz_woocommerce[adminz_woocommerce_add_buy_now_text]');
		if($buynow_text){			
			$this->add_buy_now_text($buynow_text, $admz_woo);
		}	
	}

	function add_buy_now_text($buynow_text, $admz_woo){
		$atc_buynow_position = $admz_woo->get_option_value('adminz_woocommerce_add_buy_now_hook');
		add_action( $atc_buynow_position, function () use($buynow_text,$admz_woo){
			global $product;
			$id = $product->get_id();
			if( $product->is_type( 'variable' ) ){
				if($admz_woo->is_flatsome()){
					echo do_shortcode('[button text="'.$buynow_text.'" color="primary" size="" expand="true" icon="icon-shopping-bag" class="redirect_to_checkout disabled" icon_pos="left"  link="'.wc_get_checkout_url().'?add-to-cart='.$id.'"]');
				}else{
					echo '<a href="'.wc_get_checkout_url().'?add-to-cart='.$id.'" target="_self" class="button primary expand redirect_to_checkout disabled"> <i class="icon-shopping-bag"></i>  <span>'.$buynow_text.'</span> </a>';
				}
				add_action('wp_footer',function (){
					?>
						<script type="text/javascript">
					    	window.addEventListener('DOMContentLoaded', function() {
								(function($){
									$(document).on("change","input.variation_id",function(){
										get_link($(".redirect_to_checkout"));
									});
									$(document).on("change",".input-text.qty",function(){
										get_link($(".redirect_to_checkout"));
									});							
									get_link($(".redirect_to_checkout"));
									function get_link(target){
										var link = '<?php echo wc_get_checkout_url(); ?>';
										var parent = $(".product form.variations_form");										
										if(parent.length){
											var qty = parent.find(".input-text.qty").val();
											var varid = parent.find('input[name="variation_id"]').val();										
											target.addClass('disabled');
											target.attr("href","javascript:void(0)");
											if(qty>0 && varid>0){

												var href = "";								
												if(varid){
													href += '&add-to-cart='+ varid; 
												}								
												if(qty){
													href += '&quantity='+ qty; 
												}									
												var new_href = link+"?"+href;												
												target.attr("href",new_href);
												target.removeClass('disabled');
											}
										}								
									}
								})(jQuery);
							});				
						</script>		
					<?php
				});
			}
			elseif( $product->is_type( 'simple' ) ){
				if($admz_woo->is_flatsome()){
					echo do_shortcode('[button text="'.$buynow_text.'" color="primary" size="" expand="true" icon="icon-shopping-bag" class="redirect_to_checkout" icon_pos="left"  link="'.wc_get_checkout_url().'?add-to-cart='.$id.'"]');
				}else{
					echo '<a href="'.wc_get_checkout_url().'?add-to-cart='.$id.'" target="_self" class="button primary expand redirect_to_checkout"> <i class="icon-shopping-bag"></i>  <span>'.$buynow_text.'</span> </a>';
				}
				add_action('wp_footer',function ()use($id){
					?>
					<script type="text/javascript">
						window.addEventListener('DOMContentLoaded', function() {
							(function($){
								$(".input-text.qty").change(function(){
								  $("a.redirect_to_checkout").attr("href", "<?php echo wc_get_checkout_url(); ?>?add-to-cart=<?php echo esc_attr($id); ?>" + "&quantity=" + $(this).val());
								});
							})(jQuery);
						});
					</script>	
					<?php
				});
			}
		}, 20 );
		if(isset($_GET['add-to-cart'])){
			add_filter( 'woocommerce_add_cart_item_data', function ( $cart_item_data, $product_id, $variation_id ) {
			    global $woocommerce;
			    $woocommerce->cart->empty_cart();
			    return $cart_item_data;
			} , 10,  3);
			add_filter( 'wc_add_to_cart_message_html', '__return_false' );			
		}	
		add_action('wp_footer',function(){
			?>
			<style type="text/css">
				#wrapper>.message-wrapper {
				    display:none !important;
				}
			</style>
			<?php
		});
	}

	function remove_quantity(){
		add_filter( 'woocommerce_is_sold_individually',function ( $return, $product ) {
			return true;
		}, 10, 2 );
	}

	function redirect_checkout(){
		add_filter( 'woocommerce_add_to_cart_redirect', function (){
			return wc_get_checkout_url();
		});
		add_filter( 'woocommerce_product_add_to_cart_url', function ( $add_to_cart_url, $product ){ 
			if( $product->get_sold_individually() // if individual product
			&& WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->id ) ) // if in the cart
			&& $product->is_purchasable() // we also need these two conditions
			&& $product->is_in_stock() ) {
				$add_to_cart_url = wc_get_checkout_url();
			}			 
			return $add_to_cart_url;			 
		}, 10, 2 );
		add_filter( 'woocommerce_add_cart_item_data', function ( $cart_item_data, $product_id, $variation_id ) {
		    global $woocommerce;
		    $woocommerce->cart->empty_cart();
		    return $cart_item_data;
		} , 10,  3);
		add_filter( 'wc_add_to_cart_message_html', '__return_false' );
		add_action('wp_footer',function(){
			?>
			<style type="text/css">
				#wrapper>.message-wrapper {
				    display:none !important;
				}
			</style>
			<?php
		});
	}

	function hide_add_to_cart_btn(){
		add_action( 'wp_head', function (){
			echo '<style type="text/css"> .single_add_to_cart_button {display: none;} </style>';
		}, 999 );
	}
	function change_add_to_cart_text($add_to_cart_text){
		add_filter( 'woocommerce_product_single_add_to_cart_text', function ($a) use ($add_to_cart_text){return $add_to_cart_text; });
			add_filter( 'woocommerce_product_add_to_cart_text', function ($a) use ($add_to_cart_text){return $add_to_cart_text; });
			add_action( 'wp_head', function (){
				echo '<style type="text/css"> .single_add_to_cart_button::before {content: "\e908"; margin-left: -.15em; margin-right: .4em; font-weight: normal; font-family: "fl-icons" !important;} </style>';
			}, 999 );
	}	
	static function ace_ajax_add_to_cart_handler() {
		WC_AJAX::get_refreshed_fragments();
	}
	static function ace_ajax_add_to_cart_add_fragments( $fragments ) {
		$all_notices  = WC()->session->get( 'wc_notices', array() );
		$notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );
		ob_start();
		foreach ( $notice_types as $notice_type ) {
			if ( wc_notice_count( $notice_type ) > 0 ) {
				wc_get_template( "notices/{$notice_type}.php", array(
					'notices' => array_filter( $all_notices[ $notice_type ] ),
				) );
			}
		}
		$fragments['notices_html'] = ob_get_clean();
		wc_clear_notices();
		return $fragments;
	}
	static function ace_product_page_ajax_add_to_cart_js() {
		if(!(is_single() and is_singular( 'product' ))){return ;}
		ob_start();
	    ?><script type="text/javascript" charset="UTF-8">
			jQuery(function($) {
				$('form.cart').on('submit', function(e) {
					e.preventDefault();
					var form = $(this);
					form.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });
					var formData = new FormData(form[0]);
					formData.append('add-to-cart', form.find('[name=add-to-cart]').val() );
					// Ajax action.
					$.ajax({
						url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'ace_add_to_cart' ),
						data: formData,
						type: 'POST',
						processData: false,
						contentType: false,
						complete: function( response ) {
							response = response.responseJSON;
							if ( ! response ) {
								return;
							}
							if ( response.error && response.product_url ) {
								window.location = response.product_url;
								return;
							}
							// Redirect to cart option
							if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
								window.location = wc_add_to_cart_params.cart_url;
								return;
							}
							var $thisbutton = form.find('.single_add_to_cart_button'); //							
							//var $thisbutton = null; // uncomment this if you don't want the 'View cart' button
							// Trigger event so themes can refresh other areas.
							$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
							$(".added_to_cart").remove();
							// Remove existing notices
							$( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();
							// Add new notices
							form.closest('.product').before(response.fragments.notices_html);
							form.unblock();
						}
					});
				});
			});
		</script><?php
		echo ob_get_clean();
	}	
}