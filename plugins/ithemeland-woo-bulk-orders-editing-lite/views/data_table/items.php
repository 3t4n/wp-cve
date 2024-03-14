<table id="wobel-items-list" class="widefat">
    <thead>
        <tr>
            <?php if (isset($show_id_column) && $show_id_column === true) : ?>
                <?php
                if ('id' == $sort_by) {
                    if ($sort_type == 'ASC') {
                        $sortable_icon = "<i class='dashicons dashicons-arrow-up'></i>";
                    } else {
                        $sortable_icon = "<i class='dashicons dashicons-arrow-down'></i>";
                    }
                } else {
                    $img =  WOBEL_IMAGES_URL . "/sortable.png";
                    $sortable_icon = "<img src='" . esc_url($img) . "' alt=''>";
                }
                ?>
                <th class="wobel-td70 <?php echo ($sticky_first_columns == 'yes') ? 'wobel-td-sticky wobel-td-sticky-id' : ''; ?>">
                    <input type="checkbox" class="wobel-check-item-main" title="<?php _e('Select All', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
                    <label data-column-name="id" class="wobel-sortable-column"><?php _e('ID', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?><span class="wobel-sortable-column-icon"><?php echo sprintf('%s', $sortable_icon); ?></span></label>
                </th>
            <?php endif; ?>
            <?php if (!empty($next_static_columns)) : ?>
                <?php foreach ($next_static_columns as $static_column) : ?>
                    <?php
                    if ($static_column['field'] == $sort_by) {
                        if ($sort_type == 'ASC') {
                            $sortable_icon = "<i class='dashicons dashicons-arrow-up'></i>";
                        } else {
                            $sortable_icon = "<i class='dashicons dashicons-arrow-down'></i>";
                        }
                    } else {
                        $img =  WOBEL_IMAGES_URL . "/sortable.png";
                        $sortable_icon = "<img src='" . esc_url($img) . "' alt=''>";
                    }
                    ?>
                    <th data-column-name="<?php echo esc_attr($static_column['field']) ?>" class="wobel-sortable-column wobel-td120 <?php echo ($sticky_first_columns == 'yes') ? 'wobel-td-sticky wobel-td-sticky-title' : ''; ?>"><?php echo esc_html($static_column['title']); ?><span class="wobel-sortable-column-icon"><?php echo sprintf('%s', $sortable_icon); ?></span></th>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($columns)) :
                foreach ($columns as $column_name => $column) :
                    $title = (!empty($columns_title) && isset($columns_title[$column_name])) ? $columns_title[$column_name] : '';
                    $sortable_icon = '';
                    if (isset($column['sortable']) && $column['sortable'] === true) {
                        if ($column_name == $sort_by) {
                            if ($sort_type == 'ASC') {
                                $sortable_icon = "<i class='dashicons dashicons-arrow-up'></i>";
                            } else {
                                $sortable_icon = "<i class='dashicons dashicons-arrow-down'></i>";
                            }
                        } else {
                            $img =  WOBEL_IMAGES_URL . "/sortable.png";
                            $sortable_icon = "<img src='" . esc_url($img) . "' alt=''>";
                        }
                    }

                    if (isset($display_full_columns_title) && $display_full_columns_title == 'yes') {
                        $column_title = $column['title'];
                    } else {
                        $column_title = (strlen($column['title']) > 12) ? mb_substr($column['title'], 0, 12) . '.' : $column['title'];
                    }
            ?>
                    <th data-column-name="<?php echo esc_attr($column_name); ?>" <?php echo (!empty($column['sortable'])) ? 'class="wobel-sortable-column"' : ''; ?>><?php echo (!empty($title)) ? "<span class='wobel-column-title dashicons dashicons-info' title='" . esc_attr($title) . "'></span>" : "" ?> <?php echo esc_html($column_title); ?> <span class="wobel-sortable-column-icon"><?php echo sprintf('%s', $sortable_icon); ?></span></th>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($after_dynamic_columns)) : ?>
                <?php foreach ($after_dynamic_columns as $last_column_item) : ?>
                    <th data-column-name="<?php echo esc_attr($last_column_item['field']) ?>" class="wobel-td120"><?php echo esc_html($last_column_item['title']); ?></th>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($items_loading)) : ?>
            <tr>
                <td colspan="8" class="wobel-text-alert"><?php _e('Loading ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></td>
            </tr>
        <?php
        elseif (!empty($items) && count($items) > 0) :
            if (!empty($item_provider && is_object($item_provider))) :
                $items_result = $item_provider->get_items($items, $columns);
                if (!empty($items_result)) :
                    echo (is_array($items_result) && !empty($items_result['items'])) ? sprintf('%s', $items_result['items']) : sprintf('%s', $items_result);
                endif;
            endif;
        else :
        ?>
            <tr>
                <td colspan="8" class="wobel-text-alert"><?php _e('No Data Available!', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
if (!empty($items_result['includes']) && is_array($items_result['includes'])) {
    foreach (wobel\classes\helpers\Others::array_flatten($items_result['includes']) as $include_item) {
        echo !empty($include_item) ? $include_item : '';
    }
}
