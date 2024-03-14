<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="exclude_include" class="postbox elex-gpf-table-box elex-gpf-table-box-main ">
	<h1>
		<?php esc_html_e( 'Filtering Options', 'elex-product-feed' ); ?>
	</h1>
	<table id="elex_exclusion_inclusion" class="elex-gpf-settings-table">
		<tr>
			<td class="elex-gpf-settings-table-exclude-left">
				<?php esc_html_e( 'Stock Quantity', 'elex-product-feed' ); ?>
			</td>
			<td class='elex-gpf-settings-table-exclude-middle'>
				<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Specify the stock quantity to filter the products. Please select a condition from the dropdown before entering the value.', 'elex-product-feed' ); ?>'></span>
			</td>
			<td class="elex-gpf-settings-table-exclude-right">
				<select id="elex_gpf_exclude_stock">
					<option value="">--</option>
					<option value="equals">Equals to</option>
					<option value="greater_than">Greater than or Equal to</option>
					<option value="less_than">Less than or Equal to</option>
				</select>
				<input type="number" id="elex_gpf_stock_quantity">
			</td>
		</tr>
		<tr>
			<td class="elex-gpf-settings-table-exclude-left">
				<?php esc_html_e( 'Sold Quantities', 'elex-product-feed' ); ?>
			</td>
			<td class='elex-gpf-settings-table-exclude-middle'>
				<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Specify the number of purchases required for a product to be included in the feed. Please select a condition from the dropdown before entering the value.', 'elex-product-feed' ); ?>'></span>
			</td>
			<td class="elex-gpf-settings-table-exclude-right">
				<select id="elex_gpf_exclude_sold_quantity">
					<option value="">--</option>
					<option value="equals">Equals to</option>
					<option value="greater_than">Greater than or Equal to</option>
					<option value="less_than">Less than or Equal to</option>
				</select>
				<input type="number" id="elex_gpf_sold_quantity">
				
			</td>
		</tr>
		<tr>
		<td >
		<?php esc_html_e( 'Stock Status', 'elex-product-feed' ); ?>
		</td>
		<td>
			<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Select Stock Status for products which you want to include in the feed.', 'elex-product-feed' ); ?>'></span>
		</td>
		<td>
			<select id ="stock_status" class="class_stock_status form-select chosen-select " multiple style="width: 50%;height:30px"  data-placeholder="<?php esc_attr_e( 'Choose stock status', 'elex-product-feed' ); ?>" >
			<option value="instock"><?php esc_html_e( 'In stock', 'elex-product-feed' ); ?></option>
			<option value="outofstock"><?php esc_html_e( 'Out Of stock', 'elex-product-feed' ); ?></option>
			<option value="onbackorder"><?php esc_html_e( 'Back Order', 'elex-product-feed' ); ?></option>
			</select>
		</td>
		</tr>
		<tr id="elex_gpf_featured_div">
			<td class="elex-gpf-settings-table-left">
				<?php esc_html_e( 'Featured Products', 'elex-product-feed' ); ?>
			</td>
			<td class='elex-gpf-settings-table-middle'>
				<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Enable this to include only products marked as "Featured" to the Google Shopping feed.', 'elex-product-feed' ); ?>'></span>
			</td>
			<td class="elex-gpf-settings-table-right">
				<input id="include_featured" type="checkbox">
			</td>
		</tr>
		<?php
		if ( in_array( 'dokan-lite/dokan.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			?>
			<tr>
				<td class="elex-gpf-settings-table-exclude-left">
				   <?php esc_html_e( 'Vendors', 'elex-product-feed' ); ?>
				</td>
				<td class='elex-gpf-settings-table-exclude-middle'>
					<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Select vendor(s) to filter the products.', 'elex-product-feed' ); ?>'></span>
				</td>
				<td class="elex-gpf-settings-table-exclude-right">
				   <?php
					$all_users = get_users();
					?>
					<span><select data-placeholder='<?php esc_html_e( 'Select Vendors', 'elex-product-feed' ); ?>' multiple class="elex-gpf-multiple-chosen" id="elex_gpf_vendors">
						   <?php
							foreach ( $all_users as $key => $val ) {
								if ( in_array( 'seller', $val->roles ) ) {
									echo '<option value="' . esc_html( $val->data->ID ) . '">' . esc_html( $val->data->display_name ) . '</option>';
								}
							}
							?>
						</select></span>
				</td>
			</tr>

			<?php
		}
		?>
		<tr>
			<td class="elex-gpf-settings-table-exclude-left">
			 <h1>
				<?php esc_html_e( 'Exclusion', 'elex-product-feed' ); ?>
			</h1>
		</td>
		</tr>
		<tr>
		<td class="elex-gpf-settings-table-exclude-left">
			<?php esc_html_e( 'Exclude Products', 'elex-product-feed' ); ?>
		</td>
		<td class='elex-gpf-settings-table-exclude-middle'>
			<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Start typing in the field to choose product(s) to exclude.', 'elex-product-feed' ); ?>'></span>
		</td>
		<td class="elex-gpf-settings-table-exclude-right">
			<select class="wc-product-search" multiple="multiple" style="width: 50%;height:30px" id="elex_exclude_products" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'elex-product-feed' ); ?>" data-action="woocommerce_json_search_products_and_variations"></select>
		</td>
		</tr>
	</table>
	<button id="exclude_back_button" class="botton button-large button-primary">Back</button>
	<button id="generate_feed_button" class="botton button-large button-primary">Generate Feed</button>
</div>
<?php
include_once ELEX_PRODUCT_FEED_TEMPLATE_PATH . '/elex-show-update-log.php';
