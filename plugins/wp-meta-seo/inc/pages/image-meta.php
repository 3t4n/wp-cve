<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
if (!class_exists('MetaSeoImageListTable')) {
    require_once(WPMETASEO_PLUGIN_DIR . '/inc/class.metaseo-image-list-table.php');
}

add_thickbox();
wp_enqueue_style('wpms-tippy-style');
wp_enqueue_script('wpms-tippy-core');
wp_enqueue_script('wpms-tippy');
wp_enqueue_script('wpms-my-tippy');
$metaseo_list_table = new MetaSeoImageListTable();
$metaseo_list_table->processAction();
$metaseo_list_table->prepare_items();

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
if (!empty($_REQUEST['_wp_http_referer'])) {
    wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), stripslashes($_SERVER['REQUEST_URI'])));
    exit;
}
?>

<div class="wrap seo_extended_table_page">
    <div id="icon-edit-pages" class="icon32 icon32-posts-page"></div>
    <form id="wp-seo-meta-form" class="wpms-form-table" action="" method="post">
        <div id="meta-bulk-actions" style="display:none;">
            <div class="m-tb-20">
                <h3 class="wpms-top-h3"><?php esc_html_e('Apply bulk action to', 'wp-meta-seo') ?></h3>
                <p>
                    <label class="wpms-text-action">
                        <input type="checkbox" class="mbulk_copy wpms-checkbox" value="only-selection"
                               checked="checked">
                        <?php esc_html_e('The image selection', 'wp-meta-seo') ?>
                    </label>
                </p>
                <p>
                    <label class="wpms-text-action">
                        <input type="checkbox" class="mbulk_copy wpms-checkbox" value="all">
                        <?php esc_html_e('All images', 'wp-meta-seo') ?>
                    </label>
                </p>
            </div>

            <div class="m-tb-20">
                <h3 class="wpms-top-h3"><?php esc_html_e('Action', 'wp-meta-seo') ?></h3>
                <p>
                    <label class="wpms-text-action">
                        <input type="checkbox" class="wpms-bulk-action wpms-checkbox" value="img-copy-alt">
                        <?php esc_html_e('Copy image name as Alt text', 'wp-meta-seo') ?>
                    </label>
                </p>
                <p>
                    <label class="wpms-text-action">
                        <input type="checkbox" class="wpms-bulk-action wpms-checkbox" value="img-copy-title">
                        <?php esc_html_e('Copy image name as Image title', 'wp-meta-seo') ?>
                    </label>
                </p>
                <p>
                    <label class="wpms-text-action">
                        <input type="checkbox" class="wpms-bulk-action wpms-checkbox" value="img-copy-desc">
                        <?php esc_html_e('Copy image name as Image description', 'wp-meta-seo') ?>
                    </label>
                </p>
            </div>

            <button type="button" name="do_copy" data-action="bulk_image_copy"
                    class="ju-button orange-button btn_do_copy wpms-small-btn wpms_left"><?php esc_html_e('Apply now', 'wp-meta-seo') ?></button>
            <span class="spinner wpms-spinner wpms-spinner-copy wpms_left"></span>
            <label class="bulk-msg"><?php esc_html_e('Done! You may ', 'wp-meta-seo') ?><a
                        href="<?php echo esc_url(admin_url('admin.php?page=metaseo_image_meta')) ?>"><?php esc_html_e('close the window and refresh the page...', 'wp-meta-seo') ?></a></label>
        </div>
        <?php
        echo '<h1 class="wpms-top-h1">' . esc_html__('Image Information', 'wp-meta-seo') . '
                <i class="material-icons intro-topic-tooltip" data-tippy="' . esc_html__('Where your image SEO can be optimized to get traffic from search engines image search and add smart content to your pages HTML. Optimize in priority file names, Alt text and image Titles', 'wp-meta-seo') . '">help_outline</i>
            </h1>';
        $metaseo_list_table->searchBox1();
        $metaseo_list_table->display();
        ?>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        //Scan all posts to find a group of images in their content
        metaSeoScanImages();
        //autosize(document.querySelectorAll('.metaseo-img-meta'));
        tippy('.image_scan_meta', {
            animation: 'scale',
            duration: 0,
            arrow: false,
            placement: 'top',
            theme: 'wpms-widgets-tippy_show_arow',
            onShow(instance) {
                instance.popper.hidden = instance.reference.dataset.tippy ? false : true;
                instance.setContent(instance.reference.dataset.tippy);
            }
        });
    });

</script>