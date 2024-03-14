<div class="wobel-float-side-modal" id="wobel-float-side-modal-column-manager">
    <div class="wobel-float-side-modal-container">
        <div class="wobel-float-side-modal-box">
            <div class="wobel-float-side-modal-content">
                <div class="wobel-float-side-modal-title">
                    <h2><?php esc_html_e('Column Manager', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h2>
                    <button type="button" class="wobel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wobel-wrap">
                        <div class="wobel-tab-middle-content">
                            <div class="wobel-alert wobel-alert-default">
                                <span><?php esc_html_e('Mange columns of table. You can Create your customize presets and use them in column profile section.', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                            </div>
                            <div class="wobel-column-manager-items">
                                <h3><?php esc_html_e('Column Profiles', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h3>
                                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wobel-column-manager-delete-preset-form">
                                    <input type="hidden" name="action" value="wobel_column_manager_delete_preset">
                                    <input type="hidden" name="delete_key" id="wobel_column_manager_delete_preset_key">
                                    <div class="wobel-table-border-radius">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php esc_html_e('Profile Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                                    <th><?php esc_html_e('Date Modified', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                                    <th><?php esc_html_e('Actions', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($column_manager_presets)) : ?>
                                                    <?php $i = 1 ?>
                                                    <?php foreach ($column_manager_presets as $key => $column_manager_preset) : ?>
                                                        <tr>
                                                            <td><?php echo intval($i); ?></td>
                                                            <td>
                                                                <span class="wobel-history-name"><?php echo (isset($column_manager_preset['name'])) ? esc_html($column_manager_preset['name']) : ''; ?></span>
                                                            </td>
                                                            <td><?php echo (isset($column_manager_preset['date_modified'])) ? esc_html(date('d M Y', strtotime($column_manager_preset['date_modified']))) : ''; ?></td>
                                                            <td>
                                                                <?php if (!in_array($key, \wobel\classes\repositories\Column::get_default_columns_name())) : ?>
                                                                    <button type="button" class="wobel-button wobel-button-blue wobel-column-manager-edit-field-btn" data-toggle="modal" data-target="#wobel-modal-column-manager-edit-preset" value="<?php echo esc_attr($key); ?>" data-preset-name="<?php echo (isset($column_manager_preset['name'])) ? esc_attr($column_manager_preset['name']) : ''; ?>">
                                                                        <i class="wobel-icon-pencil"></i>
                                                                        <?php esc_html_e('Edit', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                                                    </button>
                                                                    <button type="button" name="delete_preset" class="wobel-button wobel-button-red wobel-column-manager-delete-preset" value="<?php echo esc_attr($key); ?>">
                                                                        <i class="wobel-icon-trash-2"></i>
                                                                        <?php esc_html_e('Delete', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                                                    </button>
                                                                <?php else : ?>
                                                                    <i class="wobel-icon-lock1"></i>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                            <div class="wobel-column-manager-new-profile">
                                <h3 class="wobel-column-manager-section-title"><?php esc_html_e('Create New Profile', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h3>
                                <div class="wobel-column-manager-new-profile-left">
                                    <input type="text" title="<?php esc_html_e('Search Field', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" data-action="new" placeholder="<?php esc_html_e('Search Field ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" class="wobel-column-manager-search-field">
                                    <div class="wobel-column-manager-available-fields" data-action="new">
                                        <label class="wobel-column-manager-check-all-fields-btn" data-action="new">
                                            <input type="checkbox" class="wobel-column-manager-check-all-fields">
                                            <span><?php esc_html_e('Select All', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                        </label>
                                        <ul>
                                            <?php if (!empty($column_items)) : ?>
                                                <?php foreach ($column_items as $column_key => $column_field) : ?>
                                                    <li data-name="<?php echo esc_attr($column_key); ?>" data-added="false">
                                                        <label>
                                                            <input type="checkbox" data-type="field" data-name="<?php echo esc_attr($column_key); ?>" value="<?php echo esc_attr($column_field['label']); ?>">
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
                                            <button type="button" data-action="new" data-type="checked" class="wobel-button wobel-button-lg wobel-button-square-lg wobel-button-blue wobel-column-manager-add-field">
                                                <i class="wobel-icon-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wobel-column-manager-add-new-preset">
                                    <input type="hidden" name="action" value="wobel_column_manager_new_preset">
                                    <div class="wobel-column-manager-new-profile-right">
                                        <div class="wobel-column-manager-right-top">
                                            <input type="text" title="Profile Name" id="wobel-column-manager-new-preset-name" name="preset_name" placeholder="Profile name ..." required>
                                            <button type="submit" name="save_preset" id="wobel-column-manager-new-preset-btn" class="wobel-button wobel-button-lg wobel-button-blue">
                                                <img src="<?php echo WOBEL_IMAGES_URL . 'save.svg'; ?>" alt="">
                                                <?php esc_html_e('Save Preset', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                            </button>
                                        </div>
                                        <div class="wobel-column-manager-added-fields-wrapper">
                                            <p class="wobel-column-manager-empty-text"><?php esc_html_e('Please add your columns here', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></p>
                                            <div class="wobel-column-manager-added-fields" data-action="new">
                                                <div class="items"></div>
                                                <img src="<?php echo WOBEL_IMAGES_URL . 'loading.gif'; ?>" alt="" class="wobel-box-loading wobel-hide">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>