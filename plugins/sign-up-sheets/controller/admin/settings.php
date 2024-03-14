<?php
/**
 * Admin Page: Settings
 */

namespace FDSUS\Controller\Admin;

use FDSUS\Lib\Dls\Notice;
use FDSUS\Model\SettingsMetaBoxes;
use FDSUS\Model\Settings as SettingsModel;
use FDSUS\Controller\Migrate;
use FDSUS\Controller\Admin\MetaBox as AdminMetaBoxController;
use FDSUS\Id;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\DbUpdate;
use WP_Screen;
use Exception;

class Settings extends PageBase
{
    /** @var string  */
    protected $menuSlug = 'fdsus-settings';

    public function __construct()
    {
        add_action('current_screen', array(&$this, 'migrateNotice'));
        add_action('admin_init', array(&$this, 'maybeProcessReset'));
        add_action('admin_init', array(&$this, 'maybeProcessSave'));
        add_action('admin_menu', array(&$this, 'menu'));

        if (
            isset($_POST['mode'])
            && $_POST['mode'] == 'submitted'
            && isset($_POST['return_path'])
            && $_POST['return_path'] == 'true'
        ) {
            add_action('phpmailer_init', array($this, 'fixReturnPath'));
        }

        parent::__construct();
    }

    /**
     * Menu
     */
    public function menu()
    {
        add_submenu_page(
            'edit.php?post_type=' . SheetModel::POST_TYPE,
            esc_html__('Sign-up Sheets Settings', 'fdsus'),
            esc_html__('Settings', 'fdsus'),
            'manage_options',
            SettingsModel::$menuSlug,
            array(&$this, 'page')
        );
    }

    /**
     * Page
     */
    public function page()
    {
        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);
        if (!current_user_can('manage_options') && !current_user_can($caps['read_post'])) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'fdsus'));
        }
        ?>
        <div class="wrap dls_sus metabox-holder">
            <h1 class="wp-heading-inline">
                <?php esc_html_e('Sign-up Sheets', 'fdsus'); ?>
                <?php if (Id::isPro()): ?><sup class="dls-sus-pro">Pro</sup><?php endif; ?>
                <?php esc_html_e('Settings', 'fdsus'); ?>
            </h1>
            <p style="text-align: right;"><a href="#" class="dls-sus-expand-all-postbox"><?php esc_html_e('+ Expand All', 'fdsus'); ?></a></p>
            <form name="dls-sus-form" class="dls-sus-settings" method="post" action="<?php echo esc_url($this->data->getSettingsUrl()) ?>">

                <?php
                $screen = get_current_screen();
                $metabox = new AdminMetaBoxController($screen->id);
                $settingsMetaBoxModel = new SettingsMetaBoxes();
                foreach ($settingsMetaBoxModel->getData() as $metaBoxData) {
                    $metabox->add($metaBoxData);
                }
                $metabox->output();
                ?>
                <hr />
                <p class="submit">
                    <?php
                    wp_nonce_field('fdsus-settings-update', '_fdsus-nonce');
                    wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
                    wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
                    ?>
                    <input type="hidden" name="<?php echo esc_attr($this->hiddenFieldName); ?>" value="<?php echo esc_attr($this->hiddenFieldValue) ?>">
                    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'fdsus'); ?>" />
                </p>

            </form>
        </div><!-- .wrap -->
        <?php
    }

    /**
     * Maybe display migrate notice
     *
     * @param WP_Screen $currentScreen Current WP_Screen object.
     *
     * @return void
     */
    public function migrateNotice($currentScreen)
    {
        if (!$this->isCurrentScreen($currentScreen)) {
            return;
        }

        if (!empty($_GET['migrate']) && $_GET['migrate'] == 'rerun-2.1') {
            if (FDSUS_DISABLE_MIGRATE_2_0_to_2_1) {
                Notice::add(
                    'warning', esc_html__('Sorry, I cannot rerun migration.  The migration logic is currently disabled with the FDSUS_DISABLE_MIGRATE_2_0_to_2_1 configuration.', 'fdsus'), false,
                    'dlssus-migrate-disabled'
                );
                return;
            }

            $migrate = new Migrate();
            update_option($migrate->statusKey, array('state' => 'rerun'));
            delete_transient(Id::PREFIX . '_migration_running');

            delete_transient(Id::PREFIX . '_migration_timeout_rerun_count');
            $update = new DbUpdate();
            $update->scheduleAsyncUpdate();

            Notice::add(
                'info', esc_html__('Sign-up Sheets database upgrade has been triggered.', 'fdsus'), false,
                'dlssus-migrate-status'
            );
        }
    }

    /**
     * Maybe process reset of all settings
     *
     * @throws Exception
     */
    public function maybeProcessReset()
    {
        if (empty($_GET['fdsus-reset']) || $_GET['fdsus-reset'] !== 'all') {
            return;
        }

        if (!isset($_GET['_fdsus-nonce']) || !wp_verify_nonce($_GET['_fdsus-nonce'], 'fdsus-settings-reset')) {
            wp_die(esc_html__('Invalid request.', 'fdsus'));
        }

        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);
        if (!current_user_can('manage_options') && !current_user_can($caps['read_post'])) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'fdsus'));
        }

        $numberSaved = 0;
        $settingsMetaBoxModel = new SettingsMetaBoxes();
        foreach ($settingsMetaBoxModel->getData() as $metaBoxData) {
            foreach ($metaBoxData['options'] as $o) {
                $optionName = $this->getOptionArrayValueByKey('name', $o);
                $optionValue = (isset($_POST[$optionName])) ? stripslashes_deep($_POST[$optionName]) : null;

                /**
                 * Action that runs before the settings page save event
                 *
                 * @param string $optionName
                 * @param array  $optionValue
                 *
                 * @since 2.2
                 */
                do_action('fdsus_settings_page_before_reset', $optionName, $optionValue);

                /**
                 * Filters the optionValue prior to saving
                 *
                 * @param array  $optionValue
                 * @param string $optionName
                 *
                 * @since 2.2
                 */
                $optionValue = apply_filters('fdsus_settings_page_option_value_before_reset', $optionValue, $optionName);

                $deleted = delete_option($optionName);
                $numberSaved++;

                // Cleanup process
                $this->data->set_capabilities();
                set_transient(Id::PREFIX . '_flush_rewrite_rules', true);

                /**
                 * Action that runs after an option is saved
                 *
                 * @param string $optionName
                 * @param array  $optionValue
                 * @param int    $numberSaved
                 *
                 * @since 2.2
                 */
                do_action('fdsus_settings_page_after_reset', $optionName, $optionValue, $numberSaved);

                if ($numberSaved === 1) {
                    Notice::add(
                        'success',
                        esc_html__('Sign-up Sheet settings have been successfully reset to default values.', 'fdsus')
                    );
                }
            }
        }

        SettingsModel::resetUserMetaBoxOrder();
    }

    /**
     * Maybe process save
     *
     * @throws Exception
     */
    public function maybeProcessSave()
    {
        if (!isset($_POST[$this->hiddenFieldName]) || $_POST[$this->hiddenFieldName] !== $this->hiddenFieldValue) {
            return;
        }

        if (!isset($_POST['_fdsus-nonce']) || !wp_verify_nonce($_POST['_fdsus-nonce'], 'fdsus-settings-update')) {
            wp_die(esc_html__('Invalid request.', 'fdsus'));
        }

        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);
        if (!current_user_can('manage_options') && !current_user_can($caps['read_post'])) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'fdsus'));
        }

        $numberSaved = 0;
        $settingsMetaBoxModel = new SettingsMetaBoxes();
        foreach ($settingsMetaBoxModel->getData() as $metaBoxData) {
            foreach ($metaBoxData['options'] as $o) {
                $optionName = $this->getOptionArrayValueByKey('name', $o);;
                $optionValue = (isset($_POST[$optionName])) ? stripslashes_deep($_POST[$optionName]) : null;

                /**
                 * Action that runs before the settings page save event
                 *
                 * @param string $optionName
                 * @param array  $optionValue
                 *
                 * @since 2.2
                 */
                do_action('fdsus_settings_page_before_save', $optionName, $optionValue);

                /**
                 * Filters the optionValue prior to saving
                 *
                 * @param array  $optionValue
                 * @param string $optionName
                 *
                 * @since 2.2
                 */
                $optionValue = apply_filters('fdsus_settings_page_option_value_before_save', $optionValue, $optionName);

                $updated = update_option($optionName, $optionValue);
                if ($optionName == 'dls_sus_roles') $this->data->set_capabilities();
                $numberSaved++;

                // Set flag to flush rewrite on next page load
                if ($optionName === 'dls_sus_sheet_slug' && $updated) {
                    set_transient(Id::PREFIX . '_flush_rewrite_rules', true);
                }

                /**
                 * Action that runs after an option is saved
                 *
                 * @param string $optionName
                 * @param array  $optionValue
                 * @param int    $numberSaved
                 *
                 * @since 2.2
                 */
                do_action('fdsus_settings_page_after_save', $optionName, $optionValue, $numberSaved);

                if ($numberSaved === 1) {
                    Notice::add('success', esc_html__('Settings saved.', 'fdsus'));
                }
            }
        }
    }

    /**
     * Get option array value by key
     * Includes backward compatibility for older options not specified in an associative array
     *
     * @param string $key
     * @param array  $option
     *
     * @return mixed|null
     */
    public function getOptionArrayValueByKey($key, $option)
    {
        $keyIndexes = ['label', 'name', 'type', 'note', 'options', 'order', 'class', 'disabled'];
        $optionFallbackKey = array_search($key, $keyIndexes);
        if (!isset($option[$key])) {
            return isset($option[$optionFallbackKey]) ? $option[$optionFallbackKey] : null;
        }

        return $option[$key];
    }

    /**
     * Sets proper email for bouncebacks
     *
     * @param $phpmailer
     */
    public function fixReturnPath($phpmailer)
    {
        $phpmailer->Sender = $phpmailer->From; // use the from email
    }
}
