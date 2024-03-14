<?php

defined('ABSPATH') || exit;

if($block->is_editor) {
	?>
    <style>
        .frontend {
            display: none;
        }
    </style>

	<?php
}
?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-breadcrumbs frontend">

		<?php
		$iconClass = isset($settings['shopengine_breadcrumbs_icon']['desktop']) && !empty($settings['shopengine_breadcrumbs_icon']['desktop']) ? $settings['shopengine_breadcrumbs_icon']['desktop'] : 'fas fa-arrow-right';

		\ShopEngine\Widgets\Widget_Helper::instance()->wc_breadcrumb_default_filter($iconClass);

		$args = [
			'delimiter'   => '<i class="' . esc_attr($iconClass) . '" aria-hidden="true"></i>',
			'wrap_before' => '<nav class="woocommerce-breadcrumb">',
			'wrap_after'  => '</nav>',
		];

		woocommerce_breadcrumb($args);
		?>

    </div>
	<?php

	if($block->is_editor) {
		wc()->frontend_includes();
		$home_id = get_option('page_on_front') ? get_option('page_on_front') : get_option('woocommerce_shop_page_id');
		?>

        <div class="shopengine-breadcrumbs editor">

            <nav class="woocommerce-breadcrumb">
                <a href="<?php echo esc_url(get_the_permalink($home_id)); ?>">
					<?php echo esc_html(get_the_title($home_id)); ?></a>
                <i class="<?php echo esc_attr($iconClass); ?>" aria-hidden="true"></i>
				<?php the_title(); ?>
            </nav>
        </div>

		<?php
	}

	?>
</div>
