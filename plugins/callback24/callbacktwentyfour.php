<?php
/**
 * Plugin Name: Callback24
 * Description: Callback24 – oddzwoń do klienta w ciągu 15 sekund!
 * Version: 1.0.7
 * Author: itDesk
 * Author URI: https://agencja-interaktywna.opole.pl
 * License: GPL
 */
if (!defined("ABSPATH")) {
    exit;
}

class CallbacktwentyfourPlugin {

    private $options;

    public function __construct() {
        $this->options = get_option('callbacktwentyfour_options');
    }

    public function load() {
        add_action('admin_menu', [$this, 'add_callback_plugin_options_page']);
        add_action('admin_init', [$this, 'add_callback_plugin_settings']);
    }

    public function add_callback_plugin_options_page() {
        add_options_page(
                'Callback24 settings', 'Callback24 settings', 'manage_options', 'callbacktwentyfour', [$this, 'render_callback_admin_page']
        );
    }

    public function render_callback_admin_page() {
        //var_dump($_SERVER['HTTP_HOST']); exit;
        ?>
        <div class="wrap">
            <h1>Callback24</h1>
            <iframe src="https://panel.callback24.io/users/pluginLogin/ /<?=$_SERVER['HTTP_HOST']?>/WORDPRESS" style="width:100%; min-height: 300px; height:80vh; border: none; background: white; border-radius: 10px"></iframe>
        </div>

        <?php
    }

    public function add_callback_plugin_settings() {
        register_setting('callbacktwentyfour', 'callbacktwentyfour_options', [$this, 'callbacktwentyfour_options_callback']);

        add_settings_section(
                'callbacktwentyfour_settings', 'Settings', [$this, 'callback_instructions'], 'callbacktwentyfour'
        );

        add_settings_field(
                'callbacktwentyfour_options', 'Code', [$this, 'callback_fields'], 'callbacktwentyfour', 'callbacktwentyfour_settings'
        );
    }

    public function callback_instructions() {
        print 'Please copy and paste Callback24 script';
    }

    public function callback_fields() {
        printf(
                '<textarea cols="50" rows="15" id="key" name="callbacktwentyfour_options[key]" >%s</textarea>', isset($this->options['key']) ? esc_attr($this->options['key']) : ''
        );
    }

    public function returnLicense() {

            return " <script>
            page_host = window.location.host;
            if (page_host.substring(0, 4) == 'www.'){
                page_host = page_host.substring(4, page_host.length);
            }
            var script=document.createElement('script');
            script.async=true;
            script.src='https://panel.callback24.io/js/callbackWidget.js?name=wordpress_'+page_host;
            script.type='text/javascript';
            document.body.appendChild(script);
        </script>";
    }

}

if (is_admin()) {
    $plugin = new CallbacktwentyfourPlugin();
    $plugin->load();
}

function callback_head_script() {
    $plugin = new CallbacktwentyfourPlugin();
    if ($plugin->returnLicense()) {
        echo $plugin->returnLicense();
    }
}

add_action('wp_footer', 'callback_head_script');

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_callback_action_links');

function add_callback_action_links($links) {
    $mylinks = array(
        '<a style="font-weight:bold;color:red;font-size:18px;" href="' . admin_url('options-general.php?page=callbacktwentyfour') . '">Settings</a>',
    );
    return array_merge($links, $mylinks);
}
