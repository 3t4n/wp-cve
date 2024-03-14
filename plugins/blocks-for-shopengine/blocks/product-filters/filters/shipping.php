<?php
	defined('ABSPATH') || exit; // Exit if accessed directly
	
	$shipping_classes = get_terms(['taxonomy' => 'product_shipping_class', 'hide_empty' => false]);

	if ( !empty($shipping_classes) && is_array($shipping_classes) ): 
	
	$uid		= uniqid();
	$prefix		= 'shopengine_filter_shipping';
	$collapse	= false;

	if( $settings['shopengine_filter_view_mode']['desktop'] === 'collapse' ) {
		$collapse	= true;
	}
	
	$collapse_expand	= '';

	if((isset($_GET['shopengine_filter_shipping_product_shipping_class']) || 
		$settings['shopengine_filter_shipping_expand_collapse']['desktop'] === true) && 
		!empty($_GET['shipping_nonce']) && 
		wp_verify_nonce(  sanitize_text_field( wp_unslash($_GET['shipping_nonce']) ), "shipping_filter")) {
		$collapse_expand	= 'open';
	}
?>

	<div class="shopengine-filter-single <?php echo esc_attr( $collapse ? 'shopengine-collapse' : '' ) ?>">
		<div class="shopengine-filter <?php echo esc_attr( $collapse_expand ) ?>">
			<?php if(isset($settings['shopengine_filter_shipping_title']['desktop'])) : ?>
				<h3 class="shopengine-product-filter-title">
					<?php
						echo esc_html($settings['shopengine_filter_shipping_title']['desktop']);
						echo $collapse ? '<i class="eicon-chevron-right shopengine-collapse-icon"></i>' : '';
					?>
				</h3>
			<?php endif;?>
		</div>
	
		<?php echo $collapse ? '<div class="shopengine-collapse-body '. esc_attr($collapse_expand) .'">' : ''; ?>

			<?php foreach ($shipping_classes as $shipping_class): ?>
				<?php
					$id							= 'xs-filter-shipping-'.$uid . '-' . $shipping_class->slug;
					$name						=  $prefix. '_' . $shipping_class->taxonomy;
					$selector_after_page_load	= $prefix. '_'. $shipping_class->taxonomy .'-' .$shipping_class->slug;
				?>
				<div class="filter-input-group">
					<input
						class="shopengine-filter-shipping <?php echo esc_attr($selector_after_page_load) ?>"
						name="<?php echo esc_attr($name) ?>"
						type="checkbox"
						id="<?php echo esc_attr($id) ?>"
						value="<?php echo esc_attr($shipping_class->slug); ?>"
					/>
					<label class="shopengine-filter-shipping-label" for="<?php echo esc_attr($id) ?>">
						<span class="shopengine-checkbox-icon">
							<span>
								<?php render_icon($settings['shopengine_check_icon']['desktop'], ['aria-hidden' => 'true']); ?>
							</span>
						</span>
						<?php echo esc_html($shipping_class->name ); ?>
					</label>
				</div>
			<?php endforeach; ?>

		<?php echo $collapse ? '</div>' : '';?>

		<form action="" method="get" class="shopengine-filter" id="shopengine_shipping_form">
			<input type="hidden" name="<?php echo esc_attr( $prefix ) ?>" class="shopengine-filter-shipping-value">
			<input type="hidden" name="shipping_nonce" value="<?php echo esc_attr(wp_create_nonce("shipping_filter")) ?>">
		</form>
		
	</div>

<?php endif; ?>