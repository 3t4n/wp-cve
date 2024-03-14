<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    size-chart-for-woocommerce
 * @subpackage size-chart-for-woocommerce/public/includes
 * @author     Multidots
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$current_post_type = isset( $admin_post_type ) && !empty($admin_post_type) ? $admin_post_type : ''; // phpcs:ignore
$chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id ); // phpcs:ignore
$chart_label = scfw_size_chart_get_label_by_chart_id( $chart_id ); // phpcs:ignore
if ( ! isset( $size_chart_style ) || empty( $size_chart_style ) ) {
	$size_chart_style = scfw_size_chart_style_value_by_chart_id( $chart_id ); // phpcs:ignore	
}
$post_data = get_post( $chart_id ); // phpcs:ignore
$size_chart_get_sub_title_text = scfw_size_chart_get_sub_title_by_chart_id( $chart_id ); // phpcs:ignore
if ( isset($size_chart_get_sub_title_text) && !empty($size_chart_get_sub_title_text) ) {
	$size_chart_sub_title = $size_chart_get_sub_title_text;
} else {
	$size_chart_get_sub_title_text = scfw_size_chart_get_sub_title_text();
	$size_chart_sub_title = trim($size_chart_get_sub_title_text);
}

if( isset($data) && !empty($data) ){
    $chart_table = $data; // phpcs:ignore
} else {
    $chart_table = scfw_size_chart_get_chart_table_by_chart_id( $chart_id ); // phpcs:ignore
}

$is_chart_table_empty = true;
if( ( !empty($chart_position) && 'popup' === $chart_position ) || $current_post_type === 'size-chart' ) {
?>
<div class="md-size-chart-close">
    <?php
    if ( isset( $chart_label ) && ! empty( $chart_label ) ) {
        printf( '<div class="md-modal-title">%s</div>', esc_html( $chart_label ) );
    }
    ?>
    <button data-remodal-action="close"  class="remodal-close" aria-label="<?php esc_attr_e( 'Close', 'size-chart-for-woocommerce' ); ?>"></button>
</div>
<?php } 

if ( isset( $size_chart_style ) && ! empty( $size_chart_style ) && 'tab_style' === $size_chart_style ) {
	// Size chart tab title filters
	$size_guide_tab = apply_filters( 'scfw_size_guide_tab_title', 'Size Guide' );
	$chart_content_tab = apply_filters( 'scfw_chart_content_tab_title', 'How To Measure' );
	?>
	<div class="scfw_size-chart-details-tab">
		<div class="scfw_tab_underline"></div>
		<?php 
		$chart_table_arr = array();
		if ( isset( $chart_table ) && is_array( $chart_table ) ) {
			$chart_table_arr = scfw_size_chart_check_empty_array( $chart_table );	
		}
		if ( isset( $chart_table_arr ) && ! empty( $chart_table_arr ) ) {
			$is_chart_table_empty = false;
			?>
			<span class="scfw_chart-tab active-tab" data-tab="scfw_size-chart-tab-1"><?php esc_html_e( $size_guide_tab, 'size-chart-for-woocommerce' ); ?></span>
			<?php
		}

		if ( (isset( $post_data->post_content ) && ! empty( $post_data->post_content )) || (isset( $size_chart_sub_title ) && ! empty( $size_chart_sub_title )) ) {
			?>
			<span class="scfw_chart-tab <?php echo $is_chart_table_empty ? esc_attr('active-tab') : ''; ?>" data-tab="scfw_size-chart-tab-2"><?php esc_html_e( $chart_content_tab, 'size-chart-for-woocommerce' ); ?></span>
			<?php
		}
		?>
	</div>
	<?php
}
?>
<div class="chart-container" id="size-chart-id-<?php echo esc_attr( $chart_id ); // phpcs:ignore ?>">
<?php
$active_chart_class = $is_chart_table_empty ? 'active-tab' : '';
if ( $post_data->post_content ) {
	$content = wpautop($post_data->post_content);

	if (isset($size_chart_sub_title) && !empty($size_chart_sub_title)) {
		printf( '<div class="chart-content scfw-tab-content ' . esc_attr($active_chart_class) . '" id="scfw_size-chart-tab-2"><span class="md-size-chart-subtitle"><b>%s</b></span>%s</div>',
			esc_html( $size_chart_sub_title ),
			wp_kses_post( $content )
		);
	} else {
		printf( '<div class="chart-content scfw-tab-content ' . esc_attr($active_chart_class) . '" id="scfw_size-chart-tab-2">%s</div>',
			wp_kses_post( $content )
		);
	}
} else {
	if ( isset($size_chart_sub_title) && !empty($size_chart_sub_title) ) {
		printf( '<div class="chart-content scfw-tab-content ' . esc_attr($active_chart_class) . '" id="scfw_size-chart-tab-2"><span class="md-size-chart-subtitle"><b>%s</b></span></div>',
			esc_html( $size_chart_sub_title )
		);
	}
}
$chart_image_id = scfw_size_chart_get_primary_chart_image_id( $chart_id ); // phpcs:ignore
if ( $chart_image_id ) {
	$chart_image_url = wp_get_attachment_url( $chart_image_id );
	printf(
		'<div class="chart-image"><img src="%s" alt="%s" title="%s"/></div>',
		esc_url( $chart_image_url ),
		esc_attr( $post_data->post_title ),
		esc_attr( $chart_label )
	);
}

if( isset($table_style) && !empty($table_style) ){
    $table_style = $table_style;
} else {
    $table_style = '';
}

$chart_note = scfw_size_chart_popup_note( $chart_id ); // phpcs:ignore

if ( isset( $chart_table ) && array_filter( $chart_table ) ) {
	$active_chart_class = !$is_chart_table_empty ? 'active-tab' : '';
    ?>
    <div class="chart-table scfw-tab-content <?php echo esc_attr($active_chart_class); ?>" id="scfw_size-chart-tab-1">
	    <?php
	    echo wp_kses_post( scfw_size_chart_get_chart_table( $chart_table, $chart_id, $table_style ) ); // phpcs:ignore
        ?>
    </div>
    <?php 
    if( !empty( $chart_note ) ) {
         echo sprintf( wp_kses_post( '<p class="chart_note"><strong>Note: </strong>%s</p>', 'size-chart-for-woocommerce' ), wp_kses_post( $chart_note ) ); 
     }
} ?>
</div>
