<div class="d4p-group d4p-group-extra d4p-group-important">
    <h3><?php esc_html_e("Important", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <?php esc_html_e("This tool can remove plugin settings saved in the WordPress options table and all database tables added by the plugin.", "gd-mail-queue"); ?><br/><br/>
        <?php esc_html_e("Deletion operations are not reversible, and it is highly recommended to create database backup before proceeding with this tool.", "gd-mail-queue"); ?> 
        <?php esc_html_e("If you choose to remove plugin settings, once that is done, all settings will be reinitialized to default values if you choose to leave plugin active.", "gd-mail-queue"); ?>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php esc_html_e("Remove plugin settings", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="gdmaqtools[remove][settings]" value="on" /> <?php esc_html_e("All Plugin Settings", "gd-mail-queue"); ?>
        </label>
        <label>
            <input type="checkbox" class="widefat" name="gdmaqtools[remove][statistics]" value="on" /> <?php esc_html_e("All Plugin Statistics", "gd-mail-queue"); ?>
        </label>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php esc_html_e("Remove plugin CRON jobs", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="gdmaqtools[remove][cron]" value="on" /> <?php esc_html_e("All Plugin CRON Jobs", "gd-mail-queue"); ?>
        </label>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php esc_html_e("Remove database data and tables", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="gdmaqtools[remove][drop]" value="on" /> <?php esc_html_e("Remove plugins database tables and all data in them", "gd-mail-queue"); ?>
        </label>
        <label>
            <input type="checkbox" class="widefat" name="gdmaqtools[remove][truncate]" value="on" /> <?php esc_html_e("Remove all data from database tables", "gd-mail-queue"); ?>
        </label><br/>
        <hr/>
        <p><?php esc_html_e("Database tables that will be affected", "gd-mail-queue"); ?>:</p>
        <ul style="list-style: inside disc;">
            <li><?php echo esc_html(gdmaq_db()->queue); ?></li>
            <li><?php echo esc_html(gdmaq_db()->log); ?></li>
            <li><?php echo esc_html(gdmaq_db()->emails); ?></li>
            <li><?php echo esc_html(gdmaq_db()->log_email); ?></li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php esc_html_e("Disable Plugin", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="gdmaqtools[remove][disable]" value="on" /> <?php esc_html_e("Disable plugin", "gd-mail-queue"); ?>
        </label>
    </div>
</div>
