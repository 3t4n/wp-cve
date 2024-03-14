<?php if (!empty($order_statuses) && is_array($order_statuses) && !empty($order_counts_by_status) && is_array($order_counts_by_status)) : ?>
    <ul>
        <li><a href="javascript:;" data-status="all" class="wobel-bulk-edit-status-filter-item all"><?php esc_html_e('All', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> (<?php echo isset($order_counts_by_status['all']) ? intval($order_counts_by_status['all']) : 0 ?>)</a></li>
        <?php foreach ($order_statuses as $status_key => $status_label) : ?>
            <?php if (isset($order_counts_by_status[$status_key])) : ?>
                <li><a href="javascript:;" data-status="<?php echo esc_attr($status_key); ?>" class="wobel-bulk-edit-status-filter-item <?php echo esc_attr($status_key); ?>"><?php echo esc_html($status_label . ' (' . $order_counts_by_status[$status_key] . ')'); ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>