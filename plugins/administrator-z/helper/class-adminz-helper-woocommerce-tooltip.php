<?php 
namespace Adminz\Helper;

class ADMINZ_Helper_Woocommerce_Tooltip{
	function __construct($admz_woo) {
		if($admz_woo->is_flatsome() and $admz_woo->get_option_value('adminz_tooltip_products')){
			add_action('woocommerce_before_shop_loop_item',function(){
				global $product;	
				ob_start();
				?>
				<div class="tooltip_data" style="display: none ;" data-product_id="<?php echo esc_attr($product->get_id()); ?>"><?php do_action('adminz_product_tooltip'); ?></div>
				<?php
				echo ob_get_clean();
			});				
			add_action('adminz_product_tooltip',function(){
				global $product;
				ob_start();
				?>
					<div class="admz_shortdescription"><?php echo apply_filters('the_content', $product->get_short_description()); ?></div>
				<?php
				echo ob_get_clean();
			},30);				
			add_action('wp_footer',function(){
				ob_start();
				?>
				<div class="tooltip_box"></div>
				<style type="text/css">
					.tooltip_box {
						position: absolute; 
						background: white; 
						z-index: 99; 
						width:  300px; 
						box-shadow: 1px 2px 7px #23232363;
						overflow-x: hidden;
						overflow-y: auto;
						max-height: 60vh;
					}
					.tooltip_box .admz_shortdescription{
						padding: 1em;
					}
				</style>
				<script type="text/javascript">
					window.addEventListener('DOMContentLoaded', function() {
						(function($){
							if(! /Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
								let product_box = ".product-small";
								let tooltip_box = ".tooltip_box";
							    $(product_box).mousemove(function( event ) {

							    	let need_left = event.pageX + 20;
								  	let need_top = event.pageY -20;									  						  	
								  	if( (need_left + $(tooltip_box).width() + 20  ) > $(document).width() ){
									  	need_left = need_left - $(tooltip_box).width() - 60;
								  	}
								  	adminz_tooltip_content($(tooltip_box),$(this),need_left,need_top);									  	
								});
								$(product_box).mouseleave(function() {
									$(".tooltip_box").css("display","none"); 
							    });
							    function adminz_tooltip_content(tooltip_box,hoverbox,need_left,need_top){
							    	let databox = hoverbox.find('.tooltip_data');				    	
							    	let current_product_id = tooltip_box.attr("data-product_id");
							    	if(databox.length && current_product_id !== databox.attr("data-product_id")){
							    		tooltip_box.attr("data-product_id",databox.attr("data-product_id"));
							    		tooltip_box.html(databox.html());
							    		adminz_change_src(tooltip_box);

							    		console.log("changed tooltip html for " + databox.attr("data-product_id") );

							    	}
							    	if(tooltip_box .find("*:not(:empty)").length){
											tooltip_box.css("display","inline").css("left",need_left).css("top",need_top);
										}else{
											tooltip_box.css("display","none");
										}
							    	
							    }
							    function adminz_change_src(dom){
							    	if(dom.find("img").length){
								    	dom.find("img").each(function(){
								    		if($(this).hasClass("lazy-load")){
								    			var dom_src = $(this).attr("src");
								    			var dom_datasrc = $(this).attr("data-src");
								    			$(this).attr("src",dom_datasrc);
								    			$(this).attr("data-src",dom_src);
								    			$(this).removeClass("lazy-load");
								    		}
								    	});
								    }
							    }
							}
						})(jQuery)
					});
				</script>
				<?php
				echo ob_get_clean();
			});
		}
	}
	
}