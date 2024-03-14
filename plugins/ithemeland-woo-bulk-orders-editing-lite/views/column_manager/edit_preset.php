<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
    <input type="hidden" name="action" value="wobel_column_manager_edit_preset">
    <input type="hidden" name="preset_key" id="wobel-column-manager-edit-preset-key" value="">
    <div class="wobel-modal" id="wobel-modal-column-manager-edit-preset">
        <div class="wobel-modal-container">
            <div class="wobel-modal-box wobel-modal-box-lg">
                <div class="wobel-modal-content">
                    <div class="wobel-modal-title">
                        <h2><?php esc_html_e('Edit Column Preset', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h2>
                        <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                            <i class="wobel-icon-x"></i>
                        </button>
                    </div>
                    <div class="wobel-modal-body">
                        <div class="wobel-wrap">
                            <div class="wobel-column-manager-new-profile wobel-mt0">
                                <div class="wobel-column-manager-new-profile-left">
                                    <label class="wobel-column-manager-check-all-fields-btn" data-action="edit">
                                        <input type="checkbox" class="wobel-column-manager-check-all-fields">
                                        <span><?php esc_html_e('Select All', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                    </label>
                                    <input type="text" title="Search Field" data-action="edit" placeholder="<?php esc_html_e('Search Field ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" class="wobel-column-manager-search-field">
                                    <div class="wobel-column-manager-available-fields" data-action="edit">
                                        <ul>
                                            <?php if (!empty($column_items)) : ?>
                                                <?php foreach ($column_items as $column_key => $column_field) : ?>
                                                    <li data-name="<?php echo esc_attr($column_key); ?>">
                                                        <label>
                                                            <input type="checkbox" data-name="<?php echo esc_attr($column_key); ?>" data-type="field" value="<?php echo esc_attr($column_field['label']); ?>">
                                                            <?php echo esc_html($column_field['label']); ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="wobel-column-manager-new-profile-middle">
                                    <div class="wobel-column-manager-middle-buttons">
                                        <div>
                                            <button type="button" data-action="edit" class="wobel-button wobel-button-lg wobel-button-square-lg wobel-button-blue wobel-column-manager-add-field">
                                                <i class="wobel-icon-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="wobel-column-manager-new-profile-right">
                                    <div class="wobel-column-manager-right-top">
                                        <input type="text" title="Profile Name" class="wobel-w100p" id="wobel-column-manager-edit-preset-name" name="preset_name" placeholder="<?php esc_html_e('Profile name ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
                                    </div>
                                    <div class="wobel-column-manager-added-fields wobel-table-border-radius wobel-mt10" data-action="edit">
                                        <div class="items"></div>
                                        <img src="<?php echo WOBEL_IMAGES_URL . 'loading.gif'; ?>" alt="" class="wobel-box-loading wobel-hide">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wobel-modal-footer">
                        <button type="submit" name="edit_preset" class="wobel-button wobel-button-blue"><?php esc_html_e('Save Changes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>