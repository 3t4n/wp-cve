<?php
defined('ABSPATH') or die('No script kiddies please!');

class TTWManagerSettingPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'ttw_manager_settingPageMenu'));
        add_action('admin_init', array($this, 'ttw_manager_settingPageInit'));
        add_action('admin_init', array($this, 'ttw_contactUs_callback'));
    }

    /**
     * Add options page
     */
    public function ttw_manager_settingPageMenu()
    {
        // Admin Menu Page
        add_menu_page('TTW Settings', 'TTW Settings', 'manage_options', 'ttw_manager', array($this, 'ttw_manager_settingsFormPage'), TTW_ICON);
        add_submenu_page('ttw_manager', 'TTW Information', 'TTW Information', 'manage_options', 'ttw_info', array($this, 'ttw_manager_infoPage'));
    }

    /**
     * Options page callback
     */
    public function ttw_manager_settingsFormPage()
    {
        // Set class property
        $this->options = get_option('ttw_manager_settings');
        ?>
        <div class="wrap">
            <h1>Taxonomy Thumbmail And Widget Settings</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('ttw_manager_settings_group');
                do_settings_sections('ttw-manager-admin-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function ttw_manager_infoPage()
    {
        echo '<section class="informative">';
        include('' . sprintf(TTWFILE_PATH . 'lib/%s', 'ttw_information.php') . '');
        echo '</section>';
    }

    /**
     * Register and add settings
     */
    public function ttw_manager_settingPageInit()
    {
        register_setting(
            'ttw_manager_settings_group', // Option group
            'ttw_manager_settings', // Option name
            array($this, 'ttw_sanitize') // ttw_sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Select Taxonomies for which you want thumbnail:', // Title
            array($this, 'ttw_print_section_info'), // Callback
            'ttw-manager-admin-settings' // Page
        );

        add_settings_field(
            'ttw_selected_taxonomies',
            'Choose Taxonomies:',
            array($this, 'ttw_choose_taxonomies_callback'),
            'ttw-manager-admin-settings',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function ttw_sanitize($input)
    {
        $new_input = array();
        if (isset($input['ttw_selected_taxonomies']))
            $new_input['ttw_selected_taxonomies'] = $input['ttw_selected_taxonomies'];

        if (isset($input['ttw_woo_attribute_page']))
            $new_input['ttw_woo_attribute_page'] = $input['ttw_woo_attribute_page'];

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function ttw_print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function ttw_choose_taxonomies_callback()
    {
        $storedData = '';
        if (isset($this->options['ttw_selected_taxonomies'])) {
            $storedData = $this->options['ttw_selected_taxonomies'];
        }

        $ttw_manager_settins = '';
        $ttw_manager_settins .= '<select name="ttw_manager_settings[ttw_selected_taxonomies][]" id="thumbnail_taxonomies" multiple>';

        $catFlag = 0;
        if (is_array($storedData)) {
            if (in_array('category', $storedData)) {
                $catFlag = 1;
                $ttw_manager_settins .= '<option value="category" selected="selected" >category</option>';
            }
        }
        if ($catFlag == 0) {
            $ttw_manager_settins .= '<option value="category" >category</option>';
        }

        $tagFlag = 0;
        if (is_array($storedData)) {
            if (in_array('post_tag', $storedData)) {
                $tagFlag = 1;
                $ttw_manager_settins .= '<option value="post_tag" selected="selected" >post_tag</option>';
            }
        }
        if ($tagFlag == 0) {
            $ttw_manager_settins .= '<option value="post_tag" >post_tag</option>';
        }
        if (class_exists('WooCommerce')) {
            $taxonomies = get_taxonomies(array('public' => true, '_builtin' => false));
            $wootaxonomies = wc_get_attribute_taxonomies();
            if (is_array($wootaxonomies)) {
                foreach ($wootaxonomies as $wootax) {
                    $taxname = wc_attribute_taxonomy_name($wootax->attribute_name);
                    array_push($taxonomies, $taxname);
                }
            }
            array_unique($taxonomies);
        } else {
            $taxonomies = get_taxonomies(array('public' => true, '_builtin' => false));
        }
        $taxonomies = array_unique($taxonomies);
        foreach ($taxonomies as $taxonomy) {
            $selectedTxt = '';
            if (is_array($storedData)) {
                if (in_array($taxonomy, $storedData)) {
                    $selectedTxt = 'selected="selected"';
                }
            }
            $ttw_manager_settins .= '<option value="' . $taxonomy . '" ' . $selectedTxt . ' >' . $taxonomy . '</option>';
        }
        $ttw_manager_settins .= '</Select>';
        printf($ttw_manager_settins);

    }

    /**
     * Plugin Contact Us
     **/
    public function ttw_contactUs_callback()
    {
        if (isset($_REQUEST['ttwContactUs']) && wp_verify_nonce($_REQUEST['ttwContactUs'], 'ttwContact')):
            $blogName = sanitize_text_field(bloginfo('name'));
            $blogUrl = esc_url(bloginfo('url'));
            $blogAdminEmail = sanitize_email(bloginfo('admin_email'));
            $contactSubject = sanitize_text_field($_REQUEST['ttwSubject']);
            $contactMessage = esc_html($_REQUEST['ttwMessage']);

            /**
             * Filter the mail content type.
             **/
            add_filter('wp_mail_content_type', array($this, 'ttw_set_html_mail_content_type'));

            /**
             * Send mail to me : wp-admin TTW Setting contact us form submission
             **/
            $headers[] = 'From: ' . $blogName . ' <' . $blogAdminEmail . '>';
            wp_mail("sunilkumarthz@gmail.com", $contactSubject, $contactMessage, $headers);

            /**
             * Reset content-type to avoid conflicts
             **/
            remove_filter('wp_mail_content_type', array($this, 'ttw_set_html_mail_content_type'));

        endif;
    }

    /**
     * Filter the mail content type Callback function.
     **/
    public function ttw_set_html_mail_content_type()
    {
        return 'text/html';
    }

}

if (is_admin())
    $ttw_settings_page = new TTWManagerSettingPage();

?>