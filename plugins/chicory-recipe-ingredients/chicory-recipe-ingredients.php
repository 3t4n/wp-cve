<?php
/*
Plugin Name: Chicory Recipe Ingredients
Plugin URI: http://chicoryapp.com/
Description: Chicory connects your recipes directly to leading online grocery stores. The Chicory recipe plugin displays a “get ingredients delivered’ button before or after your recipe, letting readers cook the recipes they discover on your site without even having to make a trip to the grocery store.
Version: 2.0.21
Text Domain: chicory-recipe
Author: Chicory
Author URI: http://chicoryapp.com/
*/

define('CHICORY_PLUGIN_VERSION', 		'2.0.21');
define('CHICORY_PLUGIN_URL', 			plugin_dir_url(__FILE__));
define('CHICORY_PLUGIN_PATH',			plugin_dir_path(__FILE__));
define('CHICORY_PLUGIN_BASENAME', 		plugin_basename(__FILE__));
define('CHICORY_PLUGIN_REL_DIR', 		dirname(CHICORY_PLUGIN_BASENAME));
define('CHICORY_WIDGET_DOMAIN',         'www.chicoryapp.com');

/*

define('BUTTON_LOCATION_BELOW_INGREDIENTS', 'below-ingredient');
define('BUTTON_LOCATION_BELOW_RECIPE',      'below-recipe');
define('BUTTON_LOCATION_BELOW_POST',        'below-post');
define('BUTTON_LOCATION_LOWER_RIGHT',       'lower-right');

function chicory_admin_menu() {
    add_menu_page('Chicory Recipe Ingredients', 'Chicory', 'administrator', __FILE__, 'chicory_settings_page', plugins_url('/icon/icon.png', __FILE__), 82);
    add_action('admin_init', 'chicory_register_settings');
}

function chicory_settings_page() {
    $location = get_option('chicory_location_button', BUTTON_LOCATION_BELOW_INGREDIENTS);
    ?>
    <div class="wrap">
        <h2>Chicory Recipe Ingredients</h2>
        <form method="post" action="options.php">
            <?php settings_fields('chicory-settings-group') ?>
            <?php do_settings_sections('chicory-settings-group') ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Where would you like to display the Chicory button?</th>
                </tr>
                <tr>
                    <td>
                        <input type="radio" id="chicory_location_button" name="chicory_location_button"
                               value="<?php echo BUTTON_LOCATION_BELOW_INGREDIENTS ?>"
                            <?php echo ( $location == BUTTON_LOCATION_BELOW_INGREDIENTS ) ? 'checked="checked"' : '' ?> />
                        Below Ingredient List<br/><br/>

                        <input type="radio" id="chicory_location_button" name="chicory_location_button"
                               value="<?php echo BUTTON_LOCATION_BELOW_RECIPE ?>"
                            <?php echo ( $location == BUTTON_LOCATION_BELOW_RECIPE ) ? 'checked="checked"' : '' ?> />
                        Below Recipe<br/><br/>

                        <input type="radio" id="chicory_location_button" name="chicory_location_button"
                               value="<?php echo BUTTON_LOCATION_BELOW_POST ?>"
                            <?php echo ( $location == BUTTON_LOCATION_BELOW_POST ) ? 'checked="checked"' : '' ?> />
                        Bottom of Post<br/><br/>

                        <input type="radio" id="chicory_location_button" name="chicory_location_button"
                               value="<?php echo BUTTON_LOCATION_LOWER_RIGHT ?>"
                            <?php echo ( $location == BUTTON_LOCATION_LOWER_RIGHT ) ? 'checked="checked"' : '' ?> />
                        Lower Right Corner of Recipe<br/><br/>
                    </td>
                </tr>
                </tr>
            </table>
            <?php submit_button() ?>
        </form>
    </div>
<?php
}

function chicory_register_settings() {
    register_setting('chicory-settings-group', 'chicory_location_button');
}
*/

function chicory_scripts() {
    $version = explode('-', phpversion());
    $version = array_shift($version);
    if (is_singular()) {
        wp_enqueue_script('chicory-script',
            '//'. CHICORY_WIDGET_DOMAIN . '/widget_v2/'
            . '?php=' . $version
            . '&plugin=' . CHICORY_PLUGIN_VERSION
            , array(), '', true);
    }
}

function chicory_async_scripts( $tag, $handle, $src ) {
    $async_scripts = array('chicory-script');

    if (in_array($handle, $async_scripts)) {
        return "<script type='text/javascript' src='" . $src . "' async='async'></script>" . "\n";
    }

    return $tag;
}

function chicory_plugin_load_function() {
    /*
        if (is_admin()) {
            add_action('admin_menu', 'chicory_admin_menu');
        }
        else {
            add_filter('script_loader_tag', 'chicory_async_scripts', 10, 3);
            add_action('wp_enqueue_scripts', 'chicory_scripts');
        }
    */

    add_filter('script_loader_tag', 'chicory_async_scripts', 10, 3);
    add_action('wp_enqueue_scripts', 'chicory_scripts');
}

add_action('plugins_loaded','chicory_plugin_load_function');
