<div class="icon32" id="icon-woocommerce-importer"><br></div>
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
                <a href="<?php echo admin_url('admin.php?page=wf_pr_rev_csv_im_ex') ?>" class="nav-tab <?php echo ($tab == 'export') ? 'nav-tab-active' : ''; ?>"><?php _e('Product Reviews Export', 'product-reviews-import-export-for-woocommerce'); ?></a>

        <a href="<?php echo admin_url('admin.php?import=product_reviews_csv') ?>" class="nav-tab nav-tab-active "><?php _e('Product Reviews Import', 'product-reviews-import-export-for-woocommerce'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_pr_rev_csv_im_ex&tab=help'); ?>" class="nav-tab <?php echo ('help' == $tab) ? 'nav-tab-active' : ''; ?>"><?php _e('Help', 'product-reviews-import-export-for-woocommerce'); ?></a>
        <a href="https://www.webtoffee.com/product/product-import-export-woocommerce/" target="_blank" class="nav-tab nav-tab-premium"><?php _e('Upgrade to Premium for More Features', 'product-reviews-import-export-for-woocommerce'); ?></a>

    </h2><br>
<div class="tool-box bg-white p-20p pipe-view">
 
 <h3 class="title aw-title"><?php _e('Import Product Reviews in CSV Format:', 'product-reviews-import-export-for-woocommerce'); ?></h3>
    <p><?php _e('You can import product reviews (in CSV format) in to the shop .', 'product-reviews-import-export-for-woocommerce'); ?></p>

<?php if (!empty($upload_dir['error'])) : ?>
        <div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:', 'product-reviews-import-export-for-woocommerce'); ?></p>
            <p><strong><?php echo $upload_dir['error']; ?></strong></p></div>
<?php else : ?>
        <form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo esc_attr(wp_nonce_url($action, 'import-upload')); ?>">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th>
                            <label for="upload"><?php _e('Select a file from your computer', 'product-reviews-import-export-for-woocommerce'); ?></label>
                        </th>
                        <td>
                            <input type="file" id="upload" name="import" size="25" />
                            <input type="hidden" name="action" value="save" />
                            <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
                            <small><?php printf(__('Maximum size: %s', 'product-reviews-import-export-for-woocommerce'), $size); ?></small>
                        </td>
                    </tr>
                    <tr>
                                <th><label><?php _e('Update reviews if exists', 'product-reviews-import-export-for-woocommerce'); ?></label><br/></th>
                                <td>
                                    <input type="checkbox" name="merge" id="merge">
                                    <p style="font-size: 12px"><?php _e('Existing product\'s review are identified by their IDs. If this option is not selected and if a review with same ID is found in the CSV, that review will not be imported.', 'product-reviews-import-export-for-woocommerce'); ?></p>
                                </td>

                    </tr>
                    <tr>
                            <th><label><?php _e('Use SKU', 'product-reviews-import-export-for-woocommerce'); ?></label><br/></th>
                            <td>
                                <input type="checkbox" name="use_sku" />
                                <p style="font-size: 12px"><?php _e('Check to link products and reviews using SKU instead of product ID', 'product-reviews-import-export-for-woocommerce'); ?></p>
                            </td>
                        </tr>

                </tbody>
            </table>
            <p class="submit">
                <input type="submit" class="button button-primary" value="<?php esc_attr_e('Upload file and import', 'product-reviews-import-export-for-woocommerce'); ?>" />
            </p>
        </form>
<?php endif; ?>
</div>
<?php include(WF_ROOT_FILE_PATH . 'includes/views/market.php'); ?>