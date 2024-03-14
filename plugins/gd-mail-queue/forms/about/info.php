<div class="d4p-group d4p-group-import d4p-group-about">
    <h3>GD Mail Queue</h3>
    <div class="d4p-group-inner">
        <ul>
            <li><?php esc_html_e("Version", "gd-mail-queue"); ?>: <span><?php echo esc_html( gdmaq_settings()->info_version ); ?></span></li>
            <li><?php esc_html_e("Status", "gd-mail-queue"); ?>: <span><?php echo esc_html( ucfirst(gdmaq_settings()->info_status)); ?></span></li>
            <li><?php esc_html_e("Edition", "gd-mail-queue"); ?>: <span><?php echo esc_html( ucfirst(gdmaq_settings()->info_edition)); ?></span></li>
            <li><?php esc_html_e("Build", "gd-mail-queue"); ?>: <span><?php echo esc_html( gdmaq_settings()->info_build); ?></span></li>
            <li><?php esc_html_e("Date", "gd-mail-queue"); ?>: <span><?php echo esc_html( gdmaq_settings()->info_updated); ?></span></li>
        </ul>
        <hr style="margin: 1em 0 .7em; border-top: 1px solid #eee"/>
        <ul>
            <li><?php esc_html_e("First released", "gd-mail-queue"); ?>: <span><?php echo esc_html( gdmaq_settings()->info_released); ?></span></li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-import d4p-group-about">
    <h3><?php esc_html_e("System Requirements", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <ul>
            <li><?php esc_html_e("WordPress", "gd-mail-queue"); ?>: <span><?php echo esc_html( gdmaq_settings()->info_wordpress); ?></span></li>
            <li><?php esc_html_e("PHP", "gd-mail-queue"); ?>: <span><?php echo esc_html( gdmaq_settings()->info_php); ?></span></li>
            <li><?php esc_html_e("MySQL", "gd-mail-queue"); ?>: <span><?php echo esc_html( gdmaq_settings()->info_mysql); ?></span></li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-import d4p-group-about">
    <h3><?php esc_html_e("Knowledge Base and Support Forums", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <ul>
            <li><?php echo sprintf(__("To learn more about the plugin, check out plugin %s articles and FAQ. To get additional help, you can use %s.", "gd-mail-queue"),
                '<a target="_blank" href="https://support.dev4press.com/kb/product/gd-mail-queue/">'.__("knowledge base", "gd-mail-queue").'</a>',
                    '<a target="_blank" href="https://support.dev4press.com/forums/forum/plugins/gd-mail-queue/">'.__("support forum", "gd-mail-queue").'</a>'); ?></li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-import d4p-group-about">
    <h3><?php esc_html_e("Important Links", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <ul>
            <li><?php esc_html_e("On WordPress.org", "gd-mail-queue"); ?>: <span><a href="https://wordpress.org/plugins/gd-mail-queue/" target="_blank">wordpress.org/plugins/gd-mail-queue</a></span></li>
            <li><?php esc_html_e("On Dev4Press", "gd-mail-queue"); ?>: <span><a href="https://plugins.dev4press.com/gd-mail-queue/" target="_blank">plugins.dev4press.com/gd-mail-queue</a></span></li>
        </ul>
    </div>
</div>
