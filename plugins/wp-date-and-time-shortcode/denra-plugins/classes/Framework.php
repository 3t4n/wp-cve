<?php

/**
 * Framework
 *
 * The Denra Plugins Framework class.
 *
 * @author     Denra.com aka SoftShop Ltd <support@denra.com>
 * @copyright  2019-2020 Denra.com aka SoftShop Ltd
 * @license    GPLv2 or later
 * @version    1.3.7
 * @link       https://www.denra.com/
 */

namespace Denra\Plugins;

/**
 * Description of Framework
 *
 * @author Ivaylo Tinchev
 */
class Framework extends BasicExtra {
    
    // The version here and above in the comments MUST MATCH !!!
    public static $version = '1.3.7';
    
    public $url_website = 'https://denra.com/';
    public $url_support_page = 'https://denra.com/';
    public $url_donation = 'https://www.paypal.me/itinchev';
    public $email_support = 'support@denra.com';
    
    const LOGO_SIGN_BASE64_ENCODED = 
            'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPgo8IS0tR2VuZXJhdG9yOiBYYXJhIERlc2lnbmVyICh3d3cueGFyYS5jb20pLCBTVkcgZmlsdGVyIHZlcnNpb246IDYuMC4wLjQtLT4KPHN2ZyBzdHJva2Utd2lkdGg9IjAuNTAxIiBzdHJva2UtbGluZWpvaW49ImJldmVsIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgb3ZlcmZsb3c9InZpc2libGUiIHdpZHRoPSIxNDEuNzMzcHQiIGhlaWdodD0iMTQxLjczM3B0IiB2aWV3Qm94PSIwIDAgMTQxLjczMyAxNDEuNzMzIj4KIDxkZWZzPgoJPC9kZWZzPgogPGcgaWQ9IkRvY3VtZW50IiBmaWxsPSJub25lIiBzdHJva2U9ImJsYWNrIiBmb250LWZhbWlseT0iVGltZXMgTmV3IFJvbWFuIiBmb250LXNpemU9IjE2IiB0cmFuc2Zvcm09InNjYWxlKDEgLTEpIj4KICA8ZyBpZD0iU3ByZWFkIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIC0xNDEuNzMzKSI+CiAgIDxnIGlkPSJMYXllciAxIj4KICAgIDxnIGlkPSJHcm91cCIgc3Ryb2tlLWxpbmVqb2luPSJtaXRlciIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjAuNDgiIHN0cm9rZS1taXRlcmxpbWl0PSI3OS44NDAzMTkzNjEyNzc1Ij4KICAgICA8cGF0aCBkPSJNIDIuMjYyLDY1LjQwNCBMIDEwLjY1Miw1Ny4wMTMgQyAxMy42NjgsNTMuOTk3IDE4LjU2Niw1My45OTcgMjEuNTgyLDU3LjAxMyBMIDMzLjQ0LDY4Ljg3MSBDIDM2LjQ1Niw3MS44ODcgMzYuNDU2LDc2Ljc4NSAzMy40NCw3OS44MDEgTCAyNS4wNDksODguMTkyIEMgMjIuMDMzLDkxLjIwOCAxNy4xMzUsOTEuMjA4IDE0LjExOSw4OC4xOTIgTCAyLjI2Miw3Ni4zMzQgQyAtMC43NTQsNzMuMzE4IC0wLjc1NCw2OC40MiAyLjI2Miw2NS40MDQgWiIgZmlsbD0iIzAwOTFkMSIgbWFya2VyLXN0YXJ0PSJub25lIiBtYXJrZXItZW5kPSJub25lIi8+CiAgICAgPHBhdGggZD0iTSAyNy44OTgsOTEuMDQxIEwgMzYuMjkxLDgyLjY0OCBDIDM5LjMwNyw3OS42MzIgNDQuMjAzLDc5LjYzMiA0Ny4yMTksODIuNjQ5IEwgNTkuMDgsOTQuNTExIEMgNjIuMDk2LDk3LjUyOCA2Mi4wOTYsMTAyLjQyNCA1OS4wOCwxMDUuNDQgTCA1MC42ODYsMTEzLjgzMyBDIDQ3LjY3LDExNi44NDkgNDIuNzczLDExNi44NDkgMzkuNzU4LDExMy44MzIgTCAyNy44OTgsMTAxLjk3IEMgMjQuODgyLDk4Ljk1MyAyNC44ODIsOTQuMDU2IDI3Ljg5OCw5MS4wNDEgWiIgZmlsbD0iIzAwOTFkMSIgbWFya2VyLXN0YXJ0PSJub25lIiBtYXJrZXItZW5kPSJub25lIi8+CiAgICAgPHBhdGggZD0iTSA1My41MzcsMTE2LjY4MiBMIDYxLjkzMiwxMDguMjg4IEMgNjQuOTQ5LDEwNS4yNzIgNjkuODQ2LDEwNS4yNzIgNzIuODYyLDEwOC4yODkgTCA4NC43MjEsMTIwLjE0OCBDIDg3LjczOCwxMjMuMTY0IDg3LjczOCwxMjguMDYxIDg0LjcyMSwxMzEuMDc3IEwgNzYuMzI1LDEzOS40NzEgQyA3My4zMDgsMTQyLjQ4NyA2OC40MTEsMTQyLjQ4NyA2NS4zOTUsMTM5LjQ3IEwgNTMuNTM3LDEyNy42MTEgQyA1MC41MiwxMjQuNTk0IDUwLjUyLDExOS42OTggNTMuNTM3LDExNi42ODIgWiIgZmlsbD0iIzAwOTFkMSIgbWFya2VyLXN0YXJ0PSJub25lIiBtYXJrZXItZW5kPSJub25lIi8+CiAgICAgPHBhdGggZD0iTSAyMi45NzksNDQuNjg0IEwgMjguNTczLDM5LjA5IEMgMzAuNTgzLDM3LjA4IDMzLjg0OSwzNy4wOCAzNS44NTksMzkuMDkgTCA1MS4zNjUsNTQuNTk1IEMgNTMuMzc1LDU2LjYwNSA1My4zNzUsNTkuODY5IDUxLjM2NSw2MS44NzkgTCA0NS43Nyw2Ny40NzMgQyA0My43Niw2OS40ODMgNDAuNDk1LDY5LjQ4MyAzOC40ODUsNjcuNDczIEwgMjIuOTc4LDUxLjk2OCBDIDIwLjk2OCw0OS45NTggMjAuOTY5LDQ2LjY5NCAyMi45NzksNDQuNjg0IFoiIGZpbGw9IiMwMDRlNzgiIG1hcmtlci1zdGFydD0ibm9uZSIgbWFya2VyLWVuZD0ibm9uZSIvPgogICAgIDxwYXRoIGQ9Ik0gNDguNjE5LDcwLjMyMiBMIDU0LjIxMiw2NC43MjggQyA1Ni4yMjIsNjIuNzE4IDU5LjQ4Niw2Mi43MTcgNjEuNDk2LDY0LjcyNyBMIDc3LjAwMSw4MC4yMzIgQyA3OS4wMTEsODIuMjQyIDc5LjAxMSw4NS41MDcgNzcuMDAxLDg3LjUxNyBMIDcxLjQwNyw5My4xMTEgQyA2OS4zOTcsOTUuMTIxIDY2LjEzMyw5NS4xMjIgNjQuMTIzLDkzLjExMiBMIDQ4LjYxOSw3Ny42MDcgQyA0Ni42MDksNzUuNTk3IDQ2LjYwOSw3Mi4zMzIgNDguNjE5LDcwLjMyMiBaIiBmaWxsPSIjMDA5MWQxIiBtYXJrZXItc3RhcnQ9Im5vbmUiIG1hcmtlci1lbmQ9Im5vbmUiLz4KICAgICA8cGF0aCBkPSJNIDY0LjcyNCw1NC4yMTUgTCA3MC4zMTksNDguNjIgQyA3Mi4zMjksNDYuNjEgNzUuNTkzLDQ2LjYxIDc3LjYwMyw0OC42MiBMIDkzLjEwOCw2NC4xMjYgQyA5NS4xMTgsNjYuMTM2IDk1LjExNyw2OS40MDIgOTMuMTA3LDcxLjQxMiBMIDg3LjUxMyw3Ny4wMDcgQyA4NS41MDMsNzkuMDE3IDgyLjIzOCw3OS4wMTcgODAuMjI4LDc3LjAwNyBMIDY0LjcyNCw2MS41MDEgQyA2Mi43MTQsNTkuNDkxIDYyLjcxNCw1Ni4yMjUgNjQuNzI0LDU0LjIxNSBaIiBmaWxsPSIjMDA0ZTc4IiBtYXJrZXItc3RhcnQ9Im5vbmUiIG1hcmtlci1lbmQ9Im5vbmUiLz4KICAgICA8cGF0aCBkPSJNIDkwLjM2Myw3OS44NTYgTCA5NS45NTYsNzQuMjYyIEMgOTcuOTY2LDcyLjI1MiAxMDEuMjMyLDcyLjI1MiAxMDMuMjQyLDc0LjI2MiBMIDExOC43NDUsODkuNzY1IEMgMTIwLjc1NSw5MS43NzUgMTIwLjc1NSw5NS4wNDEgMTE4Ljc0NSw5Ny4wNTEgTCAxMTMuMTUxLDEwMi42NDUgQyAxMTEuMTQxLDEwNC42NTUgMTA3Ljg3NSwxMDQuNjU1IDEwNS44NjUsMTAyLjY0NSBMIDkwLjM2Myw4Ny4xNDIgQyA4OC4zNTMsODUuMTMyIDg4LjM1Myw4MS44NjYgOTAuMzYzLDc5Ljg1NiBaIiBmaWxsPSIjMDA5MWQxIiBtYXJrZXItc3RhcnQ9Im5vbmUiIG1hcmtlci1lbmQ9Im5vbmUiLz4KICAgICA8cGF0aCBkPSJNIDU3LjAwNSwxMC42NTUgTCA2NS4zOTcsMi4yNjIgQyA2OC40MTIsLTAuNzU0IDczLjMwOSwtMC43NTQgNzYuMzI1LDIuMjYyIEwgODguMTg5LDE0LjEyNiBDIDkxLjIwNSwxNy4xNDIgOTEuMjA1LDIyLjAzOCA4OC4xODksMjUuMDU0IEwgNzkuNzk2LDMzLjQ0NyBDIDc2Ljc4LDM2LjQ2MyA3MS44ODQsMzYuNDYzIDY4Ljg2OCwzMy40NDcgTCA1Ny4wMDUsMjEuNTgzIEMgNTMuOTg5LDE4LjU2NyA1My45ODksMTMuNjcgNTcuMDA1LDEwLjY1NSBaIiBmaWxsPSIjMDA0ZTc4IiBtYXJrZXItc3RhcnQ9Im5vbmUiIG1hcmtlci1lbmQ9Im5vbmUiLz4KICAgICA8cGF0aCBkPSJNIDgyLjY0NiwzNi4yOTMgTCA5MS4wMzksMjcuOSBDIDk0LjA1NSwyNC44ODQgOTguOTUxLDI0Ljg4NCAxMDEuOTY3LDI3LjkwMSBMIDExMy44MjgsMzkuNzYzIEMgMTE2Ljg0NCw0Mi43OCAxMTYuODQ0LDQ3LjY3NiAxMTMuODI4LDUwLjY5MiBMIDEwNS40MzQsNTkuMDg1IEMgMTAyLjQxOCw2Mi4xMDEgOTcuNTIxLDYyLjEwMSA5NC41MDYsNTkuMDg0IEwgODIuNjQ2LDQ3LjIyMiBDIDc5LjYzLDQ0LjIwNSA3OS42MywzOS4zMDggODIuNjQ2LDM2LjI5MyBaIiBmaWxsPSIjMDA0ZTc4IiBtYXJrZXItc3RhcnQ9Im5vbmUiIG1hcmtlci1lbmQ9Im5vbmUiLz4KICAgICA8cGF0aCBkPSJNIDEwOC4yODMsNjEuOTM0IEwgMTE2LjY3NSw1My41NDIgQyAxMTkuNjksNTAuNTI1IDEyNC41ODcsNTAuNTI1IDEyNy42MDQsNTMuNTQxIEwgMTM5LjQ2Niw2NS40MDIgQyAxNDIuNDgzLDY4LjQxNyAxNDIuNDgzLDczLjMxNCAxMzkuNDY3LDc2LjMzIEwgMTMxLjA3NCw4NC43MjQgQyAxMjguMDU4LDg3Ljc0IDEyMy4xNjIsODcuNzQgMTIwLjE0NSw4NC43MjQgTCAxMDguMjg0LDcyLjg2MyBDIDEwNS4yNjcsNjkuODQ3IDEwNS4yNjcsNjQuOTUxIDEwOC4yODMsNjEuOTM0IFoiIGZpbGw9IiMwMDRlNzgiIG1hcmtlci1zdGFydD0ibm9uZSIgbWFya2VyLWVuZD0ibm9uZSIvPgogICAgIDxwYXRoIGQ9Ik0gNzQuMjU1LDk1Ljk2IEwgNzkuODQ5LDkwLjM2NiBDIDgxLjg1OSw4OC4zNTYgODUuMTI1LDg4LjM1NiA4Ny4xMzUsOTAuMzY2IEwgMTAyLjY0MSwxMDUuODcxIEMgMTA0LjY1MSwxMDcuODgxIDEwNC42NTEsMTExLjE0NSAxMDIuNjQxLDExMy4xNTUgTCA5Ny4wNDYsMTE4Ljc0OSBDIDk1LjAzNiwxMjAuNzU5IDkxLjc3MSwxMjAuNzU5IDg5Ljc2MSwxMTguNzQ5IEwgNzQuMjU0LDEwMy4yNDQgQyA3Mi4yNDQsMTAxLjIzNCA3Mi4yNDUsOTcuOTcgNzQuMjU1LDk1Ljk2IFoiIGZpbGw9IiMwMDkxZDEiIG1hcmtlci1zdGFydD0ibm9uZSIgbWFya2VyLWVuZD0ibm9uZSIvPgogICAgIDxwYXRoIGQ9Ik0gMzkuMDgzLDI4LjU3NyBMIDQ0LjY3OCwyMi45ODIgQyA0Ni42ODgsMjAuOTcyIDQ5Ljk1MiwyMC45NzIgNTEuOTYyLDIyLjk4MiBMIDY3LjQ2NywzOC40ODggQyA2OS40NzcsNDAuNDk4IDY5LjQ3Niw0My43NjQgNjcuNDY2LDQ1Ljc3NCBMIDYxLjg3Miw1MS4zNjkgQyA1OS44NjIsNTMuMzc5IDU2LjU5Nyw1My4zNzkgNTQuNTg3LDUxLjM2OSBMIDM5LjA4MywzNS44NjMgQyAzNy4wNzMsMzMuODUzIDM3LjA3MywzMC41ODcgMzkuMDgzLDI4LjU3NyBaIiBmaWxsPSIjMDA0ZTc4IiBtYXJrZXItc3RhcnQ9Im5vbmUiIG1hcmtlci1lbmQ9Im5vbmUiLz4KICAgIDwvZz4KICAgPC9nPgogIDwvZz4KIDwvZz4KPC9zdmc+';
    
    public function __construct($id, $data = []) {
        
        global $denra_plugins;
        
        (isset($data['framework_plugin_id']) && $data['framework_plugin_id']) || die('<p>Framework plugin ID needed for '.get_class($this).'.</p>');
        
        // Set text_domain for the framework
        $this->text_domain = $id;
        
        // Set framework dir and url
        $plugin_data = $denra_plugins['data'][$data['framework_plugin_id']];
        $data['file'] = $plugin_data['file'];
        $data['dir'] = $plugin_data['dir'] . $id . '/';
        $data['url'] = $plugin_data['url'] . $id . '/';
        unset($plugin_data);
        
        parent::__construct($id, $data);
        
        // Load all plugins
        foreach($denra_plugins['data'] as $plugin_id => $plugin_data) {
            
            // Create the plugin object
            $dir_plugin_classes = $plugin_data['dir'] . 'plugin/classes/';
            require_once  $dir_plugin_classes . $plugin_data['class'] . '.php';
            $full_class_plugin = '\Denra\Plugins\\' . $plugin_data['class'];
            $denra_plugins['data'][$plugin_id]['object'] = new $full_class_plugin ($plugin_id, $plugin_data);
            
            // Create reference for faster use
            $plugin_obj = &$denra_plugins['data'][$plugin_id]['object'];
            
            // Do some housecleaning immediately after activation
            // e.f. fix plugin settings to comply with the ones in
            // the new versions if the plugin has been just activated
            $just_activated_id_u = $plugin_obj->id_u . '_just_activated';
            $just_activated = \get_option($just_activated_id_u);
            if (intval($just_activated)) {
                // The real housekeeping
                self::deleteOldSettingsData($plugin_obj->settings, $plugin_obj->settings_default);
                self::addNewSettingsData($plugin_obj->settings, $plugin_obj->settings_default);
                self::sortArrayKeysRecursively($plugin_obj->settings);
                \update_option($plugin_obj->settings_id_u, $plugin_obj->settings, FALSE);
                \update_option($just_activated_id_u, 0, FALSE);
            }
            
            // Load all text domains in case of Framework admin Home page
            if (filter_input(INPUT_GET, 'page') == $this->id && $plugin_obj->text_domain && $plugin_id != $data['framework_plugin_id']) {
                if ($plugin_obj->text_domain) {
                    $mofile = $plugin_obj->dir . 'i18n/' .  $plugin_obj->text_domain . '-' . \get_locale() . '.mo';
                    if (file_exists($mofile)) {
                        \load_textdomain($plugin_obj->text_domain, $mofile);
                    }
                }
            }
        }
        
        // Add the admin menus for the Framework
        if (\current_user_can('manage_options')) {
            \add_action('admin_menu', [&$this, 'addAdminMenus'], 1);
        }
        
    }
    
    // Delete all keys that are missing in the new plugin version
    public static function deleteOldSettingsData(&$settings, &$settings_default) {
        
        if (is_array($settings) && is_array($settings_default)) {
            foreach (array_keys($settings) as $key) {
                if (!isset($settings_default[$key])) {
                    unset($settings[$key]); // delete settings key if missing
                }
                elseif (is_array($settings[$key])) { // check subkeys recursively
                    self::deleteOldSettingsData($settings[$key], $settings_default[$key]);
                }
            }
        }
    }
    
    // Add all keys that are newly created in the new plugin version
    public static function addNewSettingsData(&$settings, &$settings_default) {
        
        if (is_array($settings) && is_array($settings_default)) {
            foreach (array_keys($settings_default) as $key) {
                if (isset($settings[$key])) {
                    if (is_array($settings[$key]) && is_array($settings_default[$key])) {
                        self::addNewSettingsData($settings[$key], $settings_default[$key]);
                        continue;                    
                    }
                }
                else {
                    $settings[$key] = $settings_default[$key];
                }
            }
        }
    }
    
    public function addAdminMenus() {
        
        \add_menu_page(
            'Denra Plugins',
            'Denra Plugins',
            'manage_options',
            $this->id,
            [&$this, 'settings'],
            $this::LOGO_SIGN_BASE64_ENCODED,
            NULL
        );
        \add_submenu_page(
            $this->id,
            \__('Home', 'denra-plugins'),
            \__('Home', 'denra-plugins'),
            'manage_options',
            $this->id);
        
    }
    
    public function settings() {
        
        global $denra_plugins;
        
        echo '<div class="denra-plugins">';
        
        echo '<h1>'.\__('Denra Plugins', 'denra-plugins') . ' ' . self::$version . '</h1><hr>';
        echo '<h2>' . \__('Installed and active plugins', 'denra-plugins') . '</h2>';

        foreach ($denra_plugins['data'] as $plugin_id => $plugin_data) {
            echo '<h3>' . $plugin_data['object']->data['Name'] . ' ' . $plugin_data['object']->data['Version'] . '</h3>';
            echo preg_replace('/(\<cite\>)(.*)(\<\/cite\>)/i', '$1$3', $plugin_data['object']->data['Description']) . '<br>[ <a href="admin.php?page=' . $plugin_id . '">' . \__('Settings', 'denra-plugins') . '</a> ]';
        }
        
        echo '<p><hr></p><h2>' . \__('Contact us', 'denra-plugins') . '</h2>';
        echo '<p>' . \__('E-mail support:', 'denra-plugins') . ' <a href="mailto:' . $denra_plugins['framework']->email_support . '">' . $denra_plugins['framework']->email_support . '</a>';
        echo '<br>' . \__('Website:', 'denra-plugins') . ' <a href="' . $denra_plugins['framework']->url_website . '" target="_blank">' . $denra_plugins['framework']->url_website . '</a></p>';
        
        echo '<p><hr></p><h2>' . \__('Donations', 'denra-plugins') . '</h2>';
        echo '<p><a href="' . $denra_plugins['framework']->url_donation . '" target="_blank">' . \__('Please donate', 'denra-plugins') . '</a>' . \__(' if you like our plugins and they are helpful to you.', 'denra-plugins') . '</p>';
        
        echo '</div>';
        
    }
    
    public static function sortArrayKeysRecursively (&$a) {
        if (is_array($a)) {
            ksort($a);
            foreach (array_keys($a) as $k) {
               self::sortArrayKeysRecursively($a[$k]);
            }
        }
    }

}
