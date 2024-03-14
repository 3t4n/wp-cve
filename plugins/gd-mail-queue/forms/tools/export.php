<input type="hidden" value="<?php echo admin_url('admin.php?page=gd-mail-queue-tools&gdmaq_handler=getback&run=export&_ajax_nonce='.wp_create_nonce('dev4press-plugin-export')); ?>" id="gdmaq-export-url" />

<div class="d4p-group d4p-group-export d4p-group-important">
    <h3><?php esc_html_e("Important", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <?php esc_html_e("With this tool you export all plugin settings into plain text file using JSON format. Do not modify export file, change can make it unusable.", "gd-mail-queue"); ?>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php esc_html_e("Select what to Export", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input checked="checked" type="checkbox" class="widefat" id="gdmaqtools-export-settings" value="on" /> <?php esc_html_e("Plugin Settings", "gd-mail-queue"); ?>
        </label>
        <label>
            <input checked="checked" type="checkbox" class="widefat" id="gdmaqtools-export-statistics" value="on" /> <?php esc_html_e("Plugin Statistics", "gd-mail-queue"); ?>
        </label>
    </div>
</div>