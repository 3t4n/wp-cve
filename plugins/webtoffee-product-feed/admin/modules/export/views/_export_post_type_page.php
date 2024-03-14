<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="wt_pf_export_main">
	<p><?php echo $step_info['description']; ?></p>
	<div class="wt_pf_warn wt_pf_post_type_wrn" style="display:none;">
		<?php _e('Please select a post type'); ?>
	</div>
	<table class="form-table wt-pfd-form-table">
		<tr>
			<th><label><?php _e('File name', 'webtoffee-product-feed'); ?></label></th>
			<td>
				<input required type="text" name="wt_pf_export_catalog_name" value="<?php echo $item_filename; ?>" id="wt_pf_export_catalog_name"/>
                                <span class="wt-pfd_form_help"><?php esc_html_e('Enter a unique file name.', 'webtoffee-product-feed'); ?></span>
			</td>
			<td></td>
		</tr>
		<tr>
			<th><label><?php _e('Country', 'webtoffee-product-feed'); ?></label></th>
			<td>

				<?php
				global $woocommerce;
				if( class_exists( 'WC_Countries' ) ){
					$countries_obj = new WC_Countries();
					$countries = $countries_obj->__get('countries');
				}else{
					$countries = array();
				}

				?>


				<select name="wt_pf_export_catalog_country" id="wt_pf_export_catalog_country">
					<?php
					foreach ($countries as $key => $value) {
						?>
						<option value="<?php echo $key; ?>" <?php echo ($item_country == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
						<?php
					}
					?>
				</select>
                            <span class="wt-pfd_form_help"><?php esc_html_e('Choose the country for which you want to generate the feed.', 'webtoffee-product-feed'); ?></span>
			</td>
			<td></td>
		</tr>
		<tr>
			<th><label><?php _e('Channel'); ?></label></th>
			<td>
				<select name="wt_pf_export_post_type">
                                        <?php
                                        foreach ($post_types as $key => $value) {
                                          ?>
                                            <option value="<?php echo $key; ?>" <?php echo ($item_type == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                                          <?php
					}
					?>
				</select>
                            <span class="wt-pfd_form_help"><?php esc_html_e('Choose the sale channel for which you\'d like to generate the feed.', 'webtoffee-product-feed'); ?></span>
			</td>
			<td></td>
		</tr>
		
		
                
<tr class="wt-feed-filter-section">
            <th><label><?php esc_html_e('Categories', 'webtoffee-product-feed'); ?></label>
            </th>
            <td style="width: 100px;">
                <?php
                $cat_filter_type = array(
                    'include_cat' => __('Include', 'webtoffee-product-feed'),
                    'exclude_cat' => __('Exclude', 'webtoffee-product-feed'),
                );
                ?>
                <select name="wt_pf_export_cat_filter_type" id="wt_pf_export_cat_filter_type" style="width: 100px;">
                <?php
                foreach ($cat_filter_type as $key => $value) {
                    ?>
                        <option value="<?php echo esc_html($key); ?>" <?php echo ($item_cat_filter_type == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pfd_form_help"><?php esc_html_e('Choose a category filter', 'webtoffee-product-feed'); ?></span>
            </td> 
        </tr>
                <tr>
            <th></th>
            <td class="wt-feed-filter-section-td" style="width:35% !important;">
                <select name="wt_pf_inc_exc_category" id="wt_pf_inc_exc_category" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __('Select product category&hellip;', 'webtoffee-product-feed'); ?>" style="width:35% !important;" >
                <?php
                $product_categories = Webtoffee_Product_Feed_Sync_Common_Helper::get_product_categories();
                foreach ($product_categories as $key => $category ) {
                    ?>
                    <option value="<?php echo $category['slug']; ?>" <?php if( !empty($inc_exc_cat) && in_array($category['slug'], $inc_exc_cat) ){ echo 'selected'; }else{ echo ''; } ?> ><?php echo esc_attr($category['name']); ?></option>								
                    <?php
                }
                ?>

                </select>
                <span class="wt-pfd_form_help"><?php esc_html_e('Search and add one or more categories.', 'webtoffee-product-feed'); ?></span>
            </td>                                                    
        </tr>        
                
                
                
		<tr>
			<th><label><?php _e('Auto-refresh'); ?></label></th>
			<td>

				<?php
				$regenerate_intervals = apply_filters( 'wt_pf_catalog_regenerate_interval', array(
					'hourly' => __('Hourly', 'webtoffee-product-feed'),
					'daily' => __('Daily', 'webtoffee-product-feed'),
					'weekly' => __('Weekly', 'webtoffee-product-feed'),
					'monthly' => __('Monthly', 'webtoffee-product-feed'),
					'manual' => __('No Refresh', 'webtoffee-product-feed'),
				));
				?>
				<select name="wt_pf_export_catalog_interval" id="wt_pf_export_catalog_interval">
					<?php
					foreach ($regenerate_intervals as $key => $value) {
						?>
						<option value="<?php echo $key; ?>" <?php echo ($item_gen_interval == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
						<?php
					}
					?>
				</select>
                            <span class="wt-pfd_form_help"><?php esc_html_e('Choose a suitable interval for refreshing the feed. Select No Refresh to disable the feed\'s auto-refresh.', 'webtoffee-product-feed'); ?></span>
			</td>
			<td></td>
		</tr>		
	</table>
	<br/>
</div>
