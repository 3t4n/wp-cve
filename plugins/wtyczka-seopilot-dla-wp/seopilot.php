<?php
/**
 * @package SeoPilot
 * @Plugin Name: SeoPilot
 * @Plugin URI: https://www.seopilot.pl/
 * @Description: [&copy; SeoPilot.pl] - code installation for WordPress
 * @Author: seopilot
 * @Contact: support@seopilot.pl
 * @Version: 3.3.091
 */
require_once(plugin_dir_path(__FILE__) . 'inc/widgets.php');

class SeoPilot
{
    static $translationNamespace = 'seopilot';
    static $client;

    function __construct()
    {
        if (!defined('SEOPILOT_USER')) {
            define('SEOPILOT_USER', get_option('SEOPILOT_USER'));
        }

        self::$client = new SeoPilotClient([
            'is_test' => get_option('SEOPILOT_TEST') == 1 ? true : false,
            'charset' => get_option('SEOPILOT_CHARSET'),
            'allow_ssl' => get_option('SEOPILOT_ALLOW_SSL') == 'yes' ? 'yes' : 'no'
        ]);

        load_plugin_textdomain(SeoPilot::$translationNamespace, false, basename(dirname(__FILE__)) . '/languages');


        add_action('admin_menu', [&$this, 'SeoPilot_Admin_Menu']);

        add_action('wp_head', [__CLASS__, 'addStyleToHead']);

        if (function_exists('register_widget')) {
            add_action('widgets_init', [__CLASS__, 'widget_reg']);
        }

        if (function_exists('add_shortcode')) {
            add_shortcode('seopilot_build_links', [__CLASS__, 'getLinks']);
            add_shortcode('seopilot_build_links_is', [__CLASS__, 'getLinksIs']);
        }
    }

    static function widget_reg()
    {
        return register_widget("SeoPilot_Widget");
    }

    static function getLinksIs($atts, $content = null)
    {
        if (self::$client->getCountLinks() > 0) {
            return str_replace('%links%', self::getLinks($atts), $content);
        } else {
            return self::$client->build_links();
        }
    }

    static function getLinks($atts, $content = null)
    {
        $count = false;
        if (isset($atts['count'])) {
            $count = intval($atts['count']);
            if ($count <= 0) {
                $count = False;
            }
        }
        $orientation = null;
        if (isset($atts['orientation']) && in_array($atts['orientation'], ['v', 'h', 's'])) {
            $orientation = $atts['orientation'];
        }
        return self::$client->build_links($count, $orientation);
    }

    static function addStyleToHead()
    {
        $result = self::$client->getStyle(false, 'v') . "\n" . self::$client->getStyle(false, 'h');
        echo '<style type="text/css">' . preg_replace("%\s+%smi", ' ', $result) . '</style>';
    }

    function SeoPilot_Admin_Menu()
    {
        add_menu_page('SeoPilot', 'SeoPilot', 'manage_options', 'seopilot-admin-menu', [__CLASS__, 'SeoPilot_Admin_Options']);
    }

    static function SeoPilot_Admin_Options()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $options = [];
        $SEOPILOT_USER = 'SEOPILOT_USER';
        $SEOPILOT_CHARSET = 'SEOPILOT_CHARSET';
        $SEOPILOT_TEST = 'SEOPILOT_TEST';
        $SEOPILOT_ALLOW_SSL = 'SEOPILOT_ALLOW_SSL';

        $options[$SEOPILOT_USER] = get_option($SEOPILOT_USER);
        $options[$SEOPILOT_CHARSET] = get_option($SEOPILOT_CHARSET);
        $options[$SEOPILOT_TEST] = get_option($SEOPILOT_TEST);
        $options[$SEOPILOT_ALLOW_SSL] = get_option($SEOPILOT_ALLOW_SSL);

        if (isset($_POST['SaveSeoPilotSettings'])) {

            $options[$SEOPILOT_USER] = $_POST[$SEOPILOT_USER];
            $options[$SEOPILOT_CHARSET] = $_POST[$SEOPILOT_CHARSET];
            $options[$SEOPILOT_TEST] = isset($_POST[$SEOPILOT_TEST]) ? 1 : 0;
            $options[$SEOPILOT_ALLOW_SSL] = isset($_POST[$SEOPILOT_ALLOW_SSL]) ? 'yes' : 'no';

            foreach ($options as $opt_name => $opt_val) {
                update_option($opt_name, $opt_val);
            }


        }
        if (isset($_POST['ClearSeoPilotCathe'])) {
            if (file_exists(self::$client->sp_links_db_file))
                @unlink(self::$client->sp_links_db_file);
        }

        if (isset($_POST['SaveSeoPilotSettings']) || isset($_POST['ClearSeoPilotCathe'])) {
            echo '<div class="updated"><p><strong>' . __('Zmiany zostały zapisane.', SeoPilot::$translationNamespace) . '</strong></p></div>';
        }

        echo '<div class="wrap">';
        echo "<h2>" . __('Ustawienia dla SeoPilot.pl', SeoPilot::$translationNamespace) . "</h2>";
        echo '
			<form name="SeoPilotAdminForm" method="post" action="">
			<div class="postbox">
				<div class="inside">
					<p><strong style="width: 200px;float:left; margin-top:5px;">' . __("Twój identyfikator SeoPilot:", SeoPilot::$translationNamespace) . '</strong> <input type="text" name="' . $SEOPILOT_USER . '" value="' . $options[$SEOPILOT_USER] . '" size="36" maxlength="32"/></p>
					<p><strong style="width: 200px;float:left; margin-top:5px;">' . __("Kodowanie:", SeoPilot::$translationNamespace) . '</strong> <input type="text" name="' . $SEOPILOT_CHARSET . '" value="' . ($options[$SEOPILOT_CHARSET] == '' ? get_bloginfo('charset') : $options[$SEOPILOT_CHARSET]) . '" size="36"/></p>
					<p><strong style="width: 200px;float:left;  margin-top:-2px;">' . __("Tryb testowy:", SeoPilot::$translationNamespace) . '</strong> <input type="checkbox" name="' . $SEOPILOT_TEST . '" value="1" ' . ($options[$SEOPILOT_TEST] == 1 ? 'checked' : '') . '/></p>
					<p><strong style="width: 200px;float:left;  margin-top:-2px;">' . __("Allow use HTTPS:", SeoPilot::$translationNamespace) . '</strong> <input type="checkbox" name="' . $SEOPILOT_ALLOW_SSL . '" value="1" ' . ($options[$SEOPILOT_ALLOW_SSL] == 'yes' ? 'checked' : '') . '/></p>
					<p>
						<input type="submit" name="SaveSeoPilotSettings" class="button-primary" value="' . esc_attr__('Save Changes') . '" />
						&nbsp;<input type="submit" name="ClearSeoPilotCathe" class="button-primary" value="' . esc_attr__('Clear cache') . '" />
					</p>
				</div>
			</div>
			</form>';

        echo '</div>';
    }
}

$seopilot = new SeoPilot();


