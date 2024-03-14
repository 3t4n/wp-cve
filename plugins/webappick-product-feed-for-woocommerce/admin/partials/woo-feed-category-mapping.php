<?php
/**
 * Add New Category Mapping View
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/admin/partial
 * @author     Ohidul Islam <wahid@webappick.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$action   = apply_filters('CTXFEED_filter_securing_input', "GET",    @$_GET['action'],    "text");
$cmapping   = apply_filters('CTXFEED_filter_securing_input', "GET",    @$_GET['cmapping'],    "text");
$wooFeedDropDown = new Woo_Feed_Dropdown();
$value           = array();
if ( $action && $cmapping ) { // phpcs:ignore
	$option = get_option( $cmapping ); // phpcs:ignore
	$value  = maybe_unserialize( $option );
}
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Category Mapping', 'woo-feed' ); ?></h2>
	<?php WPFFWMessage()->displayMessages(); ?>
	<form action="" name="feed" id="category-mapping-form" method="post" autocomplete="off">
		<?php wp_nonce_field( 'category-mapping' ); ?>
		<table class=" widefat fixed" id="cmTable">
			<tbody>
			<tr>
				<td width="30%">
					<label for="providers"><b><?php esc_html_e( 'Merchant', 'woo-feed' ); ?> <span class="requiredIn">*</span></b></label>
				</td>
				<td>
					<select name="mappingprovider" id="providers" class="generalInput" required>
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo $wooFeedDropDown->merchantsDropdown( isset( $value['mappingprovider'] ) ? $value['mappingprovider'] : '' );
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><b><?php esc_html_e( 'Mapping Name', 'woo-feed' ); ?><span class="requiredIn">*</span></b></td>
				<td>
					<input required value="<?php echo isset( $value['mappingname'] ) ? esc_attr( $value['mappingname'] ) : ''; ?>" name="mappingname" wftitle="<?php esc_attr_e( 'Mapping Name should be unique and don\'t use space. Otherwise it will override the existing Category Mapping. Example: myMappingName or my_mapping_name', 'woo-feed' ); ?>" type="text" class="generalInput wfmasterTooltip">
				</td>
			</tr>
			</tbody>
		</table>
		<br/>
		<table class="table tree widefat fixed woo-feed-category-mapping-config-table">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Local Category', 'woo-feed' ); ?></th>
				<th><?php esc_html_e( 'Merchant Category', 'woo-feed' ); ?></th>
                <th colspan="3"></th>
			</tr>
			</thead>
			<tbody>
			<?php woo_feed_render_categories( 0, '', $value ); ?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="5">
					<button name="<?php echo isset( $_GET['action'] ) ? esc_attr( sanitize_text_field( wp_unslash($_GET['action']) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>" type="submit" class="button button-large button-primary woo-feed-btn-bg-gradient-blue"><?php esc_html_e( 'Save Mapping', 'woo-feed' ); ?></button>
				</td>
			</tr>
			</tfoot>
		</table>
	</form>
</div>