<?php if (!defined('ABSPATH')) exit;
/** @var Quform_Zapier_Admin_Page_Integrations_Edit $page */
/** @var Quform_Zapier_Integration_Builder $integrationBuilder */
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
            <?php esc_html_e('Please enable JavaScript to use this page', 'quform-zapier'); ?>
        </div>
    </noscript>

    <form id="qfb-zapier-integrations-edit-form" autocomplete="off" method="post" action="<?php echo esc_url(add_query_arg(array())); ?>">
        <input type="submit" class="qfb-hidden"><!-- Prevent enter key submitting the form -->
        <input type="password" class="qfb-hidden"><!-- Stop Chrome 34+ autofilling -->

        <div class="qfb-fixed-buttons">
            <div id="qfb-fixed-save-button" class="qfb-animated-save-button" title="<?php esc_attr_e('Save', 'quform-zapier'); ?>"><i class="<?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-floppy-o')); ?>"></i></div>
        </div>

        <div class="qfb-settings">

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_zapier_integration_name"><?php esc_html_e('Name', 'quform-zapier'); ?><span class="qfb-required">*</span></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="text" id="qfb_zapier_integration_name" value="<?php echo Quform::escape($integrationBuilder->getIntegrationConfigValue($integration, 'name')); ?>">
                        <p class="qfb-description"><?php esc_html_e('Enter a name to help you identify the integration.', 'quform-zapier'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_zapier_integration_active"><?php esc_html_e('Active', 'quform-zapier'); ?></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="checkbox" class="qfb-toggle" id="qfb_zapier_integration_active" <?php checked($integrationBuilder->getIntegrationConfigValue($integration, 'active')); ?>>
                        <label for="qfb_zapier_integration_active"></label>
                        <p class="qfb-description"><?php esc_html_e('Activate or deactivate this integration.', 'quform-zapier'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_zapier_integration_form"><?php esc_html_e('Form', 'quform-zapier'); ?><span class="qfb-required">*</span></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <select id="qfb_zapier_integration_form">
                            <option value=""><?php esc_html_e('Please select', 'quform-zapier'); ?></option>
                            <?php foreach ($forms as $id => $name) : ?>
                                <option value="<?php echo Quform::escape($id); ?>" <?php selected($integrationBuilder->getIntegrationConfigValue($integration, 'formId'), $id); ?>><?php echo Quform::escape($name); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="qfb-description"><?php esc_html_e('Choose the form that will trigger this integration.', 'quform-zapier'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_zapier_integration_webhook_url"><?php esc_html_e('Webhook URL', 'quform-zapier'); ?><span class="qfb-required">*</span></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="text" id="qfb_zapier_integration_webhook_url" value="<?php echo Quform::escape($integrationBuilder->getIntegrationConfigValue($integration, 'webhookUrl')); ?>">
                        <p class="qfb-description"><?php esc_html_e('Enter the Webhook URL provided by Zapier.', 'quform-zapier'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label"><label for="qfb_zapier_integration_logic_enabled"><?php esc_html_e('Enable conditional logic', 'quform-zapier'); ?></label></div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <input type="checkbox" class="qfb-toggle" id="qfb_zapier_integration_logic_enabled" <?php checked($integrationBuilder->getIntegrationConfigValue($integration, 'logicEnabled')); ?>>
                        <label for="qfb_zapier_integration_logic_enabled"></label>
                        <p class="qfb-description"><?php esc_html_e('Create rules to determine whether or not to run this integration based on the values of form fields.', 'quform-zapier'); ?></p>
                    </div>
                </div>
            </div>

            <div class="qfb-setting<?php echo ! $integrationBuilder->getIntegrationConfigValue($integration, 'logicEnabled') ? ' qfb-hidden' : ''; ?>">
                <div class="qfb-setting-label">
                    <div id="qfb-zapier-integration-logic-sync" class="qfb-button qfb-icon-button" title="<?php esc_attr_e('Refresh the available elements', 'quform-zapier'); ?>"><i class="<?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-refresh')); ?>"></i></div>
                    <label><?php esc_html_e('Logic rules', 'quform-zapier'); ?></label>
                    <div class="qfb-add-logic-rule-wrap qfb-cf">
                        <a id="qfb-add-logic-rule" class="qfb-button-green"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-add_circle"></i><?php esc_html_e('Add logic rule', 'quform-zapier'); ?></a>
                    </div>
                </div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <div id="qfb-zapier-logic" class="qfb-logic qfb-cf"></div>
                        <div id="qfb-zapier-integration-logic-spinner" class="qfb-spinner" style="display: block;"></div>
                        <div id="qfb-zapier-logic-error" class="qfb-message-box qfb-message-box-error qfb-hidden"></div>
                    </div>
                </div>
            </div>

            <div class="qfb-setting">
                <div class="qfb-setting-label">
                    <div id="qfb-zapier-integration-additional-fields-sync" class="qfb-button qfb-icon-button" title="<?php esc_attr_e('Refresh the available field elements', 'quform-zapier'); ?>"><i class="<?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-refresh')); ?>"></i></div>
                    <label><?php esc_html_e('Additional fields', 'quform-zapier'); ?></label>
                    <div id="qfb-zapier-add-additional-field" class="qfb-button-green"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-add_circle"></i><?php esc_html_e('Add field', 'quform-zapier'); ?></div>
                </div>
                <div class="qfb-setting-inner">
                    <div class="qfb-setting-input">
                        <div class="qfb-cf">
                            <div id="qfb-zapier-additional-fields" class="qfb-hidden"></div>
                            <div id="qfb-zapier-integration-additional-fields-spinner" class="qfb-spinner" style="display: block;"></div>
                            <div id="qfb-zapier-integration-additional-fields-error" class="qfb-message-box qfb-message-box-error qfb-hidden"></div>
                            <div id="qfb-zapier-integration-additional-fields-empty" class="qfb-message-box qfb-message-box-info qfb-hidden"><?php esc_html_e('There are no additional fields yet, click "Add field" to add one.', 'quform-zapier'); ?></div>
                        </div>
                        <p class="qfb-description"><?php esc_html_e('All submitted form data is sent to Zapier, you can send extra data by adding additional fields.', 'quform-zapier'); ?></p>
                    </div>
                </div>
            </div>

        </div>

        <div id="qfb-zapier-insert-variable" class="qfb-zapier-insert-variable-menu">
            <div class="qfb-zapier-insert-variable-heading"><?php esc_html_e('Submitted Form Value', 'quform-zapier'); ?></div>
            <div id="qfb-zapier-insert-variable-element"></div>
            <?php
                foreach ($integrationBuilder->getVariables() as $variable) {
                    echo '<div class="qfb-zapier-insert-variable-heading">' . esc_html($variable['heading']) . '</div>';
                    foreach ($variable['variables'] as $tag => $description) {
                        echo '<div class="qfb-zapier-variable" data-tag="' .  esc_attr($tag) . '">' . esc_html($description) . '</div>';
                    }
                }
            ?>
        </div>

        <div class="qfb-save-settings-wrap qfb-cf"><span id="qfb-zapier-save-integration" class="qfb-button-green"><i class="<?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-floppy-o')); ?>"></i> <?php esc_attr_e('Save', 'quform-zapier'); ?></span><span class="qfb-save-settings-loading"></span></div>

    </form>
    <script>
        //<![CDATA[
        jQuery(function () {
            quform.zapier.edit.init(<?php echo wp_json_encode($integration); ?>);
        });
        //]]>
    </script>
</div>
