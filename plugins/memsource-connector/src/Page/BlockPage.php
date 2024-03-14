<?php

namespace Memsource\Page;

use Memsource\Service\BlockService;

class BlockPage extends AbstractPage
{
    /** @var BlockService */
    private $blockService;

    public function __construct(BlockService $blockService)
    {
        $this->blockService = $blockService;
    }

    public function initPage()
    {
        add_submenu_page(
            'memsource-connector',
            'Gutenberg blocks',
            'Gutenberg blocks',
            'manage_options',
            'memsource-connector-blocks',
            [$this, 'renderPage']
        );
    }

    public function renderPage()
    {
        ?>
        <div class="memsource-admin-header">
            <img class="memsource-logo" src="<?php echo MEMSOURCE_PLUGIN_DIR_URL; ?>/images/phrase-logo.svg"/>
            <span class="memsource-label">
                <?php _e('Gutenberg Blocks', 'memsource'); ?>
            </span>
        </div>

        <div class="memsource-space"></div>

        <form id="edit-blocks-form" method="POST" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="action" value="edit_blocks"/>

            <div class="memsource-admin-section-description memsource-full-width">
                <input type="submit" name="submit" class="button button-primary alignright" value="<?php _e('Save changes', 'memsource'); ?>">
                <p><?php _e('This page displays all Gutenberg Blocks from which Phrase TMS can extract a text to translate.', 'memsource'); ?></p>
            </div>
            <div class="memsource-space"></div>

            <table class="wp-list-table widefat fixed striped table-view-list pages memsource-full-width">
                <thead>
                <tr>
                    <th><?php _e('Block', 'memsource'); ?></th>
                    <th><?php _e('Attributes', 'memsource'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->blockService->listInstalledBlocks() as $block) { ?>
                    <tr>
                        <td>
                            <p class="memsource-form-black">
                                <?php echo $block->name; ?>
                            </p>
                            <p class="memsource-form-gray" style="margin-top: 7px">
                                <strong>
                                    <?php echo $block->title; ?>
                                </strong>
                                <br>
                                <?php echo $block->description; ?>
                            </p>
                        </td>
                        <td>
                            <ul style="margin: 0">
                                <?php
                                foreach ($block->attributes as $attribute) {
                                    echo '<li><label>
                                        <input type="checkbox"
                                               name="blocks[' . $block->name . '][' . $attribute->name . ']"
                                               value="1" ' .
                                               ($attribute->translatable ? 'checked' : '') . ' ' .
                                               ($attribute->uneditable ? 'disabled' : '') .
                                         '> ' .
                                         $attribute->name .
                                    '</label></li>';
                                } ?>
                            </ul>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="<?php _e('Save changes', 'memsource'); ?>">
            </p>

            <?php
            $blocks = $this->blockService->listUserDefinedBlocks();
            foreach ($blocks as $blockName => $blockAttributesList) {
                foreach ($blockAttributesList as $attribute => $translate) {
                    echo '<input type="hidden" name="blocks[' . $blockName . '][' . $attribute . ']" value="' . ($translate ? '1' : '') . '">';
                }
            }
            ?>

        </form>

        <br>

        <h2 id="custom-blocks" style="margin-top: 20px"><?php _e('Custom blocks', 'memsource'); ?></h2>

        <script>
            function confirmDeleteBlock(block) {
                if (confirm('<?php _e('Do you really want to delete block ', 'memsource'); ?>' + '"' + block + '"?')) {
                    window.location.href = '<?php echo admin_url('admin.php'); ?>?action=delete_block&block=' + block;
                }
            }

            function editBlock(name, attributes) {
                document.getElementById('block').value = name;
                document.getElementById('attributes').value = attributes;
            }
        </script>

        <?php
        if (empty($blocks)) {
            _e('No custom blocks found.', 'memsource');
            echo "<br>";
        } else { ?>
            <table class="wp-list-table widefat fixed striped table-view-list pages memsource-full-width">
                <thead>
                <tr>
                    <th><?php _e('Block Name', 'memsource'); ?></th>
                    <th><?php _e('Attributes', 'memsource'); ?></th>
                    <th><?php _e('Actions', 'memsource'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $allBlocks = $this->blockService->listCustomBlocks();
                foreach ($blocks as $blockName => $blockAttributesList) {
                    $blockAttributes = implode(', ', array_keys($blockAttributesList));
                    $allBlockAttributes = implode(', ', array_keys($allBlocks[$blockName]));
                    echo '<tr>
                            <td><strong>' . $blockName . '</strong></td>
                            <td>' . $blockAttributes . '</td>
                            <td>
                                <a href="#custom-blocks" onclick="editBlock(\'' . esc_html($blockName) . '\', \'' . esc_html($allBlockAttributes) . '\')">
                                    ' . __('Edit', 'memsource') . '
                                </a>
                                |
                                <a href="#custom-blocks" class="memsource-form-red" onclick="confirmDeleteBlock(\'' . $blockName . '\')">
                                    ' . __('Delete', 'memsource') . '
                                </a>
                            </td>
                        </tr>';
                }
                ?>
                </tbody>
            </table>
        <?php } ?>

            <br>

            <h3 style="margin-top: 20px"><?php _e('Add or edit custom block', 'memsource'); ?></h3>

            <form id="add-block-form" method="POST" action="<?php echo admin_url('admin.php'); ?>">
                <input type="hidden" name="action" value="add_update_block"/>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th>
                            <label for="block"><?php _e('Block name', 'memsource'); ?></label>
                        </th>
                        <td><input type="text" id="block" name="block" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>
                            <label for="attributes"><?php _e('Block attributes', 'memsource'); ?></label>
                            <br>
                            <span class="memsource-form-gray" style="margin-top: 5px"><?php _e('Comma separated list', 'memsource'); ?></span>
                        </th>
                        <td><textarea id="attributes" name="attributes" class="large-text" rows="3" cols="50"></textarea></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input type="submit" class="memsource-button" value="<?php _e('Save block', 'memsource'); ?>"/></td>
                    </tr>
                    </tbody>
                </table>
            </form>
    <?php
    }
}
