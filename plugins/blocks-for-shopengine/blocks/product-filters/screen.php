<?php
defined('ABSPATH') || exit;
require_once "helper.php";

use ShopEngine\Widgets\Products;

$price_min = $settings['shopengine_filter_price_min']['desktop'] ? $settings['shopengine_filter_price_min']['desktop'] : 0;
$price_max = $settings['shopengine_filter_price_max']['desktop'] ? $settings['shopengine_filter_price_max']['desktop'] : 9999;

$default_range = apply_filters('shopengine_filter_price_range', [$price_min, $price_max]);
$dot_type = $settings['shopengine_range_slider_dot_type']['desktop'] ? 'dot-type-' . $settings['shopengine_range_slider_dot_type']['desktop'] : 'dot-type-square';
$filter_align = $settings['shopengine_filter_toggle_button_toggler_alignment']['desktop'] ? 'shopengine-filter--align-' . $settings['shopengine_filter_toggle_button_toggler_alignment']['desktop'] : 'shopengine-filter--align-left';
$off_canvas = $settings['shopengine_filter_offcanvas']['desktop'] === true ? 'shopengine-filter--offcanvas-yes' : '';

?>
<div class="<?php echo esc_attr($dot_type . ' ' . $filter_align . ' ' . $off_canvas); ?>">
	<div class="shopengine shopengine-widget">
		<div class="shopengine-product-filters">
			<?php if ($settings['shopengine_filter_toggle_button']['desktop'] === true) : ?>
				<div class="shopengine-filter-group">
					<div class="shopengine-filter-group-toggle-wrapper">
						<!-- Filter button trigger -->
						<button type="button" class="shopengine-btn shopengine-filter-group-toggle" data-target="#shopengine-filter-group-content" id="shopengine-filter-group-toggle">
							<?php if ($settings['shopengine_filter_toggler_icon_status']['desktop']) : ?>
								<!-- Left Icon -->
								<?php if ($settings['shopengine_filter_toggler_icon_position']['desktop'] == 'left') :
									render_icon($settings['shopengine_pstock_in_stock_icon']['desktop'], ['aria-hidden' => 'true']);
								endif; ?>

								<?php echo esc_html($settings['shopengine_filter_toggle_button_toggler']['desktop']); ?>

								<!-- Right Icon -->
								<?php if ($settings['shopengine_filter_toggler_icon_position']['desktop'] == 'right') :
									render_icon($settings['shopengine_pstock_in_stock_icon']['desktop'], ['aria-hidden' => 'true']);
								endif; ?>
							<?php else :
								echo esc_html($settings['shopengine_filter_toggle_button_toggler']['desktop']);
							endif; ?>
						</button>
					</div>
					<div id="shopengine-filter-group-content" class="shopengine-filter-group-content-wrapper">
						<?php if($settings['shopengine_filter_offcanvas_overlay']['desktop']): ?>
							<div class="shopengine-filter-overlay"></div>
						<?php endif; ?>
						<div class="shopengine-filter-group-content">
						<?php endif; ?>

						<!-- FILTERS START -->
						<div class="shopengine-product-filters-wrapper" data-filter-price="<?php echo esc_attr($settings['shopengine_filter_toggle_price']['desktop'] === true ? 'yes' : ''); ?>" data-filter-rating="<?php echo esc_attr($settings['shopengine_filter_toggle_rating']['desktop'] === true ? 'yes' : ''); ?>" data-filter-color="<?php echo esc_attr($settings['shopengine_filter_toggle_color']['desktop'] === true ? 'yes' : ''); ?>" data-filter-category="<?php echo esc_attr($settings['shopengine_filter_toggle_category']['desktop'] === true ? 'yes' : ''); ?>" data-filter-attribute="<?php echo esc_attr($settings['shopengine_enable_attribute']['desktop'] === true ? 'yes' : ''); ?>" data-filter-label="<?php echo esc_attr($settings['shopengine_enable_label']['desktop'] === true ? 'yes' : ''); ?>" data-filter-image="<?php echo esc_attr($settings['shopengine_enable_image']['desktop'] === true ? 'yes' : ''); ?>" data-filter-shipping="<?php echo esc_attr($settings['shopengine_enable_shipping']['desktop'] === true ? 'yes' : ''); ?>" data-filter-stock="<?php echo esc_attr($settings['shopengine_enable_stock']['desktop'] === true ? 'yes' : ''); ?>" data-filter-onsale="<?php echo esc_attr($settings['shopengine_enable_onsale']['desktop'] === true ? 'yes' : ''); ?>" data-filter-view-mode="<?php echo esc_attr($settings['shopengine_filter_view_mode']['desktop'] ?  $settings['shopengine_filter_view_mode']['desktop']: ''); ?>">
							<?php
							if (true === $settings['shopengine_filter_toggle_price']['desktop']) {
								include_once 'filters/price.php';
							}

							if (true === $settings['shopengine_filter_toggle_rating']['desktop']) {
								$tplRating = 'filters/rating.php';
								include $tplRating;
							}

							if (true === $settings['shopengine_filter_toggle_color']['desktop']) {

								$color_options = Products::instance()->get_all_color_terms();

								if (!empty($color_options)) {

									$tplColor = 'filters/color.php';

									include $tplColor;
								}
							}

							if (true === $settings['shopengine_filter_toggle_category']['desktop']) {

								$orderby = isset($settings['shopengine_filter_category_orderby']['desktop']) ? $settings['shopengine_filter_category_orderby']['desktop'] : 'name';
								$hierarchical = isset($settings['shopengine_filter_category_hierarchical']['desktop']) ? $settings['shopengine_filter_category_hierarchical']['desktop'] : true;
								$show_parent_only = isset($settings['shopengine_filter_category_show_parent_only']['desktop']) ? $settings['shopengine_filter_category_show_parent_only']['desktop'] : '';
								$hide_empty = isset($settings['shopengine_filter_category_hide_empty']['desktop']) ? $settings['shopengine_filter_category_hide_empty']['desktop'] : false;

								$args = [
									'hide_empty'	=> $hide_empty,
								];

								if ($hierarchical || $show_parent_only) {
									$args['hierarchical'] = $hierarchical;
									$args['parent'] = 0;
								}

								if ('order' === $orderby) {
									$args['orderby'] = 'meta_value_num';
									$args['meta_key'] = 'order';
								} else {
									$args['orderby'] = 'name';
									$args['order'] = 'ASC';
								}

								$product_categories = get_terms('product_cat', $args);

								$tplCategory = 'filters/category.php';

								include $tplCategory;
							}

							if (true === $settings['shopengine_enable_image']['desktop']) {

								$image_options = Products::instance()->get_all_image_terms();

								if (!empty($image_options)) {

									$tplImage = 'filters/image.php';

									include $tplImage;
								}
							}

							if (true === $settings['shopengine_enable_label']['desktop']) {

								$label_options = Products::instance()->get_all_label_terms();

								if (!empty($label_options)) {

									$tplLabel = 'filters/label.php';

									include $tplLabel;
								}
							}

							if (isset($settings['shopengine_enable_attribute']['desktop']) && $settings['shopengine_enable_attribute']['desktop'] === true) {

								$tplAttribute = 'filters/attribute.php';

								include $tplAttribute;
							}

							if (isset($settings['shopengine_enable_shipping']['desktop']) && $settings['shopengine_enable_shipping']['desktop'] === true) {

								$tplShipping = 'filters/shipping.php';

								include $tplShipping;
							}

							if (isset($settings['shopengine_enable_stock']['desktop']) && $settings['shopengine_enable_stock']['desktop'] === true) {

								$tplStock = 'filters/stock.php';

								include $tplStock;
							}

							if (isset($settings['shopengine_enable_onsale']['desktop']) && $settings['shopengine_enable_onsale']['desktop'] === true) {

								$tplOnSale = 'filters/onsale.php';

								include $tplOnSale;
							}

							?>
						</div>
						<!-- FILTERS END -->

						<?php if ($settings['shopengine_filter_toggle_button']['desktop'] === true) : ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>