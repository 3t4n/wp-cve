<?php if (!defined('ABSPATH')) exit;
/** @var Quform_Mailchimp_Admin_Page_Integrations_List $page */
/** @var Quform_Mailchimp_Integration_List_Table $table */
/** @var int $perPage */
/** @var string $mdiPrefix */
?><div class="qfb qfb-cf">
    <?php
        echo $page->getMessagesHtml();
        echo $page->getNavHtml();
        echo $page->getSubNavHtml();
    ?>
    <form method="get">
        <input type="hidden" name="page" value="quform.mailchimp">
        <?php
            // Remove action results from sortable headers and row actions
            $_SERVER['REQUEST_URI'] = remove_query_arg(array('activated', 'deactivated', 'duplicated', 'trashed', 'untrashed', 'deleted', 'error'), $_SERVER['REQUEST_URI']);

            $table->search_box(esc_html__('Search', 'quform-mailchimp'), 'qfb-search-integrations');
            $table->views();
            $table->display();
        ?>
    </form>

    <div id="qfb-mc-integrations-table-settings" class="qfb-popup">
        <div id="qfb-mc-integrations-table-settings-inner" class="qfb-popup-content">
            <div class="qfb-settings">

                <div class="qfb-settings-heading"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-list"></i><?php esc_html_e('Table options', 'quform-mailchimp'); ?></div>

                <div class="qfb-setting">
                    <div class="qfb-setting-label">
                        <label for="qfb_mailchimp_integrations_per_page"><?php esc_html_e('Integrations per page', 'quform-mailchimp'); ?></label>
                    </div>
                    <div class="qfb-setting-inner">
                        <div class="qfb-setting-input">
                            <input type="text" id="qfb_mailchimp_integrations_per_page" value="<?php echo Quform::escape($perPage); ?>">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="qfb-popup-buttons">
            <div class="qfb-popup-save-button"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-check"></i></div>
            <div class="qfb-popup-close-button"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-close"></i></div>
        </div>

        <div class="qfb-popup-overlay"></div>
    </div>

    <div id="qfb-add-new-mc-integration-popup" class="qfb-popup">
        <div id="qfb-add-new-mc-integration-popup-inner" class="qfb-popup-content">
            <div>
                <div class="qfb-settings">

                    <div class="qfb-settings-heading"><?php esc_html_e('Create an integration', 'quform-mailchimp'); ?></div>
                    <div class="qfb-settings-subheading"><?php esc_html_e('Enter a name to help you identify the integration', 'quform-mailchimp'); ?></div>

                    <div class="qfb-setting qfb-setting-label-above">
                        <div class="qfb-setting-inner">
                            <div class="qfb-setting-input">
                                <input type="text" id="qfb-add-new-mc-integration-name" placeholder="<?php esc_attr_e('e.g. Contact form to Newsletter', 'quform-mailchimp'); ?>" maxlength="64">
                            </div>
                        </div>
                    </div>

                    <div class="qfb-setting">
                        <div class="qfb-add-new-mc-integration-buttons qfb-cf">
                            <div id="qfb-add-new-mc-integration-submit" class="qfb-button-green"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-add_circle"></i><?php esc_html_e('Add Integration', 'quform-mailchimp'); ?></div>
                            <span id="qfb-add-new-mc-integration-loading" class="qfb-loading-spinner"></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="qfb-popup-buttons">
            <div class="qfb-popup-close-button"><i class="<?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-close"></i></div>
        </div>

        <div class="qfb-popup-overlay"></div>

    </div>

</div>
