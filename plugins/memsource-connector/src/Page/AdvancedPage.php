<?php

namespace Memsource\Page;

use Memsource\Service\OptionsService;
use Memsource\Utils\LogUtils;

class AdvancedPage extends AbstractPage
{
    /** @var OptionsService */
    private $optionsService;

    public function __construct(OptionsService $optionsService)
    {
        $this->optionsService = $optionsService;
    }

    public function initPage()
    {
        add_submenu_page('memsource-connector', 'Advanced Options', 'Advanced', 'manage_options', 'memsource-connector-advanced', [$this, 'renderPage']);
    }

    public function renderPage()
    {
        ?>
        <script>
            jQuery(document).ready(function() {
                initClipboard('<?php _e('Copied!', 'memsource'); ?>');
            });
            function emailToMemsource() {
                if (confirm("<?php _e('Do you really want to send the log file to Phrase?', 'memsource'); ?>")) {
                    var data = {
                        action: 'zip_and_email_log'
                    };
                    jQuery('#email-spinner').addClass('is-active');
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#email-spinner').removeClass('is-active');
                        jQuery('#email-result').html('File ' + response.zipFile + ' has been sent to Phrase.');
                    });
                }
            }
            function deleteLogFile() {
                if (confirm("<?php _e('Do you really want to delete the log file?', 'memsource'); ?>")) {
                    var data = {
                        action: 'delete_log'
                    };
                    jQuery('#delete-spinner').addClass('is-active');
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#delete-spinner').removeClass('is-active');
                        var files = [];
                        if (response.logDeleted) {
                            files.push(response.logDeleted);
                        }
                        if (response.zipDeleted) {
                            files.push(response.zipDeleted);
                        }
                        jQuery('#delete-result').html('Files ' + files.join(', ') + ' has been deleted.');
                    });
                }
            }
        </script>

        <div class="memsource-admin-header">
            <img class="memsource-logo"
                 src="<?php echo MEMSOURCE_PLUGIN_DIR_URL ?>/images/phrase-logo.svg"/>
            <span class="memsource-label"><?php _e('Advanced Settings', 'memsource'); ?></span>
        </div>
        <div class="memsource-space"></div>
        <div class="memsource-admin-section-description">
            <?php _e('This page contains options and tools to help us investigate potential issues with the plugin. Please do not do any changes here unless we ask you to do so.', 'memsource'); ?>
        </div>
        <div class="memsource-space-big"></div>

        <!-- Memsource plugin settings -->

        <h3>
            <?php _e('Phrase TMS plugin settings', 'memsource'); ?>
        </h3>
        <p>
            <?php _e('Active multilingual plugin', 'memsource'); ?>: <b><?php echo $this->optionsService->getActiveTranslationPluginKey(); ?></b>
        </p>
        <?php if ($this->optionsService->isDebugMode()) { ?>
            <div id="memsource-admin-toggle-options" class="memsource-expand-link">
                    <span id="memsource-admin-link-options" class="clickable underline"
                          onclick="toggleSection('options', '<?php _e('Show Phrase TMS Plugin options', 'memsource'); ?>', '<?php _e('Hide Phrase TMS Plugin options', 'memsource'); ?>')">
                        <?php _e('Show Phrase TMS Plugin options', 'memsource'); ?>
                    </span>
                <span id="memsource-admin-arrow-options" class="dashicons dashicons-arrow-down normal-icon"></span>
            </div>
            <div id="memsource-admin-section-options" class="memsource-section-init">
                <?php
                $textarea = '';
                $memsourceOptions = $this->optionsService->getAllMemsourceOptions();
                foreach ($memsourceOptions as $option) {
                    $textarea .= $option['option_name'] . ': ' . $option['option_value'] . "\n";
                }
                ?>
                <textarea id="memsource-options" class="textarea-options"
                          title="<?php _e('Phrase TMS Plugin options', 'memsource'); ?>"
                          readonly><?php echo $textarea; ?></textarea>
                <br/>
                <button id="options-copy" class="btn"
                        data-clipboard-target="#memsource-options"><?php _e('Copy to clipboard', 'memsource'); ?></button>
                <span id="options-copy-result"></span>
            </div>
        <?php } ?>
        <div class="memsource-space-big"></div>

        <!-- Debug mode -->

        <h3>
            <?php _e('Debug mode', 'memsource'); ?>
        </h3>
        <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="action" value="set_debug_mode"/>
            <input id="debugMode" type="checkbox" name="debugMode"
                <?php echo($this->optionsService->isDebugMode() ? " checked" : "") ?>/>
            <label for="debugMode"><?php _e('Debug mode', 'memsource'); ?></label>
            <div class="memsource-space"></div>
            <input type="submit" class="memsource-button" value="<?php _e('Save', 'memsource'); ?>"/>
        </form>
        <div class="memsource-space-big"></div>


        <!-- Log file -->

        <?php if ($this->optionsService->isDebugMode()) { ?>
            <h3>
                <?php _e('Log file', 'memsource'); ?>
            </h3>
            <div class="memsource-space"></div>
            <?php _e('Phrase log file name', 'memsource'); ?>: <?php echo LogUtils::LOG_FILE_NAME; ?><br/>
            <?php _e('Phrase log file size', 'memsource'); ?>: <?php echo LogUtils::getLogFileSizeFormatted(); ?>
            <div class="memsource-space"></div>
            <?php if (LogUtils::getLogFileSize() > 0) { ?>
                <div>
                    <form target="_blank" style="display: inline">
                        <input type="hidden" name="action" value="download_logs"/>
                        <button id="download-button" class="memsource-button auto-size">
                            <?php _e('Download the log file', 'memsource'); ?>
                        </button>
                    </form>
                    <input id="email-button" class="memsource-button auto-size" type="button"
                           value="<?php _e('Zip and email log file to Phrase', 'memsource'); ?>"
                           onclick="emailToMemsource()"/>
                    <span id="email-spinner" class="spinner"></span>
                </div>
                <div id="email-result"></div>
                <div class="memsource-space"></div>
                <div>
                    <input id="delete-button" class="memsource-button auto-size" type="button"
                           value="<?php _e('Delete the log file', 'memsource'); ?>"
                           onclick="deleteLogFile()"/>
                    <span id="delete-spinner" class="spinner"></span>
                </div>
                <div id="delete-result"></div>
            <?php } ?>
        <?php } ?>

        <?php
    }
}
