<div class="wrap wpil-report-page wpil_styles">
    <?=Wpil_Base::showVersion()?>
    <h1 class="wp-heading-inline">Clicks Report</h1>
    <hr class="wp-header-end">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content" style="position: relative;">
                <?php include_once 'report_tabs.php'; ?>
                <div id="report_clicks">
                    <form>
                        <input type="hidden" name="page" value="link_whisper" />
                        <input type="hidden" name="type" value="clicks" />
                        <input type="hidden" name="click_delete_confirm_text" value="<?php _e('Do you really want to delete all the click data in the row?', 'wpil'); ?>" />
                        <?php $table->search_box('Search', 'search'); ?>
                    </form>
                    <?php $table->display(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var wpil_admin_url = '<?php echo admin_url()?>';
</script>