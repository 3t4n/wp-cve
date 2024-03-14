<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
	<div class="shopengine-product-size-chart-body">
		<?php if ($shopengine_product_size_type['desktop'] === 'modal') : ?>
			<button class="shopengine-product-size-chart-button"><?php echo esc_html($shopengine_product_size_charts_button_text['desktop']); ?></button>
			<div class="shopengine-product-size-chart" data-model="yes">
				<div class="shopengine-product-size-chart-contant">
					<img src="<?php echo esc_url($chart) ?>" alt="">
				</div>
			</div>
		<?php elseif ($shopengine_product_size_type['desktop'] === 'normal') : ?>
			<h2 class="shopengine-product-size-chart-heading"><?php echo esc_html($shopengine_product_size_charts_title_text['desktop']); ?></h2>
			<div class="shopengine-product-size-chart-img">
				<img src="<?php echo esc_url($chart) ?>" />
			</div>
		<?php endif; ?>
	</div>
</div>