<?php
/*
Plugin Name: Double the Donation
Plugin URI: https://doublethedonation.com/
Description: Matching gifts plugin for nonprofits, powered by Double the Donation
Author: Double the Donation
Version: 1.1.2
Requires at least: 3.0
Requires PHP: 5.6.20
Author URI: https://doublethedonation.com/about-us/
*/

global $wp_version;

require_once(ABSPATH . "wp-admin/includes/plugin.php");

function doublethedonation_plugin_setup()
{
// defaults for our options
    add_option('doublethedonation_api_host', 'https://doublethedonation.com');
    add_option('doublethedonation_public_key', '');
    add_option('doublethedonation_cache_version', date('r'));
}

// install our plugin
add_action('plugins_loaded', 'doublethedonation_plugin_setup');

function doublethedonation_get_version()
{
    $plugin_data = get_plugin_data(__FILE__);
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}

function doublethedonation_bust_cache()
{
    update_option('doublethedonation_cache_version', date('r'));
}

function doublethedonation_simple_fetch($url)
{
    global $wp_query;
    $response = wp_remote_get($url, array('timeout' => 60));
    return wp_remote_retrieve_body($response);
}

function doublethedonation_shortcode($attrs)
{
    $current_key = get_option('doublethedonation_public_key');

    if ($current_key != 'null') {

        /* If the api key is present, print the following. */
        /* You'll need create some API validation callback.*/

        wp_enqueue_script("doublethedonation_plugin_js", "https://doublethedonation.com/api/js/ddplugin.js", null, null, true);
        wp_enqueue_style("doublethedonation_plugin_css", "https://doublethedonation.com/api/css/ddplugin.css");

        return '<script>var DDCONF = { API_KEY: "' . $current_key . '" };</script>
                <div id="dd-container"></div>';
    } else {
        return "";
    }
}

add_shortcode('doublethedonation', 'doublethedonation_shortcode');


/************************************************
 * Code related to the Admin area of the plugin.
 */

add_action('admin_menu', 'doublethedonation_create_menu_page');
add_action('admin_init', 'register_doublethedonation_settings');

function doublethedonation_create_menu_page()
{
    add_menu_page("Double the Donation Admin", "Matching Gifts", "manage_options", "doublethedonation", "display_doublethedonation_settings", null, '100.1338');
}

function register_doublethedonation_settings()
{
    register_setting('doublethedonation-settings-group', 'doublethedonation_api_host');
    register_setting('doublethedonation-settings-group', 'doublethedonation_public_key');
}


function doublethedonation_option($value, $label, $selected)
{
    $value = htmlspecialchars($value);
    $label = htmlspecialchars($label);
    $selected = ($selected == $value) ? ' selected ' : NULL;
    echo "<option value=\"{$value}\" {$selected}>{$label}</option>";
}

function display_doublethedonation_settings()
{
    if (isset($_GET["doublethedonation_remove_key"]) && !isset($_GET["settings-updated"])) {
        update_option('doublethedonation_public_key', '');
        update_option('doublethedonation_setup_step', '');
    }
    $current_key = get_option('doublethedonation_public_key');
    $status = "Offline";
    $activated = false;
    $api_host = get_option('doublethedonation_api_host');
    if ($current_key) {
        $get = wp_remote_get("$api_host/api/v1/check_wordpress_key/$current_key");
        $response_code = wp_remote_retrieve_response_code($get);
        if ($response_code == 200) {
            update_option('doublethedonation_public_key', wp_remote_retrieve_body($get));
            $status = "Online";
            $activated = true;
        }
    }

    ?>

  <style type="text/css">
    .doublethedonation-admin {
      font-size: 16px;
      line-height: 1.5em;
    }

    .doublethedonation-link {
      float: left;
      margin-right: 50px;
    }

    .doublethedonation-status {
      clear: both;
      width: 100%;
      padding: 10px;
      text-transform: uppercase;
      color: white;
      text-align: center;
      font-size: 18px;
      font-weight: bold;
    }

    p.submit {
      text-align: center;
    }

    .doublethedonation-Offline {
      background-color: #B3000B;
    }

    .doublethedonation-Online {
      background-color: #6AC228;
    }

    .doublethedonation-input {
      width: 30%;
      height: 50px;
      font-size: 18px;
    }

    .doublethedonation-url {
      font-size: 18px;
    }

    .doublethedonation-admin .button-primary {
      height: 50px;
      width: 150px;
      font-size: 18px;
      text-align: center;
      margin: auto;
    }

    .helptext {
      margin-top: -10px;
      color: #777;
    }
  </style>
  <div class="doublethedonation-admin">
    <h1>Double the Donation - Matching Gifts Tool</h1>

    <div class="doublethedonation-status doublethedonation-<?php echo $status ?>"><?php echo $status ?></div>

    <form method="post" action="options.php">
        <?php settings_fields('doublethedonation-settings-group'); ?>


        <?php if (isset($_GET["advanced"])) { ?>
          <label>API Host:
            <input class="doublethedonation-input"
                   type="text"
                   name="doublethedonation_api_host"
                   value="<?php echo get_option('doublethedonation_api_host'); ?>"/>
          </label>
        <?php } else { ?>
          <input class="doublethedonation-input"
                 type="hidden"
                 name="doublethedonation_api_host"
                 value="<?php echo get_option('doublethedonation_api_host'); ?>"/>
        <?php } ?>

        <?php if (!$activated) { ?>

          <div style="text-align: center;">

              <?php if ($current_key && !$activated) { ?>

                <h2>Hmm... let's try that again</h2>

                <p>You tried this API key: <b><?php echo $current_key; ?></b></p>
                <p>Unfortunately, it didn't work... did you paste in the right key?</p>

              <?php } ?>

            <h2>Enter your API Key:</h2>
            <input class="doublethedonation-input" type="text" name="doublethedonation_public_key"/>

              <?php submit_button("Enter", "primary"); ?>

            <h3>Don't have an API Key? <a href="https://doublethedonation.com/pricing/" target="_blank">Sign up for
                Double the Donation</a></h3>
            <h3>Need help? <a href="https://doublethedonation.com/wordpress-matching-gifts-plugin/" target="_blank">Click
                here for instructions.</a></h3>
          </div>

        <?php } else { ?>

          <div class="text-align: center">
            <h3>API Key: <b><?php echo get_option('doublethedonation_public_key'); ?></b>
              <a href="admin.php?page=doublethedonation&doublethedonation_remove_key=true">(Change)</a></h3></div>

        <?php } ?>

    </form>

      <?php if ($activated) { ?>

        <div style="background: #EEEEEE; padding: 10px;">
          <h2>You're all set up! Next steps:</h2>
          <ol>
            <li>Use our shortcode <b>[doublethedonation]</b> on the page or blog post you want the plugin to appear on.
            </li>
            <li>Update or Save the page.</li>
            <li>The Double the Donation matching gifts and volunteer grants plugin will appear on the page or blog
              post.
            </li>
          </ol>
        </div>

      <?php } else { ?>

      <?php } ?>

  </div>
<?php } ?>
