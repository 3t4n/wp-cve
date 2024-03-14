<div class="hjqs-ln-settings-header">
    <div class="hjqs-ln-settings-title-section">
        <h1><?php echo $current_page->get_page_title() ?></h1>
        <small>
            <script type="text/javascript" src="<?php echo plugin_dir_url(dirname( __FILE__, 2 )); ?>assets/js/button.prod.min.js" data-name="bmc-button" data-slug="hugojqs" data-color="#FFDD00" data-emoji=""  data-font="Cookie" data-text="Buy me a coffee" data-outline-color="#000000" data-font-color="#000000" data-coffee-color="#ffffff" ></script>
        </small>
    </div>
    <nav class="hjqs-ln-settings-tabs-wrapper" aria-label="Menu secondaire">
	    <?php do_action('hjqs_legal_notice_before_nav'); ?>
        <?php foreach ($pages as $page) : ?>
        <a href="options-general.php?page=<?php echo $page->get_slug() ?>" class="hjqs-ln-settings-tab <?php echo $page === $current_page ? 'active' : '' ?>" <?php echo $page === $current_page ? 'aria-current="true"' : null ?>><?php echo $page->get_page_title() ?></a>
        <?php endforeach; ?>
	    <?php do_action('hjqs_legal_notice_after_nav'); ?>
    </nav>
</div>
<?php do_action('hjqs_legal_notice'); ?>
