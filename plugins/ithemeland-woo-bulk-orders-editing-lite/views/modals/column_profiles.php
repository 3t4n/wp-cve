<div class="wobel-float-side-modal" id="wobel-float-side-modal-column-profiles">
    <div class="wobel-float-side-modal-container">
        <div class="wobel-float-side-modal-box">
            <div class="wobel-float-side-modal-content">
                <div class="wobel-float-side-modal-title">
                    <h2><?php esc_html_e('Column Profiles', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h2>
                    <button type="button" class="wobel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" style="float: left; width: 100%; height: 100%;">
                    <input type="hidden" name="action" value="<?php echo esc_attr($plugin_key . '_load_column_profile'); ?>">
                    <div class="wobel-float-side-modal-body">
                        <div class="wobel-wrap">
                            <div class="wobel-alert wobel-alert-default">
                                <span><?php esc_html_e('You can load saved column profile presets through Column Manager. You can change the columns and save your changes too.', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                            </div>
                            <div class="wobel-column-profiles-choose">
                                <label for="wobel-column-profiles-choose"><?php esc_html_e('Choose Preset', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <select id="wobel-column-profiles-choose" name="preset_key">
                                    <?php
                                    if (!empty($column_manager_presets)) :
                                        $i = 0;
                                        foreach ($column_manager_presets as $column_manager_preset) :
                                            if ($i == 0) {
                                                $first_key = $column_manager_preset['key'];
                                            }
                                    ?>
                                            <option value="<?php echo esc_attr($column_manager_preset['key']); ?>" <?php echo (!empty($active_columns_key) && $active_columns_key == $column_manager_preset['key']) ? 'selected' : ''; ?>><?php echo esc_html($column_manager_preset['name']); ?></option>
                                    <?php
                                            $i++;
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <label class="wobel-column-profile-select-all">
                                    <input type="checkbox" id="wobel-column-profile-select-all" data-profile-name="<?php echo (!empty($active_columns_key)) ? esc_attr($active_columns_key) : ''; ?>">
                                    <span><?php esc_html_e('Select All', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                </label>
                            </div>
                            <div class="wobel-column-profile-search">
                                <label for="wobel-column-profile-search"><?php esc_html_e('Search', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> </label>
                                <input type="text" id="wobel-column-profile-search" placeholder="<?php esc_html_e('Search Column ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
                            </div>
                            <div class="wobel-column-profiles-fields">
                                <?php if (!empty($grouped_fields)) :
                                    $compatibles = [];
                                    if (!empty($grouped_fields['compatibles'])) {
                                        $compatibles = $grouped_fields['compatibles'];
                                        unset($grouped_fields['compatibles']);
                                    }
                                ?>
                                    <div class="wobel-column-profile-fields">
                                        <?php foreach ($grouped_fields as $group_name => $column_fields) : ?>
                                            <?php if (!empty($column_fields)) : ?>
                                                <div class="wobel-column-profile-fields-group">
                                                    <div class="group-title">
                                                        <h3><?php echo esc_html($group_name); ?></h3>
                                                    </div>
                                                    <ul>
                                                        <?php foreach ($column_fields as $name => $column_field) : ?>
                                                            <li>
                                                                <label>
                                                                    <input type="checkbox" name="columns[]" value="<?php echo esc_attr($name); ?>">
                                                                    <?php echo esc_html($column_field['label']); ?>
                                                                </label>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        <?php
                                        endforeach;
                                        if (!empty($compatibles) && is_array($compatibles)) : ?>
                                            <div class="wobel-column-profile-compatibles-group">
                                                <strong class="wobel-column-profile-compatibles-group-title"><?php esc_html_e('Fields from certain third-party plugins', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></strong>
                                                <?php foreach ($compatibles as $compatible_name => $compatible_fields) : ?>
                                                    <div class="wobel-column-profile-fields-group">
                                                        <div class="group-title">
                                                            <h3><?php echo esc_html($compatible_name); ?></h3>
                                                        </div>
                                                        <ul>
                                                            <?php foreach ($compatible_fields as $compatible_field_name => $compatible_field) : ?>
                                                                <li>
                                                                    <label>
                                                                        <input type="checkbox" name="columns[]" value="<?php echo esc_attr($compatible_field_name); ?>">
                                                                        <?php echo esc_html($compatible_field['label']); ?>
                                                                    </label>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="wobel-float-side-modal-footer">
                        <button type="submit" class="wobel-button wobel-button-blue wobel-float-left" id="wobel-column-profiles-apply" data-preset-key="<?php echo (!empty($first_key)) ? $first_key : ''; ?>">
                            <?php esc_html_e('Apply To Table', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                        </button>
                        <div class="wobel-column-profile-save-dropdown" style="display: none">
                            <span>
                                <?php esc_html_e('Save Changes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                <i class="wobel-icon-chevron-down"></i>
                            </span>
                            <div class="wobel-column-profile-save-dropdown-buttons">
                                <ul>
                                    <li id="wobel-column-profiles-update-changes" <?php echo (!empty($active_columns_key) && !empty($default_columns_name) && in_array($active_columns_key, $default_columns_name)) ? 'style="display:none;"' : ''; ?>>
                                        <?php esc_html_e('Update selected preset', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                    </li>
                                    <li id="wobel-column-profiles-save-as-new-preset">
                                        <?php esc_html_e('Save as new preset', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>