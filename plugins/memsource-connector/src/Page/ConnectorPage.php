<?php

namespace Memsource\Page;

use Memsource\Service\ExternalPlugin\AcfPlugin;
use Memsource\Service\OptionsService;
use Memsource\Service\TranslationWorkflowService;

class ConnectorPage extends AbstractPage
{
    /** @var OptionsService */
    private $optionsService;

    /** @var TranslationWorkflowService */
    private $translationWorkflowService;

    /** @var AcfPlugin */
    private $acfPlugin;

    public function __construct(OptionsService $optionsService, TranslationWorkflowService $translationWorkflowService, AcfPlugin $acfPlugin)
    {
        $this->optionsService = $optionsService;
        $this->translationWorkflowService = $translationWorkflowService;
        $this->acfPlugin = $acfPlugin;
    }

    public function initPage()
    {
        add_menu_page('Phrase Connector', 'Phrase', 'manage_options', 'memsource-connector', [$this, 'renderPage'], plugin_dir_url(__FILE__) . '../../images/phrase-icon.svg');
        add_submenu_page('memsource-connector', 'Phrase Connector', 'Connector', 'manage_options', 'memsource-connector', [$this, 'renderPage']);
    }

    public function renderPage()
    {
        $listStatuses = $this->optionsService->getListStatuses();
        $insertStatus = $this->optionsService->getInsertStatus();
        $urlRewrite = $this->optionsService->isUrlRewriteEnabled();
        $copyPermalink = $this->optionsService->isCopyPermalinkEnabled();
        ?>
        <script>
            jQuery(document).ready(function() {
                initClipboard('<?php _e('Copied!', 'memsource'); ?>');
                checkListStatuses();
            });
            function generateToken() {
                var data = {
                    action: 'generate_token'
                };
                jQuery.post(ajaxurl, data, function(response) {
                    jQuery('#token-text-field').val(response.token);
                });
            }
            function checkListStatuses() {
                var element = jQuery('#list-status-warning');
                var checked = jQuery('#list-status-section').find('input:checked');
                if (checked.length === 0) {
                    element.html('<span class="red-icon"><?php _e('Not selecting any statuses will result in no content being offered for translation. Saving changes now will choose publish status automatically.', 'memsource'); ?></span>');
                } else {
                    element.html('');
                }
            }
        </script>
        <div class="memsource-admin-area">
            <div class="memsource-admin-header">
                <img class="memsource-logo"
                     src="<?php echo MEMSOURCE_PLUGIN_DIR_URL ?>/images/phrase-logo.svg"/>
                <span class="memsource-label"><?php _e('Settings', 'memsource'); ?></span>
            </div>
            <div class="memsource-space"></div>
            <div class="memsource-admin-title"><?php _e('Connect Phrase TMS plugin to Phrase TMS', 'memsource'); ?></div>
            <div class="memsource-admin-section">
                <div class="memsource-admin-section-title"><?php _e('Connector', 'memsource'); ?></div>
                <div class="memsource-admin-section-description">
                    <p><?php _e('Phrase TMS enables integrations with several CMS systems and online repositories, including WordPress. Connectors allow users to connect their Phrase TMS account with these systems, draw content for translations directly from them into Phrase TMS, and deliver translated content back into the system in the same file format.', 'memsource'); ?></p>
                    <p class="memsource-subtitle"><?php _e('When should you use the connector?', 'memsource'); ?></p>
                    <p><?php _e('If you will be selecting the content for translation from within Phrase TMS, use the settings below to establish the connection between your WordPress site and Phrase TMS. The connector feature also allows for a fully automated process of pulling translation content from WordPress.', 'memsource'); ?></p>
                    <p><?php _e('See our documentation for a step-by-step guide on how to <a href="https://support.phrase.com/hc/en-us/articles/5709657294620" target="_blank">Set up the integration in Phrase TMS</a> and how to <a href="https://support.phrase.com/hc/en-us/articles/5709647363356" target="_blank">Automate Project Creation in Phrase TMS</a>.', 'memsource'); ?></p>
                </div>

                <!-- Connector settings toggle -->

                <div id="memsource-admin-toggle-connector" class="memsource-expand-link">
                    <span class="dashicons dashicons-admin-generic gray-icon"></span>
                    <span id="memsource-admin-link-connector" class="clickable underline"
                          onclick="toggleSection('connector', '<?php _e('Show Connector settings', 'memsource'); ?>', '<?php _e('Hide Connector settings', 'memsource'); ?>')">
                        <?php _e('Show Connector settings', 'memsource'); ?>
                    </span>
                    <span id="memsource-admin-arrow-connector" class="dashicons dashicons-arrow-down gray-icon"></span>
                </div>

                <div id="memsource-admin-section-connector" class="memsource-section-init">

                    <!-- Connector token -->

                    <label for="token-text-field">
                        <strong>
                            <?php _e('Phrase TMS Connector authentication token', 'memsource'); ?>:
                        </strong>
                    </label>
                    <div class="memsource-space-small"></div>
                    <input id="token-text-field"
                           type="text"
                           name="token"
                           value="<?php echo $this->optionsService->getToken(); ?>"
                           readonly
                           class="memsource-token-field"/>
                    <button id="token-copy"
                            class="btn"
                            data-clipboard-target="#token-text-field">
                        <?php _e('Copy to clipboard', 'memsource'); ?>
                    </button>
                    <span id="token-copy-result"></span>
                    <div class="memsource-space-small"></div>
                    <span class="clickable underline"
                          onclick="generateToken()">
                        <?php _e('Generate new token', 'memsource'); ?>
                    </span>
                    <div class="memsource-space-big"></div>

                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">

                        <!-- Post statuses -->

                        <input type="hidden" name="action" value="save_connector_options"/>
                        <div id="list-status-section" class="checkbox-section">
                            <strong>
                                <?php _e('Import posts with the following status', 'memsource'); ?>:
                            </strong>
                            <div class="memsource-space-small"></div>
                            <input type="checkbox" id="list-status-publish" name="list-status-publish" onclick="checkListStatuses()" value="publish" <?php echo(in_array("publish", $listStatuses, true) ? "checked" : ""); ?>/>
                            <label for="list-status-publish">
                                <?php _e('publish', 'memsource'); ?></label>
                            <br/>
                            <input type="checkbox" id="list-status-draft" name="list-status-draft" onclick="checkListStatuses()" value="draft" <?php echo(in_array("draft", $listStatuses, true) ? "checked" : ""); ?>/>
                            <label for="list-status-draft"><?php _e('draft', 'memsource'); ?></label>
                        </div>
                        <div class="checkbox-section">
                            <strong>
                                <?php _e('Set status for exported posts to', 'memsource'); ?>:
                            </strong>
                            <div class="memsource-space-small"></div>
                            <input type="radio" id="insert-status-publish" name="insert-status" value="publish" <?php echo($insertStatus === "publish" ? "checked" : ""); ?>/>
                            <label for="insert-status-publish"><?php _e('publish', 'memsource'); ?></label>
                            <br/>
                            <input type="radio" id="insert-status-draft" name="insert-status" value="draft" <?php echo($insertStatus === "draft" ? "checked" : ""); ?>/>
                            <label for="insert-status-draft"><?php _e('draft', 'memsource'); ?></label>
                        </div>
                        <div class="memsource-space-small"></div>
                        <div id="list-status-warning"></div>
                        <div class="memsource-space-big"></div>

                        <!-- URL rewriting -->

                        <div id="url-rewrite-section" class="checkbox-section">
                            <strong>
                                <?php _e('URL rewriting', 'memsource'); ?>:
                            </strong>
                            <div class="memsource-space-small"></div>
                            <input type="checkbox" id="url-rewrite" name="url-rewrite" value="on" <?php echo $urlRewrite ? 'checked' : ''; ?>/>
                            <label
                                for="url-rewrite"
                                title="<?php _e('URLs or Slugs are not be localised by default, checking this box will enable the ability to localise URLs or Slugs and target copies will have changed URLs based on the WMPL behaviour setup', 'memsource'); ?>"
                                class="memsource-tooltip"
                            >
                                <?php _e('Rewrite URLs when exporting jobs from Phrase TMS', 'memsource'); ?>
                            </label>
                            <div class="memsource-space-smaller"></div>
                            <input type="checkbox" id="copy-permalink" name="copy-permalink" value="on" <?php echo $copyPermalink ? 'checked' : ''; ?>/>
                            <label for="copy-permalink">
                                <?php _e('Translated content will use the same permalink as the source', 'memsource'); ?>
                            </label>
                            <br/>
                        </div>
                        <div class="memsource-space-big"></div>

                        <!-- Translation workflow -->

                        <strong>
                            <?php _e('Translation workflow', 'memsource'); ?>:
                        </strong>

                        <?php if ($this->translationWorkflowService->isAcfEnabled()) { ?>
                            <fieldset>
                        <?php } else { ?>
                            <p>
                                <strong>
                                    <?php _e('To use this feature the best possible way, please make sure you have installed the Advanced Custom Fields (ACF) plugin. This will allow you to create a multi-value picklist.', 'memsource'); ?>
                                </strong>
                            </p>
                            <fieldset disabled="disabled" style="color: gray">
                        <?php } ?>

                            <p>
                                <?php _e('Configure the plugin to use a specific field to represent stages in the translation workflow for Posts, Pages, and Custom Posts. 
                                The recommended type is Select with values: Translate, In translation, Translation ready, that can be left empty.', 'memsource'); ?>
                            </p>

                            <table>
                                <tr>
                                    <td>
                                        <label for="translation-workflow-field-name">
                                            <?php _e('Field that represents translation workflow stages', 'memsource'); ?>:
                                        </label>
                                    </td>
                                    <td>
                                        <?php $this->acfSelectBox(TranslationWorkflowService::FIELD_NAME); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label
                                            for="translation-workflow-field-value-list"
                                            title="<?php _e('Phrase TMS will import content with the specified value (e.g. Translate). The author can apply this value to content when ready for translation.', 'memsource'); ?>"
                                            class="memsource-tooltip"
                                        >
                                            <?php _e('Translate content with value', 'memsource'); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            id="translation-workflow-field-value-list"
                                            name="translation-workflow[<?php echo TranslationWorkflowService::FIELD_VALUE_LIST; ?>]"
                                            value="<?php echo $this->translationWorkflowService->getValue(TranslationWorkflowService::FIELD_VALUE_LIST); ?>"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label
                                            for="translation-workflow-field-value-submit"
                                            title="<?php _e('The field value is updated to the specified value (e.g. In translation) when the import of source content to Phrase TMS successfully completes.', 'memsource'); ?>"
                                            class="memsource-tooltip"
                                        >
                                            <?php _e('Upon submission to Phrase TMS set it to', 'memsource'); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            id="translation-workflow-field-value-submit"
                                            name="translation-workflow[<?php echo TranslationWorkflowService::FIELD_VALUE_SUBMIT; ?>]"
                                            value="<?php echo $this->translationWorkflowService->getValue(TranslationWorkflowService::FIELD_VALUE_SUBMIT); ?>"
                                        />
                                    </td>
                                </tr>
                                <tr style="height: 40px">
                                    <td>
                                        <span
                                            title="<?php _e('The field value is updated to the specified value (e.g. Translation ready) when the import of translations from Phrase successfully completes.', 'memsource'); ?>" class="memsource-tooltip"
                                            style=""
                                        >
                                            <?php _e('Upon retrieval of translations', 'memsource'); ?>:
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right">
                                        <label for="translation-workflow-field-value-translate-source">
                                            <?php _e('Set source content to', 'memsource'); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            id="translation-workflow-field-value-translate-source"
                                            name="translation-workflow[<?php echo TranslationWorkflowService::FIELD_VALUE_TRANSLATE_SOURCE; ?>]"
                                            value="<?php echo $this->translationWorkflowService->getValue(TranslationWorkflowService::FIELD_VALUE_TRANSLATE_SOURCE); ?>"
                                        />
                                    </td>
                                    <td>
                                        <?php _e('when the first translation is returned', 'memsource'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right">
                                        <label for="translation-workflow-field-value-translate-target">
                                            <?php _e('Set translated content to', 'memsource'); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            id="translation-workflow-field-value-translate-target"
                                            name="translation-workflow[<?php echo TranslationWorkflowService::FIELD_VALUE_TRANSLATE_TARGET; ?>]"
                                            value="<?php echo $this->translationWorkflowService->getValue(TranslationWorkflowService::FIELD_VALUE_TRANSLATE_TARGET); ?>"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div class="memsource-space"></div>

                            <strong>
                                <?php _e('Target languages', 'memsource'); ?>:
                            </strong>

                            <p>
                                <?php _e('Automated Project Creation only creates jobs for languages specified in the custom field identified by its Field Name – e.g. "target_languages". 
                                The field type should be the multi-value picklist, where the values match the Phrase TMS <a href="https://support.phrase.com/hc/en-us/articles/5709608511516" target="_blank">language codes</a>.', 'memsource'); ?>
                            </p>

                            <table>
                                <tr>
                                    <td>
                                        <label for="translation-workflow-field-name-target-languages">
                                            <?php _e('Translate to languages specified in (APC)', 'memsource'); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <?php $this->acfSelectBox(TranslationWorkflowService::FIELD_NAME_TARGET_LANGUAGES); ?>
                                    </td>
                                </tr>
                            </table>

                            <div class="memsource-space-big"></div>

                        </fieldset>

                        <input type="submit" class="memsource-button" value="<?php _e('Save', 'memsource'); ?>"/>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    private function acfSelectBox(string $field)
    {
        ?>
        <select
                id="translation-workflow-field-name"
                name="translation-workflow[<?php echo $field; ?>]">
            <option value=""></option>
            <?php
            foreach ($this->acfPlugin->getAllAcfFields() as $acfField) {
                if ($this->translationWorkflowService->getValue($field) === $acfField) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                echo "<option value=\"$acfField\" $selected>$acfField</option>";
            }
            ?>
        </select>
        <?php
    }
}
