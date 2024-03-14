<div class="wrap wppass-wrap">
    <h1><?php esc_html_e('Wp Edit Password Protected ', 'wp-edit-password-protected') ?></h1>
    <div id="dashboard-widgets" class="metabox-holder">
        <div id="postbox-container-1" class="postbox-container ">
            <div id="dashboard_site_health" class="postbox meta-box-sortables">
                <div class="postbox-header">
                    <h2 class="hndle ui-sortable-handle"><?php esc_html_e('Member Only Setup Guide', 'wp-edit-password-protected') ?></h2>
                </div>
                <div class="inside">
                    <div class="wppass-widget">
                        <div class="wppass-details">
                            <a target="_blank" href="https://www.youtube.com/watch?v=yD6gVNa8vpc">
                                <img src="<?php echo esc_url(WP_EDIT_PASS_ASSETS . 'img/admin-only-page.png') ?>" alt="<?php esc_attr_e('Member Only Setup Guide', 'wp-edit-password-protected') ?>"></a>
                            <a target="_blank" class="button button-primary wpeditp-guide-link" href="<?php echo esc_url(admin_url('/customize.php?autofocus[panel]=wppass_adminpage_panel')); ?>"><?php esc_html_e('Member Only Page Setup', 'wp-edit-password-protected'); ?></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div id="postbox-container-2" class="postbox-container">
            <div id="dashboard_site_health" class="postbox meta-box-sortables">
                <div class="postbox-header">
                    <h2 class="hndle ui-sortable-handle"><?php esc_html_e('Password Protected Form Setup Guide', 'wp-edit-password-protected') ?></h2>
                </div>
                <div class="inside">
                    <div class="wppass-widget">
                        <div class="wppass-details">
                            <a target="_blank" href="https://www.youtube.com/watch?v=1OSEhxFVjUM">
                                <img src="<?php echo esc_url(WP_EDIT_PASS_ASSETS . 'img/wpedit-pass.png') ?>" alt="<?php esc_attr_e('Member Only Setup Guide', 'wp-edit-password-protected') ?>"></a>
                            <a target="_blank" class="button button-primary wpeditp-guide-link" href="<?php echo esc_url(admin_url('/customize.php?autofocus[panel]=wppass_protected_panel')); ?>"><?php esc_html_e('Password Protected Form Setup', 'wp-edit-password-protected'); ?></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>