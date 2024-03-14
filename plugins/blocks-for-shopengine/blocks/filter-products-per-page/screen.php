<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
    <form action="" method="get" class="shopengine-filter shopengine-products-per-page">
		<?php
		$lists = is_string($settings['shopengine_ppp_list']['desktop']) ? $settings['shopengine_ppp_list']['desktop'] : '9, 12, 18, 24';
		$lists = explode(',', $lists);
		if($lists) {
			foreach($lists as $list) {
				$list       = (int)$list;
				$is_checked = get_query_var('posts_per_page') === $list ? 'checked' : '';
				?>
                <label>
                    <input type="radio" name="shopengine_products_per_page"
                           value="<?php echo esc_attr($list); ?>" <?php echo esc_attr($is_checked); ?>>
                    <span><?php echo esc_html($list); ?></span>
                </label>
				<?php
			}
		}
		?>
    </form>
</div>
