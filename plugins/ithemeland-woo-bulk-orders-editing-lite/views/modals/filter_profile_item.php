<?php if (!empty($filter_item)) : ?>
    <tr class="<?php echo (isset($filter_profile_use_always) && $filter_profile_use_always == $filter_item['key']) ? 'wobel-filter-profile-loaded' : ''; ?>">
        <td>
            <span class="wobel-history-name"><?php echo esc_html($filter_item['name']); ?></span>
        </td>
        <td><?php echo esc_html(date('Y M d', strtotime($filter_item['date_modified']))); ?></td>
        <td>
            <input type="radio" class="wobel-filter-profile-use-always-item" name="use_always" value="<?php echo esc_attr($filter_item['key']); ?>" <?php echo (isset($filter_profile_use_always) && $filter_profile_use_always == $filter_item['key']) ? 'checked="checked"' : ''; ?> title="<?php esc_html_e('Use it constantly', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
        </td>
        <td>
            <button type="button" class="wobel-button wobel-button-blue wobel-bulk-edit-filter-profile-load" value="<?php echo esc_attr($filter_item['key']); ?>">
                <i class="wobel-icon-download-cloud"></i>
                Load
            </button>
            <?php if ($filter_item['key'] != 'default') : ?>
                <button type="button" class="wobel-button wobel-button-red wobel-bulk-edit-filter-profile-delete" value="<?php echo esc_attr($filter_item['key']); ?>">
                    <i class="wobel-icon-trash-2"></i>
                    <?php esc_html_e('Delete', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                </button>
            <?php endif; ?>
        </td>
    </tr>
<?php endif; ?>