<?php
/**
 * Import & Export Options.
 *
 * @package white-label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * white_label_Import_Export_Options extends white_label_Settings_Api
 *
 * @author White Label
 * @version 1.0.0
 */
#[\AllowDynamicProperties]
class white_label_Import_Export_Options
{
    /**
     * $option_keys ids of the get_option settings.
     *
     * @var array
     */
    public $option_keys = [];
    /**
     * $options_page_slug slug of the admin option page.
     *
     * @var boolean
     */
    public $options_page_slug = '';
    /**
     * Override the completed text.
     *
     * @var string
     */
    public $completed_message = '';

    /**
     * Construct.
     */
    public function __construct()
    {
        // Add admin notices.
        $this->notices = new white_label_admin_notices();

        add_action('admin_init', [$this, 'process_settings_import'], 50);
        add_action('admin_init', [$this, 'process_settings_export'], 50);
    }

    public function set_option_keys($option_keys)
    {
        $formatted_keys = [];

        // Check for multidimensional array used on from settings.
        if (is_array($option_keys)) {
            // Our sections may be an array with an ID inside.
            foreach ($option_keys as $key => $value) {
                $formatted_keys[] = $key;
            }
        } elseif (is_array($option_keys)) {
            // hanlde normal arrays.
            $formatted_keys = $option_keys;
        }
        // if single string is provided.
        if (is_string($option_keys)) {
            $formatted_keys = $option_keys;
        }

        $this->option_keys = $formatted_keys;
    }

    public function set_option_page_slug($slug)
    {
        // Set page url to redirect to later.
        $this->options_page_slug = $slug;
    }

    public function set_completed_message($completed_message)
    {
        // Set completed message.
        $this->completed_message = $completed_message;
    }

    public function exit_import_process()
    {
        // Reload the settings page to finish off.
        wp_safe_redirect(admin_url('options-general.php?page='.$this->options_page_slug));
        exit;
    }

    /**
     * Process options for exporting, generate & download .json file.
     *
     * @return void
     */
    public function process_settings_export()
    {
        if (empty($_POST['white_label_action']) || 'export_settings' !== $_POST['white_label_action']) {
            return;
        }

        $nonce = isset($_POST['white_label_export_nonce']) ? sanitize_key($_POST['white_label_export_nonce']) : false;

        if (!wp_verify_nonce($nonce, 'white_label_export_nonce')) {
            return;
        }
        if (!current_user_can('manage_options')) {
            return;
        }

        // Create .json file.
        ignore_user_abort(true);
        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=white_label-settings-export-'.date('m-d-Y').'.json');
        header('Expires: 0');

        echo wp_json_encode($this->bundle_options_together());

        exit;
    }

    /**
     * Process the .json import file and finish the import.
     *
     * @return void
     */
    public function process_settings_import()
    {
        if (empty($_POST['white_label_action']) || 'import_settings' !== $_POST['white_label_action']) {
            return;
        }

        $nonce = isset($_POST['white_label_import_nonce']) ? sanitize_key($_POST['white_label_import_nonce']) : false;

        if (!wp_verify_nonce($nonce, 'white_label_import_nonce')) {
            return;
        }
        if (!current_user_can('manage_options')) {
            return;
        }

        $file_details = !empty($_FILES['import_file']) ? wp_unslash($_FILES['import_file']) : false; // phpcs:ignore

        $import_file = $file_details && !empty($file_details['tmp_name']) ? $file_details['tmp_name'] : false;

        if (empty($file_details) || empty($import_file)) {
            $this->notices->add_notice(
                'error',
                __('Import cancelled. No import file uploaded.', 'white-label')
            );
            $this->exit_import_process();
        }

        $extension = explode('.', $file_details['name']);
        $extension = end($extension);

        if ($extension !== 'json') {
            $this->notices->add_notice(
                'error',
                __('Import cancelled. Please use a valid .json file.', 'white-label')
            );
            $this->exit_import_process();
        }

        // Retrieve the settings from the file and convert the json object to an array.
        $settings = (array) json_decode(file_get_contents($import_file), true); // phpcs:ignore

        if (empty($settings)) {
            $this->notices->add_notice(
                'error',
                __('Import cancelled. File is empty.', 'white-label')
            );
            $this->exit_import_process();
        }

        // Import into DB.
        $this->import_options_bundle($settings);

        // Double exit to make sure.
        $this->exit_import_process();
    }

    /**
     * Get all option sections by their key and bundle them into one array.
     *
     * @return array of all settings.
     */
    public function bundle_options_together()
    {
        $bundle = [];
        // Bundle all settings sections together.
        foreach ($this->option_keys as $section_key) {
            $bundle[$section_key] = get_option($section_key);
        }
        return $bundle;
    }

    /**
     * Import each setting section from the bundle.
     *
     * @param array $imported_bundle array of settings sections.
     * @return void
     */
    public function import_options_bundle($imported_bundle)
    {
        if (!is_array($imported_bundle)) {
            $this->notices->add_notice(
                'error',
                __('Import cancelled. Incorrect file structure, no array detected.', 'white-label')
            );
            $this->exit_import_process();
        }

        $matched_settings = false;
        $count = 0;

        foreach ($imported_bundle as $key => $value) {
            $check_if_any_setting = in_array($key, $this->option_keys, true);

            if ($check_if_any_setting) {
                $matched_settings = true;
                $count++;
            }
        }

        if ($matched_settings === false) {
            $this->notices->add_notice(
                'error',
                __('Import cancelled. Import did not match the White Label settings.', 'white-label')
            );
            $this->exit_import_process();
        }

        // Delete Menus and Plugins settings to force migration function to run.
        delete_option('white_label_menus');
        delete_option('white_label_plugins');

        // import each options section from the uploaded bundle.
        foreach ($imported_bundle as $section_key => $options) {
            update_option($section_key, $options, false);
        }

        if (!empty($this->completed_message)) {
            $completed_message = $this->completed_message;
        } else {
            $completed_message = __('Import Complete:', 'white-label').' '.$count.' '.__('sections has been sucessfully uploaded & imported into White Label.', 'white-label');
        }

        $this->notices->add_notice(
            'success',
            $completed_message
        );
    }

    /**
     * Get the html for import & Export settings.
     *
     * @return void
     */
    public function display_html()
    {
        ?>
            <div class="white-label-subsection">
                <h3 class="white-label-subheading">
                    <?php _e('Import Settings', 'white-label'); ?>
                    <a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/import-and-export-white-label-settings/"><span class="dashicons dashicons-editor-help"></span></a>
                </h3>
                <p class="description"><?php _e('Import the plugin settings from a .json file. This file can be obtained by exporting your settings from another site.', 'white-label'); ?></p>
                <hr>
                <form method="post" enctype="multipart/form-data">
                    <p>
                        <input type="file" name="import_file"/>
                    </p>
                    <p>
                        <input type="hidden" name="white_label_action" value="import_settings" />
                        <?php wp_nonce_field('white_label_import_nonce', 'white_label_import_nonce'); ?>
                        <?php submit_button(__('Import'), 'secondary', 'submit', false, ['id' => 'submit-import']); ?>
                    </p>
                </form>
            </div>

            <div class="white-label-subsection">
                <h3 class="white-label-subheading">
                    <?php _e('Export Settings', 'white-label'); ?>
                    <a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/import-and-export-white-label-settings/"><span class="dashicons dashicons-editor-help"></span></a>
                </h3>
                <p class="description"><?php _e('Export the plugin settings for this site as a .json file. This allows you to easily import your configuration into another site.', 'white-label'); ?></p>
                <hr>
                <form method="post">
                    <p><input type="hidden" name="white_label_action" value="export_settings" /></p>
                    <p>
                        <?php wp_nonce_field('white_label_export_nonce', 'white_label_export_nonce'); ?>
                        <?php submit_button(__('Export'), 'secondary', 'submit', false, ['id' => 'submit-export']); ?>
                    </p>
                </form>
            </div>
        </div><!--end .wrap-->
		<?php
    }
}
