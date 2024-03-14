<?php

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Quick_Bulk_Edit', false)) {

    class WC_iZettle_Quick_Bulk_Edit
    {

        public function __construct()
        {
            add_filter('handle_bulk_actions-edit-product', array($this, 'handle_bulk_actions'), 10, 3);
            add_filter('bulk_actions-edit-product', array($this, 'define_bulk_actions'));

            add_action('woocommerce_product_bulk_edit_end', array($this, 'bulk_edit_fields'));
            add_action('woocommerce_product_quick_edit_end', array($this, 'quick_edit_fields'));
            add_action('woocommerce_product_bulk_edit_save', array($this, 'bulk_edit_save'));
            add_action('woocommerce_product_quick_edit_save', array($this, 'quick_edit_save'));
            add_action('manage_product_posts_custom_column', array($this, 'generate_data'), 100, 2);
        }

        public function bulk_edit_fields()
        {
            ?>
            <label>
			<span class="title"><?php _e('Exclude from Zettle', 'woo-izettle-integration');?></span>
			<span class="input-text-wrap">
				<select class="izettle_nosync" name="_izettle_nosync">
					<?php $options = array(
                '' => __('— No change —', 'woo-izettle-integration'),
                'yes' => __('Yes', 'woo-izettle-integration'),
                'no' => __('No', 'woo-izettle-integration'),
            );
            foreach ($options as $key => $value) {
                echo '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
            }
            ?>
				</select>
			</span>
		</label>
        <?php }

        public function quick_edit_fields()
        {
            ?>
                <div class="inline-edit-group izettle_nosync_field">
                    <label class="izettle_nosync">
                        <input type="checkbox" name="_izettle_nosync" value="1">
                        <span class="checkbox-title"><?php esc_html_e('Exclude from Zettle', 'woo-izettle-integration');?></span>
                    </label>
                </div>
                <div class="price_fields">
			<label>
				<span class="title"><?php esc_html_e('Zettle Price', 'woo-izettle-integration');?></span>
				<span class="input-text-wrap">
					<input type="text" name="_izettle_special_price" class="text wc_input_price izettle_special_price" placeholder="<?php esc_attr_e('Zettle Price', 'woo-izettle-integration');?>" value="">
				</span>
			</label>
			<br class="clear" />
			<label>
				<span class="title"><?php esc_html_e('Zettle Cost price', 'woo-izettle-integration');?></span>
				<span class="input-text-wrap">
					<input type="text" name="_izettle_cost_price" class="text wc_input_price izettle_cost_price" placeholder="<?php esc_attr_e('Zettle Cost price', 'woo-izettle-integration');?>" value="">
				</span>
			</label>
			<br class="clear" />
		</div>
            <?php }

        /*
         * Bulk Edit Save
         */

        public function bulk_edit_save($product)
        {

            $product_id = $product->get_id();

            // update checkbox
            if (isset($_REQUEST['_izettle_nosync'])) {
                if ('yes' == $_REQUEST['_izettle_nosync']) {
                    update_post_meta($product_id, '_izettle_nosync', 'yes');
                } elseif ('no' == $_REQUEST['_izettle_nosync']) {
                    update_post_meta($product_id, '_izettle_nosync', '');
                }

            }

        }

        /*
         * Quick Edit Save
         */

        public function quick_edit_save($product)
        {

            $product_id = $product->get_id();

            // update the price
            if (isset($_REQUEST['_izettle_special_price'])) {
                update_post_meta($product_id, '_izettle_special_price', $_REQUEST['_izettle_special_price']);
            }

            // update the cost price
            if (isset($_REQUEST['_izettle_cost_price'])) {
                update_post_meta($product_id, '_izettle_cost_price', $_REQUEST['_izettle_cost_price']);
            }

            // update checkbox
            if (isset($_REQUEST['_izettle_nosync'])) {
                update_post_meta($product_id, '_izettle_nosync', 'yes');
            } else {
                update_post_meta($product_id, '_izettle_nosync', '');
            }

        }

        public function generate_data($column, $post_id)
        {
            switch ($column) {
                case 'name':
                    echo '
                    <div class="hidden" id="izettle_inline_' . absint($post_id) . '">
                    <div class="izettle_nosync">' . esc_html(wc_bool_to_string('yes' == get_post_meta($post_id, '_izettle_nosync', true))) . '</div>
                    <div class="izettle_cost_price">' . esc_html(get_post_meta($post_id, '_izettle_cost_price', true)) . '</div>
                    <div class="izettle_special_price">' . esc_html(get_post_meta($post_id, '_izettle_special_price', true)) . '</div>
                    </div>
                    ';
                    break;
            }
        }

        public function handle_bulk_actions($redirect_to, $action, $ids)
        {
            if ('izettle_clean_product_meta' == $action) {
                foreach (array_reverse($ids) as $product_id) {
                    as_schedule_single_action(as_get_datetime_object(), 'wciz_remove_product_data', array($product_id));
                }
                
                IZ_Notice::add('Clearing Zettle meta data data on selected WooCommerce products. This might take a few minutes...', 'success');
            }
            return esc_url_raw($redirect_to);
        }

        public function define_bulk_actions($actions)
        {
            if ('yes' == get_option('izettle_allow_metadata_deletion')) {
                $actions['izettle_clean_product_meta'] = __('Clean Zettle meta from product', 'woo-izettle-integration');
            }
            return $actions;
        }

    }
    new WC_iZettle_Quick_Bulk_Edit();
}