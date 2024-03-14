<div class="wobel-float-side-modal" id="wobel-float-side-modal-settings">
    <div class="wobel-float-side-modal-container">
        <div class="wobel-float-side-modal-box">
            <div class="wobel-float-side-modal-content">
                <div class="wobel-float-side-modal-title">
                    <h2><?php esc_html_e('Settings', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h2>
                    <button type="button" class="wobel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" style="width: 100%; float: left; height: 100%;">
                    <input type="hidden" name="action" value="wobel_settings">
                    <div class="wobel-float-side-modal-body">
                        <div class="wobel-wrap">
                            <div class="wobel-tab-middle-content">
                                <div class="wobel-alert wobel-alert-default">
                                    <span><?php esc_html_e('You can set bulk editor settings', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-count-per-page"><?php esc_html_e('Count Per Page', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[count_per_page]" id="wobel-quick-per-page" title="The number of orders per page">
                                        <?php
                                        if (!empty($count_per_page_items)) :
                                            foreach ($count_per_page_items as $count_per_page_item) :
                                        ?>
                                                <option value="<?php echo intval(esc_attr($count_per_page_item)); ?>" <?php if (isset($settings['count_per_page']) && $settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                                                    <?php echo esc_html($count_per_page_item); ?>
                                                </option>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-default-sort-by"><?php esc_html_e('Default Sort By', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select id="wobel-settings-default-sort-by" class="wobel-input-md" name="settings[default_sort_by]">
                                        <option value="id" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'id') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('ID', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-default-sort"><?php esc_html_e('Default Sort', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[default_sort]" id="wobel-settings-default-sort" class="wobel-input-md">
                                        <option value="ASC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'ASC') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('ASC', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                        <option value="DESC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'DESC') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('DESC', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-close-popup-after-applying"><?php _e('Close popup after applying', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[close_popup_after_applying]" id="wobel-settings-close-popup-after-applying" class="wobel-input-md">
                                        <option value="yes" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php _e('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'no') ? 'selected' : ''; ?>>
                                            <?php _e('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-sticky-first-columns"><?php esc_html_e("Sticky 'ID' Column", 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[sticky_first_columns]" id="wobel-settings-sticky-first-columns" class="wobel-input-md">
                                        <option value="yes" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-display-full-columns-title"><?php esc_html_e('Display Columns Label', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[display_full_columns_title]" id="wobel-settings-display-full-columns-title" class="wobel-input-md">
                                        <option value="yes" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Completely', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('In short', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-show-customer-details"><?php esc_html_e('Customer Details Column', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[show_customer_details]" id="wobel-settings-show-customer-details" class="wobel-input-md">
                                        <option value="yes" <?php echo (isset($settings['show_customer_details']) && $settings['show_customer_details'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Show as popup', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['show_customer_details']) && $settings['show_customer_details'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Show as text', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-show-order-items-popup"><?php esc_html_e('Order Items Column', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[show_order_items_popup]" id="wobel-settings-show-order-items-popup" class="wobel-input-md">
                                        <option value="yes" <?php echo (isset($settings['show_order_items_popup']) && $settings['show_order_items_popup'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Show as popup', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['show_order_items_popup']) && $settings['show_order_items_popup'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Show as text', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-show-billing-shipping-address-popup"><?php esc_html_e('Billing/Shipping Address', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[show_billing_shipping_address_popup]" id="wobel-settings-show-billing-shipping-address-popup" class="wobel-input-md">
                                        <option value="yes" <?php echo (isset($settings['show_billing_shipping_address_popup']) && $settings['show_billing_shipping_address_popup'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Show as popup', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['show_billing_shipping_address_popup']) && $settings['show_billing_shipping_address_popup'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Show as text', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-colorize-status-column"><?php esc_html_e("Colorize 'Status' Column", 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[colorize_status_column]" id="wobel-settings-colorize-status-column" class="wobel-input-md">
                                        <option value="yes" <?php echo (isset($settings['colorize_status_column']) && $settings['colorize_status_column'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['colorize_status_column']) && $settings['colorize_status_column'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wobel-form-group">
                                    <label for="wobel-settings-keep-filled-data-in-bulk-edit-form"><?php esc_html_e('Keep filled data in bulk edit form', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select name="settings[keep_filled_data_in_bulk_edit_form]" id="wobel-settings-keep-filled-data-in-bulk-edit-form" class="wobel-input-md">
                                        <option value="yes" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wobel-float-side-modal-footer">
                        <button type="submit" class="wobel-button wobel-button-blue">
                            <?php $img = WOBEL_IMAGES_URL . 'save.svg'; ?>
                            <img src="<?php echo esc_url($img); ?>" alt="">
                            <span><?php esc_html_e('Save Changes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>