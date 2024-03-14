<?php
/*Admin Menu Here*/
add_action('admin_menu','PQDFW_adminmenu',99);
function PQDFW_adminmenu() {
    add_submenu_page(
		'woocommerce',
		__('Product Quantity Dropdown ', 'product-quantity-dropdown'),
		__('Product Quantity Dropdown', 'product-quantity-dropdown'),
		'manage_woocommerce',
		'product-quantity-dropdown',
		'PQDFW_add_quantity_dropdown'
	);
}

/*Quantity Dropdown*/
function PQDFW_add_quantity_dropdown(){
	global $pqdfw_comman;
	?>
    <div class="wrap">
    	<div class="PQDFW_container">
    		<form method="post">
    			<h1><?php echo esc_html_e('Product Quantity Dropdown Settings' , 'product-quantity-dropdown-for-woocommerce')?></h1>
                <div class="card pqdfw_notice">
                    <h2><?php echo __('Please help us spread the word & keep the plugin up-to-date', 'product-quantity-dropdown-for-woocommerce');?></h2>
                    <p>
                        <a class="button-primary button" title="<?php echo __('Support Product Quantity Dropdown', 'product-quantity-dropdown-for-woocommerce-pro');?>" target="_blank" href="https://www.plugin999.com/support/"><?php echo __('Support', 'product-quantity-dropdown-for-woocommerce'); ?></a>
                        <a class="button-primary button" title="<?php echo __('Rate Product Quantity Dropdown', 'product-quantity-dropdown-for-woocommerce');?>" target="_blank" href="https://wordpress.org/support/plugin/product-quantity-dropdown-for-woocommerce/reviews/?filter=5"><?php echo __('Rate the plugin ★★★★★', 'product-quantity-dropdown-for-woocommerce'); ?></a>
                    </p>
                </div>
                <?php if(isset($_REQUEST['message']) && $_REQUEST['message'] == 'success'){ ?>
                    <div class="notice notice-success is-dismissible"> 
                        <p><strong><?php echo esc_html( 'Your Setting Saved Successfully...!', 'product-quantity-dropdown-for-woocommerce-pro' );?></strong></p>
                    </div>
                <?php } ?>
    			<table class="quantity_table">
    				<tr>
    					<th><h4><?php echo esc_html_e('Product Quantity Dropdown For Woocommerce' , 'product-quantity-dropdown-for-woocommerce')?></h4></th>
	    				<td>
	    					<input type="checkbox" name="pqdfw_comman[enable_plugin]" class="enable_plugin_check" value="yes" <?php if ($pqdfw_comman['enable_plugin'] == "yes" ) { echo 'checked="checked"'; } ?>>
							<label for="pqdfw_comman[enable_plugin]"><?php echo esc_html_e('Enable plugin'  , 'product-quantity-dropdown-for-woocommerce'); ?>  </label>
	    				</td>
    				</tr>
    			</table>
    			<div class="product_private select_quantity_rule">
    				<h2><?php echo esc_html_e('Select Quantity Rule' , 'product-quantity-dropdown-for-woocommerce'); ?></h2>
    			</div>
    			<table class="quantity_table">
    				<tr>
    					<th>
    						<?php echo esc_html_e('Quantity Product Type' , 'product-quantity-dropdown-for-woocommerce'); ?>    
    					</th>
    					<td>
    						<input type="radio" class="quantity_product_rule" name="quantity_product_rule" value="all_product" <?php if(get_option('quantity_product_rule', 'all_product') == 'all_product' ) { echo 'checked'; } ?>><?php echo esc_html_e('All Product' , 'product-quantity-dropdown-for-woocommerce'); ?> 
                     		<input type="radio" class="quantity_product_rule" name="quantity_product_rule" value="specific_product" <?php if(get_option('quantity_product_rule', 'all_product') == 'specific_product' ) { echo 'checked'; } ?>><?php echo esc_html_e('Specific Product' , 'product-quantity-dropdown-for-woocommerce'); ?> 
    					</td>
    				</tr>
    				<tr class="product_private product_specific">
                        <th>
                        	<label><?php echo esc_html_e('Select Product' , 'product-quantity-dropdown-for-woocommerce');?></label>
                        </th>
                        <td>
                        	<select id="pqdfw_select_product" name="pqdfw_select2[]" multiple="multiple" style="width:60%;" disabled>
	                           	
                           </select>
                           <label class="pqdfw_comman_link"><?php echo __('This Option Available in ','product-quantity-dropdown-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/product-quantity-dropdown-for-woocommerce/" target="_blank"><?php echo esc_html_e('Pro Version'  , 'product-quantity-dropdown-for-woocommerce'); ?></a></label> 
                        </td>
                    </tr>
    				<tr class="product_private product_specific">
                        <th>
                        	<label><?php echo esc_html_e('Select Product Categories',  'product-quantity-dropdown-for-woocommerce');?></label>
                        </th>
                        <td>
                        	<select id="pqdfw_select_cats" name="pqdfw_cats_select2[]" multiple="multiple" style="width:60%;" disabled>
	                           	
                            </select>
                            <label class="pqdfw_comman_link"><?php echo __('This Option Available in ','product-quantity-dropdown-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/product-quantity-dropdown-for-woocommerce/" target="_blank"><?php echo esc_html_e('Pro Version'  , 'product-quantity-dropdown-for-woocommerce'); ?></a></label>    
                        </td>
                    </tr>
                    <tr class="product_private product_specific">
                        <th>
                        	<label><?php echo esc_html_e('Select Product Tags','product-quantity-dropdown-for-woocommerce');?></label>
                        </th>
                        <td>
                        	<select id="pqdfw_select_tags" name="pqdfw_tags_select2[]" multiple="multiple" style="width:60%;" disabled>
	                           
	                        </select>
                            <label class="pqdfw_comman_link"><?php echo __('This Option Available in ','product-quantity-dropdown-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/product-quantity-dropdown-for-woocommerce/" target="_blank"><?php echo esc_html_e('Pro Version'  , 'product-quantity-dropdown-for-woocommerce'); ?></a></label>			                       
                        </td>
                    </tr>
    			</table>
    			<div class="product_private general_setting">
    				<h2><?php echo esc_html_e('General Settings' , 'product-quantity-dropdown-for-woocommerce'); ?></h2>
    			</div>		    				
    			<table class="quantity_table">
    				<tr class="product_private">
    					<th><?php echo esc_html_e('Minimum Quantity' , 'product-quantity-dropdown-for-woocommerce'); ?></th>
    					<td>
    						<input type="number" name="pqdfw_comman[pqdfw_min_quantity]" value="<?php echo esc_attr($pqdfw_comman['pqdfw_min_quantity']); ?>">
    					</td>
    				</tr>
    				<tr class="product_private">
    					<th><?php echo esc_html_e('Maximum Quantity' , 'product-quantity-dropdown-for-woocommerce'); ?></th>
    					<td>
    						<input type="number" name="pqdfw_comman[pqdfw_max_quantity]" value="<?php echo esc_attr($pqdfw_comman['pqdfw_max_quantity']); ?>">
    					</td>
    				</tr>
    				<tr class="product_private">
    					<th><?php echo esc_html_e('Step' , 'product-quantity-dropdown-for-woocommerce'); ?></th>
    					<td>
    						<input type="number" name="pqdfw_comman[pqdfw_step_quantity]" value="<?php echo esc_attr($pqdfw_comman['pqdfw_step_quantity']); ?>">
    					</td>
    				</tr>
    				<tr class="product_private">
    					<th><?php echo esc_html_e('Drop-Down Label' , 'product-quantity-dropdown-for-woocommerce'); ?></th>
    					<td>
    						<input type="text" name="pqdfw_comman[pqdfw_dropdown_lable]" class="pqdfw_dropdown_lable" value="<?php echo esc_attr($pqdfw_comman['pqdfw_dropdown_lable']);?>"><br>
    						<label><i><?php echo esc_html_e('(Text to add Dropdown Label)' , 'product-quantity-dropdown-for-woocommerce'); ?></i></label>
    					</td>
    				</tr>
                    <tr class="product_private">
                        <th><?php echo esc_html_e('Disable Drop-Down in Shop/category Page' , 'product-quantity-dropdown-for-woocommerce'); ?></th>
                        <td>
                            <input type="checkbox" name="pqdfw_comman[disable_shop_category_dropdown]" value="yes" disabled>
                            <label class="pqdfw_comman_link"><?php echo __('This Option Available in ','product-quantity-dropdown-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/product-quantity-dropdown-for-woocommerce/" target="_blank"><?php echo esc_html_e('Pro Version'  , 'product-quantity-dropdown-for-woocommerce'); ?></a></label>
                        </td>
                    </tr>
    			</table>	    			
    			<input type="hidden" name="pqdfw_form_submit" value="pqdfw_save_option">
                <input type="submit" value="Save changes" name="submit" class="button-primary" id="pqdfw-btn-space">
    		</form>
    	</div>
    </div>
    <?php
}



/*Page Option Save Here*/
add_action( 'init',  'PQDFW_save_option');

function PQDFW_save_option(){
	if( current_user_can('administrator') ) { 
        if(isset($_REQUEST['pqdfw_form_submit']) && $_REQUEST['pqdfw_form_submit'] == 'pqdfw_save_option'){
    	 	$isecheckbox = array(
            	'enable_plugin',
            );
            foreach ($isecheckbox as $key_isecheckbox => $value_isecheckbox) {

                if(!isset($_REQUEST['pqdfw_comman'][$value_isecheckbox])){                        	
                    $_REQUEST['pqdfw_comman'][$value_isecheckbox] ='no';
                }
            }

    		$quantity_product_rule = sanitize_text_field( $_REQUEST['quantity_product_rule'] );
    		update_option('quantity_product_rule', $quantity_product_rule, 'yes');

            foreach ($_REQUEST['pqdfw_comman'] as $key_wfwc_comman => $value_wfwc_comman) {
              	update_option($key_wfwc_comman, sanitize_text_field($value_wfwc_comman), 'yes');
            }

        wp_redirect( admin_url( '/admin.php?page=product-quantity-dropdown&message=success' ) );
        exit;
        }
    }
}

/* Single Product Setting */
add_filter( 'woocommerce_product_data_tabs', 'pqdfw_custom_product_tabs' );
function pqdfw_custom_product_tabs( $tabs ) {
    $tabs['combo_product'] = array(
        'label'     => __( 'Product Quantity', 'woocommerce' ),
        'target'    => 'pqdfw_options',
        'class'     => array( 'product_if_quantity' ),
    );
    return $tabs;
}

add_action( 'woocommerce_product_data_panels',  'pqdfw_custom_product_tabs_fields' );
function pqdfw_custom_product_tabs_fields() {
    ?> 
    <div id="pqdfw_options" class="panel woocommerce_options_panel">
        <div class= 'options_group'>
            <p class="form-field">
                <label><?php esc_html_e( 'Minimum Quantity', 'product-quantity-dropdown-for-woocommerce' ); ?></label>
                <input type="number" name="pqdfw_pro_min_quantity" value="<?php echo esc_attr(get_post_meta( get_the_ID(), 'pqdfw_pro_min_quantity', true )); ?>">
            </p>
            <p class="form-field">
                <label><?php esc_html_e( 'Maximum Quantity', 'product-quantity-dropdown-for-woocommerce' ); ?></label>
                <input type="number" name="pqdfw_pro_max_quantity" value="<?php echo esc_attr(get_post_meta( get_the_ID(), 'pqdfw_pro_max_quantity', true )); ?>">
            </p>
            <p class="form-field">
                <label><?php esc_html_e( 'Step', 'product-quantity-dropdown-for-woocommerce' ); ?></label>
                <input type="number" name="pqdfw_pro_step_quantity" value="<?php echo esc_attr(get_post_meta( get_the_ID(), 'pqdfw_pro_step_quantity', true )); ?>">
            </p>
        </div>
    </div>
    <?php
}


add_action( 'woocommerce_process_product_meta',  'pqdfw_save_proddata_custom_fields' );
function pqdfw_save_proddata_custom_fields( $post_id ) {

    $pqdfw_pro_max_quantity = sanitize_text_field( $_POST['pqdfw_pro_max_quantity'] );
    update_post_meta( $post_id, 'pqdfw_pro_max_quantity', $pqdfw_pro_max_quantity );

    $pqdfw_pro_min_quantity = sanitize_text_field( $_REQUEST['pqdfw_pro_min_quantity'] );
    update_post_meta($post_id, 'pqdfw_pro_min_quantity', $pqdfw_pro_min_quantity);

    $pqdfw_pro_step_quantity = sanitize_text_field( $_POST['pqdfw_pro_step_quantity'] );
    update_post_meta( $post_id, 'pqdfw_pro_step_quantity', $pqdfw_pro_step_quantity );

}