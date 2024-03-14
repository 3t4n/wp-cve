<div id="wobel-loading" class="wobel-loading">
    <?php esc_html_e('Loading ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
</div>

<?php
if (!empty($flush_message)) {
    include WOBEL_VIEWS_DIR . "alerts/flush_message.php";
}
?>

<div id="wobel-main">
    <div id="wobel-header">
        <div class="wobel-plugin-title">
            <span class="wobel-plugin-name"><img src="<?php echo WOBEL_IMAGES_URL . 'wobel_icon_original.svg'; ?>" alt=""><?php echo esc_html($title); ?></span>
        </div>
        <ul class="wobel-header-left">
            <li title="Help">
                <a href="<?php echo (!empty($doc_link)) ? esc_url($doc_link) : '#'; ?>">
                    <i class="wobel-icon-book"></i>
                </a>
            </li>
            <li id="wobel-full-screen" title="Full screen">
                <i class="wobel-icon-enlarge"></i>
            </li>
            <li class="wobel-get-pro-button" title="Get Pro">
                <a target="_blank" href="<?php echo esc_url(WOBEL_UPGRADE_URL); ?>">
                    <i class="wobel-icon-star-full"></i> Get Pro
                </a>
            </li>
        </ul>
    </div>