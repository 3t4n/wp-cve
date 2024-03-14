<?php
/**
 * Demo Import Kit Base
 *
 * @package Demo Import Kit
 */
// Block direct access to the main plugin file.
defined('ABSPATH') or die('No script kiddies please!');

class Demo_Import_Kit_Base_Class
{
    /**
     * Private variables
     */
    private $upgrade_pro, $importer, $plugin_page, $primary_cat, $secondary_cat, $import_files, $logger, $selected_index, $selected_import_files, $microtime, $return_message;

    /**
     * Class construct function, to initiate the plugin.
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'demo_import_kit_page_create'));
        add_action('wp_ajax_dik_import_demo_data', array($this, 'dik_import_demo_data_ajax_callback'));
        add_action('wp_ajax_demo_import_kit_grid_primary_tab', array($this, 'demo_import_kit_grid_primary_tab_ajax_callback'));
        add_action('init', array($this, 'setup_plugin_with_filter_data'), 50);
        if (isset($_POST['demo-import-kit-download'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require DIK_PATH . 'inc/export.php';
            add_action('init', array($this, 'demo_import_kit_create_file'));
        }
    }

    public function demo_import_kit_create_file()
    {
        $args['content'] = 'all';
        $content = demo_import_kit_export_wp($args);
        $widget = demo_import_kit_wie_generate_export_data();
        $customizer = demo_import_kit_customizer_data();
        WP_Filesystem();
        global $wp_filesystem;
        if (!file_exists(DEMO_IMPORT_KIT_FOLDER)) {
            $wp_filesystem->mkdir(DEMO_IMPORT_KIT_FOLDER);
        }
        $wp_filesystem->put_contents(DEMO_IMPORT_KIT_FOLDER . 'content.xml', $content);
        $wp_filesystem->put_contents(DEMO_IMPORT_KIT_FOLDER . 'widget.json', $widget);
        $wp_filesystem->put_contents(DEMO_IMPORT_KIT_FOLDER . 'customizer.dat', $customizer);
        $this->demo_import_kit_export_zip(DEMO_IMPORT_KIT_FOLDER, $wp_filesystem);
    }

    public function demo_import_kit_export_zip($directory, $wp_filesystem)
    {
        $zip = new ZipArchive;
        $zip_filename = esc_attr(get_option('template')) . '-data.zip';
        $zip->open($zip_filename, ZipArchive::CREATE && ZipArchive::OVERWRITE);
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($directory));
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=" . pathinfo($zip_filename, PATHINFO_BASENAME));
        header("Content-Length: " . filesize($zip_filename));
        readfile($zip_filename);
        $wp_filesystem->rmdir($zip_filename, true);
        $wp_filesystem->rmdir($directory, true);
    }

    /**
     * Creates the plugin page and a submenu item in WP Appearance menu.
     */
    public function demo_import_kit_page_create()
    {
        $plugin_page_setup = apply_filters('dik/plugin_page_setup', array(
                'parent_slug' => 'themes.php',
                'page_title' => esc_html__('Demo Import Kit', 'demo-import-kit'),
                'menu_title' => esc_html__('Demo Import Kit', 'demo-import-kit'),
                'capability' => 'import',
                'menu_slug' => 'demo-import-kit',
            )
        );
        $this->plugin_page = add_submenu_page($plugin_page_setup['parent_slug'], $plugin_page_setup['page_title'], $plugin_page_setup['menu_title'], $plugin_page_setup['capability'], $plugin_page_setup['menu_slug'], array($this, 'demo_import_kit_page'));
    }

    /**
     * Plugin page display.
     */
    public function demo_import_kit_page()
    {
        if (!current_user_can('upload_files')) {
            wp_die(__('Sorry, you are not allowed to install demo on this site.', 'demo-import-kit'));
        }
        require DIK_PATH . 'inc/views.php';
    }

    /**
     * Import ajax Callback
     */
    public function dik_import_demo_data_ajax_callback()
    {
        // Try to update PHP memory limit (so that it does not run out of it).
        ini_set('memory_limit', apply_filters('dik/import_memory_limit', '350M'));
        ini_set('max_execution_time', apply_filters('dik/import_max_execution_time', 30000));
        // Verify if the AJAX call is valid (checks nonce and current_user_can).
        $this->verify_ajax_call();
        if (!$use_existing_importer_data) {

            $widget = $content = $customizer = '';

            // Set the AJAX call number.
            // Error messages displayed on front page.
            $this->return_message = '';
            // Create a date and time string to use for demo and log file names.
            $demo_import_start_time = date(apply_filters('dik/date_format_for_file_names', 'Y-m-d__H-i-s'));
            // Get selected file index or set it to 0.
            $this->selected_index = empty($_POST['selected']) ? 0 : absint($_POST['selected']);
            if (isset($_POST['demoSlug']) && $_POST['demoSlug']) {

                $template = get_template();
               
                // ================= Widget Import
                $widget_response = wp_remote_get('https://raw.githubusercontent.com/themeinwp/demo-content/main/' . esc_attr($template) . '/'.esc_attr($_POST['demoSlug']).'/widget.json');

                // Only execute if our config is loaded properly
                if ( is_array( $widget_response ) && ! is_wp_error( $widget_response ) && ( 200 === wp_remote_retrieve_response_code( $widget_response ) ) ) {

                    $widget = wp_remote_retrieve_body($widget_response);
                }


                // ================ Customizer Import
                $customizer_response = wp_remote_get('https://raw.githubusercontent.com/themeinwp/demo-content/main/' . esc_attr($template) . '/'.esc_attr($_POST['demoSlug']).'/customizer.dat');

                // Only execute if our config is loaded properly
                if ( is_array( $customizer_response ) && ! is_wp_error( $customizer_response ) && ( 200 === wp_remote_retrieve_response_code( $customizer_response ) ) ) {
                    $customizer = wp_remote_retrieve_body($customizer_response);
                }

                
                // ================ Content Import
                $content_response = wp_remote_get('https://raw.githubusercontent.com/themeinwp/demo-content/main/' . esc_attr($template) . '/'.esc_attr($_POST['demoSlug']).'/content.xml');

                
                // Only execute if our config is loaded properly
                if ( is_array( $content_response ) && ! is_wp_error( $content_response ) && ( 200 === wp_remote_retrieve_response_code( $content_response ) ) ) {
                    $content = wp_remote_retrieve_body($content_response);
                }

                WP_Filesystem();
                global $wp_filesystem;
                if (!file_exists(DEMO_IMPORT_KIT_FOLDER)) {
                    $wp_filesystem->mkdir(DEMO_IMPORT_KIT_FOLDER);
                }
                if ($content) {
                    $wp_filesystem->put_contents(DEMO_IMPORT_KIT_FOLDER . 'content.xml', $content);
                }
                if ($widget) {
                    $wp_filesystem->put_contents(DEMO_IMPORT_KIT_FOLDER . 'widget.json', $widget);
                }
                if ($customizer) {
                    $wp_filesystem->put_contents(DEMO_IMPORT_KIT_FOLDER . 'customizer.dat', $customizer);
                }
                if (empty($content) || empty($customizer) || empty($widget)) {
                    wp_send_json(esc_html__('Content Importing  Failed. No import files specified!', 'demo-import-kit'));
                    die();
                }
                $uploaded_files = array(
                    'content' => DEMO_IMPORT_KIT_FOLDER . 'content.xml',
                    'widgets' => DEMO_IMPORT_KIT_FOLDER . 'widget.json',
                    'customizer' => DEMO_IMPORT_KIT_FOLDER . 'customizer.dat',
                );
                // Get paths for the uploaded files.
                $this->selected_import_files = $uploaded_files;
                $this->import_files[$this->selected_index]->import_file_name = esc_html__('Manually uploaded files', 'demo-import-kit');
            } elseif (!empty($_FILES)) { // Using manual file uploads?
                WP_Filesystem();
                global $wp_filesystem;
                if (!file_exists(DEMO_IMPORT_KIT_FOLDER)) {
                    $wp_filesystem->mkdir(DEMO_IMPORT_KIT_FOLDER);
                }
                move_uploaded_file($_FILES['content_file']['tmp_name'], DEMO_IMPORT_KIT_FOLDER . $_FILES['content_file']['name']);
                $file_name = scandir(DEMO_IMPORT_KIT_FOLDER)[2];
                $zip = new ZipArchive;
                $res = $zip->open(DEMO_IMPORT_KIT_FOLDER . $file_name);
                if ($res === TRUE) {
                    $zip->extractTo(DEMO_IMPORT_KIT_FOLDER);
                    $zip->close();
                }
                if (!file_exists(DEMO_IMPORT_KIT_FOLDER . 'content.xml') ||
                    !file_exists(DEMO_IMPORT_KIT_FOLDER . 'widget.json') ||
                    !file_exists(DEMO_IMPORT_KIT_FOLDER . 'customizer.dat')) {
                    wp_send_json(esc_html__('Content Importing Failed. No import files specified!', 'demo-import-kit'));
                    die();
                }
                $uploaded_files = array(
                    'content' => DEMO_IMPORT_KIT_FOLDER . 'content.xml',
                    'widgets' => DEMO_IMPORT_KIT_FOLDER . 'widget.json',
                    'customizer' => DEMO_IMPORT_KIT_FOLDER . 'customizer.dat',
                );
                // Get paths for the uploaded files.
                $this->selected_import_files = $uploaded_files;
                // Set the name of the import files, because we used the uploaded files.
                $this->import_files[$this->selected_index]->import_file_name = esc_html__('Manually uploaded files', 'demo-import-kit');
            } elseif (!empty($this->import_files[$this->selected_index])) {
                WP_Filesystem();
                global $wp_filesystem;
                $template = get_template();
                if (!file_exists(DEMO_IMPORT_KIT_FOLDER)) {
                    $wp_filesystem->mkdir(DEMO_IMPORT_KIT_FOLDER);
                }
                copy($this->import_files[$this->selected_index]['local_content_file'], DEMO_IMPORT_KIT_FOLDER . $template . '.zip');
                $file_name = scandir(DEMO_IMPORT_KIT_FOLDER)[2];
                $zip = new ZipArchive;
                $res = $zip->open(DEMO_IMPORT_KIT_FOLDER . $file_name);
                if ($res === TRUE) {
                    $zip->extractTo(DEMO_IMPORT_KIT_FOLDER);
                    $zip->close();
                }
                if (!file_exists(DEMO_IMPORT_KIT_FOLDER . 'content.xml') &&
                    !file_exists(DEMO_IMPORT_KIT_FOLDER . 'widget.json') &&
                    !file_exists(DEMO_IMPORT_KIT_FOLDER . 'customizer.dat')) {
                    wp_send_json(esc_html__('Content Importing Failed. No import files specified!', 'demo-import-kit'));
                    die();
                }
                $uploaded_files = array(
                    'content' => DEMO_IMPORT_KIT_FOLDER . 'content.xml',
                    'widgets' => DEMO_IMPORT_KIT_FOLDER . 'widget.json',
                    'customizer' => DEMO_IMPORT_KIT_FOLDER . 'customizer.dat',
                );
                // Get paths for the uploaded files.
                $this->selected_import_files = $uploaded_files;
            } else {
                // Send JSON Error response to the AJAX call.
                wp_send_json(esc_html__('Content Importing Failed. No import files specified!', 'demo-import-kit'));
            }
        }
        /**
         * Import content.
         */
        $this->return_message .= $this->import_content($this->selected_import_files['content']);
        /**
         * Import widgets.
         */
        if (!empty($this->selected_import_files['widgets']) && empty($this->return_message)) {
            $this->import_widgets($this->selected_import_files['widgets']);
        }
        /**
         *Import customize.
         */
        if (!empty($this->selected_import_files['customizer']) && empty($this->return_message)) {
            $this->import_customizer($this->selected_import_files['customizer']);
        }
        /**
         * Remove Temp folder and files
         */
        if (file_exists(DEMO_IMPORT_KIT_FOLDER)) {
            $wp_filesystem->rmdir(DEMO_IMPORT_KIT_FOLDER, true);
        }
        do_action('dik/after_import');
        // Success or Fail message
        if (empty($this->return_message)) {
            esc_html_e('Content Import Completed Successfully.', 'demo-import-kit');
        } else {
            esc_html_e('Content Importing Failed.', 'demo-import-kit');
            echo esc_html($this->return_message);
        }
        die();
    }

    /**
     * Import content from an WP XML file.
     *
     * @param string $import_file_path path to the import file.
     */
    private function import_content($import_file_path)
    {
        $this->microtime = microtime(true);
        // This should be replaced with multiple AJAX calls (import in smaller chunks)
        // so that it would not come to the Internal Error, because of the PHP script timeout.
        // Also this function has no effect when PHP is running in safe mode
        // http://php.net/manual/en/function.set-time-limit.php.
        // Increase PHP max execution time.
        set_time_limit(apply_filters('dik/set_time_limit_for_demo_data_import', 3000));
        // Disable import of authors.
        add_filter('wxr_importer.pre_process.user', '__return_false');
        // Import content.
        if (!empty($import_file_path)) {
            ob_start();
            $this->importer->import($import_file_path);
            $message = ob_get_clean();
        }
        // Delete content importer data for current import from DB.
        delete_transient('DIK_importer_data');
        // Return any error messages for the front page output (errors, critical, alert and emergency level messages only).
        return $this->logger->error_output;
    }

    /**
     * Import widgets from WIE or JSON file.
     *
     * @param string $widget_import_file_path path to the widget import file.
     */
    private function import_widgets($widget_import_file_path)
    {
        // Widget import results.
        $results = array();
        // Create an instance of the Widget Importer.
        $widget_importer = new DIK_Widget_Importer();
        // Import widgets.
        if (!empty($widget_import_file_path)) {
            // Import widgets and return result.
            $results = $widget_importer->import_widgets($widget_import_file_path);
        }
        ob_start();
        $widget_importer->format_results_for_log($results);
        $message = ob_get_clean();
    }

    /**
     * Import customizer from a DAT file, generated by the Customizer Export/Import plugin.
     *
     * @param string $customizer_import_file_path path to the customizer import file.
     */
    private function import_customizer($customizer_import_file_path)
    {
        // Try to import the customizer settings.
        $results = DIK_Customizer_Importer::import_customizer_options($customizer_import_file_path);
    }

    /**
     * Get data from filters, after the theme has loaded and instantiate the importer.
     */
    public function setup_plugin_with_filter_data()
    {
        // Get info of import data files and filter it.
        $this->import_files = apply_filters('demo_import_kit_import_files', array());
        $this->primary_cat = apply_filters('demo_import_kit_primary_cat', array());
        $this->secondary_cat = apply_filters('demo_import_kit_secondary_cat', array());
        $this->upgrade_pro = apply_filters('demo_import_kit_upgrade_pro', false);
        // Importer options array.
        $importer_options = apply_filters('dik/importer_options', array(
            'fetch_attachments' => true,
        ));
        // Logger options for the logger used in the importer.
        $logger_options = apply_filters('dik/logger_options', array(
            'logger_min_level' => 'warning',
        ));
        // Configure logger instance and set it to the importer.
        $this->logger = new DIK_Logger();
        $this->logger->min_level = $logger_options['logger_min_level'];
        if (!function_exists('get_cli_args')) {
            // Create importer instance with proper parameters.
            $this->importer = new Importer($importer_options, $this->logger);
        }
    }

    public function demo_import_kit_grid_primary_tab_ajax_callback()
    {
        $this->verify_ajax_call();
        if (isset($_POST['PrimaryCat']) && sanitize_text_field(wp_unslash($_POST['PrimaryCat']))) {
            $PrimaryCat = sanitize_text_field(wp_unslash($_POST['PrimaryCat']));
            $import_files_array = $this->import_files;
            $primary_cat_array = $this->primary_cat;
            $secondary_cat_array = $this->secondary_cat;
            $import_files_array = json_encode($import_files_array);
            $import_files_array = base64_encode($import_files_array);
            $import_files_array = base64_decode($import_files_array);
            $import_files_array = json_decode($import_files_array);
            ?>
            <div class="dik-content-wrapper">
                <div class="dik-main-content">
                    <div class="dik-wrapper">
                        <?php
                        if ($import_files_array) { ?>
                            <div class="dik-grid-panel dik-grid-main">
                                <?php
                                foreach ($import_files_array as $key => $import_file) {
                                    $class = '';
                                    if (isset($import_file->secondary_category_id)) {
                                        $cat_in_1 = $import_file->secondary_category_id;
                                        if ($cat_in_1) {
                                            foreach ($cat_in_1 as $cat_1) {
                                                $class .= $cat_1 . ' ';
                                            }
                                        }
                                    }
                                    $ed_status = true;
                                    if (isset($import_file->primary_category_id)) {
                                        $primary_cat = $import_file->primary_category_id;
                                        if (in_array($PrimaryCat, $primary_cat) || $PrimaryCat == 'all') {
                                            $ed_status = true;
                                        } else {
                                            $ed_status = false;
                                        }
                                    }
                                    if ($ed_status) {
                                        $upgrade_pro = $this->upgrade_pro;
                                        $this->demo_import_kit_content_render($class, $import_file, $keym, $upgrade_pro);
                                    }
                                } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        }
        die();
    }

    /**
     * Tab html render
     **/
    public static function demo_import_kit_primary_tab_render($primary_cat_array, $import_files_array)
    { ?>
        <div class="dik-plugin-masthead <?php if (!$primary_cat_array) {
            echo 'dik-category-disabled';
        } ?>">
            <div class="dik-plugin-masthead-area dik-plugin-masthead-left">
                <div class="dik-branding-wrap">
                    <div class="dik-logo">
                        <img src="<?php echo esc_url(DIK_URL . 'assets/image/logo.png'); ?>" class="dik-logo-image">
                    </div>
                </div>
            </div>
            <div class="dik-plugin-masthead-area dik-plugin-masthead-center">
                <?php if ($import_files_array) { ?>
                    <div class="dik-search-box-wrap">
                        <div class="dik-search-box">
                            <div class="dik-search">
                                <input id="dik-search-input" class="dik-search-input" type="text"
                                       placeholder="<?php esc_attr_e('Search', 'demo-import-kit'); ?>"
                                       name="dik-search-demo">
                                <button class="dik-search-icon">
                                    <?php echo demo_import_kit_svg('search', true); ?>
                                </button>
                                <button class="dik-cross-icon">
                                    <?php echo demo_import_kit_svg('cross', true); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="dik-plugin-masthead-area dik-plugin-masthead-right">
                <div class="dik-header-controls dik-hidden-sm">
                    <div class="dik-header-download">
                        <form method="post" id="demo-import-kit-filters" action="">
                            <input type="hidden" name="demo-import-kit-download" value="true"/>
                            <input type="submit" name="submit" id="submit" class="button button-primary"
                                   value="<?php esc_html_e('Download Export File', 'demo-import-kit') ?>">
                        </form>
                    </div>
                </div>
                <div class="dik-header-filter">
                    <div class="dik-dropdown">
                        <div class="dik-dropdown-button"><?php esc_html_e('All', 'demo-import-kit'); ?> <span class="dik-dropdown-icon"><?php echo demo_import_kit_svg('chevron-down', true); ?></span></div>
                        <ul class="dik-dropdown-list">
                            <li data-filter="all" class="all active">
                                <?php esc_html_e('All', 'demo-import-kit'); ?>
                            </li>
                            <?php foreach ($primary_cat_array as $key => $title) { ?>
                                <li data-filter="<?php echo esc_attr($key); ?>" class="<?php echo esc_html($title); ?>">
                                    <?php echo esc_html($title); ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="dik-plugin-search dik-hidden-desktop">
                   <button type="button" class="dik-search-button">
                        <?php echo demo_import_kit_svg('search', true); ?>
                   </button>
                </div>
                <div class="dik-plugin-exit">
                    <a href="<?php echo esc_url(get_dashboard_url()); ?>">
                        <?php echo demo_import_kit_svg('exit', true); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Tab html render
     **/
    public static function demo_import_kit_secondary_tab_render($secondary_cat_array)
    {
        if ($secondary_cat_array) { ?>
            <div class="dik-nav-filters">
                <div class="dik-wrapper">
                    <div class="dik-nav-filters-group">
                        <div class="dik-category-list-item">
                            <a class="dik-tab dik-tab-active" data-current="*" data-filter="*"
                               href="javascript:void(0)">
                                <?php esc_html_e('All', 'demo-import-kit'); ?>
                            </a>
                        </div>
                        <?php foreach ($secondary_cat_array as $key => $title) { ?>
                            <div class="dik-category-list-item">
                                <a data-current="<?php echo esc_attr($key); ?>" class="dik-tab"
                                   data-filter=".<?php echo esc_attr($key); ?>" href="javascript:void(0)">
                                    <?php echo esc_html($title); ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php }
    }

    /**
     * Tab html render
     **/
    public static function demo_import_kit_content_render($class, $import_file, $key, $upgrade_pro = false)
    {
        //echo '<pre>';print_r($import_file);die;
        ?>
        <div class="dik-columns dik-columns-default dik-grid-items dik-search-filter <?php echo esc_attr($class); ?> <?php echo($import_file->primary_category_id[0]); ?>"
             data-category="<?php echo($import_file->primary_category_id[0]); ?>">
            <div class="dik-columns-content">
                <div class="dik-browser-bar">
                    <div class="dik-browser-head">
                        <span class="dik-browser-control red"></span>
                        <span class="dik-browser-control yellow"></span>
                        <span class="dik-browser-control green"></span>
                        <?php if (isset($import_file->thumbnail_sticker)) { ?>
                            <span class="dik-label-sticker">
                                    <span>
                                    <?php echo esc_html($import_file->thumbnail_sticker); ?>
                                        </span>
                                </span>
                        <?php } ?>
                    </div>
                    <div class="dik-demo-preview">
                        <?php if (isset($import_file->import_preview_image_url)) { ?>
                            <div class="dik-thumbnail">
                                <img src="<?php echo esc_url($import_file->import_preview_image_url); ?>">
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ((isset($import_file->demo_slug) && $import_file->demo_slug) || (isset($import_file->local_content_file) && $import_file->local_content_file)) { ?>
                        <div class="dik-more-details">
                            <a demo-slug="<?php if (isset($import_file->demo_slug)) {
                                echo esc_attr($import_file->demo_slug);
                            } ?>" class="dik-import-button action-import-grid" thumbid="<?php echo esc_attr($key); ?>"
                               href="javascript:void(0)">
                                <?php esc_html_e('Import', 'demo-import-kit'); ?>
                            </a>
                        </div>
                    <?php } else {
                        if ($upgrade_pro) { ?>
                            <div class="dik-more-details dik-upgrade-details">
                                <a target="_blank" class="dik-upgrade-button"
                                   href="<?php echo esc_url($upgrade_pro); ?>">
                                    <?php esc_html_e('Upgrade to Pro', 'demo-import-kit'); ?>
                                </a>
                            </div>
                        <?php }
                    } ?>
                    <div class="dik-browser-footer">
                        <?php if (isset($import_file->import_file_name)) { ?>
                            <h3 class="dik-demo-title"><?php echo esc_attr($import_file->import_file_name); ?></h3>
                        <?php } ?>
                        <div class="dik-demo-actions">
                            <div class="dik-import-preview">
                                <?php if (isset($import_file->preview_link)) { ?>
                                    <a href="<?php echo esc_attr($import_file->preview_link); ?>" class="button"
                                       target="_blank">
                                        <?php esc_html_e('Preview', 'demo-import-kit'); ?>
                                    </a>
                                <?php } ?>
                                <?php if ((isset($import_file->demo_slug) && $import_file->demo_slug) || (isset($import_file->local_content_file) && $import_file->local_content_file)) { ?>
                                    <a demo-slug="<?php if (isset($import_file->demo_slug)) {
                                        echo esc_attr($import_file->demo_slug);
                                    } ?>" class="dik-import-button action-import-grid button button-primary"
                                       thumbid="<?php echo esc_attr($key); ?>" href="javascript:void(0)">
                                        <?php esc_html_e('Import', 'demo-import-kit'); ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Tab html render
     **/
    public static function demo_import_kit_upgrade_to_pro($upgrade_pro = false)
    {
        if ($upgrade_pro) { ?>
            <div class="dik-plugin-footer">
                <div class="dik-wrapper">
                    <div class="dik-row dik-row-space">
                        <div class="dik-columns dik-footer-left">
                            <a href="<?php echo esc_url(get_dashboard_url()); ?>" class="dik-icon-link">
                                <?php echo demo_import_kit_svg('arrow-left', true); ?>
                                <?php esc_html_e('Exit to Dashboard', 'demo-import-kit'); ?>
                            </a>
                        </div>
                        <div class="dik-columns dik-footer-right">
                            <h5 class="dik-footer-title"> <?php esc_html_e('Sign up today and get unlimited access to all Premium Features and Dedicated Support, at a single low cost!', 'demo-import-kit'); ?></h5>
                            <a target="_blank" class="dik-button dik-button-primary dik-button-upsell"
                               href="<?php echo esc_url($upgrade_pro); ?>">
                                <?php esc_html_e('Upgrade to Pro', 'demo-import-kit'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    }

    /**
     * Get data from a file
     *
     * @param string $file_path file path where the content should be saved.
     * @return string $data, content of the file or WP_Error object with error message.
     */
    public static function data_from_file($file_path)
    {
        // By this point, the $wp_filesystem global should be working, so let's use it to read a file.
        global $wp_filesystem;
        $data = $wp_filesystem->get_contents($file_path);
        if (!$data) {
            return new WP_Error(
                'failed_reading_file_from_server',
                sprintf(
                    __('An error occurred while reading a file from your server! Tried reading file from path: %s%s.', 'demo-import-kit'),
                    '<br>',
                    $file_path
                )
            );
        }
        // Return the file data.
        return $data;
    }

    /**
     * Check if the AJAX call is valid.
     */
    public static function verify_ajax_call()
    {
        check_ajax_referer('demo-import-kit-ajax-verification', 'security');
        // Check if user has the WP capability to import data.
        if (!current_user_can('import')) {
            esc_html_e('You are not allowed to import demo content', 'demo-import-kit');
            wp_die();
        }
    }
}

$GLOBALS['demo_import_kit_base_global'] = new Demo_Import_Kit_Base_Class();