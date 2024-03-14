<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
if (!class_exists('MetaSeoBrokenLinkTable')) {
    require_once(WPMETASEO_PLUGIN_DIR . '/inc/class.metaseo-broken-link-table.php');
}

wp_enqueue_script('wplink');
wp_enqueue_style('editor-buttons');
wp_enqueue_style('metaseo-google-icon');
wp_enqueue_style('wpms-tippy-style');
wp_enqueue_script('wpms-tippy-core');
wp_enqueue_script('wpms-tippy');

$metaseo_list_table = new MetaSeoBrokenLinkTable();
$metaseo_list_table->processAction();
$metaseo_list_table->prepare_items();
$a = json_encode($metaseo_list_table->items);

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
if (!empty($_REQUEST['_wp_http_referer'])) {
    wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), stripslashes($_SERVER['REQUEST_URI'])));
    exit;
}
?>

    <div class="wrap broken_link_table seo_extended_table_page">
        <div id="icon-edit-pages" class="icon32 icon32-posts-page"></div>
        <form id="wp-seo-meta-form" class="wpms-form-table" action="" method="post">
            <?php
            echo '<h1 class="wpms-top-h1">' . esc_html__('404 & Redirects', 'wp-meta-seo') . '
                <i class="material-icons intro-topic-tooltip" data-tippy="'.esc_html__('Search engines hates to index pages that returns a 404 error. The 404 and redirect manager index all your internal and external errors in order to make redirect. You can also make custom redirects for whatever the reason is', 'wp-meta-seo').'">help_outline</i>
            </h1>';
            $metaseo_list_table->searchBox1();
            $metaseo_list_table->brokenFilter('sl_broken[]');
            $metaseo_list_table->display();
            ?>
        </form>

        <?php
        $w               = '100%';
        $text            = esc_html__('Bring your WordPress website SEO to the next level with the PRO Addon: Automatic
         redirect based on rules, automatic 404 error index, redirect with link manager,
          Email notification and more!', 'wp-meta-seo');
        $class_btn_close = 'close_broken_link';
        require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/notification.php');
        ?>
    </div>
<?php
wp_enqueue_script('wpms-broken-link');
?>