<?php
/*
  Plugin Name: Contact Form 7 AutoResponder Addon
  Plugin URI: https://wpsolutions-hq.com
  Description: Allows adding visitors to your Mailchimp list when they submit a message using Contact Form 7
  Author: wpsolutions
  Version: 3.1
  Author URI: https://wpsolutions-hq.com
  Text Domain: cf7-autoresponder-addon
 */
define('CF7ADDON_PATH', dirname(__FILE__) . '/');
$path = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
define('CF7ADDON_URL', $path);

if (!class_exists('MailChimp')) {
    include_once ( CF7ADDON_PATH . 'inc/mailchimp/MailChimp_api_v3.php' );
}

function cf7_ar_show_plugin_settings_link($links, $file) 
{
    if ($file == plugin_basename(__FILE__)){
            $settings_link = '<a href="options-general.php?page=cf7_autoresp_addon">Settings</a>';
            array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'cf7_ar_show_plugin_settings_link', 10, 2 );


if(is_admin()){
    add_action('admin_print_styles', 'admin_menu_page_styles');
}

function admin_menu_page_styles() 
{
    wp_enqueue_style('cf7-autoresponder-admin-styles', CF7ADDON_URL. '/css/cf7-autoresponder-admin-styles.css');
} 


add_action('plugins_loaded', 'cf7_addon_execute_plugins_loaded_operations');

function cf7_addon_execute_plugins_loaded_operations() {
    if (!function_exists('wpcf7_install')) {
        add_action('admin_notices', 'cf7_addon_conflict_check');
        return;
    }
    // Add a menu for our options page
    add_action('admin_menu', 'cf7_autoresp_addon_add_page');
}

function cf7_addon_conflict_check() {
    $cf7_link = '<a href="https://contactform7.com/">Contact Form 7 plugin</a>';
    $info_msg = '<div class="error fade"><p><strong>'.sprintf( __('Attention! You do not have the %s active. The Contact Form 7 AutoResponder Addon can only work if Contact Form 7 is active.', 'cf7-autoresponder-addon'), $cf7_link).'</strong></p></div>';
    echo $info_msg;
}

function cf7_autoresp_addon_add_page() {
    $cf7_mc_admin_menu = add_menu_page('CF7 MC Addon', 'CF7 MC Addon', 'manage_options', 'cf7_autoresp_addon', 'cf7_autoresp_addon_option_page', 'dashicons-email-alt');
}

// Draw the admin page
function cf7_autoresp_addon_option_page() {
    $mc_enabled = 0;
    $mc_api_key = '';
    $mc_list_name = '';
    $mc_disable_double_opt = false;
    $instructions_url = '<a href="https://wpsolutions-hq.com/contact-form-7-mailchimp-addon-plugin/">this page</a>'; //TODO!!

    //process form submission
    if (isset($_POST['auto_resp_update'])) {
        $errors = '';
        $nonce = $_REQUEST['_wpnonce'];
        if (!wp_verify_nonce($nonce, 'auto-responder-addon-settings-nonce')) {
            die(__('Nonce check failed during save settings!', 'cf7-autoresponder-addon'));
        }
        
        if(isset($_POST['cf7_autoresponder_acknowledge'])) {
            
        }

        if (!empty($_POST['mc-api'])) {
            $mc_api_key = sanitize_text_field($_POST['mc-api']);
        } else {
            $errors .= __('Please enter your MailChimp API key.','cf7-autoresponder-addon').'<br/>';
        }
        
        if (isset($_POST['enable-mc'])) {
            $mc_enabled = 1;
        } else {
            $mc_enabled = 0;
        }

        if (!$errors) {
            //add the data to the wp_options table
            $options = array(
                'mc_enabled' => $mc_enabled,
                'mc_api_key' => $mc_api_key,
                'mc_list_name' => $mc_list_name,
                'mc_disable_double_opt' => $mc_disable_double_opt,
            );
            update_option('cf7_autoresp_addon', $options); //store the results in WP options table
            echo '<div id="message" class="updated fade">';
            echo '<p>Settings Saved</p>';
            echo '</div>';
        } else {
            echo '<div id="message" class="error"><p>' . $errors . '</p></div>';
        }
    }
    
    $mc_settings = get_option('cf7_autoresp_addon');
    if (!empty($mc_settings)) {
        $mc_settings = get_option('cf7_autoresp_addon');
        $mc_enabled = $mc_settings['mc_enabled'];
        $mc_api_key = $mc_settings['mc_api_key'];
        $mc_list_name = $mc_settings['mc_list_name'];
        $mc_disable_double_opt = $mc_settings['mc_disable_double_opt'];
    }
    ?>
    <div class="wrap">
        <div id="poststuff">
            <div id="post-body">
                <form action="" method="POST">
                <?php wp_nonce_field('auto-responder-addon-settings-nonce'); ?>
                    <input type="hidden" name="auto_resp_update" id="auto_resp_update" value="true" />
                    <h2><?php _e('Contact Form 7 AutoResponder Addon', 'cf7-autoresponder-addon'); ?></h2>
<?php
                    if(!empty($mc_list_name)) {
?>                        
                    <div class="cf7_ar_orange_box">
                        <?php
                        echo '<strong>'.__('<p>NOTE: This plugin has recently been improved and the usage instructions have changed.','cf7-autoresponder-addon') . '</strong>' .
                        '<br />'.sprintf( __('See below for a short summary of how to use this plugin, OR, for more in-depth instructions see %s.', 'cf7-autoresponder-addon'), $instructions_url) .
                        '</p>';
                        ?>
                    </div>
<?php                    
                        }
?>
                    <div class="cf7_ar_blue_box">
                        <?php
                        echo '<p>'.__('This plugin allows you to add people to your MailChimp list from specific CF7 forms on your site.', 'cf7-autoresponder-addon').
                        '<br />'.__('The basic way to use this plugin is as follows:', 'cf7-autoresponder-addon').
                        '<br />'.sprintf( '<strong>%s</strong>', __( 'Step 1: ', 'cf7-autoresponder-addon' )).__('Configure and save the settings below.', 'cf7-autoresponder-addon').
                        '<br />'.sprintf( '<strong>%s</strong>', __( 'Step 2: ', 'cf7-autoresponder-addon' )).__('Edit the CF7 form you want to use to add people to your Mailchimp list.', 'cf7-autoresponder-addon').
                        '<br />'.sprintf( '<strong>%s</strong>', __( 'Step 3: ', 'cf7-autoresponder-addon' )).__('Go to the "Additional Settings" tab in the CF7 form settings.', 'cf7-autoresponder-addon').
                        '<br />'.sprintf( '<strong>%s</strong>', __( 'Step 4: ', 'cf7-autoresponder-addon' )).__('Add the following line:', 'cf7-autoresponder-addon').
                        '<br />'.__('mc_list_name: <strong>your-list-name</strong>', 'cf7-autoresponder-addon').
                        '<br />("<strong>your-list-name</strong>"'.__(' should be replaced with the case-sensitive actual name of your Maichimp list', 'cf7-autoresponder-addon'). ')'.                               
                        '<br />'.sprintf( '<strong>%s</strong>', __( 'Step 5: ', 'cf7-autoresponder-addon' )).__('Save your CF7 form settings.', 'cf7-autoresponder-addon').
                        '<br /><br />'.__('After doing the above this plugin will add people to your Mailchimp list whenever they submit the relevant CF7 form.', 'cf7-autoresponder-addon').
                        '<br />'.__('NOTE - The above simple example will only collect the email address to add to your list.', 'cf7-autoresponder-addon').
                        '<br />'.__('If you wish to collect more "List Fields" such as first or last name etc you can easily do that by entering the required values in the "Additional Settings" tab.', 'cf7-autoresponder-addon').
                        '<br />'.sprintf( __('See %s for more info.', 'cf7-autoresponder-addon'), $instructions_url).
                        '</p>';
                        ?>
                    </div>
                    <div class="cf7_ar_orange_box">
                        <?php
                        $premium_url = '<a href="https://wpsolutions-hq.com/premium-contact-form-7-mailchimp-plugin/">premium version</a>'; //TODO!!
                        echo '<p>'.sprintf( '<strong>%s</strong>',__('Do you want even more features?', 'cf7-autoresponder-addon')).
                        '<br />'.__('The premium plugin offers more functionality such as:', 'cf7-autoresponder-addon').
                        '<br /><ul>'.                                
                        '<li>'.sprintf( '<strong>%s</strong>',__('- Add subscribers to one or multiple Maichimp list groups', 'cf7-autoresponder-addon')).'</li>'.
                        '<li>'.sprintf( '<strong>%s</strong>',__('- Create Mailchimp interest categories and groups directly from Wordpress', 'cf7-autoresponder-addon')).'</li>'.
                        '<li>'.sprintf( '<strong>%s</strong>',__('- View your Mailchimp lists and subscriber counts directly from Wordpress', 'cf7-autoresponder-addon')).'</li>'.
                        '<li>'.sprintf( '<strong>%s</strong>',__('- View and create merge vars for a Mailchimp list from Wordpress', 'cf7-autoresponder-addon')).'</li>'.
                        '<li>'.sprintf( '<strong>%s</strong>',__('.....and more!', 'cf7-autoresponder-addon')).'</li>'.
                        '</ul>'.
                        '<br />'.sprintf( __('See the %s for more info.', 'cf7-autoresponder-addon'), $premium_url).
                        '</p>';
                        ?>
                    </div>
                    

                    <div class="postbox">
                        <h3 class="hndle"><label for="title"><?php _e('Enter Your MailChimp Account Details', 'cf7-autoresponder-addon'); ?></label></h3>
                        <div class="inside">
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="enable-mc"><?php _e('Enable Mailchimp List Insertion', 'cf7-autoresponder-addon'); ?>: </label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="enable-mc" <?php if ($mc_enabled) echo ' checked="checked"'; ?> />
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="MCAPIKey"><?php _e('Enter MailChimp API Key', 'cf7-autoresponder-addon'); ?>:</label>
                                    </th>
                                    <td>
                                        <input type="text" size="40" name="mc-api" value="<?php echo esc_html($mc_api_key); ?>" />
                                    </td>
                                </tr>
<?php
                                if(!empty($mc_list_name)) {
?>                                    
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="MCListName"><?php _e('Enter MailChimp List Name', 'cf7-autoresponder-addon'); ?>:</label>
                                    </th>
                                    <td>
                                        <input type="text" size="40" name="mc-list-name" value="<?php echo esc_html($mc_list_name); ?>" disabled/>
                                        <span class="description"><?php echo sprintf( __('THIS FIELD HAS BEEN DEPRECATED. Please see the new instructions on %s for more info.', 'cf7-autoresponder-addon'), $instructions_url); ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="disable-double-opt"><?php _e('Disable Double Opt-in Email', 'cf7-autoresponder-addon'); ?>: </label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="disable-double-opt" <?php if ($mc_disable_double_opt) echo ' checked="checked"'; ?> disabled />
                                        <span class="description"><?php echo sprintf( __('THIS FIELD HAS BEEN DEPRECATED. Please see the new instructions on %s for more info.', 'cf7-autoresponder-addon'), $instructions_url); ?></span>
                                    </td>
                                </tr>
<?php                                    
                                }
?>
                            </table>
                        </div></div>
                    <input name="Submit" type="submit" value="Save Settings" class="button-primary" />
                </form>
            </div></div>
    </div> <!-- End wrap -->
    <?php
}

add_action('wpcf7_before_send_mail', 'wpcf7_before_send_mail_tasks'); //use the cf7 hook

/**
 * Uses the wpcf7_before_send_mail hook to process the MailChimp tasks when a form is submitted
 * @param type $cf7
 * @return type
 */
function wpcf7_before_send_mail_tasks($cf7) {
    $cf_instance = WPCF7_Submission::get_instance(); //get submitted data
    
    //get values from config settings 
    $auto_resp_settings = get_option('cf7_autoresp_addon');
    $mc_enabled = $auto_resp_settings['mc_enabled'];
    if(empty($mc_enabled)) return;
    
    $mc_list = $auto_resp_settings['mc_list_name'];
    
    if(empty($mc_list)){
        // use new method
        wpcf7_process_form_mailchimp($cf_instance, $auto_resp_settings);
    }else{
        // use old method - deprecated
        wpcf7_before_send_mail_tasks_old_method($cf7);
    }
}

function wpcf7_process_form_mailchimp($cf_instance, $auto_resp_settings) {
    //get values from config settings 
    $mc_api = $auto_resp_settings['mc_api_key'];
    $cform = $cf_instance->get_contact_form();

    // check if an "opt-in" checkbox has been added to the CF7 form
    $posted_data = $cf_instance->get_posted_data();
    
    if (array_key_exists('mc-subscribe', $posted_data)) {
        if (empty($posted_data['mc-subscribe'][0])) {
            return; //do not subscribe if user has left opt-in box disabled
        }
    }
    
    // check if admin has configured form to skip mc subscriptions
    $skip_autoresp = $cform->is_true('mc_skip');
    if($skip_autoresp) return;
    
    $sender_email = '';
    if(isset($posted_data['EMAIL'])){ 
        $sender_email = $posted_data['EMAIL'];
    }else{
        foreach($posted_data as $key=>$value){
            if(strpos($key, 'email') === false) {
                continue;
            } else {
                $sender_email = $posted_data[$key];
            }
        }
    }
    $mc_disable_double_opt = '';
    $list_id = '';
    $cf7_merge_values = $cform->additional_setting('mc_merge_field', 50);
    $cf_list_name = $cform->additional_setting('mc_list_name', 50);
    $cf7_list_id = $cform->additional_setting('mc_list_id', 50);
    $cf7_disable_opt = $cform->additional_setting('mc_disable_double_opt', 1);
    
    if(empty($cf7_list_id) && empty($cf_list_name)) {
        error_log(date("Y-m-d H:i:s") . " - Error - mc_list_id or mc_list_name item not found! Check your config in CF7 'Additional Settings' tab. \n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        return;
    }
    
    $mc_list_name = empty($cf_list_name[0])?'':$cf_list_name[0];
    $mc_list_id = empty($cf7_list_id[0])?'':$cf7_list_id[0];
    
    try {
        $api = new MailChimp($mc_api);
    } catch (Exception $e) {
        error_log(date("Y-m-d H:i:s") . " - Error!\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tCode=" . $e->getCode() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tMsg=" . $e->getMessage() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        return;
    }

    $merge_vars = array();
    if(empty($mc_list_id)){
        // Get list id if list name was only provided
        $mc_list_id = get_list_id($api, $mc_list_name);
        if(empty($mc_list_id)) {
            error_log(date("Y-m-d H:i:s") . " - Error - list not found with name ".$mc_list_name.". Try using mc_list_id instead. \n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
            return;
        }
    }
    
    if(!empty($cf7_disable_opt)) {
        $mc_disable_double_opt = $cf7_disable_opt[0];
    }
    
    if ($mc_disable_double_opt) {
        $status = 'subscribed'; // will subscribe the user automatically after form submission
    } else {
        $status = 'pending'; // will cause mailchimp to send a double-opt-in email
    }

    $api_array = array('email_address' => $sender_email, 'status' => $status); //this is the special format needed when calling subscribe method

    // do merge var processing
    try {
        $merge_vars_info = $api->get("lists/" . $mc_list_id . "/merge-fields");
    } catch (Exception $e) {
        error_log(date("Y-m-d H:i:s") . " - Error!\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tCode=" . $e->getCode() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tMsg=" . $e->getMessage() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        return;
    }
    
    if (!empty($cf7_merge_values)) {
        $merge_vars_data = $merge_vars_info['merge_fields']; // Get the data from API containing all merge_fields 
        // Now iterate through the configured merge fields and check that the field/tag exists in mailchimp
        foreach ($cf7_merge_values as $tag) {
            foreach ($merge_vars_data as $mv_elem) {
                if ($mv_elem['tag'] == $tag) {
                    if (array_key_exists($tag, $posted_data)) {
                        $merge_vars[$tag] = $posted_data[$tag];
                    }
                }
            }
        }
        $api_array['merge_fields'] = $merge_vars;
    }

    // Now add the subscriber to the list
    try {
        $retval = $api->post("lists/" . $mc_list_id . "/members", $api_array);                
        // check if some kind of error was returned from mailchimp:
        if (!array_key_exists('email_address', $retval) && !array_key_exists('list_id', $retval)) {
            if(isset($retval['title']) && $retval['title'] == 'Member Exists') return; // don't log this type to prevent log file growing
            
            error_log(date("Y-m-d H:i:s") . " - Mailchimp Error:\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
            error_log(print_r($retval, true), 3, dirname(__FILE__) . '/cf7_autoresp.log');
        }
    } catch (Exception $e) {
        error_log(date("Y-m-d H:i:s") . " - Error!\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tCode=" . $e->getCode() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tMsg=" . $e->getMessage() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        return;
    }
    
    return;
}

/**
 * Retrieves the list ID from Mailchimp using list name
 * 
 * @param Mailchimp object $mailchimp: 
 * @param string $list_name
 * @return list_id or false
 */
function get_list_id($mailchimp='', $list_name='') {
    if(empty($mailchimp) || empty($list_name)) {
        return false;
    }
    $list_id = '';
    try {
        $yourlists = $mailchimp->get('lists');
    } catch (Exception $e) {
        error_log(date("Y-m-d H:i:s") . " - Error!\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tCode=" . $e->getCode() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tMsg=" . $e->getMessage() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        return false;
    }
    
    if(empty($yourlists['lists'])) {
        return false;
    }
    
    foreach ($yourlists['lists'] as $list) {
        if ($list['name'] == $list_name) {
            $list_id = $list['id'];
            break;
        }
    }
    if(empty($list_id)) {
        return false;
    } else {
        return $list_id;
    }
}

/**
 * Deprecated - This is the old way to add subscribers.
 * @param type $cf7
 * @return type
 */
function wpcf7_before_send_mail_tasks_old_method($cf7) {
    //get values from config settings 
    $auto_resp_settings = get_option('cf7_autoresp_addon');
    $mc_api = $auto_resp_settings['mc_api_key'];
    $mc_list = $auto_resp_settings['mc_list_name'];
    $mc_disable_double_opt = $auto_resp_settings['mc_disable_double_opt'];

    //the following few lines will check if an "opt-in" checkbox has been added to the CF7 form
    $submission = WPCF7_Submission::get_instance(); //get submitted data
    $posted_data = $submission->get_posted_data();
    if (array_key_exists('mc-subscribe', $posted_data)) {
        if (empty($posted_data['mc-subscribe'][0])) {
            return; //do not subscribe if user has left opt-in box disabled
        }
    }

    $scanned_tags = $cf7->form_scan_shortcode();
    $email = '';
    if (array_key_exists('your-email', $posted_data)) {
        $email = $posted_data['your-email']; //get the submitted email address from CF7	
    }

    $firstname = '';
    if (array_key_exists('your-name', $posted_data)) {
        $firstname = $posted_data['your-name']; //in case someone uses the standard default CF7 form
    } else if (array_key_exists('your-first-name', $posted_data)) {
        $firstname = $posted_data['your-first-name']; //in case someone creates this field
    }

    if (array_key_exists('your-last-name', $posted_data)) {
        $lastname = $posted_data["your-last-name"]; //in case someone creates this field
    } else {
        $lastname = '';
    }

    //Check if form has a list name specified inside it
    $form_list_name = get_form_list_name($scanned_tags);
    if ($form_list_name) {
        $mc_list = $form_list_name;
    }

    $mergeVars = array(
        'FNAME' => $firstname,
        'LNAME' => $lastname,
    );

    //Check form for any extra submitted field tags to be added to autoresponder
    $custom_tags = array();
    //check for any fields submitted which contain the special prefix "MCTAG-"
    foreach ($posted_data as $key => $val) {
        if (strpos($key, 'MCTAG-') !== FALSE) {
            $mc_tag = substr(trim($key), 6); //Remove the "MCTAG-" prefix
            $custom_tags[$mc_tag] = $val;
        }
    }

    

    try {
        $api = new MailChimp($mc_api);
        $yourlists = $api->get('lists');
    } catch (Exception $e) {
        error_log(date("Y-m-d H:i:s") . " - Error!\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tCode=" . $e->getCode() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        error_log("\tMsg=" . $e->getMessage() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
        return;
    }

    if ($mc_disable_double_opt) {
        $status = 'subscribed'; // will subscribe the user automatically after form submission
    } else {
        $status = 'pending'; // will cause mailchimp to send a double-opt-in email
    }

    $api_array = array('email_address' => $email, 'status' => $status); //this is the special format needed when calling subscribe method

    foreach ($yourlists['lists'] as $list) {
        if ($list['name'] == $mc_list) {
            $merge_vars_info = $api->get("lists/" . $list['id'] . "/merge-fields");
            if (!empty($custom_tags)) {
                $merge_vars_data = $merge_vars_info['merge_fields']; //Get the actual array containing all of the merge_vars
                //Now iterate through custom_tags array and check that the tag exists in mailchimp
                foreach ($custom_tags as $tag => $value) {
                    foreach ($merge_vars_data as $mv_elem) {
                        if ($mv_elem['tag'] == $tag) {
                            $mergeVars[$tag] = $value;
                        }
                    }
                }
                $api_array['merge_fields'] = $mergeVars;
            }

            try {
                $retval = $api->post("lists/" . $list['id'] . "/members", $api_array);
                // check if some kind of error was returned from mailchimp:
                if (!array_key_exists('email_address', $retval) && !array_key_exists('list_id', $retval)) {
                    error_log(date("Y-m-d H:i:s") . " - Mailchimp Error:\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
                    error_log(print_r($retval, true), 3, dirname(__FILE__) . '/cf7_autoresp.log');
                }
            } catch (Exception $e) {
                error_log(date("Y-m-d H:i:s") . " - Error!\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
                error_log("\tCode=" . $e->getCode() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
                error_log("\tMsg=" . $e->getMessage() . "\n", 3, dirname(__FILE__) . '/cf7_autoresp.log');
                return;
            }
        }
    }
    return;
}

/**
 * Deprecated - This has been replaced by a new technique using the CF7 additional settings tab
 * (see function wpcf7_process_form_mailchimp)
 * @param type $cf7
 * @return type
 */

function get_form_list_name($scanned_tags) {
    //this function will extract a list name from the form if it is specified
    $form_list = '';
    foreach ($scanned_tags as $item) {
        if ($item['type'] == 'submit') {
            if (strpos($item['raw_values'][0], "|")) {
                //get the listname
                $res = explode("|", $item['raw_values'][0]);
                $list_name = trim($res[1]);
                if (!empty($list_name)) {
                    $form_list = $list_name;
                }
                break;
            }
        }
    }
    return $form_list;
}
