<?php

defined('ABSPATH') || exit;

$catalog_orderby_options = [
	'menu_order' => esc_html__('Default sorting', 'shopengine-gutenberg-addon'),
	'popularity' => esc_html__('Sort by popularity', 'shopengine-gutenberg-addon'),
	'rating'     => esc_html__('Sort by average rating', 'shopengine-gutenberg-addon'),
	'date'       => esc_html__('Sort by latest', 'shopengine-gutenberg-addon'),
	'price'      => esc_html__('Sort by price: low to high', 'shopengine-gutenberg-addon'),
	'price-desc' => esc_html__('Sort by price: high to low', 'shopengine-gutenberg-addon'),
	'title'      => esc_html__('Sort by title: a to z', 'shopengine-gutenberg-addon'),
	'title-desc' => esc_html__('Sort by title: z to a', 'shopengine-gutenberg-addon'),
];

$orderby     = get_query_var('orderby');
$edit_screen = in_array($orderby, array_keys($catalog_orderby_options)) ? $orderby : 'menu_order'; // Validate OrderBy default value.
?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-filter-orderby">
        <form action="#" method="get"
              class="shopengine-filter shopengine-filter-orderby-<?php echo esc_attr($settings['shopengine_orderby_type']['desktop']); ?>">
			<?php if('dropdown' === $settings['shopengine_orderby_type']['desktop']) : ?>
                <!-- DROPDOWN STYLE -->
                <i class="fas fa-chevron-down" style="font-style: normal; pointer-events: none;"></i>
                <select name="orderby" class="orderby"
                        aria-label="<?php esc_attr_e('Shop order', 'shopengine-gutenberg-addon'); ?>">
					<?php foreach($catalog_orderby_options as $id => $name) : ?>
                        <option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
					<?php endforeach; ?>
                </select>
			<?php else : ?>
                <!-- LIST SELECT STYLE -->
				<?php foreach($catalog_orderby_options as $id => $name) : ?>
                    <div class="orderby-input-group">
                        <input name="orderby" class="orderby" type="radio"
                               id="orderby-<?php echo esc_attr($id); ?>"
                               aria-label="<?php echo esc_attr__('Shop order', 'shopengine-gutenberg-addon'); ?>"
							<?php checked($orderby, $id); ?>
                               value="<?php echo esc_attr($id); ?>"/>
                        <label for="orderby-<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></label>
                    </div>
				<?php endforeach; ?>
			<?php endif; ?>
        </form>
    </div>
</div>
