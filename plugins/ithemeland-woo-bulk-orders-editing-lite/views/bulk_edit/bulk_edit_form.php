<div class="wobel-float-side-modal" id="wobel-float-side-modal-bulk-edit">
    <div class="wobel-float-side-modal-container">
        <div class="wobel-float-side-modal-box">
            <div class="wobel-float-side-modal-content">
                <div class="wobel-float-side-modal-title">
                    <h2><?php esc_html_e('Bulk Edit Form', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h2>
                    <button type="button" class="wobel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-float-side-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-tabs">
                            <div class="wobel-tabs-navigation">
                                <nav class="wobel-tabs-navbar">
                                    <ul class="wobel-tabs-list" data-content-id="wobel-bulk-edit-tabs">
                                        <?php if (!empty($bulk_edit_form_tabs_title) && is_array($bulk_edit_form_tabs_title)) : ?>
                                            <?php $bulk_edit_tab_title_counter = 1; ?>
                                            <?php foreach ($bulk_edit_form_tabs_title as $tab_key => $tab_label) : ?>
                                                <li><a class="wobel-tab-item <?php echo ($bulk_edit_tab_title_counter == 1) ? 'selected' : ''; ?>" data-content="<?php echo esc_attr($tab_key); ?>" href="#"><?php echo esc_attr($tab_label); ?></a></li>
                                                <?php $bulk_edit_tab_title_counter++; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wobel-tabs-contents wobel-mt30" id="wobel-bulk-edit-tabs">
                                <?php if (!empty($bulk_edit_form_tabs_content)) : ?>
                                    <?php foreach ($bulk_edit_form_tabs_content as $tab_key => $bulk_edit_tab) : ?>
                                        <?php echo (!empty($bulk_edit_tab['wrapper_start'])) ? $bulk_edit_tab['wrapper_start'] : ''; ?>
                                        <?php
                                        if (!empty($bulk_edit_tab['fields_top']) && is_array($bulk_edit_tab['fields_top'])) {
                                            foreach ($bulk_edit_tab['fields_top'] as $top_item) {
                                                echo sprintf('%s', $top_item);
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($bulk_edit_tab['tabs_titles'])) : ?>
                                            <ul class="wobel-sub-tab-titles">
                                                <?php foreach ($bulk_edit_tab['tabs_titles'] as $sub_tab_key => $sub_tab_title) : ?>
                                                    <li><a href="javascript:;" class="wobel-sub-tab-title" data-content="<?php echo esc_attr($sub_tab_key); ?>"><?php echo esc_html($sub_tab_title); ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (!empty($bulk_edit_tab['tabs_content'])) : ?>
                                            <div class="wobel-sub-tab-contents">
                                                <?php foreach ($bulk_edit_tab['tabs_content'] as $sub_tab_content_key => $fields_group) : ?>
                                                    <div class="wobel-sub-tab-content" data-content="<?php echo esc_attr($sub_tab_content_key); ?>">
                                                        <?php
                                                        if (!empty($fields_group) && is_array($fields_group)) :
                                                            foreach ($fields_group as $group_key => $fields) :
                                                                if (!empty($fields) && is_array($fields)) :
                                                                    if (!empty($fields['header']) && is_array($fields['header'])) {
                                                                        foreach ($fields['header'] as $header_item) {
                                                                            echo sprintf('%s', $header_item);
                                                                        }
                                                                    }
                                                                    foreach ($fields as $field_items) : ?>
                                                                        <div class="wobel-form-group" <?php echo (!empty($field_items['wrap_attributes'])) ? printf('%s', $field_items['wrap_attributes']) : ''; ?>>
                                                                            <?php
                                                                            if (!empty($field_items['html']) && is_array($field_items['html'])) :
                                                                                foreach ($field_items['html'] as $field) {
                                                                                    echo sprintf('%s', $field);
                                                                                }
                                                                            endif;
                                                                            ?>
                                                                        </div>
                                                        <?php
                                                                    endforeach;
                                                                    if (!empty($fields['footer']) && is_array($fields['footer'])) {
                                                                        foreach ($fields['footer'] as $footer_item) {
                                                                            echo sprintf('%s', $footer_item);
                                                                        }
                                                                    }
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($bulk_edit_tab['fields']) && is_array($bulk_edit_tab['fields'])) : ?>
                                            <?php foreach ($bulk_edit_tab['fields'] as $field_key => $field_items) : ?>
                                                <?php
                                                if (!empty($field_items) && is_array($field_items)) : ?>
                                                    <div class="wobel-form-group" <?php echo (!empty($field_items['wrap_attributes'])) ? printf('%s', $field_items['wrap_attributes']) : ''; ?>>
                                                        <div>
                                                            <?php
                                                            if (!empty($field_items['header']) && is_array($field_items['header'])) {
                                                                foreach ($field_items['header'] as $header_item) {
                                                                    echo sprintf('%s', $header_item);
                                                                }
                                                            }

                                                            if (!empty($field_items['html']) && is_array($field_items['html'])) :
                                                                foreach ($field_items['html'] as $field_html) :
                                                                    echo sprintf('%s', $field_html);
                                                                endforeach;
                                                            endif;

                                                            if (!empty($field_items['footer']) && is_array($field_items['footer'])) {
                                                                foreach ($field_items['footer'] as $footer_item) {
                                                                    echo sprintf('%s', $footer_item);
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php echo (!empty($bulk_edit_tab['wrapper_end'])) ? $bulk_edit_tab['wrapper_end'] : ''; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wobel-float-side-modal-footer">
                    <button type="button" class="wobel-button wobel-button-blue" id="wobel-bulk-edit-form-do-bulk-edit">
                        <?php esc_html_e('Do Bulk Edit', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                    <button type="button" class="wobel-button wobel-button-white" id="wobel-bulk-edit-form-reset">
                        <?php esc_html_e('Reset Form', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>