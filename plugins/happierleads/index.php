<?php
/**
 * index.php
 *
 * Copyright (c) 2016-2020 "happierleads" https://www.happierleads.com
 *
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * See COPYRIGHT.txt and LICENSE.txt
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Happierleads
 * @package Happierleads
 * @since happierleads 3.0.1
 *
 * Plugin Name: Happierleads
 * Plugin URI: https://www.happierleads.com
 * Version: 3.0.1
 * Description: Identify your B2B website visitors that work remotely 
 * Author: Happierleads
 * Author URI: https://www.happierleads.com
 */
namespace happierleads\Happierleads_script_includer;
// to add happierslead menu page in admin
if (!defined('ABSPATH')) {
    die('Invalid request.');
}   
define( 'HLSI_LICENSE', true );
if(!class_exists('Happierleads_script_includer')){
  class Happierleads_script_includer{
    function __construct(){ 
      add_action('admin_menu', array(&$this, 'happierleads_script_page_create'));
      register_deactivation_hook(__FILE__, array(&$this, 'happierleads_settingsplugin_deactivate'));
      add_action('wp_enqueue_scripts', array(&$this, 'happiersleads_happierleads_script'), 100);
      add_action('admin_enqueue_scripts', array(&$this, 'happiersleads_loader_admin_style'));

    }
// to create submenu page
    function happierleads_script_page_create() {
        add_submenu_page('options-general.php', 'Happierleads Settings', 'Happierleads Settings', 'edit_posts', 'happierleads_settings', array(&$this, 'happierleads_scriptoption_page_display'), 74);
    }
// to deactivate happierslead
    function happierleads_settingsplugin_deactivate() {
        delete_option('happierleads_settings_script_val'); //delete plugin specific options upon deactivation
    }
// setting in admin 
    function happierleads_scriptoption_page_display() {
        ?>  
        <div class="wrap">
            <?php
            if (isset($_POST['happierleads_script_val']) &&
                    wp_verify_nonce($_POST['nonce'], 'wporg_options')) {


                if (count(explode(' ', sanitize_text_field($_POST['happierleads_script_val']))) > 1) {
                    echo '<div id="message" class="error notice notice-error is-dismissible"><p><strong>Please do not add any space in option value.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                } else {
                    update_option('happierleads_script_val', sanitize_text_field($_POST['happierleads_script_val']));
                    echo '<div id="message" class="updated notice notice-success is-dismissible"><p><strong>Publisher Id saved Successfully.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                }
            }
            $cidvalue = get_option('happierleads_script_val');
            ?>
            <div class="above_happierleads">
                <div class="head"> 
                    <div class="leftside"> 
                        <span><?php echo esc_html(__('Settings', 'text_domain')); ?></span>
                    </div>
                    <div class="rightside">
                        <a href="<?php echo (filter_var('https://www.happierleads.com', FILTER_VALIDATE_URL) ? 'https://www.happierleads.com' : ''); ?>" target="_blank"><?php echo esc_html(__('Help', 'text_domain')); ?></a>
                    </div>
                </div>
            </div>
            <div class="scriptingbox">
                <div class="left_section_happierleads">
                    <p><?php echo esc_html(__('Copy your Client ID from Happierleads.com and paste it below, you can check your Client ID', 'text_domain')); ?> <a href="<?php echo (filter_var('https://admin.happierleads.com/setup-tracking-script', FILTER_VALIDATE_URL) ? 'https://admin.happierleads.com/setup-tracking-script' : ''); ?>" target="_blank"> <?php echo esc_html(__('here ', 'text_domain')); ?></a></p>
                    <form method="post">
                        <table class="form-table">
                            <tr valign="top"><th scope="row"><?php echo esc_html(__('Happierleads Status:', 'text_domain')); ?></th>
                                <td>
                                    <?php
                                    $happierleads_script_val = get_option('happierleads_script_val');
                                    $happierleads_script_val = trim($happierleads_script_val);
                                    $cidvalue = trim($cidvalue);
                                    if ($happierleads_script_val && $cidvalue) {
                                        echo "<strong class='activer'>Active</strong>";
                                    } else {
                                        echo "<strong class='deactiver'>Inactive</strong>";
                                    }
                                    ?>
                                </td>
                            </tr> 
                            <tr valign="top"><th scope="row"><?php echo esc_html(__('Happierleads ClientID:', 'text_domain')); ?></th>
                                <td><input required placeholder="Please Insert ClientID" type="text" name="happierleads_script_val" value="<?php echo $cidvalue; ?>" /><label><?php echo esc_html(__('Required')); ?></label></td>
                            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wporg_options'); ?>" />
                            </tr>                                  
                        </table>
                        <p class="submit">
                            <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
                        </p>
                    </form>
                    <p><?php _e('If you have any questions or suggestions about this plugin, reach out to us at '); ?><a href="mailto:hello@happierleads.com"><?php echo _e('hello@happierleads.com'); ?></a></p>
                </div>

                <div class="right_section_happierleads">
                    <?php echo '<img with="300px" src="' . esc_url(plugins_url('images/happierleads-logo.png', __FILE__)) . '" > '; ?>
                </div>
            </div>
        </div>      
        <?php
    }
// to add script  in frontend
function happiersleads_happierleads_script() {
    $happierleads_script_val = get_option('happierleads_script_val');
    $happierleads_script_val = trim($happierleads_script_val);

    // Register the script
    wp_register_script('happierleads_handle', esc_url(plugins_url('assets/script.js', __FILE__)), array(),'3.0.0', true);

// Localize the script with new data
    $translation_array = array('happierleads' => $happierleads_script_val);
    wp_localize_script('happierleads_handle', 'object_name', $translation_array);

// Enqueued script with localized data.
    wp_enqueue_script('happierleads_handle');
}
function happiersleads_loader_admin_style() {
    wp_register_style('cadmin_css', esc_url(plugins_url('assets/custom.min.css', __FILE__)), false, '3.0.0');
    wp_enqueue_style('cadmin_css', esc_url(plugins_url('assets/custom.min.css', __FILE__)), false, '3.0.0');
}

  }

   $s = new \happierleads\Happierleads_script_includer\Happierleads_script_includer();
}