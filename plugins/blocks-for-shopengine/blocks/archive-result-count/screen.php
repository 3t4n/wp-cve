<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
    <div class="shopengine-archive-result-count">
		<?php

		\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();

		if($block->is_editor) { ?>

            <p class="woocommerce-result-count"><?php echo esc_html__('Showing all results', 'shopengine-gutenberg-addon'); ?></p> <?php

		} else {
			woocommerce_result_count();
		} ?>
    </div>
</div>




