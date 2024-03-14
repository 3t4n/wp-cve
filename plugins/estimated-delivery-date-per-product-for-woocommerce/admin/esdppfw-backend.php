<?php
add_action( 'admin_menu','esdppfw_submenu_page');
function esdppfw_submenu_page() {
    add_submenu_page( 'woocommerce', 'Product Est Date', 'Product Est Date', 'manage_options', 'product-estdate','esdppfw_product_est_date_callback_func');
}

function esdppfw_product_est_date_callback_func(){
	?>
		<div class="wrap">
            <h2><?php echo __( 'Product Est Date Setting', 'estimated-shipping-date-per-product-for-woocommerce' );?></h2>
            <?php if(isset($_REQUEST['message'])  && $_REQUEST['message'] == 'success'){ ?>
                <div class="notice notice-success is-dismissible"> 
                    <p><strong><?php echo __( 'Setting saved successfully.', 'estimated-shipping-date-per-product-for-woocommerce' );?></strong></p>
                </div>
            <?php } ?>
        </div>
        <div class="esdppfw_container">
        	<form method="post">
        		<ul class="nav-tab-wrapper woo-nav-tab-wrapper">
                    <li class="nav-tab" data-tab="esdppfw-tab-general"><?php echo __( 'General Settings', 'estimated-shipping-date-per-product-for-woocommerce' );?></li>
                </ul>
                <div id="esdppfw-tab-general" class="tab-content current">
                	<div class="postbox">
                		<div class="inside">
                			<table class="form-table">
                				<tr>
                					<th><?php echo __( 'Enabled Delivery Date', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="checkbox" name="est_delvry_date" value="yes"<?php checked('yes',get_option('est_delvry_date','yes')); ?>><strong><?php echo __( 'Enable/Disable', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></strong></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Enabled For All Products', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="checkbox" name="est_date_ena_all_pro" value="yes"<?php checked('yes',get_option('est_date_ena_all_pro','')); ?>><strong><?php echo __( 'Enable/Disable', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></strong><p class="description"><?php echo __( 'This Setting Enable Dleivery Time For all Products. You can enable this Option if all your products have same delivery time.', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Delivery Time', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="text" name="est_delvry_date_all_pro" value="<?php echo esc_attr(get_option('est_delvry_date_all_pro','2')); ?>"><p class="description"><?php echo __( 'in day', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Display on Single Product Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="checkbox" name="est_date_display_single_pro" value="yes"<?php checked('yes',get_option('est_date_display_single_pro','yes')); ?>><strong><?php echo __( 'Enable/Disable', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></strong></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Delivery Text Position on Single Product Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td>
                						<select name="delvry_text_position_sinpro">
	                						<option value="single_pro_sum"<?php selected('single_pro_sum',get_option('delvry_text_position_sinpro','single_pro_sum')); ?>><?php echo __( 'Single Product Summary', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
	                						<option value="before_atc_btn"<?php selected('before_atc_btn',get_option('delvry_text_position_sinpro','single_pro_sum')); ?>><?php echo __( 'Before Add to Cart Button', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
	                						<option value="after_atc_quantity"<?php selected('after_atc_quantity',get_option('delvry_text_position_sinpro','single_pro_sum')); ?>><?php echo __( 'After Add to Cart Quantity', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
	                						<option value="after_atc_btn"<?php selected('after_atc_btn',get_option('delvry_text_position_sinpro','single_pro_sum')); ?>><?php echo __( 'After Add to Cart Button', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
	                						<option value="pro_meta_start"<?php selected('pro_meta_start',get_option('delvry_text_position_sinpro','single_pro_sum')); ?>><?php echo __( 'Product meta Start', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
	                						<option value="pro_meta_end"<?php selected('pro_meta_end',get_option('delvry_text_position_sinpro')); ?>><?php echo __( 'Product meta end', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
                						</select>
                					</td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Delivery Text Background Color For Single Products', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="text" class="color-picker" data-alpha="true" data-default-color="#f5f5f5" name="single_pro_delivry_text_bg" value="<?php echo esc_attr(get_option('single_pro_delivry_text_bg','#f5f5f5')); ?>"/></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Delivery Text Color For Single Products', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="text" class="color-picker" data-alpha="true" data-default-color="#ff0000" name="single_pro_delivry_text_color" value="<?php echo esc_attr(get_option('single_pro_delivry_text_color','#ff0000')); ?>"/></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Display on Cart Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="checkbox" name="est_display_on_cartpage" value="yes"<?php checked('yes',get_option('est_display_on_cartpage','yes')); ?>><strong><?php echo __( 'Enable/Disable', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></strong></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Delivery Text Position on Cart Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td>
                						<select name="delvry_text_position_cart">
	                						<option value="before_cart_table"<?php selected('before_cart_table',get_option('delvry_text_position_cart','before_cart_table')); ?>><?php echo __( 'Before Cart Table', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
	                						<option value="after_cart_table"<?php selected('after_cart_table',get_option('delvry_text_position_cart','before_cart_table')); ?>><?php echo __( 'After Cart Table', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
                						</select>
                					</td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Display on Checkout Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="checkbox" name="est_display_on_checkoutpage" value="yes"<?php checked('yes',get_option('est_display_on_checkoutpage','yes')); ?>><strong><?php echo __( 'Enable/Disable', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></strong></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Delivery Text Position on Checkout Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td>
                						<select name="delvry_text_position_checkout">
	                						<option value="before_order_review"<?php selected('before_order_review',get_option('delvry_text_position_checkout','before_order_review')); ?>><?php echo __( 'Before Order Review', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
	                						<option value="review_order_before_payment"<?php selected('review_order_before_payment',get_option('delvry_text_position_checkout','before_order_review')); ?>><?php echo __( 'Before Order Review Payment', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></option>
                						</select>
                					</td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Display on Order Page/ Order Email', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="checkbox" name="est_display_on_orderpage" value="yes"<?php checked('yes',get_option('est_display_on_orderpage','yes')); ?>><strong><?php echo __( 'Enable/Disable', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></strong></td>
                				</tr>

                				<tr>
                					<th><?php echo __( 'Delivery Text Position on Order Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td>
                						<select name="delvry_text_position_order">
                							<option value="before_order_detail" <?php selected('before_order_detail', get_option("delvry_text_position_order","before_order_detail")) ?>><?php echo esc_html('Before Order Details','estimated-shipping-date-per-product-for-woocommerce'); ?></option>
	                						<option value="inside_order_detail" <?php selected('inside_order_detail', get_option("delvry_text_position_order","before_order_detail")) ?>><?php echo esc_html('Inside Order Details Table','estimated-shipping-date-per-product-for-woocommerce'); ?></option>
					                        <option value="after_customer_detail" <?php selected('after_customer_detail', get_option("delvry_text_position_order","before_order_detail")) ?>><?php echo esc_html('After Customer Address','estimated-shipping-date-per-product-for-woocommerce'); ?></option>
                						</select>
                					</td>
                				</tr>

                				<tr>
                					<th><?php echo __( 'Delivery Text Position on Email', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td>
                						<input type="radio" name="email_before_order_table" value="email_before_order_table" checked disabled><?php echo esc_html('Before Order Table','estimated-shipping-date-per-product-for-woocommerce'); ?>
                						<input type="radio" name="email_after_order_table" value="email_after_order_table" disabled><?php echo esc_html('After Order Table','estimated-shipping-date-per-product-for-woocommerce'); ?>
                						<label class="esdppfw_comman_link"><?php echo __('This Option Available in ','estimated-shipping-date-per-product-for-woocommerce');?> <a href="https://www.topsmodule.com/product/estimated-delivery-date-per-product-for-woocommerce/" target="_blank"><?php echo esc_html('Pro Version','estimated-shipping-date-per-product-for-woocommerce'); ?></a></label>
                					</td>
                				</tr>

                				<tr>
                					<th><?php echo __( 'Hide if Product is Out of Stock?', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="checkbox" name="hide_outofstock_product" value="yes"<?php checked('yes',get_option('hide_outofstock_product','')); ?> disabled><strong><?php echo __( 'Enable/Disable', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></strong><label class="esdppfw_comman_link"><?php echo __('This Option Available in ','estimated-shipping-date-per-product-for-woocommerce');?> <a href="https://www.topsmodule.com/product/estimated-delivery-date-per-product-for-woocommerce/" target="_blank"><?php echo esc_html('Pro Version','estimated-shipping-date-per-product-for-woocommerce'); ?></a></label></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Hide if Product is Backorder?', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="checkbox" name="hide_product_backorder" value="yes"<?php checked('yes',get_option('hide_product_backorder','')); ?> disabled><strong><?php echo __( 'Enable/Disable', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></strong><label class="esdppfw_comman_link"><?php echo __('This Option Available in ','estimated-shipping-date-per-product-for-woocommerce');?> <a href="https://www.topsmodule.com/product/estimated-delivery-date-per-product-for-woocommerce/" target="_blank"><?php echo esc_html('Pro Version','estimated-shipping-date-per-product-for-woocommerce'); ?></a></label></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'For All Products Delivery Text For Product Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="text" name="delvry_text_pro_page" value="<?php echo esc_attr(get_option('delvry_text_pro_page','this item will be delivery on')); ?>"></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'For All Products Delivery Text For Cart And Checkout Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="text" name="delvry_text_cart_checkout" value="<?php echo esc_attr(get_option('delvry_text_cart_checkout','your order will be delivery on')); ?>">
                					</td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'For All Products Delivery Text For Order Page', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="text" name="delvry_text_order_page" value="<?php echo esc_attr(get_option('delvry_text_order_page','your order will be delivery on')); ?>"></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Delivery Text Background Color all Products', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="text" class="color-picker" data-alpha="true" data-default-color="#000000" name="delivry_text_bg" value="<?php echo esc_attr(get_option('delivry_text_bg','#000000')); ?>"/></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Delivery Text Color all Products', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td><input type="text" class="color-picker" data-alpha="true" data-default-color="#ffffff" name="delivry_text_color" value="<?php echo esc_attr(get_option('delivry_text_color','#ffffff')); ?>"/></td>
                				</tr>
                				<tr>
                					<th><?php echo __( 'Delivery Date Format', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></th>
                					<td>
                						<input type="text" name="delvry_date_format" value="d,F Y" disabled>
                						<label class="esdppfw_comman_link"><?php echo __('This Option Available in ','estimated-shipping-date-per-product-for-woocommerce');?> <a href="https://www.topsmodule.com/product/estimated-delivery-date-per-product-for-woocommerce/" target="_blank"><?php echo esc_html('Pro Version','estimated-shipping-date-per-product-for-woocommerce'); ?></a></label>
                						<p class="description"><?php echo __( 'Example', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'd - The day of the month (from 01 to 31)', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'D - A textual representation of a day (three letters)', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'j - The day of the month without leading zeros (1 to 31)', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'F - A full textual representation of a month (January through December)', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'l (lowercase "L") - A full textual representation of a day', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'm - A numeric representation of a month (from 01 to 12)', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'M - A short textual representation of a month (three letters)', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'n - A numeric representation of a month, without leading zeros (1 to 12)', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'Y - A four digit representation of a year', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>
                						<p class="description"><?php echo __( 'y - A two digit representation of a year', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></p>

                					</td>
                				</tr>
                			</table>
                		</div>
                	</div>
                </div>
                <input type="hidden" name="estdateaction" value="est_date_save_option">
                <input type="submit" value="Save changes" name="submit" class="button-primary">
        	</form>
        </div>
	<?php
}

add_action('init','esdppfw_save_product_est_date');
function esdppfw_save_product_est_date(){
	if (isset($_REQUEST['estdateaction'])) {
		if ($_REQUEST['estdateaction'] == 'est_date_save_option') {
			if (isset($_REQUEST['est_delvry_date'])) {
				update_option('est_delvry_date',sanitize_text_field($_REQUEST['est_delvry_date']));
			}else{
				update_option('est_delvry_date','');
			}
			
			if (isset($_REQUEST['est_date_ena_all_pro'])) {
				update_option('est_date_ena_all_pro',sanitize_text_field($_REQUEST['est_date_ena_all_pro']));
			}else{
				update_option('est_date_ena_all_pro','');
			}
			
			if (isset($_REQUEST['est_delvry_date_all_pro'])) {
				update_option('est_delvry_date_all_pro',sanitize_text_field($_REQUEST['est_delvry_date_all_pro']));
			}else{
				update_option('est_delvry_date_all_pro','');
			}

			if (isset($_REQUEST['est_date_display_single_pro'])) {
				update_option('est_date_display_single_pro',sanitize_text_field($_REQUEST['est_date_display_single_pro']));
			}else{
				update_option('est_date_display_single_pro','');
			}

			if (isset($_REQUEST['delvry_text_position_sinpro'])) {
				update_option('delvry_text_position_sinpro',sanitize_text_field($_REQUEST['delvry_text_position_sinpro']));
			}else{
				update_option('delvry_text_position_sinpro','');
			}

			if (isset($_REQUEST['single_pro_delivry_text_bg'])) {
				update_option('single_pro_delivry_text_bg',sanitize_text_field($_REQUEST['single_pro_delivry_text_bg']));
			}else{
				update_option('single_pro_delivry_text_bg','');
			}

			if (isset($_REQUEST['single_pro_delivry_text_color'])) {
				update_option('single_pro_delivry_text_color',sanitize_text_field($_REQUEST['single_pro_delivry_text_color']));
			}else{
				update_option('single_pro_delivry_text_color','');
			}

			if (isset($_REQUEST['delvry_text_position_cart'])) {
				update_option('delvry_text_position_cart',sanitize_text_field($_REQUEST['delvry_text_position_cart']));
			}else{
				update_option('delvry_text_position_cart','');
			}

			if (isset($_REQUEST['delvry_text_position_checkout'])) {
				update_option('delvry_text_position_checkout',sanitize_text_field($_REQUEST['delvry_text_position_checkout']));
			}else{
				update_option('delvry_text_position_checkout','');
			}

			if (isset($_REQUEST['est_display_on_cartpage'])) {
				update_option('est_display_on_cartpage',sanitize_text_field($_REQUEST['est_display_on_cartpage']));
			}else{
				update_option('est_display_on_cartpage','');
			}

			if (isset($_REQUEST['est_display_on_checkoutpage'])) {
				update_option('est_display_on_checkoutpage',sanitize_text_field($_REQUEST['est_display_on_checkoutpage']));
			}else{
				update_option('est_display_on_checkoutpage','');
			}

			if (isset($_REQUEST['delvry_text_position_order'])) {
				update_option('delvry_text_position_order',sanitize_text_field($_REQUEST['delvry_text_position_order']));
			}else{
				update_option('delvry_text_position_order','');
			}

			if (isset($_REQUEST['est_display_on_orderpage'])) {
				update_option('est_display_on_orderpage',sanitize_text_field($_REQUEST['est_display_on_orderpage']));
			}else{
				update_option('est_display_on_orderpage','');
			}

			if (isset($_REQUEST['delvry_text_pro_page'])) {
				update_option('delvry_text_pro_page',sanitize_text_field($_REQUEST['delvry_text_pro_page']));
			}else{
				update_option('delvry_text_pro_page','');
			}

			if (isset($_REQUEST['delvry_text_cart_checkout'])) {
				update_option('delvry_text_cart_checkout',sanitize_text_field($_REQUEST['delvry_text_cart_checkout']));
			}else{
				update_option('delvry_text_cart_checkout','');
			}

			if (isset($_REQUEST['delvry_text_order_page'])) {
				update_option('delvry_text_order_page',sanitize_text_field($_REQUEST['delvry_text_order_page']));
			}else{
				update_option('delvry_text_order_page','');
			}

			if (isset($_REQUEST['delivry_text_bg'])) {
				update_option('delivry_text_bg',sanitize_text_field($_REQUEST['delivry_text_bg']));
			}else{
				update_option('delivry_text_bg','');
			}

			if (isset($_REQUEST['delivry_text_color'])) {
				update_option('delivry_text_color',sanitize_text_field($_REQUEST['delivry_text_color']));
			}else{
				update_option('delivry_text_color','');
			}

			wp_redirect( admin_url( '/admin.php?page=product-estdate&message=success' ) );
            exit;
		}
	}
	
}

/* Single Product Setting */

// Add custom product setting tab.
add_action('woocommerce_product_data_tabs','esdppfw_custom_product_data_tab');
function esdppfw_custom_product_data_tab($tabs){
	$tabs['esdppfw_product_data'] = array(
		'label'    =>  __( 'Product Est Date', 'estimated-shipping-date-per-product-for-woocommerce' ),
		'target'   => 'esdppfw_product_data',
		'priority' => 75,
	);
	return $tabs;
}

add_action( 'woocommerce_product_data_panels', 'esdppfw_custom_tab_callback_func' );
function esdppfw_custom_tab_callback_func(){
	?>
	<div id="esdppfw_product_data" class="panel woocommerce_options_panel">
		<div class="options_group">
			<p class="form-field">
				<label><?php echo __( 'Delivery Time:', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></label>
				<?php 
					$est_date_delivry_time = get_post_meta(get_the_id(),'est_date_delivry_time',true);
					if (empty($est_date_delivry_time)) {
						$est_date_delvrytime = '2';
					}else{
						$est_date_delvrytime = $est_date_delivry_time;
					}
				?>
				<input type="text" class="short" name="est_date_delivry_time" value="<?php echo esc_attr($est_date_delvrytime); ?>">
				<span class="description"><?php echo __( 'In Day', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></span>
			</p>
			<p class="form-field">
				<label><?php echo __( 'Delivery Date Text:', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></label>
				<?php 
					$delivry_datetext = get_post_meta(get_the_id(),'delivry_datetext',true);
					if (empty($delivry_datetext)) {
						$est_date_datetext = 'This item will be delivery on';
					}else{
						$est_date_datetext = $delivry_datetext;
					}
				?>
				<input type="text" class="short" name="delivry_datetext" value="<?php echo esc_attr($est_date_datetext); ?>">
			</p>
			<p class="form-field">
				<label><?php echo __( 'Delivery Text For Out Of Stock:', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></label>
				<?php 
					$delvry_text_outstock = get_post_meta(get_the_id(),'delvry_text_outstock',true);
					if (empty($delvry_text_outstock)) {
						$est_date_text_outstock = 'This item will be delivery on';
					}else{
						$est_date_text_outstock = $delvry_text_outstock;
					}
				?>
				<input type="text" class="short" name="delvry_text_outstock" value="<?php echo esc_attr($est_date_text_outstock); ?>">
			</p>
			<p class="form-field">
				<label><?php echo __( 'Delivery text on Order Page:', 'estimated-shipping-date-per-product-for-woocommerce' ); ?></label>
				<?php 
					$delvry_text_orderpage = get_post_meta(get_the_id(),'delvry_text_orderpage',true);
					if (empty($delvry_text_orderpage)) {
						$est_date_text_orderpage = 'Your order will be delivery on';
					}else{
						$est_date_text_orderpage = $delvry_text_orderpage;
					}
				?>
				<input type="text" class="short" name="delvry_text_orderpage" value="<?php echo esc_attr($est_date_text_orderpage); ?>">
			</p>
		</div>
	</div>
	<?php
}

// add_action('save_post','esdppfw_save_product_est_date_val');
add_action( 'woocommerce_process_product_meta',  'esdppfw_save_product_est_date_val' );
function esdppfw_save_product_est_date_val($post){
	if (isset($_REQUEST['est_date_delivry_time'])) {
		update_post_meta($post,'est_date_delivry_time',sanitize_text_field($_REQUEST['est_date_delivry_time']));
	}else{
		update_post_meta($post,'est_date_delivry_time','');
	}

	if (isset($_REQUEST['delivry_datetext'])) {
		update_post_meta($post,'delivry_datetext',sanitize_text_field($_REQUEST['delivry_datetext']));
	}else{
		update_post_meta($post,'delivry_datetext','');
	}

	if (isset($_REQUEST['delvry_text_outstock'])) {
		update_post_meta($post,'delvry_text_outstock',sanitize_text_field($_REQUEST['delvry_text_outstock']));
	}else{
		update_post_meta($post,'delvry_text_outstock','');
	}

	if (isset($_REQUEST['delvry_text_orderpage'])) {
		update_post_meta($post,'delvry_text_orderpage',sanitize_text_field($_REQUEST['delvry_text_orderpage']));
	}else{
		update_post_meta($post,'delvry_text_orderpage','');
	}

}