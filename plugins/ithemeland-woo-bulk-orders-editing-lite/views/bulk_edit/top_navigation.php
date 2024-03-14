<div class="wobef-top-nav">
    <div class="wobef-top-nav-buttons" id="wobef-bulk-edit-navigation">
        <div class="wobef-top-nav-buttons-group">
            <button type="button" id="wobef-bulk-edit-bulk-edit-btn" data-toggle="modal" data-target="#wobef-modal-bulk-edit" class="wobef-button-blue" data-fetch-order="<?php echo (isset($settings['fetch_data_in_bulk'])) ? esc_attr($settings['fetch_data_in_bulk']) : ''; ?>">
                <?php esc_html_e('Bulk Edit', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
            </button>
        </div>
        <div class="wobef-top-nav-buttons-border"></div>
        <div class="wobef-top-nav-buttons-group">
            <button type="button" data-toggle="modal" data-target="#wobef-modal-column-profiles">
                <?php esc_html_e('Column Profile', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
            </button>
            <button type="button" data-toggle="modal" data-target="#wobef-modal-filter-profiles">
                <?php esc_html_e('Filter Profiles', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
            </button>
            <?php $reset_filters_visibility = (!empty($filter_profile_use_always) && $filter_profile_use_always != 'default') ? "display:inline-table" : "display:none"; ?>
            <button type="button" id="wobef-bulk-edit-reset-filter" class="wobef-button-blue" <?php echo 'style="' . esc_attr($reset_filters_visibility) . '"'; ?>>
                <?php esc_html_e('Reset Filter', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
            </button>
        </div>
        <div class="wobef-top-nav-buttons-border"></div>
        <div class="wobef-top-nav-buttons-group">
            <button type="button" title="Undo latest history" id="" class="wobef-button-blue" disabled="disabled">
                <?php esc_html_e('Undo', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
            </button>
            <button type="button" title="Redo" class="wobef-button-blue" id="" disabled="disabled">
                <?php esc_html_e('Redo', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
            </button>
            <button type="button" data-toggle="modal" data-target="#wobef-modal-new-item"><?php esc_html_e('New Order', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></button>
        </div>
        <div class="wobef-top-nav-buttons-border"></div>
        <div class="wobef-bulk-edit-form-selection-tools">
            <div class="wobef-top-nav-buttons-group">
                <button type="button" id="wobef-bulk-edit-unselect"><?php esc_html_e('Unselect', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></button>
                <button type="button" id="wobef-bulk-edit-duplicate" data-toggle="modal" data-target="#wobef-modal-item-duplicate"><?php esc_html_e('Duplicate', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                </button>
                <div class="wobef-bulk-edit-delete-item">
                    <span>
                        <?php esc_html_e('Delete', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                        <i class="lni lni-chevron-down"></i>
                    </span>
                    <div class="wobef-bulk-edit-delete-item-buttons" style="display: none;">
                        <ul>
                            <li class="wobef-bulk-edit-delete-action" data-delete-type="trash">
                                <?php esc_html_e('Move to trash', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                            </li>
                            <li class="wobef-bulk-edit-delete-action" data-delete-type="permanently">
                                <?php esc_html_e('Permanently', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="wobef-top-nav-buttons-border"></div>
        </div>
        <?php if (!empty($order_statuses) && is_array($order_statuses)) : ?>
            <div class="wobef-top-nav-buttons-group">
                <select id="wobef-bulk-edit-change-status" title="<?php esc_html_e('Change Order Status', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
                    <option value=""><?php esc_html_e('Change Status', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                    <?php foreach ($order_statuses as $status_key => $status_label) : ?>
                        <option value="<?php echo sanitize_text_field($status_key); ?>"><?php echo sanitize_text_field($status_label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        <div class="wobef-top-nav-buttons-group">
            <label>
                <input type="checkbox" id="wobef-inline-edit-bind">
                <?php esc_html_e('Bind Edit', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                <i title="Set the value of edited order to all selected orders" class="dashicons dashicons-info"></i>
            </label>
        </div>
    </div>
    <div class="wobef-top-nav-filters">
        <div class="wobef-top-nav-status-filter"></div>
        <div class="wobef-top-nav-filters-left">
            <div class="wobef-top-nav-filters-per-page">
                <select id="wobef-quick-per-page" title="The number of orders per page">
                    <?php foreach (wobef\classes\helpers\Setting::get_count_per_page_items() as $count_per_page_item) : ?>
                        <option value="<?php echo intval(esc_attr($count_per_page_item)); ?>" <?php if (isset($current_settings['count_per_page']) && $current_settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                            <?php echo esc_html($count_per_page_item); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (isset($settings['show_quick_search']) && $settings['show_quick_search'] == 'yes') : ?>
                <?php $quick_search_input = (isset($last_filter_data) && !empty($last_filter_data['search_type']) && $last_filter_data['search_type'] == 'quick_search') ? $last_filter_data : ''; ?>
                <div class="wobef-top-nav-filters-search">
                    <input type="text" id="wobef-quick-search-text" placeholder="<?php esc_html_e('Quick Search ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" title="Quick Search" value="<?php echo (isset($quick_search_input['quick_search_text'])) ? esc_html($quick_search_input['quick_search_text']) : '' ?>">
                    <select id="wobef-quick-search-field" title="Select Field">
                        <option value="id" <?php echo (isset($quick_search_input['quick_search_field']) && $quick_search_input['quick_search_field'] == 'id') ? 'selected' : '' ?>>
                            <?php esc_html_e('ID', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                        </option>
                    </select>
                    <select id="wobef-quick-search-operator" title="Select Operator">
                        <option value="exact" <?php echo (isset($quick_search_input['quick_search_operator']) && $quick_search_input['quick_search_operator'] == 'exact') ? 'selected' : '' ?>>
                            <?php esc_html_e('Exact', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                        </option>
                    </select>
                    <button type="button" id="wobef-quick-search-button" class="wobef-filter-form-action" data-search-action="quick_search">
                        <i class="lni lni-funnel"></i>
                    </button>
                    <?php if (!empty($quick_search_input)) : ?>
                        <button type="button" id="wobef-quick-search-reset" class="wobef-button wobef-button-blue">Reset Filter</button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="wobef-items-pagination">
            <?php include 'pagination.php'; ?>
        </div>
    </div>
</div>