<div class="wobel-float-side-modal" id="wobel-float-side-modal-import-export">
    <div class="wobel-float-side-modal-container">
        <div class="wobel-float-side-modal-box">
            <div class="wobel-float-side-modal-content">
                <div class="wobel-float-side-modal-title">
                    <h2><?php esc_html_e('Import/Export', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h2>
                    <button type="button" class="wobel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wobel-wrap">
                        <div class="wobel-tab-middle-content">
                            <div class="wobel-alert wobel-alert-default">
                                <span><?php esc_html_e('Import/Export orders as CSV files', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>.</span>
                            </div>
                            <div class="wobel-export">
                                <form action="<?php echo esc_url(admin_url("admin-post.php")); ?>" method="post">
                                    <input type="hidden" name="action" value="wobel_export_orders">
                                    <div id="wobel-export-items-selected"></div>
                                    <div class="wobel-export-fields">
                                        <div class="wobel-export-field-item">
                                            <strong class="label"><?php esc_html_e('Orders', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></strong>
                                            <label class="wobel-export-radio">
                                                <input type="radio" name="orders" value="all" checked="checked" id="wobel-export-all-items-in-table">
                                                <?php esc_html_e('All Orders In Table', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                            </label>
                                            <label class="wobel-export-radio">
                                                <input type="radio" name="orders" id="wobel-export-only-selected-items" value="selected" disabled="disabled">
                                                <?php esc_html_e('Only Selected orders', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                            </label>
                                        </div>
                                        <div class="wobel-export-field-item">
                                            <strong class="label"><?php esc_html_e('Fields', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></strong>
                                            <label class="wobel-export-radio">
                                                <input type="radio" name="fields" value="all" checked="checked">
                                                <?php esc_html_e('All Fields', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                            </label>
                                            <label class="wobel-export-radio">
                                                <input type="radio" name="fields" value="visible">
                                                <?php esc_html_e('Only Visible Fields', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                                            </label>
                                        </div>
                                        <div class="wobel-export-field-item">
                                            <label class="label" for="wobel-export-delimiter"><?php esc_html_e('Delimiter', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                            <select name="wobel-export-delimiter" id="wobel-export-delimiter">
                                                <option value=",">,</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="wobel-export-buttons">
                                        <div class="wobel-export-buttons-left">
                                            <button type="submit" class="wobel-button wobel-button-lg wobel-button-blue" id="wobel-export-orders">
                                                <i class="wobel-icon-filter1"></i>
                                                <span><?php esc_html_e('Export Now', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="wobel-import">
                                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="wobel_import_orders">
                                    <div class="wobel-import-content">
                                        <p><?php esc_html_e("If you have orders in another system, you can import those into this site. ", 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></p>
                                        <input type="file" name="import_file" required>
                                    </div>
                                    <div class="wobel-import-buttons">
                                        <div class="wobel-import-buttons-left">
                                            <button type="submit" name="import" class="wobel-button wobel-button-lg wobel-button-blue">
                                                <i class="wobel-icon-filter1"></i>
                                                <span><?php esc_html_e('Import Now', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                            </button>
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