<div class="wobel-float-side-modal" id="wobel-float-side-modal-history">
    <div class="wobel-float-side-modal-container">
        <div class="wobel-float-side-modal-box">
            <div class="wobel-float-side-modal-content">
                <div class="wobel-float-side-modal-title">
                    <h2><?php esc_html_e('History', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h2>
                    <button type="button" class="wobel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-float-side-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-alert wobel-alert-default">
                            <span><?php esc_html_e('List of your changes and possible to roll back to the previous data', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                        </div>
                        <div class="wobel-alert wobel-alert-danger">
                            <span class="wobel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                            <a href="<?php echo esc_url(WOBEL_UPGRADE_URL); ?>"><?php echo esc_html(WOBEL_UPGRADE_TEXT); ?></a>
                        </div>
                        <div class="wobel-history-filter">
                            <div class="wobel-history-filter-fields">
                                <div class="wobel-history-filter-field-item">
                                    <label for="wobel-history-filter-operation"><?php esc_html_e('Operation', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select id="wobel-history-filter-operation">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                        <?php if (!empty($history_types = \wobel\classes\repositories\History::get_operation_types())) : ?>
                                            <?php foreach ($history_types as $history_type_key => $history_type_label) : ?>
                                                <option value="<?php echo esc_attr($history_type_key); ?>"><?php echo esc_html($history_type_label); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="wobel-history-filter-field-item">
                                    <label for="wobel-history-filter-author"><?php esc_html_e('Author', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <select id="wobel-history-filter-author">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                        <?php if (!empty($users)) : ?>
                                            <?php foreach ($users as $user_item) : ?>
                                                <option value="<?php echo esc_attr($user_item->ID); ?>"><?php echo esc_html($user_item->user_login); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="wobel-history-filter-field-item">
                                    <label for="wobel-history-filter-fields"><?php esc_html_e('Fields', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <input type="text" id="wobel-history-filter-fields" placeholder="for example: ID">
                                </div>
                                <div class="wobel-history-filter-field-item wobel-history-filter-field-date">
                                    <label><?php esc_html_e('Date', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <input type="text" id="wobel-history-filter-date-from" class="wobel-datepicker wobel-date-from" data-to-id="wobel-history-filter-date-to" placeholder="<?php esc_html_e('From ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
                                    <input type="text" id="wobel-history-filter-date-to" class="wobel-datepicker" placeholder="<?php esc_html_e('To ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
                                </div>
                            </div>
                            <div class="wobel-history-filter-buttons">
                                <div class="wobel-history-filter-buttons-left">
                                    <button type="button" disabled="disabled" class="wobel-button wobel-button-lg wobel-button-blue">
                                        <i class="wobel-icon-filter1"></i>
                                        <span><?php esc_html_e('Apply Filters', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                    </button>
                                    <button type="button" disabled="disabled" class="wobel-button wobel-button-lg wobel-button-gray">
                                        <i class="wobel-icon-rotate-cw"></i>
                                        <span><?php esc_html_e('Reset Filters', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                    </button>
                                </div>
                                <div class="wobel-history-filter-buttons-right">
                                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                        <button type="button" disabled="disabled" class="wobel-button wobel-button-lg wobel-button-red">
                                            <i class="wobel-icon-trash-2"></i>
                                            <span><?php esc_html_e('Clear History', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="wobel-history-items">
                            <h3><?php esc_html_e('Column(s)', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h3>
                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                <div class="wobel-table-border-radius">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php esc_html_e('History Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                                <th><?php esc_html_e('Author', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                                <th class="wobel-mw125"><?php esc_html_e('Date Modified', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                                <th class="wobel-mw250"><?php esc_html_e('Actions', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php include 'history_items.php'; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="wobel-history-pagination-container">
                                    <?php include 'history_pagination.php'; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>