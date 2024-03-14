<?php
if(!defined('ABSPATH')){
	exit;
}
?>
<div class="wrap">
    <h2>Premmerce Redirect Manager</h2>

    <?php echo $htmlTabs; ?>

    <?php if ($errorMessage): ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <?php echo $errorMessage; ?>
            </p>
        </div>
    <?php endif; ?>

    <?php $redirectType = isset($oldValues['redirect_type']) ? $oldValues['redirect_type'] : '' ?>
    <?php $redirectMethod = isset($oldValues['redirect_method']) ? $oldValues['redirect_method'] : '' ?>

    <div id="col-left">
        <div class="col-wrap">
            <div class="form-wrap">
                <form method="post" class="validate">
	                <?php wp_nonce_field( 'premmerce-redirect-create' ); ?>
                    <input type="hidden" name="action" value="create">
                    <h3><?php _e('Add new redirect', 'premmerce-redirect'); ?></h3>

                    <div class="form-field form-required">
                        <label for="old_url"><?php _e('Source URL', 'premmerce-redirect'); ?></label>
                        <div class="site-url-block">
                            <div class="site-url-block__label">
                                <code><?php echo get_home_url(); ?></code>
                            </div>
                            <div class="site-url-block__field">
                                <input id="old_url"
                                       type="text"
                                       name="old_url"
                                       style="display: table-cell; width: 100%"
                                       class="resource_url"
                                       oninput="setCustomValidity('')"
                                       value="<?= isset($oldValues['old_url']) ? $oldValues['old_url'] : '' ?>"
                                       data-url required
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-field form-required">
                        <label for="redirect_type"><?php _e('Type of target URL', 'premmerce-redirect'); ?></label>

                        <select id="redirect_type" name="redirect_type">
                            <option value="url" <?php selected(($redirectType == 'url') || ($redirectType == '')) ?>>
                                <?php _e('URL', 'premmerce-redirect'); ?>
                            </option>

                            <option value="page" <?php selected($redirectType == 'page') ?>>
                                <?php _e('Page', 'premmerce-redirect'); ?>
                            </option>

                            <option value="post" <?php selected($redirectType == 'post') ?>>
                                <?php _e('Post', 'premmerce-redirect'); ?>
                            </option>

                            <option value="category" <?php selected($redirectType == 'category') ?>>
                                <?php _e('Post category', 'premmerce-redirect'); ?>
                            </option>

                            <?php if (is_plugin_active('woocommerce/woocommerce.php')) : ?>
                                <option value="product" <?php selected($redirectType == 'product') ?>>
                                    <?php _e('Product', 'premmerce-redirect'); ?>
                                </option>

                                <option value="product_category" <?php selected($redirectType == 'product_category') ?>>
                                    <?php _e('Product category', 'premmerce-redirect'); ?>
                                </option>
                            <?php endif; ?>

                        </select>
                    </div>
                    <div class="form-field form-required">
                        <label style="cursor: text"><?php _e('HTTP response status code', 'premmerce-redirect'); ?></label>

                        <fieldset>
                            <label>
                                <input type="radio" name="redirect_method" value="301"
                                    <?php checked(($redirectMethod == '301') || ($redirectMethod == '')) ?>>
                                <?php _e('301 Moved permanently', 'premmerce-redirect'); ?>
                            </label>

                            <label>
                                <input type="radio" name="redirect_method" value="302"
                                    <?php checked($redirectMethod == '302') ?>>
                                <?php _e('302 Moved temporarily', 'premmerce-redirect'); ?>
                            </label>
                        </fieldset>
                    </div>
                    <div data-type="redirect_url"
                         class="redirect_data form-field form-required"
                        <?php echo !in_array($redirectType,['url','']) ?: 'style="display: block"'; ?>>

                        <label for="url_content"><?php _e('URL', 'premmerce-redirect'); ?></label>

                        <input type="text" id="url_content" name="url_content" oninput="setCustomValidity('')"
                               class="data_url"
                               required data-url
                               <?php echo !in_array($redirectType,['url','']) ? 'disabled' : '' ?>
                               value="<?php echo isset($oldValues['url_content']) ? $oldValues['url_content'] : '' ?>">
                    </div>

                    <?php if (is_plugin_active('woocommerce/woocommerce.php')) : ?>
                        <div data-type="redirect_product"
                             class="redirect_data form-field form-required"
                             <?php echo ($redirectType == '' || $redirectType != 'product') ?: 'style="display: block"'; ?>>

                            <label for="redirect_product"><?php _e('Product', 'premmerce-redirect'); ?></label>

                            <select id="redirect_product" data-type-select="redirect_product" name="product_content">
                                <?php if (isset($oldValues['product_content']) && $oldValues['product_content']) : ?>
                                    <option value="<?php echo $oldValues['product_content']; ?>">
                                        <?php echo get_the_title($oldValues['product_content']); ?>
                                    </option>
                                <?php else: ?>
                                    <option value=""><?php _e('Select product', 'premmerce-redirect'); ?></option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div data-type="redirect_product_category"
                             class="redirect_data form-field form-required"
                             <?php echo ($redirectType == '' || $redirectType != 'product_category') ?: 'style="display: block"'; ?>>

                            <label for="redirect_product_category"><?php _e('Product category', 'premmerce-redirect'); ?></label>

                            <select id="redirect_product_category" data-type-select="redirect_product_category" name="product_category_content">
                                <?php if (isset($oldValues['product_category_content']) && $oldValues['product_category_content']) : ?>
                                    <option value="<?php echo $oldValues['product_category_content']; ?>">
                                        <?php echo get_term($oldValues['product_category_content'])->name; ?>
                                    </option>
                                <?php else: ?>
                                    <option value=""><?php _e('Select product category', 'premmerce-redirect'); ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div data-type="redirect_category"
                         class="redirect_data form-field form-required"
                         <?php echo ($redirectType == '' || $redirectType != 'category') ?: 'style="display: block"'; ?>>

                        <label for="redirect_category"><?php _e('Post category', 'premmerce-redirect'); ?></label>

                        <select id="redirect_category" data-type-select="redirect_category" name="category_content">
                            <?php if (isset($oldValues['category_content']) && $oldValues['category_content']) : ?>
                                <option value="<?php echo $oldValues['category_content']; ?>"><?php echo get_term($oldValues['category_content'])->name; ?></option>
                            <?php else: ?>
                                <option value=""><?php _e('Select post category', 'premmerce-redirect'); ?></option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div data-type="redirect_post"
                         class="redirect_data form-field form-required"
                         <?php echo ($redirectType == '' || $redirectType != 'post') ?: 'style="display: block"'; ?>>

                        <label for="redirect_post"><?php _e('Post'); ?></label>
                        <select id="redirect_post" data-type-select="redirect_post" name="post_content">
                            <?php if (isset($oldValues['post_content']) && $oldValues['post_content']) : ?>
                                <option value="<?php echo $oldValues['post_content']; ?>"><?php echo get_the_title($oldValues['post_content']); ?></option>
                            <?php else: ?>
                                <option value=""><?php _e('Select post', 'premmerce-redirect'); ?></option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div data-type="redirect_page"
                         class="redirect_data form-field form-required"
                        <?php echo ($redirectType == '' || $redirectType != 'page') ?: 'style="display: block"'; ?>>

                        <label for="redirect_page"><?php _e('Select page', 'premmerce-redirect'); ?></label>
                        <select id="redirect_page" data-type-select="redirect_page" name="page_content">
                            <?php if (isset($oldValues['page_content']) && $oldValues['page_content']) : ?>
                                <option value="<?php echo $oldValues['page_content']; ?>"><?php echo get_the_title($oldValues['page_content']); ?></option>
                            <?php else: ?>
                                <option value=""><?php _e('Select page', 'premmerce-redirect'); ?></option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <?php submit_button(__('Add new redirect', 'premmerce-redirect')); ?>
                </form>
            </div>
        </div>
    </div>

    <div id="col-right">
        <br>
        <div class="col-wrap">
            <form action="" method="POST">
                <?php $redirectsTable->display(); ?>
            </form>
        </div>
    </div>

    <div data-lang-name="api-url" data-lang-value="<?php echo get_rest_url() ?>"></div>
</div>

<div data-lang-name="confirm-delete" data-lang-value="<?php
	printf(
		"%s\n%s\n%s",
		esc_attr__( 'You are about to permanently delete these items from your site.', 'premmerce-redirect' ),
		esc_attr__( 'This action cannot be undone.', 'premmerce-redirect' ),
		esc_attr__( '\'Cancel\' to stop, \'OK\' to delete.', 'premmerce-redirect' )
	);
?>">
</div>
