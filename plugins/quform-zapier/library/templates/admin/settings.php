<?php
if (!defined('ABSPATH')) exit;
/** @var Quform_Zapier_Admin_Page_Settings $page */
/** @var Quform_Zapier_Options $options */
/** @var array $caps */
/** @var array $roles */
/** @var string $mdiPrefix */
?><div class="qfb qfb-cf">

    <?php
        echo $page->getNavHtml();
        echo $page->getSubNavHtml();
    ?>

    <noscript>
        <div class="qfb-message-box qfb-message-box-error">
            <?php esc_html_e('Please enable JavaScript to use this page', 'quform-zapier'); ?>
        </div>
    </noscript>

    <form id="qfb-zapier-settings-form" autocomplete="off" method="post" action="<?php echo esc_url(add_query_arg(array())); ?>">
        <input type="submit" class="qfb-hidden"><!-- Prevent enter key submitting the form -->

        <div class="qfb-fixed-buttons">
            <div id="qfb-fixed-save-button" class="qfb-animated-save-button" title="<?php esc_attr_e('Save', 'quform-zapier'); ?>"><i class="<?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-floppy-o')); ?>"></i></div>
        </div>

        <div class="qfb-settings">

            <div class="qfb-settings-heading"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-power_settings_new"></i><?php esc_html_e('Enable Zapier integrations', 'quform-zapier'); ?></div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_zapier_enabled"><?php esc_html_e('Enabled', 'quform-zapier'); ?></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="checkbox" class="qfb-toggle" id="qfb_zapier_enabled" <?php checked($options->get('enabled')); ?>>
                        <label for="qfb_zapier_enabled"></label>
                        <p class="qfb-description"><?php esc_html_e('Enable or disable all Zapier integrations.', 'quform-zapier'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-settings-heading"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-group_add"></i><?php esc_html_e('Permissions', 'quform-zapier'); ?></div>

            <p class="qfb-description qfb-below-heading"><?php esc_html_e('These options allow you to give permissions for other Roles to access parts of the plugin.', 'quform-zapier'); ?></p>

            <div class="qfb-setting">

                <div class="qfb-table qfb-permissions-table">
                    <div class="qfb-table-row">
                        <div class="qfb-table-cell"></div>
                        <?php foreach ($caps as $capName) : ?>
                            <div class="qfb-table-cell qfb-permissions-capability-name"><?php echo esc_html($capName); ?></div>
                        <?php endforeach; ?>
                    </div>

                    <?php foreach ($roles as $roleKey => $role) : ?>
                        <?php
                            if ($roleKey === 'administrator') {
                                continue;
                            }
                        ?>
                        <div class="qfb-table-row">
                            <div class="qfb-table-cell qfb-permissions-role-name"><?php echo esc_html($role['name']); ?></div>
                            <?php foreach ($caps as $cap => $capName) : ?>
                                <?php
                                    $id = sprintf('qfb-capability-%s-%s', $roleKey, $cap);
                                    $checked = isset($role['capabilities'][$cap]) && $role['capabilities'][$cap] ? ' checked' : '';
                                ?>
                                <div class="qfb-table-cell">
                                    <input type="checkbox" id="<?php echo esc_attr($id); ?>" class="qfb-permissions-capability qfb-mini-toggle" data-capability="<?php echo esc_attr($cap); ?>" data-role="<?php echo esc_attr($roleKey); ?>" <?php echo $checked; ?>>
                                    <label for="<?php echo esc_attr($id); ?>"></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                </div>

            </div>

            <div class="qfb-settings-heading"><i class="<?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-trash-o')); ?>"></i> <?php esc_html_e('Uninstall Quform Zapier', 'quform-zapier'); ?></div>

            <div class="qfb-setting">

                <div class="qfb-setting-label">
                    <label for="qfb_zapier_uninstall_confirm"><?php esc_html_e('Confirm uninstall', 'quform-zapier'); ?></label>
                </div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <div class="qfb-cf">
                            <input type="checkbox" id="qfb_zapier_uninstall_confirm" name="qfb_zapier_uninstall_confirm" class="qfb-toggle" value="1">
                            <label for="qfb_zapier_uninstall_confirm"></label>
                        </div>
                        <p class="qfb-description"><?php esc_html_e('Confirm that you want to delete all Zapier integrations and plugin settings, and deactivate the plugin. You can delete the plugin afterwards from the Plugins page.', 'quform-zapier'); ?></p>
                    </div>
                </div>

            </div>

            <div class="qfb-setting qfb-hidden">

                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">

                        <div class="qfb-message-box qfb-message-box-error">
                            <div class="qfb-message-box-inner">
                                <?php esc_html_e('WARNING: All Zapier integrations and plugin settings will be deleted cannot be recovered unless you have a backup of the database.', 'quform-zapier'); ?>
                            </div>
                        </div>

                        <button type="button" id="qfb-zapier-do-uninstall" class="qfb-button-green"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-delete_forever"></i> <?php esc_attr_e('Uninstall', 'quform-zapier'); ?></button>
                        <span id="qfb-zapier-uninstall-loading" class="qfb-loading-spinner"></span>

                    </div>
                </div>

            </div>

        </div>

        <div class="qfb-save-settings-wrap qfb-cf"><span id="qfb-zapier-save-settings" class="qfb-button-green"><i class="<?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-floppy-o')); ?>"></i> <?php esc_attr_e('Save', 'quform-zapier'); ?></span><span class="qfb-save-settings-loading"></span></div>

    </form>
</div>
