<?php if ( ! defined( 'ABSPATH' ) ) exit;

$plugin_dir_url =  plugin_dir_url( __FILE__ );

if ( ! empty( $_POST ) && check_admin_referer( 'phoen_woo_category_action', 'phoen_woo_category_action_field' ) ) {

	if(isset($_POST['cat_mode']) && sanitize_text_field( $_POST['cat_mode'] ) == 'Save'){
								
		$product_cat_btn	  = isset($_POST['product_cat_btn'])?sanitize_text_field( $_POST['product_cat_btn'] ) :'';
		 
		$product_cat_price    = isset($_POST['product_cat_price'])?sanitize_text_field( $_POST['product_cat_price'] ) :'';
		
		$product_cat_rating   = isset($_POST['product_cat_rating'])?sanitize_text_field( $_POST['product_cat_rating'] ) :'';
		
		$product_cat_review   =isset($_POST['product_cat_review'])?sanitize_text_field( $_POST['product_cat_review'] ) :'';
		
		$shop_cat_btn	  = isset($_POST['shop_cat_btn'])?sanitize_text_field( $_POST['shop_cat_btn'] ) :'';
		 
		$shop_cat_price    = isset($_POST['shop_cat_price'])?sanitize_text_field( $_POST['shop_cat_price'] ) :'';
		
		$shop_cat_rating   = isset($_POST['shop_cat_rating'])?sanitize_text_field( $_POST['shop_cat_rating'] ) :'';
		
		$catlog_setting	=	array(
		
								'product_cat_btn'=>$product_cat_btn,
								
								'product_cat_price'=>$product_cat_price,
								
								'product_cat_rating'=>$product_cat_rating,
								
								'product_cat_review'=>$product_cat_review,
								
								'shop_cat_btn'=>$shop_cat_btn,
								
								'shop_cat_price'=>$shop_cat_price,
								
								'shop_cat_rating'=>$shop_cat_rating,
							
							);
		
		update_option('phoen_woocommerce_catlog_mode',$catlog_setting);
		
	}
				
}
			 
	$gen_settings = get_option('phoen_woocommerce_catlog_mode'); ?>

	<form method="post" name="phoen_woo_category">

		<?php wp_nonce_field( 'phoen_woo_category_action', 'phoen_woo_category_action_field' ); ?>
		
		<br/>
		
		<div id="normal-sortables" class="meta-box-sortables">
				<div id="pho_wcpc_box" class="postbox ">
					
					<div class="inside">
						<div class="pho_check_pin">

							<div class="column two">
								<!----<h2>Get access to Pro Features</h2>----->

							

									<div class="pho-upgrade-btn">

										<a href="https://www.phoeniixx.com/product/catalog-for-woocommerce/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>../assets/images/premium-btn.png" /></a>
										<a href="http://catalog.phoeniixxdemo.com/shop/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>../assets/images/demo-btn.png"></a>
									</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		
		
		<table class="form-table">
		
			<tr>
			
				<th>
											
				</th>
																	
				<th>
				
					<?php _e('Product Page','phoeniixx_woocommerce_extension'); ?>
				
				</th>
				
				<th>
				
					<?php _e('Shop Page','phoeniixx_woocommerce_extension'); ?>
				
				</th>
				
			</tr>
											
			<tr>
				<th>
				
					<?php _e('Hide add to Cart Button','phoeniixx_woocommerce_extension'); ?>
					
				</th>
				
				<th>
				
					<input class="p_check" type="checkbox" name="product_cat_btn" value="1" <?php echo(isset($gen_settings['product_cat_btn']) && $gen_settings['product_cat_btn'] == '1')?'checked':'';?> >
				
				</th>
				
				<th>
					
					<input class="p_check" type="checkbox" name="shop_cat_btn" value="1" <?php echo(isset($gen_settings['shop_cat_btn']) && $gen_settings['shop_cat_btn'] == '1')?'checked':'';?> >
				
				</th>
			
			</tr>
			
			<tr>
				<th>
					<?php _e('Hide Price tag','phoeniixx_woocommerce_extension'); ?>
					
				</th>
				
				<th>
					
					<input class="p_check" type="checkbox" name="product_cat_price" value="1" <?php echo(isset($gen_settings['product_cat_price']) && $gen_settings['product_cat_price'] == '1')?'checked':'';?> >
				
				</th>
				
				<th>
					
					<input class="p_check" type="checkbox" name="shop_cat_price" value="1" <?php echo(isset($gen_settings['shop_cat_price']) && $gen_settings['shop_cat_price'] == '1')?'checked':'';?> >
				
				</th>
			
			</tr>
			
			<tr>
				
				<th>
					
					<?php _e('Hide Rating','phoeniixx_woocommerce_extension'); ?>
				
				</th>
				
				<th>
					
					<input class="p_check" type="checkbox" name="product_cat_rating" value="1" <?php echo(isset($gen_settings['product_cat_rating']) && $gen_settings['product_cat_rating'] == '1')?'checked':'';?> >
				
				</th>
				
				<th>
					
					<input class="p_check" type="checkbox" name="shop_cat_rating" value="1" <?php echo(isset($gen_settings['shop_cat_rating']) && $gen_settings['shop_cat_rating'] == '1')?'checked':'';?> >
				
				</th>
				
			</tr>
			
			<tr>
				<th>
					
					<?php _e('Hide Reviews','phoeniixx_woocommerce_extension'); ?>
				</th>
				
				<th>
					
					<input class="p_check" type="checkbox" name="product_cat_review" value="1" <?php echo(isset($gen_settings['product_cat_review']) && $gen_settings['product_cat_review'] == '1')?'checked':'';?> >
				
				</th>
				
				<th>
					
				</th>
			
			</tr>
									
			<tr>
				<th>
					
					<input type="submit" class="button button-primary" value="Save" name="cat_mode">
					
					<input type="button" class="reset_cat button-primary" value="Reset" name="reset_cat">
				
				</th>
			
			</tr>
		
		</table>
	
	</form>
	
	
	<style>
	
	a:focus { box-shadow: none;}
	
	.postbox {
		background: #fff none repeat scroll 0 0;
		border: 1px solid #e5e5e5;
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
		min-width: 255px;
	}
	
	

	.postbox .inside {
		margin: 11px 0;
		position: relative;
	}
	
	
	.pho-upgrade-btn {
		margin-top: 30px;
	}

	</style>