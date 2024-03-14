<?php

namespace Memsource\Page;

use Memsource\Dao\MetaDao;
use Memsource\Service\CustomFields\CustomFieldsSettingsService;

class CustomFieldsPage extends AbstractPage
{
    private const MENU_SLUG = 'memsource-connector-content-management';
    private const PAGE_SIZE = 50;

    /** @var CustomFieldsSettingsService */
    private $customFieldsSettingsService;

    /** @var MetaDao */
    private $metaDao;

    public function __construct(CustomFieldsSettingsService $customFieldsSettingsService, MetaDao $metaDao)
    {
        $this->customFieldsSettingsService = $customFieldsSettingsService;
        $this->metaDao = $metaDao;
    }

    public function initPage()
    {
        add_submenu_page('memsource-connector', 'Custom fields', 'Custom fields', 'manage_options', self::MENU_SLUG, [$this, 'renderPage']);
    }

    public function renderPage()
    {
        ?>
        <div class="memsource-admin-header">
            <img class="memsource-logo" src="<?php echo MEMSOURCE_PLUGIN_DIR_URL; ?>/images/phrase-logo.svg"/>
            <span class="memsource-label"><?php _e('Custom fields', 'memsource'); ?></span>
        </div>
        <div class="memsource-space"></div>
        <div class="memsource-admin-section-description"><?php _e('<p>Select which custom fields should be exported for translation with a post or a page. Whenever a new theme or a page builder is installed to WordPress, the list of the custom fields is automatically updated.</p>', 'memsource-custom-fields-description'); ?></div>
        <?php
            $selectAllBlock = '<p style="padding: 2px 7px"><label><input type="checkbox" class="select-all"> Select all</label></p>';
            echo $selectAllBlock;

            $totalPages = max(1, ceil($this->metaDao->countAllMetaKeys() / self::PAGE_SIZE));

        if (isset($_GET['pagination']) && (int) $_GET['pagination'] > 0) {
            $currentPage = (int) $_GET['pagination'];
        } else {
            $currentPage = 1;
        }
        ?>
        <hr>
        <form id="memsource-content-settings-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="memsource_content_settings_form"/>
            <input type="hidden" name="referer" value="<?php echo esc_url(admin_url('admin.php')) . '?page=' . self::MENU_SLUG . "&pagination=$currentPage"; ?>">
            <input type="hidden" name="pagination" value="<?php echo $currentPage ?>">
            <table style="width: 50%; text-align: left;">
                <thead>
                    <tr>
                        <th class="manage-column column-title column-primary"><?php _e('Export', 'memsource'); ?></th>
                        <th class="manage-column column-title column-primary"><?php _e('Name', 'memsource'); ?></th>
                        <th class="manage-column column-title column-primary"><?php _e('Type', 'memsource'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $items = $this->getItems();
                    $itemsCount = count($items);
                    $i = 1;

                foreach ($items as $item) {
                    if ($i < $itemsCount || $itemsCount <= self::PAGE_SIZE) {
                        echo sprintf(
                            '<tr><td><input type="checkbox" class="item" name="%s" value="1" %s></td><td>%s</td><td>%s</td></tr>',
                            $item['hash'],
                            ($item['checked'] ? 'checked' : ''),
                            $item['name'],
                            $item['type']
                        );
                        $i++;
                    }
                }

                if (!$items) {
                    echo '<tr><td colspan="3">No content found.</td></tr>';
                }
                ?>
                </tbody>
            </table>
            <hr>
            <?php echo $selectAllBlock; ?>
            <table style="width: 50%; text-align: left;">
                <tr>
                    <td>
                        <input type="submit" class="memsource-button" value="<?php _e('Save', 'memsource'); ?>"/>
                    </td>
                    <td style="text-align: center;">
                    <?php
                        echo "Showing page $currentPage of $totalPages total. ";

                    if ($currentPage > 1) {
                        echo ' | <a href="' . add_query_arg('pagination', $currentPage - 1) . '">Previous page</a>';
                    }

                    if ($itemsCount > self::PAGE_SIZE) {
                        echo ' | <a href="' . add_query_arg('pagination', $currentPage + 1) . '">Next page</a>';
                    }
                    ?>
                    </td>
                </tr>
            </table>
        </form>

        <script>
            var items = jQuery('#memsource-content-settings-form .item');
            var selectAllCheckbox = jQuery('.select-all');

            //select all on request
            selectAllCheckbox.change(function(){
                var checked = jQuery(this).prop('checked');
                items.prop('checked', checked);
                selectAllCheckbox.prop('checked', checked);
            });

            //uncheck items for select all when is changing an item
            items.click(function(){
                selectAllCheckbox.prop('checked', false);
            });
        </script>
        <?php
    }

    /**
     * Handler for submitted form.
     */
    public function formSubmit()
    {
        global $wpdb;
        $wpdb->query('START TRANSACTION');
        foreach ($this->getItems() as $item) {
            $this->customFieldsSettingsService->saveContentSettings(
                $item['key'],
                $item['type'],
                isset($_POST[$item['hash']])
            );
        }
        $wpdb->query('COMMIT');
        wp_redirect($_POST['referer']);
    }

    private function getItems(): array
    {
        $items = [];
        $page = 1;

        if (isset($_GET['pagination']) && (int) $_GET['pagination'] > 0) {
            $page = (int) $_GET['pagination'];
        } elseif (isset($_POST['pagination']) && (int) $_POST['pagination'] > 0) {
            $page = (int) $_POST['pagination'];
        }

        $fields = $this->metaDao->findMetaKeys($page, self::PAGE_SIZE);
        $settings = $this->customFieldsSettingsService->findContentSettings();

        foreach ($fields as $field) {
            $items[] = [
                'hash' => $field->getHash(),
                'key' => $field->getName(),
                'checked' => !isset($settings[$field->getHash()]) || $settings[$field->getHash()]->exportForTranslation(),
                'name' => $field->getName(),
                'type' => $field->getType(),
            ];
        }

        return $items;
    }
}
