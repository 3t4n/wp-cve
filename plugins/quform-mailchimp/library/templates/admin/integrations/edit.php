<?php if (!defined('ABSPATH')) exit;
/** @var Quform_Mailchimp_Admin_Page_Integrations_Edit $page */
/** @var Quform_Mailchimp_Integration_Builder $integrationBuilder */
/** @var array $integration */
/** @var array $forms */
/** @var string $mdiPrefix */
?><div class="qfb qfb-cf">
    <?php
        echo $page->getMessagesHtml();
        echo $page->getNavHtml();
        echo $page->getSubNavHtml();
    ?>

    <noscript>
        <div class="qfb-message-box qfb-message-box-error">
            <?php esc_html_e('Please enable JavaScript to use this page', 'quform-mailchimp'); ?>
        </div>
    </noscript>

    <form id="qfb-mc-integrations-edit-form" autocomplete="off" method="post" action="<?php echo esc_url(add_query_arg(array())); ?>">
        <input type="submit" class="qfb-hidden"><!-- Prevent enter key submitting the form -->
        <input type="password" class="qfb-hidden"><!-- Stop Chrome 34+ autofilling -->

        <div class="qfb-fixed-buttons">
            <div id="qfb-fixed-save-button" class="qfb-animated-save-button" title="<?php esc_attr_e('Save', 'quform-mailchimp'); ?>"><i class="<?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-floppy-o')); ?>"></i></div>
        </div>

        <div class="qfb-settings">

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_mc_integration_name"><?php esc_html_e('Name', 'quform-mailchimp'); ?><span class="qfb-required">*</span></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="text" id="qfb_mc_integration_name" value="<?php echo Quform::escape($integrationBuilder->getIntegrationConfigValue($integration, 'name')); ?>">
                        <p class="qfb-description"><?php esc_html_e('Enter a name to help you identify the integration.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_mc_integration_active"><?php esc_html_e('Active', 'quform-mailchimp'); ?></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="checkbox" class="qfb-toggle" id="qfb_mc_integration_active" <?php checked($integrationBuilder->getIntegrationConfigValue($integration, 'active')); ?>>
                        <label for="qfb_mc_integration_active"></label>
                        <p class="qfb-description"><?php esc_html_e('Activate or deactivate this integration.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_mc_integration_form"><?php esc_html_e('Form', 'quform-mailchimp'); ?><span class="qfb-required">*</span></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <select id="qfb_mc_integration_form">
                            <option value=""><?php esc_html_e('Please select', 'quform-mailchimp'); ?></option>
                            <?php foreach ($forms as $id => $name) : ?>
                                <option value="<?php echo Quform::escape($id); ?>" <?php selected($integrationBuilder->getIntegrationConfigValue($integration, 'formId'), $id); ?>><?php echo Quform::escape($name); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="qfb-description"><?php esc_html_e('Choose the form to add contacts from.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label">
                    <div id="qfb-mc-integration-list-sync" class="qfb-button qfb-icon-button" title="<?php esc_attr_e('Refresh the available lists', 'quform-mailchimp'); ?>"><i class="<?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-refresh')); ?>"></i></div>
                    <label for="qfb_mc_integration_list"><?php esc_html_e('List', 'quform-mailchimp'); ?><span class="qfb-required">*</span></label>
                </div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <div class="qfb-cf">
                            <select id="qfb_mc_integration_list" class="qfb-hidden">
                                <option value="<?php echo Quform::escape($integrationBuilder->getIntegrationConfigValue($integration, 'listId')); ?>" selected><?php echo Quform::escape($integrationBuilder->getIntegrationConfigValue($integration, 'listName')); ?></option>
                            </select>
                            <div id="qfb-mc-integration-list-spinner" class="qfb-spinner" style="display: block;"></div>
                            <div id="qfb-mc-integration-list-error" class="qfb-message-box qfb-message-box-error qfb-hidden"></div>
                        </div>
                        <p class="qfb-description"><?php esc_html_e('Choose the Mailchimp list to add contacts to.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label">
                    <div id="qfb-mc-integration-email-sync" class="qfb-button qfb-icon-button" title="<?php esc_attr_e('Refresh the available email address fields', 'quform-mailchimp'); ?>"><i class="<?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-refresh')); ?>"></i></div>
                    <label for="qfb_mc_integration_email_element"><?php esc_html_e('Email address field', 'quform-mailchimp'); ?><span class="qfb-required">*</span></label>
                </div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <div class="qfb-cf">
                            <select id="qfb_mc_integration_email_element" class="qfb-hidden">
                                <option value="<?php echo Quform::escape($integrationBuilder->getIntegrationConfigValue($integration, 'emailElement')); ?>" selected></option>
                            </select>
                            <div id="qfb-mc-integration-email-spinner" class="qfb-spinner" style="display: block;"></div>
                            <div id="qfb-mc-integration-email-error" class="qfb-message-box qfb-message-box-error qfb-hidden"></div>
                        </div>
                        <p class="qfb-description"><?php esc_html_e('Choose the email address form field.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_mc_integration_double_opt_in"><?php esc_html_e('Double opt-in', 'quform-mailchimp'); ?></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="checkbox" class="qfb-toggle" id="qfb_mc_integration_double_opt_in" <?php checked($integrationBuilder->getIntegrationConfigValue($integration, 'doubleOptIn')); ?>>
                        <label for="qfb_mc_integration_double_opt_in"></label>
                        <p class="qfb-description"><?php esc_html_e('Send contacts an opt-in confirmation email when they subscribe to your list.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label">
                    <div id="qfb-mc-integration-merge-fields-sync" class="qfb-button qfb-icon-button" title="<?php esc_attr_e('Refresh the available merge field elements and tags', 'quform-mailchimp'); ?>"><i class="<?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-refresh')); ?>"></i></div>
                    <label><?php esc_html_e('Merge fields', 'quform-mailchimp'); ?></label>
                    <div id="qfb-mc-merge-add-merge-field" class="qfb-button-green"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-add_circle"></i><?php esc_html_e('Add merge field', 'quform-mailchimp'); ?></div>
                </div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <div class="qfb-cf">
                            <div id="qfb-mc-merge-fields" class="qfb-hidden"></div>
                            <div id="qfb-mc-integration-merge-fields-spinner" class="qfb-spinner" style="display: block;"></div>
                            <div id="qfb-mc-integration-merge-fields-error" class="qfb-message-box qfb-message-box-error qfb-hidden"></div>
                            <div id="qfb-mc-integration-merge-fields-empty" class="qfb-message-box qfb-message-box-info qfb-hidden"><?php esc_html_e('There are no merge fields yet, click "Add merge field" to add one.', 'quform-mailchimp'); ?></div>
                        </div>
                        <p class="qfb-description"><?php esc_html_e('Save additional information about the contact.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label">
                    <div id="qfb-mc-integration-groups-sync" class="qfb-button qfb-icon-button" title="<?php esc_attr_e('Refresh the available groups', 'quform-mailchimp'); ?>"><i class="<?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-refresh')); ?>"></i></div>
                    <label for="qfb_mc_integration_groups"><?php esc_html_e('Groups', 'quform-mailchimp'); ?></label>
                </div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <div class="qfb-cf">
                            <div id="qfb-mc-integration-groups-outer">
                                <select id="qfb_mc_integration_groups" multiple data-placeholder="<?php esc_attr_e('Choose groups', 'quform-mailchimp'); ?>" style="width:100%;"></select>
                            </div>
                            <div id="qfb-mc-integration-groups-spinner" class="qfb-spinner" style="display: block;"></div>
                            <div id="qfb-mc-integration-groups-error" class="qfb-message-box qfb-message-box-error qfb-hidden"></div>
                        </div>
                        <p class="qfb-description"><?php esc_html_e('Choose the groups to add the contact to.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_mc_integration_tags"><?php esc_html_e('Tags', 'quform-mailchimp'); ?></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="text" id="qfb_mc_integration_tags" value="<?php echo Quform::escape($integrationBuilder->getIntegrationConfigValue($integration, 'tags')); ?>">
                        <p class="qfb-description"><?php esc_html_e('Enter a comma-separated list of tags to apply to the contact.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_mc_integration_logic_enabled"><?php esc_html_e('Enable conditional logic', 'quform-mailchimp'); ?></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="checkbox" class="qfb-toggle" id="qfb_mc_integration_logic_enabled" <?php checked($integrationBuilder->getIntegrationConfigValue($integration, 'logicEnabled')); ?>>
                        <label for="qfb_mc_integration_logic_enabled"></label>
                        <p class="qfb-description"><?php esc_html_e('Create rules to determine whether or not to run this integration based on the values of form fields.', 'quform-mailchimp'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting<?php echo ! $integrationBuilder->getIntegrationConfigValue($integration, 'logicEnabled') ? ' qfb-hidden' : ''; ?>">
                <div class="qfb-setting-label">
                    <div id="qfb-mc-integration-logic-sync" class="qfb-button qfb-icon-button" title="<?php esc_attr_e('Refresh the available elements', 'quform-mailchimp'); ?>"><i class="<?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-refresh')); ?>"></i></div>
                    <label><?php esc_html_e('Logic rules', 'quform-mailchimp'); ?></label>
                    <div class="qfb-add-logic-rule-wrap qfb-cf">
                        <a id="qfb-add-logic-rule" class="qfb-button-green"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-add_circle"></i><?php esc_html_e('Add logic rule', 'quform-mailchimp'); ?></a>
                    </div>
                </div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <div id="qfb-mc-logic" class="qfb-logic qfb-cf"></div>
                        <div id="qfb-mc-integration-logic-spinner" class="qfb-spinner" style="display: block;"></div>
                        <div id="qfb-mc-logic-error" class="qfb-message-box qfb-message-box-error qfb-hidden"></div>
                    </div>
                </div>
            </div>

        </div>

        <div id="qfb-mc-insert-variable" class="qfb-mc-insert-variable-menu">
            <div class="qfb-mc-insert-variable-heading"><?php esc_html_e('Submitted Form Value', 'quform-mailchimp'); ?></div>
            <div id="qfb-mc-insert-variable-element"></div>
            <?php
                foreach ($integrationBuilder->getVariables() as $variable) {
                    echo '<div class="qfb-mc-insert-variable-heading">' . esc_html($variable['heading']) . '</div>';
                    foreach ($variable['variables'] as $tag => $description) {
                        echo '<div class="qfb-mc-variable" data-tag="' .  esc_attr($tag) . '">' . esc_html($description) . '</div>';
                    }
                }
            ?>
        </div>

        <div class="qfb-save-settings-wrap qfb-cf"><span id="qfb-mc-save-integration" class="qfb-button-green"><i class="<?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-floppy-o')); ?>"></i> <?php esc_attr_e('Save', 'quform-mailchimp'); ?></span><span class="qfb-save-settings-loading"></span></div>

    </form>
    <script>
        //<![CDATA[
        jQuery(function () {
            quform.mailchimp.edit.init(<?php echo wp_json_encode($integration); ?>);
        });
        //]]>
    </script>
</div>
