<?php
/*
  Plugin Name: WP Authenticity Checker (WAC)
  Plugin URI: http://www.infobeans.com
  Description: WP Authenticity Checker scans all of your wordpress <strong>themes and plugins </strong> for potentially malicious or unwanted code, find the malicious code in seconds, <strong>Add on for autofix the malware infection</strong>.
  Author: Kapil Yadav
  Version: 1.0
  Author URI: http://www.infobeans.com
 */

class wordpress_autheticity_checker {

    public $version, $prefix, $page_title, $menu_title, $capability, $debug, $menu_slug, $malware_definations, $plug_error_count, $theme_error_count, $plugin_dir_root, $plugin_dirs = [], $tmp_plugins = [], $theme_dirs = [], $tmp_themes = [], $tmp_core = [], $core_dirs = [];

    public function __construct() {

        $this->version = '1.0';
        $this->prefix = 'wac';
        $this->home_path = ABSPATH;
        $this->plugin_dir_root = $this->home_path . 'wp-content/plugins';
        $this->theme_dir_root = $this->home_path . 'wp-content/themes';
        $this->page_title = 'WP Auth Checker';
        $this->menu_title = 'WP Auth Checker';
        $this->capability = 'manage_options';
        $this->menu_slug = 'wac_admin_panel';
        $this->plug_error_count = 0;
        $this->theme_error_count = 0;
        $this->help_tabs = array(
            array(
                'id' => 'wac_help_tab1',
                'title' => __('WAC Description'),
                'content' => '<p>' . __('Complete solution for checking plugins and themes for malicious code with line number and edit file link to debug and resolve.') . '</p>',
            ),
            array(
                'id' => 'wac_help_tab2',
                'title' => __('Removing malwares'),
                'content' => '<p>' . __('Click to edit link below the hilighted malicious code and check and remove the code from files.') . '</p>',
            ),
            array(
                'id' => 'wac_help_tab3',
                'title' => __('About Author'),
                'content' => '<p>' . __('Passionate Wordpress Developer and Security Researcher.<br><br><strong class="dashicons-before dashicons-businessman"></strong><a href="https://profiles.wordpress.org/lpkapil008/#content-plugins" target="_blank"> Wordpress Profile</a><br><strong class="dashicons-before dashicons-email-alt"></strong> <a href="mailto:kapil.yadav@infobeans.com"> kapil.yadav@infobeans.com</a>') . '</p>',
            )
        );

        //Malware definations expression and syntax, add more itme in array to update definations
        $this->malware_definations = 'base64';
        $this->debug = true;  // For Advance users developers set it true
        //Create constant for prefix
        if (!defined('WAP_PREFIX')) {
            define('WAP_PREFIX', $this->prefix);
        }

        //Action hooks on init
        add_action('init', array($this, '__init'));
    }

    /* All required instances and calls */

    public function __init() {
        add_action('admin_enqueue_scripts', array($this, 'wacLoadAdminStyles'));
        add_action('admin_menu', array($this, 'wacAddOptionPage'));
    }

    public function wacLoadAdminStyles() {
        wp_enqueue_style('wac_admin', plugins_url('wp-authenticity-checker-wac/assets/css/wac_admin.css'));
    }

    public function wacAddOptionPage() {
        //Add option page
        $wac_option_page = add_menu_page($this->page_title, $this->menu_title, $this->capability, $this->menu_slug, array($this, 'wacRenderAdminInterface'), plugins_url('wp-authenticity-checker-wac/assets/images/icon.gif'));

        // Adds my_help_tab when my_admin_page loads
        add_action('load-' . $wac_option_page, array($this, 'wacAdminHelpTab'));
    }

    public function wacRenderAdminInterface() {
        ?>
        <div class='wrap'>
            <h2><img id="admin_wac_option_logo" src="<?php echo plugins_url('wp-authenticity-checker-wac/assets/images/icon.gif'); ?>"> WP Authenticity Checker <i class='wac_brand'>( WAC )</i> <p class='description space10'> Plugins, Themes authenticity checker and malicious code scanner.</p></h2>

            <h3 class='left_space10 dashicons-before dashicons-admin-plugins'>  Plugins scan results</h3>
            <?php self::wacScanPluginsDir($this->plugin_dir_root); ?>
            <?php
            if ($this->plug_error_count > 0) {
                echo "<div class='wac-good'><strong class='dashicons-before dashicons-no'> STATUS: </strong>( Malicious code found in {$this->plug_error_count} plugin files )</div>";
            } else {
                echo "<div class='wac-clean'><strong class='dashicons-before dashicons-yes'> STATUS: </strong>( No Malicious code found in plugin files )</div>";
            }
            ?>
            <h3 class='left_space10 dashicons-before dashicons-admin-appearance'>  Themes scan results</h3>
            <?php self::wacScanThemessDir($this->theme_dir_root); ?>
            <?php
            if ($this->theme_error_count > 0) {
                echo "<div class='wac-good'><strong class='dashicons-before dashicons-no'> STATUS: </strong>( Malicious code found in {$this->theme_error_count} theme files )</div>";
            } else {
                echo "<div class='wac-clean'><strong class='dashicons-before dashicons-yes'> STATUS: </strong>( No Malicious code found in theme files )</div>";
            }
            ?>
            <!--<h3 class='left_space10'>Wordpress core scan</h3> -->
            <?php //self::wacScanWpCoreDir($this->home_path);    ?>

        </div>
        <?php
    }

    public function removeArrayDotDirectory($dataArr) {
        return array_values(array_diff($dataArr, array(".", "..")));
    }

    public static function wac_find_recursive_php_files($path) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $path) {
            if ($path->isDir()) {
                //skip directories
                continue;
            } else {
                $fileNameArr = explode(".", $path);
                if ('php' == end($fileNameArr)) {
                    $files[] = $path->__toString();
                }
            }
        }
        return $files;
    }

    /* Plugin malicous code scanning */

    public function wacScanPluginsDir($dir) {

        foreach (new DirectoryIterator($dir) as $file) {
            if ($file->isDir() && !$file->isDot()) {
                $this->plugin_dirs[] = $file->getFilename();
            }
        }

	//Self plugin fix
	foreach ($this->plugin_dirs as $k => $d) {
            if ($d == 'wp-authenticity-checker-wac') {
                unset($this->plugin_dirs[$k]);
            }
        }

        if (count($this->plugin_dirs) > 0) {

            foreach ($this->plugin_dirs as $plug_dir) {

                $this->tmp_plugin[$plug_dir] = self::wac_find_recursive_php_files($dir . "/" . $plug_dir);
            }


            //Loop and Read Files and check for base64 Encoded code 
            foreach ($this->tmp_plugin as $key => $current_files) {

                foreach ($current_files as $file) {

                    //Avoid plugin file itself for scan
                    if ($this->plugin_dir_root . '/' . $key . '/wp-authenticity-checker-wac.php' != $file) {

                        $bad_lines = null;
                        $is_found = null;
                        $lines = file($file, FILE_IGNORE_NEW_LINES); // Read the theme file into an array
                        $line_index = 0;

                        foreach ($lines as $this_line) {
                            if (stristr($this_line, $this->malware_definations)) { // Check for any base64 functions
                                //Increment plug error count flag
                                $this->plug_error_count++;
                                $bad_lines .= "<div class=\"wac-bad\"><strong>Line " . ($line_index + 1) . ":</strong> \"" . trim(htmlspecialchars(substr(stristr($this_line, "base64"), 0, 45))) . "...\"<br><div class='dashicons-before dashicons-sos wac_edit'> Edit:</div> <a href='" . admin_url('plugin-editor.php?file=' . str_replace($this->plugin_dir_root, "", $file)) . "'>" . str_replace($this->plugin_dir_root, " ", $file) . "</a></div>";
                            }
                            $line_index++;
                        }

                        echo $bad_lines;
                    }
                }
            }
        }
    }

    /* Themes malicious code scanning */

    public function wacScanThemessDir($dir) {

        foreach (new DirectoryIterator($dir) as $file) {
            if ($file->isDir() && !$file->isDot()) {
                $this->theme_dirs[] = $file->getFilename();
            }
        }

        if (count($this->theme_dirs) > 0) {

            foreach ($this->theme_dirs as $theme_dir) {

                $this->tmp_themes[$theme_dir] = self::wac_find_recursive_php_files($dir . "/" . $theme_dir);
            }

            //Loop and Read Files and check for base64 Encoded code 
            foreach ($this->tmp_themes as $key => $current_files) {

                foreach ($current_files as $file) {

                    $bad_lines = null;
                    $is_found = null;
                    $lines = file($file, FILE_IGNORE_NEW_LINES); // Read the theme file into an array
                    $line_index = 0;

                    foreach ($lines as $this_line) {
                        if (stristr($this_line, $this->malware_definations)) { // Check for any base64 functions
                            //Increment theme error count flag
                            $this->theme_error_count++;

                            $bad_lines .= "<div class=\"wac-bad\"><strong>Line " . ($line_index + 1) . ":</strong> \"" . trim(htmlspecialchars(substr(stristr($this_line, "base64"), 0, 45))) . "...\"<br><div class='dashicons-before dashicons-sos wac_edit'> Edit:</div> <a href='" . admin_url('theme-editor.php?file=' . str_replace($this->theme_dir_root . "/" . $key . "/", "", $file)) . "&theme=" . $key . "'>" . str_replace($this->theme_dir_root, "", $file) . "</a></div>";
                        }
                        $line_index++;
                    }

                    echo $bad_lines;
                }
            }
        }
    }

    /* Wordpress core cms malicious code scanning */

    public function wacScanWpCoreDir($dir) {

        foreach (new DirectoryIterator($dir) as $file) {
            if ($file->isDir() && !$file->isDot()) {
                $this->core_dirs[] = $file->getFilename();
            }
        }


        if (count($this->core_dirs) > 0) {

            foreach ($this->core_dirs as $core_dir) {

                $this->tmp_core[$core_dir] = self::wac_find_recursive_php_files($this->home_path . $core_dir);
            }

            //Loop and Read Files and check for base64 Encoded code 
            foreach ($this->tmp_core as $key => $current_files) {

                //Avoid wp-content dir as it's already scanned in themes and plugins
                if ('wp-content' != $key) {
                    foreach ($current_files as $file) {

                        $bad_lines = null;
                        $is_found = null;
                        $lines = file($file, FILE_IGNORE_NEW_LINES); // Read the theme file into an array
                        $line_index = 0;

                        foreach ($lines as $this_line) {
                            if (stristr($this_line, $this->malware_definations)) { // Check for any base64 functions
                                $bad_lines .= "<div class=\"wac-bad\"><strong>Line " . ($line_index + 1) . ":</strong> \"" . trim(htmlspecialchars(substr(stristr($this_line, "base64"), 0, 45))) . "...\"<br>" . $file . "</div>";
                            }
                            $line_index++;
                        }

                        echo $bad_lines;
                    }
                }
            }
        }
    }

    /* Add WAC Admin Help Tabs */

    public function wacAdminHelpTab() {
        $screen = get_current_screen();

        // Add my_help_tab if current screen is My Admin Page
        foreach ($this->help_tabs as $tab) {
            $screen->add_help_tab($tab);
        }
    }

}

//Initialize
$wacObject = new wordpress_autheticity_checker();
