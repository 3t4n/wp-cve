<?php

namespace Memsource\Page;

use Memsource\Service\ShortcodeService;

class ShortcodePage extends AbstractPage
{
    private $shortcodeService;

    public function __construct(ShortcodeService $shortcodeService)
    {
        $this->shortcodeService = $shortcodeService;
    }

    public function initPage()
    {
        add_submenu_page('memsource-connector', 'Shortcodes', 'Shortcodes', 'manage_options', 'memsource-connector-shortcodes', [$this, 'renderPage']);
    }

    public function renderPage()
    {
        ?>
        <script>
            function checkInvalidCharacters() {
                var shortCode = jQuery('#shortCode').val();
                var attributes = jQuery('#attributes').val();
                if (!/^[a-zA-Z_-]+$/.test(shortCode)) {
                    alert('<?php _e('Invalid shortcode. Please make sure it contains only letters, underscores and hyphens.', 'memsource'); ?>');
                }
                else if (attributes && !/^[a-zA-Z0-9_,-]+$/.test(attributes)) {
                    alert('<?php _e('Invalid attributes. Please make sure it contains only letters, numbers, underscores and hyphens.', 'memsource'); ?>');
                } else {
                    jQuery('#add-short-code-form').submit();
                }
            }
            function confirmDeleteShortCode(shortCode) {
                if (confirm('<?php _e('Do you really want to delete ', 'memsource'); ?>' + shortCode + '?')) {
                    window.location.href = '<?php echo admin_url('admin.php'); ?>?action=delete_short_code&shortCode=' + shortCode;
                }
            }
        </script>
        <div class="memsource-admin-header">
            <img class="memsource-logo"
                 src="<?php echo MEMSOURCE_PLUGIN_DIR_URL ?>/images/phrase-logo.svg"/>
            <span class="memsource-label"><?php _e('Shortcodes', 'memsource'); ?></span>
        </div>
        <div class="memsource-space"></div>
        <div
                class="memsource-admin-section-description"><?php _e('This page displays all shortcodes from which Phrase TMS can extract a text to translate.', 'memsource'); ?></div>
        <div class="memsource-space"></div>
        <form id="add-short-code-form" method="POST" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="action" value="add_update_short_code"/>
            <label for="shortCode"><?php _e('Add new shortcode', 'memsource'); ?>:</label>
            <input id="shortCode" type="text" name="shortCode"/>
            <label for="attributes"><?php _e('Attributes (comma separated)', 'memsource'); ?>:</label>
            <input id="attributes" type="text" name="attributes"/>
            <input type="button" class="memsource-button" value="<?php _e('Submit', 'memsource'); ?>"
                   onclick="checkInvalidCharacters()"/>
        </form>
        <div class="memsource-space"></div>
        <?php
        $shortcodeData = $this->shortcodeService->getShortcodeData();
        foreach ($shortcodeData['types'] as $type) {
            ?>
            <div class="tag-section">
                <h4><?php echo $type ?></h4>
                <table class="tag-table">
                    <tr>
                        <td><b><?php _e('Tag', 'memsource'); ?></b></td>
                        <td><b><?php _e('Attributes', 'memsource'); ?></b></td>
                    </tr>
                    <?php
                    foreach ($shortcodeData['codes'] as $shortcode) {
                        if ($shortcode['type'] === $type) {
                            ?>
                            <tr class="tag-row">
                                <td><?php echo $shortcode['tag'] ?></td>
                                <td>
                                    <?php
                                    $values = [];
                                    foreach ($shortcode['attributes'] as $attribute) {
                                        $values[] = $attribute['name'];
                                    }
                                    echo implode("<br/>", $values);
                                    ?>
                                </td>
                                <?php
                                if ($shortcode['editable']) {
                                    ?>
                                    <td>
                                    <span class="dashicons dashicons-no-alt red-icon clickable"
                                          title="<?php _e('Delete this shortcode', 'memsource'); ?>"
                                          onclick="confirmDeleteShortCode('<?php echo $shortcode['tag'] ?>')"></span>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
            <?php
        }
        ?>
        <?php
    }
}
