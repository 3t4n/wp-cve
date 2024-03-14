<?php
if(!defined('ABSPATH')){
	exit;
}
?>
<div class="wrap">
    <h2>Premmerce Redirect Manager</h2>

    <?php echo $htmlTabs; ?>

    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>

        <table class="form-table">

            <?php if (is_plugin_active('woocommerce/woocommerce.php')) : ?>
            <tr>
                <th scope="row"><?php _e('Automatic redirects', 'premmerce-redirect') ?></th>
                <td>
                    <fieldset>
                        <label>
                            <input type="checkbox" name="premmerce_redirect_delete_product" <?php checked(get_option('premmerce_redirect_delete_product') == 'on'); ?> />
                            <?php _e('Add 301 redirects to the product category after the product has been moved to the Trash', 'premmerce-redirect') ?>
                        </label>
                    </fieldset>

                    <br>

                    <fieldset>
                        <label>
                            <input type="checkbox" name="premmerce_redirect_change_status_product" <?php checked(get_option('premmerce_redirect_change_status_product') == 'on'); ?> />
                            <?php _e('Add 302 redirects to the product category after the status of a product has been changed from Published.', 'premmerce-redirect') ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <?php endif; ?>

            <tr>
                <th scope="row">
                    <label for="premmerce_redirect_items_per_page">
                        <?php _e('Pagination', 'premmerce-redirect') ?>
                    </label>
                </th>
                <td>
                    <fieldset>
                        <label for="premmerce_redirect_items_per_page">
                            <?php _e('Nubmer of redirects per page:', 'premmerce-redirect') ?>
                            <input type="number"
                                   min="1"
                                   max="999"
                                   class="small-text"
                                   name="premmerce_redirect_items_per_page"
                                   id="premmerce_redirect_items_per_page"
                                   data-pagination-number
                                   value="<?= get_option('premmerce_redirect_items_per_page') ? get_option('premmerce_redirect_items_per_page') : 10 ?>" />
                        </label>
                    </fieldset>
                </td>
            </tr>

        </table>

        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="premmerce_redirect_delete_product,premmerce_redirect_change_status_product,premmerce_redirect_items_per_page" />

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>
