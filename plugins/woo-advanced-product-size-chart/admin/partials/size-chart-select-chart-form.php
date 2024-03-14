<?php
/**
 * Provide a admin area form view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    size-chart-for-woocommerce
 * @subpackage size-chart-for-woocommerce/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Add an nonce field so we can check for it later.
wp_nonce_field( 'size_chart_select_custom_box', 'size_chart_select_custom_box' );

// Use get_post_meta to retrieve an existing value of chart from the database.
global $post;
$chart_id = scfw_size_chart_get_product( $post->ID );
$chart_id = (is_array($chart_id)) ? $chart_id : [$chart_id];
?>

<div id="size-chart-meta-fields">
    <div class="field">
        <div class="field-item">
            <label for="prod-chart"></label>
            <select name="prod-chart[]" id="prod-chart" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Type the size chart name', 'size-chart-for-woocommerce' ); ?>" data-minimum_input_length="3" data-nonce="<?php echo esc_attr( wp_create_nonce( 'size_chart_search_nonce' ) ); ?>">
				<?php
				if ( isset( $chart_id ) && is_array( $chart_id ) ) {
					foreach( $chart_id as $chart_val ) {
						printf(
							"<option data-url='#' value='%s' selected><span>%s</span></option>",
							esc_attr( $chart_val ),
							esc_html( get_the_title( $chart_val ) )
						);
					}
				}
				?>
            </select>
        </div>
    </div>
</div>
