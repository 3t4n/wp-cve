<?php
/*
    "WordPress Plugin Template" Copyright (C) 2018 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

include_once('WoocommerceAliexpressDropshipping_InstallIndicator.php');

class WoocommerceAliexpressDropshipping_LifeCycle extends WoocommerceAliexpressDropshipping_InstallIndicator
{

    public function install()
    {

        // Initialize Plugin Options
        $this->initOptions();

        // Initialize DB Tables used by the plugin
        $this->installDatabaseTables();

        // Other Plugin initialization - for the plugin writer to override as needed
        $this->otherInstall();

        // Record the installed version
        $this->saveInstalledVersion();

        // To avoid running install() more then once
        $this->markAsInstalled();
    }

    public function uninstall()
    {
        $this->otherUninstall();
        $this->unInstallDatabaseTables();
        $this->deleteSavedOptions();
        $this->markAsUnInstalled();
    }

    /**
     * Perform any version-upgrade activities prior to activation (e.g. database changes)
     * @return void
     */
    public function upgrade()
    {
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=105
     * @return void
     */
    public function activate()
    {
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=105
     * @return void
     */
    public function deactivate()
    {
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return void
     */
    protected function initOptions()
    {
    }

    public function addActionsAndFilters()
    {
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables()
    {
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables()
    {
    }

    /**
     * Override to add any additional actions to be done at install time
     * See: http://plugin.michael-simpson.com/?page_id=33
     * @return void
     */
    protected function otherInstall()
    {
    }

    /**
     * Override to add any additional actions to be done at uninstall time
     * See: http://plugin.michael-simpson.com/?page_id=33
     * @return void
     */
    protected function otherUninstall()
    {
    }

    /**
     * Puts the configuration page in the Plugins menu by default.
     * Override to put it elsewhere or create a set of submenus
     * Override with an empty implementation if you don't want a configuration page
     * @return void
     */
    public function addSettingsSubMenuPage()
    {
        $this->addSettingsSubMenuPageToPluginsMenu();
        //$this->addSettingsSubMenuPageToSettingsMenu();
    }


    protected function requireExtraPluginFiles()
    {
        require_once(ABSPATH . 'wp-includes/pluggable.php');
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    /**
     * @return string Slug name for the URL to the Setting page
     * (i.e. the page for setting options)
     */
    protected function getSettingsSlug()
    {
        return get_class($this) . 'Settings';
    }
    protected function addSettingsSubMenuPageToPluginsMenu()
    {
        $this->requireExtraPluginFiles();
        $displayName = $this->getPluginDisplayName();

        $parentSlug = $this->getSettingsSlug(); // Get the slug of the parent menu

        add_menu_page(
            $displayName,
            $displayName,
            'manage_options',
            $parentSlug, // Use the parent slug as the parent menu
            array(&$this, 'submenuPageCallback'),
            plugin_dir_url(__FILE__) . 'images/theshark_27x27.png'
        );

        // Add a submenu under the parent menu



        add_submenu_page(
            $parentSlug, // Use the parent slug
            'AliExpress Import', // Replace with your submenu title
            'AliExpress Import', // Replace with your submenu title
            'manage_options', // Capability required to access the submenu
            'aliexpress-slug', // Replace with your submenu slug
            array(&$this, 'submenuAliExpressCallback') // Callback function for the submenu page
        );


        add_submenu_page(
            $parentSlug, // Use the parent slug
            'Ebay Import', // Replace with your submenu title
            'Ebay Import', // Replace with your submenu title
            'manage_options', // Capability required to access the submenu
            'Ebay-slug', // Replace with your submenu slug
            array(&$this, 'submenuEbayCallback') // Callback function for the submenu page
        );


        add_submenu_page(
            $parentSlug, // Use the parent slug
            'Etsy Import', // Replace with your submenu title
            'Etsy Import', // Replace with your submenu title
            'manage_options', // Capability required to access the submenu
            'Etsy-slug', // Replace with your submenu slug
            array(&$this, 'submenuEtsyCallback') // Callback function for the submenu page
        );

        add_submenu_page(
            $parentSlug, // Use the parent slug
            'Products', // Replace with your submenu title
            'Products', // Replace with your submenu title
            'manage_options', // Capability required to access the submenu
            'products-slug', // Replace with your submenu slug
            array(&$this, 'submenuPageCallback') // Callback function for the submenu page
        );


        add_submenu_page(
            $parentSlug, // Use the parent slug
            'Configuration', // Replace with your submenu title
            'Configuration', // Replace with your submenu title
            'manage_options', // Capability required to access the submenu
            'Configuration-slug', // Replace with your submenu slug
            array(&$this, 'submenuConfigurationPageCallback') // Callback function for the submenu page
        );

        add_submenu_page(
            $parentSlug, // Use the parent slug
            'Chrome extension', // Replace with your submenu title
            'Chrome extension',  // Replace with your submenu title
            'manage_options', // Capability required to access the submenu
            'extension-slug', // Replace with your submenu slug
            array(&$this, 'chromeExtensionCallbackPage') // Callback function for the submenu page
        );
        update_option('plugin_license_activated', false);



        $isPremuim = false;
        if ($isPremuim) {
            add_submenu_page(
                $parentSlug, // Use the parent slug
                'License Activation', // Replace with your submenu title
                'License Activation', // Replace with your submenu title
                'manage_options', // Capability required to access the submenu
                'license-activation-slug', // Replace with your submenu slug
                array(&$this, 'submenuLicenseActivationCallback') // Callback function for the submenu page
            );
        } else {
            update_option('plugin_license_activated', true);

            add_submenu_page(
                $parentSlug, // Use the parent slug
                'Go Pro', // page title
                'Go Pro', // menu title
                'manage_options', // capability
                'sharkdropship-go-pro', // menu slug

                array(&$this, 'my_plugin_go_pro_page_callback') // Callback function for the submenu page

            );


        }
    }



    function my_plugin_go_pro_page_callback()
    {
?>
        <div class="wrap">
            <h1>Go Pro</h1>
            <p>Unlock more features with our Pro plan!</p>
            <div class="feature-cards">
                <div class="card">
                    <h3>Chrome extension</h3>
                    <p>Direct import from supplier website</p>
                </div>

                <div class="card">
                    <h3>Continious Updates and support</h3>
                    <p>Many New features are ongoing </p>
                </div>

                <div class="card">
                    <h3>Import All Product Details</h3>
                    <p>Comprehensive import options for every product detail.</p>
                </div>
                <div class="card">
                    <h3>1000 Product/supplier Imports per Day</h3>
                    <p>Easily import up to 1000 products daily.</p>
                </div>
                <div class="card">
                    <h3>1000/supplier Product Updates per Day</h3>
                    <p>Keep your product catalog up-to-date with daily updates.</p>
                </div>

                <!-- Add more cards for other features -->
            </div>
            <a style="margin-top: 20px;" href="https://sharkdropship.com/wooshark-dropshipping/" target="_blank" class="button button-primary">Upgrade Now</a>
        </div>
        <style>
            .feature-cards {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
            }

            .card {
                flex-basis: calc(33% - 20px);
                border: 1px solid #ddd;
                padding: 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .card h3 {
                margin-top: 0;
            }
        </style>
    <?php
    }




    public function submenuLicenseActivationCallback()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activate_license'])) {
            $license_key = sanitize_text_field($_POST['license_key']);

            // Send a request to your backend server for license validation
            $validation_result = $this->validateLicense($license_key);

            if ($validation_result === true) {
                // License is valid, store activation status
                update_option('plugin_license_activated', true);
                echo '<p style="color:green">License activated successfully!</p>';
            } else {
                update_option('plugin_license_activated', false);
                // License is not valid, display an error message
                echo '<p style="color:red">License activation failed. Please check your license key.</p>';
            }
        }

        // Display the license activation form
    ?>
        <div class="wrap">
            <h2>License Activation</h2>
            <form method="post">
                <label for="license_key">Enter your license key:</label>
                <input type="text" id="license_key" name="license_key" required>
                <input type="submit" name="activate_license" value="Activate License">
            </form>
        </div>
<?php
    }


    public function validateLicense($license_key)
    {
        $url = 'https://wooshark.website:8002/getActiveHostAliexpressEbayAmazon';
        $website = get_site_url(); // Get the current website's URL
        $activation_code = $license_key;

        // Prepare the request body data
        $request_data = array(
            'clientWebsite' => $website,
            'activationCode' => $activation_code,
        );

        $response = wp_remote_post(
            $url,
            array(
                'body' => json_encode($request_data), // Convert data to JSON format
                'headers' => array('Content-Type' => 'application/json'),
            )
        );

        if (is_wp_error($response)) {
            // Handle the error, e.g., log it or return an error message
            return false;
        }

        // Check the HTTP response status code
        $response_code = wp_remote_retrieve_response_code($response);

        if ($response_code === 200) {
            // License is valid
            return true;
        } else {
            // License is not valid
            return false;
        }
    }




    public function settingMenu()
    {

        // $html_file_path = plugin_dir_path(__FILE__) . 'templates/product-template.html';


        // if (file_exists($html_file_path)) {
        //     include $html_file_path;
        // } else {
        //     echo 'HTML file not found.';
        // }

        // wp_enqueue_style('products-custom.css', plugin_dir_url(__FILE__) . 'css/products-custom.css'); // Replace 'custom.css' with your CSS file name

        // wp_enqueue_script('products', plugin_dir_url(__FILE__) . 'js/products.js', array('jquery'), NULL, false);
        // // wp_enqueue_script('startup', plugin_dir_url(__FILE__) . 'js/startup.js', array('jquery'), NULL, false);
        // // wp_enqueue_script('ebay', plugin_dir_url(__FILE__) . 'js/ebay-import.js', array('jquery'), NULL, false);
        // wp_enqueue_script('toast', plugin_dir_url(__FILE__) . 'js/jquery.toast.min.js', array('jquery'), NULL, false);
        // wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), NULL, false);
        // wp_enqueue_style('toastCss', plugin_dir_url(__FILE__) . 'css/jquery.toast.min.css');
        // wp_enqueue_style('customcss', plugin_dir_url(__FILE__) . 'css/main.css');
        // wp_enqueue_style('bootstrapCss', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
        // wp_enqueue_script('quill', plugin_dir_url(__FILE__) . 'js/quill.js', array('jquery'), NULL, false);
        // wp_enqueue_style('quillCss', plugin_dir_url(__FILE__) . 'css/quill.css');
        // wp_enqueue_script('math', plugin_dir_url(__FILE__) . 'js/math.js', array('jquery'), NULL, false);
        // wp_enqueue_style('awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.css');
        // wp_enqueue_style('mdbcss', plugin_dir_url(__FILE__) . 'css/mdb.min.css');
        // wp_enqueue_script('fontawJs', plugin_dir_url(__FILE__) . 'js/font-aesome.min.js', array('jquery'), NULL, false);

        // wp_localize_script(
        //     'products',
        //     'wooshark_params',
        //     array(
        //         'ajaxurl' => admin_url('admin-ajax.php'),
        //         'nonce' => wp_create_nonce('ajax-nonce')
        //     )
        // );

        // wp_enqueue_script('my-submenu-script', plugins_url('/js/my-submenu-script.js', __FILE__));


    }






    public function chromeExtensionCallbackPage() {
        // Enqueue necessary scripts and styles
        wp_enqueue_script('extension', plugin_dir_url(__FILE__) . 'js/extension.js', array('jquery'), NULL, false);
        wp_enqueue_style('extension-custom.css', plugin_dir_url(__FILE__) . 'css/extension-custom.css'); // Replace 'custom.css' with your CSS file name

        // Fetch reference ID from the WordPress options
        $reference_id = get_option('my_plugin_reference_id', '');
        if (empty($reference_id)) {
            $reference_id = wp_generate_uuid4(); // Generates a random UUID
            update_option('my_plugin_reference_id', $reference_id);
        }
    
        // Localize the script with your data
        $data_to_pass = array('referenceId' => $reference_id);
        wp_localize_script(
            'extension',
            'myPluginData',
            $data_to_pass
        );
    
        // Include the HTML template
        $html_file_path = plugin_dir_path(__FILE__) . 'templates/extension-template.html';
        if (file_exists($html_file_path)) {
            $html_content = file_get_contents($html_file_path);
            $html_content = str_replace('{{REFERENCE_ID}}', esc_html($reference_id), $html_content);
            echo $html_content;
        } else {
            echo 'HTML file not found.';
        }
    
        // Uncomment and include any additional scripts or styles if needed
        // wp_enqueue_style('extension-custom.css', plugin_dir_url(__FILE__) . 'css/extension-custom.css');
        // Additional enqueues if needed...
    }
    

    public function submenuConfigurationPageCallback()
    {

        $html_file_path = plugin_dir_path(__FILE__) . 'templates/configuration-template.html';

        if (file_exists($html_file_path)) {
            include $html_file_path;
        } else {
            echo 'HTML file not found.';
        }

        wp_enqueue_style('configuration-custom.css', plugin_dir_url(__FILE__) . 'css/configuration-custom.css'); // Replace 'custom.css' with your CSS file name

        wp_enqueue_script('configuration', plugin_dir_url(__FILE__) . 'js/configuration.js', array('jquery'), NULL, false);
        // wp_enqueue_script('common-multisupplier.js', plugin_dir_url(__FILE__) . 'js/common-multisupplier.js', array('jquery'), NULL, false);


        wp_enqueue_script('toast', plugin_dir_url(__FILE__) . 'js/jquery.toast.min.js', array('jquery'), NULL, false);
        wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), NULL, false);
        wp_enqueue_style('toastCss', plugin_dir_url(__FILE__) . 'css/jquery.toast.min.css');
        wp_enqueue_style('customcss', plugin_dir_url(__FILE__) . 'css/main.css');
        wp_enqueue_style('bootstrapCss', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
        wp_enqueue_script('quill', plugin_dir_url(__FILE__) . 'js/quill.js', array('jquery'), NULL, false);
        wp_enqueue_style('quillCss', plugin_dir_url(__FILE__) . 'css/quill.css');
        wp_enqueue_script('math', plugin_dir_url(__FILE__) . 'js/math.js', array('jquery'), NULL, false);
        wp_enqueue_style('awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.css');
        wp_enqueue_style('mdbcss', plugin_dir_url(__FILE__) . 'css/mdb.min.css');
        wp_enqueue_script('fontawJs', plugin_dir_url(__FILE__) . 'js/font-aesome.min.js', array('jquery'), NULL, false);

        wp_localize_script(
            'configuration',
            'wooshark_params',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ajax-nonce')
            )
        );

        // wp_enqueue_script('my-submenu-script', plugins_url('/js/my-submenu-script.js', __FILE__));


    }


    public function submenuPageCallback()
    {
        $is_activated = get_option('plugin_license_activated');

        if (!$is_activated) {
            echo '<p>You need to activate your license to access this feature.</p>';
            return;
        }

        $html_file_path = plugin_dir_path(__FILE__) . 'templates/product-template.html';

        if (file_exists($html_file_path)) {
            include $html_file_path;
        } else {
            echo 'HTML file not found.';
        }

        wp_enqueue_style('products-custom.css', plugin_dir_url(__FILE__) . 'css/products-custom.css'); // Replace 'custom.css' with your CSS file name

        wp_enqueue_script('products', plugin_dir_url(__FILE__) . 'js/products.js', array('jquery'), NULL, false);
        // wp_enqueue_script('common-multisupplier.js', plugin_dir_url(__FILE__) . 'js/common-multisupplier.js', array('jquery'), NULL, false);

        wp_enqueue_script('toast', plugin_dir_url(__FILE__) . 'js/jquery.toast.min.js', array('jquery'), NULL, false);
        wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), NULL, false);
        wp_enqueue_style('toastCss', plugin_dir_url(__FILE__) . 'css/jquery.toast.min.css');
        wp_enqueue_style('customcss', plugin_dir_url(__FILE__) . 'css/main.css');
        wp_enqueue_style('bootstrapCss', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
        wp_enqueue_script('quill', plugin_dir_url(__FILE__) . 'js/quill.js', array('jquery'), NULL, false);
        wp_enqueue_style('quillCss', plugin_dir_url(__FILE__) . 'css/quill.css');
        wp_enqueue_script('math', plugin_dir_url(__FILE__) . 'js/math.js', array('jquery'), NULL, false);
        wp_enqueue_style('awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.css');
        wp_enqueue_style('mdbcss', plugin_dir_url(__FILE__) . 'css/mdb.min.css');
        wp_enqueue_script('fontawJs', plugin_dir_url(__FILE__) . 'js/font-aesome.min.js', array('jquery'), NULL, false);

        wp_localize_script(
            'products',
            'wooshark_params',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ajax-nonce')
            )
        );

        // wp_enqueue_script('my-submenu-script', plugins_url('/js/my-submenu-script.js', __FILE__));


    }





    public function submenuEtsyCallback()
    {

        $is_activated = get_option('plugin_license_activated');

        if (!$is_activated) {
            echo '<p>You need to activate your license to access this feature.</p>';
            return;
        }
        $html_file_path = plugin_dir_path(__FILE__) . 'templates/etsy-product-template.html';

        if (file_exists($html_file_path)) {
            include $html_file_path;
        } else {
            echo 'HTML file not found.';
        }

        wp_enqueue_style('etsy-custom.css', plugin_dir_url(__FILE__) . 'css/etsy-custom.css'); // Replace 'custom.css' with your CSS file name
        // wp_enqueue_script('commonetsy-multisupplier.js', plugin_dir_url(__FILE__) . 'js/common-multisupplier.js', array('jquery'), NULL, false);

        wp_enqueue_script('etsy', plugin_dir_url(__FILE__) . 'js/etsy.js', array('jquery'), NULL, false);
        wp_enqueue_script('toast', plugin_dir_url(__FILE__) . 'js/jquery.toast.min.js', array('jquery'), NULL, false);
        wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), NULL, false);
        wp_enqueue_style('toastCss', plugin_dir_url(__FILE__) . 'css/jquery.toast.min.css');
        wp_enqueue_style('customcss', plugin_dir_url(__FILE__) . 'css/main.css');
        wp_enqueue_style('bootstrapCss', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
        wp_enqueue_script('quill', plugin_dir_url(__FILE__) . 'js/quill.js', array('jquery'), NULL, false);
        wp_enqueue_style('quillCss', plugin_dir_url(__FILE__) . 'css/quill.css');
        wp_enqueue_script('math', plugin_dir_url(__FILE__) . 'js/math.js', array('jquery'), NULL, false);
        wp_enqueue_style('awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.css');
        wp_enqueue_style('mdbcss', plugin_dir_url(__FILE__) . 'css/mdb.min.css');
        wp_enqueue_script('fontawJs', plugin_dir_url(__FILE__) . 'js/font-aesome.min.js', array('jquery'), NULL, false);

        wp_localize_script(
            'etsy',
            'wooshark_params',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ajax-nonce')
            )
        );
    }


    public function submenuEbayCallback()
    {

        $is_activated = get_option('plugin_license_activated');

        if (!$is_activated) {
            echo '<p>You need to activate your license to access this feature.</p>';
            return;
        }
        $html_file_path = plugin_dir_path(__FILE__) . 'templates/ebay-product-template.html';

        if (file_exists($html_file_path)) {
            include $html_file_path;
        } else {
            echo 'HTML file not found.';
        }

        wp_enqueue_style('ebay-custom.css', plugin_dir_url(__FILE__) . 'css/ebay-custom.css'); // Replace 'custom.css' with your CSS file name
        // wp_enqueue_script('commonebay-multisupplier.js', plugin_dir_url(__FILE__) . 'js/common-multisupplier.js', array('jquery'), NULL, false);

        wp_enqueue_script('ebay', plugin_dir_url(__FILE__) . 'js/ebay.js', array('jquery'), NULL, false);
        wp_enqueue_script('toast', plugin_dir_url(__FILE__) . 'js/jquery.toast.min.js', array('jquery'), NULL, false);
        wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), NULL, false);
        wp_enqueue_style('toastCss', plugin_dir_url(__FILE__) . 'css/jquery.toast.min.css');
        wp_enqueue_style('customcss', plugin_dir_url(__FILE__) . 'css/main.css');
        wp_enqueue_style('bootstrapCss', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
        wp_enqueue_script('quill', plugin_dir_url(__FILE__) . 'js/quill.js', array('jquery'), NULL, false);
        wp_enqueue_style('quillCss', plugin_dir_url(__FILE__) . 'css/quill.css');
        wp_enqueue_script('math', plugin_dir_url(__FILE__) . 'js/math.js', array('jquery'), NULL, false);
        wp_enqueue_style('awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.css');
        wp_enqueue_style('mdbcss', plugin_dir_url(__FILE__) . 'css/mdb.min.css');
        wp_enqueue_script('fontawJs', plugin_dir_url(__FILE__) . 'js/font-aesome.min.js', array('jquery'), NULL, false);

        wp_localize_script(
            'ebay',
            'wooshark_params',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ajax-nonce')
            )
        );

        // wp_enqueue_script('my-submenu-script', plugins_url('/js/my-submenu-script.js', __FILE__));


    }


    public function submenuAliExpressCallback()
    {

        $is_activated = get_option('plugin_license_activated');

        if (!$is_activated) {
            echo '<p>You need to activate your license to access this feature.</p>';
            return;
        }

        $html_file_path = plugin_dir_path(__FILE__) . 'templates/aliexpress-product-template.html';

        if (file_exists($html_file_path)) {
            include $html_file_path;
        } else {
            echo 'HTML file not found.';
        }

        wp_enqueue_style('aliexpress-custom.css', plugin_dir_url(__FILE__) . 'css/aliexpress-custom.css'); // Replace 'custom.css' with your CSS file name
        // wp_enqueue_script('commonaliexpress-multisupplier.js', plugin_dir_url(__FILE__) . 'js/common-multisupplier.js', array('jquery'), NULL, false);

        wp_enqueue_script('aliexpress', plugin_dir_url(__FILE__) . 'js/aliexpress.js', array('jquery'), NULL, false);
        wp_enqueue_script('toast', plugin_dir_url(__FILE__) . 'js/jquery.toast.min.js', array('jquery'), NULL, false);
        wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), NULL, false);
        wp_enqueue_style('toastCss', plugin_dir_url(__FILE__) . 'css/jquery.toast.min.css');
        wp_enqueue_style('customcss', plugin_dir_url(__FILE__) . 'css/main.css');
        wp_enqueue_style('bootstrapCss', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
        wp_enqueue_script('quill', plugin_dir_url(__FILE__) . 'js/quill.js', array('jquery'), NULL, false);
        wp_enqueue_style('quillCss', plugin_dir_url(__FILE__) . 'css/quill.css');
        wp_enqueue_script('math', plugin_dir_url(__FILE__) . 'js/math.js', array('jquery'), NULL, false);
        wp_enqueue_style('awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.css');
        wp_enqueue_style('mdbcss', plugin_dir_url(__FILE__) . 'css/mdb.min.css');
        wp_enqueue_script('fontawJs', plugin_dir_url(__FILE__) . 'js/font-aesome.min.js', array('jquery'), NULL, false);

        wp_localize_script(
            'aliexpress',
            'wooshark_params',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ajax-nonce')
            )
        );

        // wp_enqueue_script('my-submenu-script', plugins_url('/js/my-submenu-script.js', __FILE__));


    }




    protected function addSettingsSubMenuPageToSettingsMenu()
    {
        // $this->requireExtraPluginFiles();
        // $displayName = $this->getPluginDisplayName();
        // add_options_page(
        //     $displayName,
        //     $displayName,
        //     'manage_options',
        //     $this->getSettingsSlug(),
        //     array(&$this, 'settingsPage')
        // );
    }

    /**
     * @param  $name string name of a database table
     * @return string input prefixed with the WordPress DB table prefix
     * plus the prefix for this plugin (lower-cased) to avoid table name collisions.
     * The plugin prefix is lower-cases as a best practice that all DB table names are lower case to
     * avoid issues on some platforms
     */
    protected function prefixTableName($name)
    {
        global $wpdb;
        return $wpdb->prefix .  strtolower($this->prefix($name));
    }


    /**
     * Convenience function for creating AJAX URLs.
     *
     * @param $actionName string the name of the ajax action registered in a call like
     * add_action('wp_ajax_actionName', array(&$this, 'functionName'));
     *     and/or
     * add_action('wp_ajax_nopriv_actionName', array(&$this, 'functionName'));
     *
     * If have an additional parameters to add to the Ajax call, e.g. an "id" parameter,
     * you could call this function and append to the returned string like:
     *    $url = $this->getAjaxUrl('myaction&id=') . urlencode($id);
     * or more complex:
     *    $url = sprintf($this->getAjaxUrl('myaction&id=%s&var2=%s&var3=%s'), urlencode($id), urlencode($var2), urlencode($var3));
     *
     * @return string URL that can be used in a web page to make an Ajax call to $this->functionName
     */
    public function getAjaxUrl($actionName)
    {
        return admin_url('admin-ajax.php') . '?action=' . $actionName;
    }
}
