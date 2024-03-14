<?php
defined( 'ABSPATH' ) || exit;
?>

<?php do_action( 'woocommerce_widget_price_filter_start', $args ); ?>
<div class="price_slider_wrapper">
	<div class="price_slider" style="display:none;"></div>
	<div class="price_slider_amount" data-step="<?php echo esc_attr( $step ); ?>">
		<input type="text" id="min_price" name="min_price" value="<?php echo esc_attr( $current_min_price ); ?>"
			data-min="<?php echo esc_attr( $min_price ); ?>"
			placeholder="<?php echo esc_attr__( 'Min price', 'shopready-elementor-addon' ); ?>" />
		<input type="text" id="max_price" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>"
			data-max="<?php echo esc_attr( $max_price ); ?>"
			placeholder="<?php echo esc_attr__( 'Max price', 'shopready-elementor-addon' ); ?>" />
		<?php /* translators: Filter: verb "to filter" */ ?>
		<span class="shop-ready-pro-price-filter-text"> <?php echo esc_html__( 'Filter', 'shopready-elementor-addon' ); ?>
		</span>
		<div class="price_label" style="display:none;">
			<?php echo esc_html__( 'Price:', 'shopready-elementor-addon' ); ?> <span class="from"></span> &mdash; <span
				class="to"></span>
		</div>
		<?php echo wp_kses_post( wc_query_string_form_fields( null, array( 'min_price', 'max_price', 'paged' ), '', true ) ); ?>
		<div class="clear"></div>
	</div>
</div>
<?php do_action( 'woocommerce_widget_price_filter_end', $args ); ?>
