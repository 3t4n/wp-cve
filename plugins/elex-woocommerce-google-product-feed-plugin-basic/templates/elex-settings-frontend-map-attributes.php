<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="settings_map_attributes" class="postbox elex-gpf-table-box elex-gpf-table-box-main ">
	<table>
		<tr>
		<td><h1>Map Attributes</h1></td>
		<td><span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Select product fields (attributes) corresponding to the  Google Attributes listed on the left. If you want to enter a text value, click the icon next to each field. You can also customize your product attribute values by prepending or appending other product attributes. You can also choose to set different product attributes based on specific conditions. You can add optional fields by clicking the Add Field button.', 'elex-product-feed' ); ?>'></span></td>
	</tr>
	</table>
	<h3><?php esc_html_e( 'Required Fields', 'elex-product-feed' ); ?></h3>
	<hr>
	<table id="elex_required_attr_map" class="elex-gpf-settings-table widefat">

	</table>
	<h3><?php esc_html_e( 'Optional Fields', 'elex-product-feed' ); ?></h3>
	<table id="elex_optional_attr_map" class="elex-gpf-settings-table widefat">

	</table>
	<button id="attribute_back_button" class="botton button-large button-primary">Back</button>
	<button id="save_settings_attr_map_add_new" class="botton button-large button-primary">Add Field</button>
	<button id="attribute_continue" class="botton button-large button-primary" style="margin-left: 75%;">Save & Continue</button>
</div>
<div class="wrap postbox elex-gpf-table-box elex-gpf-table-box-main" style="display: none;">
	<table>
		<tr>
		<td><h1>Block notices from other plugins</h1></td>
	</tr>
	</table>
</div>
<?php
include_once ELEX_PRODUCT_FEED_TEMPLATE_PATH . '/elex-settings-frontend-exclude-include.php';
