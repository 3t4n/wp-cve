<?php

/**
 * Plugin Class
 *
 * This is the basic class for all plugins in the Denra Plugins Framework.
 *
 * @author     Denra.com aka SoftShop Ltd <support@denra.com>
 * @copyright  2019 Denra.com aka SoftShop Ltd
 * @license    GPLv2 or later
 * @version    1.1.1
 * @link       https://www.denra.com/
 */

namespace Denra\Plugins;

class Plugin extends BasicExtra {
    
    public $file; // the plugin file from __FILE__
    
    public $data; // plugin data from get_plugin_data()
    
    public $admin_title_menu; // the admin title for the WP admin menu

    public $settings = [];
    public $settings_id_u;
    public $settings_form;
    public $settings_default = [
        'delete_plugin_settings_on_uninstall' => 0
    ];
    
    public function __construct($id, $data = []) {
        
        // Check if file data is provided
        (isset($data['file']) && $data['file']) || die('<p>Plugin file info needed for '.get_class($this).'.</p>');
        
        $this->file = $data['file'];
        
        $data['dir'] .= 'plugin/';
        $data['url'] .= 'plugin/';
                
        // Call the parent constructor
        parent::__construct($id, $data);
        
        if(!function_exists('get_plugin_data')) {
            require_once( \ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $this->data = \get_plugin_data($this->file);
        
        // Load or create settings for the plugin
        $this->settings = $this->settings_default;
        if (count($this->settings_default) > 1) { // if plugin added more settings
            $this->settings_id_u = $this->id_u . '_settings';
            $this->settings = \maybe_unserialize(\get_option($this->settings_id_u));
            if (!$this->settings || !is_array($this->settings) || count($this->settings) <= 1) {
                Framework::sortArrayKeysRecursively($this->settings_default);
                $this->settings = $this->settings_default;
            }
        }
        
                
        if (\current_user_can('manage_options')) {
            if ($this->admin_title_menu) {
                \add_action('admin_menu', [&$this, 'settingsSubMenu'], 2);
                \add_filter('plugin_action_links_'.\plugin_basename($this->file), [&$this, 'settingsLink']);
            }
            \add_filter('plugin_row_meta', [&$this, 'pluginRowMeta'], 10, 2);
        }
        
    }
    
    public function settingsSubMenu() {
        
        global $denra_plugins;
        
        \add_submenu_page($denra_plugins['framework']->id,  $this->data['Name'] . ' ' . $this->data['Version'], $this->admin_title_menu, 'manage_options', $this->id, [&$this, 'adminPage']);
        
    }
    
    function settingsLink($links) {
        
        $links[] = '<a href="' . \admin_url('admin.php?page=' . $this->id) . '">' . \__('Settings', 'denra-plugins') . '</a>';
        return $links;
        
    }
    
    public function pluginRowMeta($links, $file) {
        
        global $denra_plugins;
        
        if ($this->plugin_basename === $file) {
            $links = array_merge($links, [
                'support' => '<a href="mailto:' . $denra_plugins['framework']->email_support . '">' . \__('E-mail Support', 'denra-plugins') . '</a>',
                'website' => '<a href="' . $denra_plugins['framework']->url_website . '" target="_blank">' . \__('Website', 'denra-plugins') . '</a>',
                'donate' => '<a href="' . $denra_plugins['framework']->url_donation . '" target="_blank">' . \__('Donate!', 'denra-plugins') . '</a>'
            ]);
        }
        return $links;
        
    }
    
    public function adminPage() {
        
        echo '<div class="denra-plugins">';
        
        $submit = filter_input(INPUT_POST, 'submit');
        if ($submit && \check_admin_referer('update_settings_'. $this->settings_id_u)) {
            $this->adminSettingsProcessing();
            $this->adminSettingsSave();
        }
        
        $this->adminSettingsHeader();
        $this->adminSettingsContent();
        $this->adminSettingsFooter();
        
        echo '</div>';
        
    }
    
    public function adminSettingsHeader() {
        
        echo '<div class="denra-plugins-header">';
        echo '<h1>' . \__($this->data['Name'], $this->text_domain) . ' ' . $this->data['Version'] . '</h1>';
        echo '</div>';
        
        echo '<div class="denra-plugins-content">';
        
        \do_action('denra_plugins_admin_settings_header');
        
        if (is_array($this->settings) && count($this->settings) > 1) {
            echo '<h2>' . \__('Settings', 'denra-plugins') . '</h2>';
            echo '<form action="?page=' . $this->id . '" method="post" id="denra-plugins-form-settings">';
        }
        
        \do_action('denra_plugins_admin_settings_form_top');
        
    }
    
    public function adminSettingsContent() {

        \do_action('denra_plugins_admin_settings_content');
        
    }
    
    public function adminSettingsFooter() {
        
        global $denra_plugins;
        
        \do_action('denra_plugins_admin_settings_form_bottom');
        
        if (is_array($this->settings) && count($this->settings) > 1) {
            $del_set_id = 'delete_plugin_settings_on_uninstall';
            echo '<fieldset>';
            echo '<legend>'  . \__('Uninstall Settings', 'denra-plugins') . '</legend>';
            \wp_nonce_field('update_settings_'. $this->settings_id_u);
            echo '<label for="' . $del_set_id . '"><input id="' . $del_set_id . '" name="' . $del_set_id . '" type="checkbox" value="1"'. ($this->settings['delete_plugin_settings_on_uninstall'] ? 'checked' : '') .' /> ' . \__('Delete all plugin settings on uninstall.', 'denra-plugins') . '</label>';
            echo '<input id="submit" class="button button-primary" name="submit" type="submit" value="' . \__('Save settings', 'denra-plugins') . '">';
            echo '</form>';
        }
        
        echo '</div>';
        
        echo '<div class="denra-plugins-footer">';
        
        \do_action('denra_plugins_admin_settings_footer_top');
        
        echo '<p><hr></p><h2>' . \__('Plugin Information', 'denra-plugins') . '</h2>';
        echo '<p>' . \__('Learn more about this plugin on it\'s page at WordPress.org:', 'denra-plugins') . '<br><a href="' . $this->data['PluginURI'] . '" target="_blank">' . $this->data['PluginURI'] . '</a></p>';
        echo '<p>' . \__('Get free support by e-mail:', 'denra-plugins') . '<br><a href="mailto:' . $denra_plugins['framework']->email_support . '">' . $denra_plugins['framework']->email_support . '</a></p>';
        echo '<p><a href="' . $denra_plugins['framework']->url_donation . '" target="_blank">' . \__('Please donate', 'denra-plugins') . '</a>' . \__(' if you like this plugin and it is helpful to you.', 'denra-plugins') . '</p>';
        
        \do_action('denra_plugins_admin_settings_footer_bottom');
        
        echo '</div>';
        
    }
    
    public function adminSettingsProcessing() {
        
        \do_action('denra_plugins_admin_settings_processing');
        
        $this->settings['delete_plugin_settings_on_uninstall'] = filter_input(INPUT_POST, 'delete_plugin_settings_on_uninstall', FILTER_SANITIZE_NUMBER_INT);
        
    }
    
    public function adminSettingsSave() {
        
        \do_action('denra_plugins_admin_settings_save');
        
        \update_option($this->settings_id_u, $this->settings, FALSE);
        echo '<p class="form-saved">' . \__('The settings were saved.', 'denra-plugins') . '</p>';
        
    }
    
}
