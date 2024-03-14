<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
if (!class_exists('WPMSCategoryMetaTable')) {
    require_once(WPMETASEO_PLUGIN_DIR . '/inc/class.metaseo-category-meta-table.php');
}

add_thickbox();
$wpmsCategoryMeta = new WPMSCategoryMetaTable();
$wpmsCategoryMeta->processAction();
$wpmsCategoryMeta->prepare_items();

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
if (!empty($_REQUEST['_wp_http_referer'])) {
    wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), stripslashes($_SERVER['REQUEST_URI'])));
    exit;
}
?>

<div class="wrap seo_extended_table_page">
    <div id="icon-edit-pages" class="icon32 icon32-posts-page"></div>

    <form id="wp-seo-meta-form" class="wpms-form-table" action="" method="post">
        <div id="cat-meta-bulk-actions" style="display:none;">
            <div class="m-tb-20">
                <h3 class="wpms-top-h3"><?php esc_html_e('Apply bulk action to', 'wp-meta-seo') ?></h3>
                <p>
                    <label class="wpms-text-action">
                        <input type="checkbox" class="mbulk_copy wpms-checkbox" value="all">
                        <?php esc_html_e('All Categories', 'wp-meta-seo') ?>
                    </label>
                </p>
                <p>
                    <label class="wpms-text-action">
                        <input type="checkbox" class="mbulk_copy wpms-checkbox" value="only-selection" checked="checked">
                        <?php esc_html_e('Only Category selection', 'wp-meta-seo') ?>
                    </label>
                </p>
            </div>
            <div class="m-tb-20">
                <h3 class="wpms-top-h3"><?php esc_html_e('Action', 'wp-meta-seo') ?></h3>
                <p>
                    <label class="wpms-text-action">
                        <input type="checkbox" class="wpms-bulk-action wpms-checkbox wpms-bulk-action-metatitle" value="cat-name-to-title">
                        <?php esc_html_e('Copy Title as Meta Title', 'wp-meta-seo') ?>
                    </label>
                </p>
                <p>
                    <label class="wpms-text-action">
                        <input type="checkbox" class="wpms-bulk-action wpms-checkbox wpms-bulk-action-metadesc" value="cat-name-to-desc">
                        <?php esc_html_e('Copy Title as Meta Description', 'wp-meta-seo') ?>
                    </label>
                </p>
            </div>
            <button type="button" name="do_copy" data-action="bulk_cat_copy"
                    class="ju-button orange-button btn_do_cat_copy post_do_copy wpms-small-btn wpms_left"><?php esc_html_e('Apply now', 'wp-meta-seo') ?></button>
            <span class="spinner wpms-spinner wpms-spinner-cat-copy wpms_left"></span>
            <label class="bulk-msg"><?php esc_html_e('Done! You may ', 'wp-meta-seo') ?><a href="<?php echo esc_url(admin_url('admin.php?page=metaseo_category_meta')) ?>"><?php esc_html_e('close the window and refresh the page...', 'wp-meta-seo') ?></a></label>
        </div>
        <?php
        echo '<h1 class="wpms-top-h1">' . esc_html__('Category Meta', 'wp-meta-seo') . '
                <i class="material-icons intro-topic-tooltip" data-tippy="'.esc_html__('Edit all your post/product categories meta information here  and apply bulk edition on them', 'wp-meta-seo').'">help_outline</i>
            </h1>';
        $wpmsCategoryMeta->searchBox(esc_html__('Search Categories', 'wp-meta-seo'), 'wpms_cat_content');
        $wpmsCategoryMeta->display();
        ?>
    </form>

</div>