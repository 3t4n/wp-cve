<?php

	defined('ABSPATH') || exit;

	$stock_status = wc_get_product_stock_status_options();

	if ( isset($stock_status) && is_array($stock_status) ): 

	$name		= 'shopengine_filter_stock';
	$collapse	= false;

	if( $settings['shopengine_filter_view_mode']['desktop'] === 'collapse' ) {
		$collapse	= true;
	}
	
	$collapse_expand	= '';

	if((isset($_GET['shopengine_filter_stock']) || 
	$settings['shopengine_filter_stock_expand_collapse']['desktop'] === true) && 
	!empty($_GET['stock_nonce']) && 
	wp_verify_nonce( sanitize_text_field( wp_unslash($_GET['stock_nonce']) ), "stock_filter")) {
		$collapse_expand	= 'open';
	}
?>

	<div class="shopengine-filter-single <?php echo esc_attr( $collapse ? 'shopengine-collapse' : '' ) ?>">
		<div class="shopengine-filter <?php echo esc_attr( $collapse_expand ) ?>">
			<?php if(isset($settings['shopengine_filter_stock_title'])) : ?>
				<h3 class="shopengine-product-filter-title">
					<?php
						echo esc_html($settings['shopengine_filter_stock_title']['desktop']);
						echo $collapse ? '<i class="eicon-chevron-right shopengine-collapse-icon"></i>' : '';
					?>
				</h3>
			<?php endif;?>
		</div>
	
		<?php 
			$collapse ? '<div class="shopengine-collapse-body '. esc_attr($collapse_expand) .'">' : '';
			echo wp_kses_post($collapse); 
		?>

			<?php foreach ($stock_status as $key => $stock): ?>
				<?php
					$id		= 'xs-filter-stock-'.$key;
				?>
				<div class="filter-input-group">
					<input
						class="shopengine-filter-stock <?php echo esc_attr($name.'-'.$key) ?>"
						name="<?php echo esc_attr($name) ?>"
						type="checkbox"
						id="<?php echo esc_attr($id) ?>"
						value="<?php echo esc_attr($key); ?>"
					/>
					<label class="shopengine-filter-stock-label" for="<?php echo esc_attr($id) ?>">
						<span class="shopengine-checkbox-icon">
							<span>
								<?php render_icon($settings['shopengine_check_icon']['desktop'], ['aria-hidden' => 'true']); ?>
							</span>
						</span>
						<?php echo esc_html($stock ); ?>
					</label>
				</div>
			<?php endforeach; ?>

		<?php echo $collapse ? '</div>' : '';?>

		<form action="" method="get" class="shopengine-filter" id="shopengine_stock_form">
			<input type="hidden" name="<?php echo esc_attr( $name ) ?>" class="shopengine-filter-stock-value">
			<input type="hidden" name="stock_nonce" value="<?php echo esc_attr(wp_create_nonce("stock_filter")) ?>">
		</form>
		
	</div>

<?php endif; ?>