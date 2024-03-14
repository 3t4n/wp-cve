<?php
/*
Plugin Name: Copyscape Premium
Plugin URI: http://www.copyscape.com/
Description: The Copyscape Premium plugin lets you check if new content is unique before it is published, by checking for duplicate content on the web. If you do not already have a Copyscape Premium account, please <a href="http://www.copyscape.com/redirect/?to=prosignup" target="_blank">sign up</a>, select 'Premium API'  from the 'Copyscape Premium' menu, and click 'Enable API access'  to see your API key. Return to Wordpress, activate the WP plugin, and enter your API key when prompted, or enter it directly into the plugin <a href="./options-general.php?page=copyscape_menu">settings</a>.
Version: 1.3.4
Author: Copyscape / Indigo Stream Technologies
Author URI: http://www.copyscape.com/
License: MIT
*/

/*
Copyright (c) 2020 Copyscape / Indigo Stream Technologies (www.copyscape.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

global $copyscape_tbl_version;        // Table version - change this when publishing an update if table data changes
$copyscape_tbl_version = "1.0";
global $copyscape_full_comparisons;        // Number of full comparisons for the results
$copyscape_full_comparisons = "5";

register_activation_hook(__FILE__, 'copyscape_activation');        // Runs copyscape_activation on activation
register_uninstall_hook(__FILE__, 'copyscape_uninstall');        // Runs copyscape_uninstall on uninstall

add_filter('post_updated_messages', 'copyscape_updated_messages', 30);        // Replaces the "post published/edited" message
//add_filter("post_type_labels_post", 'copyscape_update_labels', 99);

add_action('admin_enqueue_scripts', 'copyscape_init');        // Registers scripts [JS] (CHANGED BY NIKOLAI IAKIMOV !!!)
add_action('transition_post_status', 'copyscape_post', 130, 3);        // Tracks post publishing and updating
add_action('admin_menu', 'copyscape_menu');        // Adds our menu to the admin options menu
add_action('admin_notices', 'copyscape_admin_notice');        // Admin notice handling
add_action('plugins_loaded', 'copyscape_update_tbl');        // Update table if version changed
add_action('admin_init', 'copyscape_redirect');        // Redirects to First Time Wizard after setup (once!)
add_action('admin_init', 'copyscape_roleset');        // Assigns can_copyscape to roles
add_action('init', 'copyscape_override');        // Publishes post if user selects "publish anyway"
add_action('wp_ajax_nopriv_copyscape_check', 'ajax_copyscape_post', 1);
add_action('wp_ajax_copyscape_check', 'ajax_copyscape_post', 1);

define('COPYSCAPE_TBL', 'copyscape_tbl');        // Table name

define('OP_COPYSCAPE_NOTICE', 'copyscape_notice');        // Admin notice text
define('OP_COPYSCAPE_REPLACE', 'copyscape_replace');        // Replace publish/update confirmation message
define('OP_COPYSCAPE_POSTID', 'op_copyscape_postID');        // Holds post ID for republishing
define('OP_COPYSCAPE_TBLVERSION', 'op_copyscape_tblversion');        // Table version
define('OP_COPYSCAPE_PROCESS', 'op_copyscape_process');        // Signals that Copyscape is processing a request

/* TABLE variables - persistent	*/
define('COPYSCAPE_USER', 'copyscape_user');        // Username in the Copyscape API
define('COPYSCAPE_KEY', 'copyscape_key');        // User key in the Copyscape API
define('COPYSCAPE_AUTO', 'copyscape_auto');        // TRUE if autocheck enabled
define('COPYSCAPE_UPDATE', 'copyscape_update');        // TRUE if check on update enabled
define('COPYSCAPE_INSTANT', 'copyscape_instant');        // TRUE if instant check enabled
define('COPYSCAPE_ROLE', 'copyscape_role');        // The minimal user role for the plugin

define('COPYSCAPE_WIZARD_FIRST', 'copyscape_wizard_first');        // FALSE if still need to run the name/key wizard
define('COPYSCAPE_WIZARD_SECOND', 'copyscape_wizard_second');        // FALSE if still need to run the options wizard

/* Hooks into the wordpress notice system to display OP_COPYSCAPE_NOTICE */
function copyscape_admin_notice()
{
    if (get_option(OP_COPYSCAPE_NOTICE, NULL) != NULL) {
        echo '<div class="updated"><p><strong>' . get_option(OP_COPYSCAPE_NOTICE) . '</strong></p></div>';
        delete_option(OP_COPYSCAPE_NOTICE);
    }
}

/* Runs when the plugin is activated */
function copyscape_activation()
{
    if (current_user_can('install_plugins')) {        // Avoid possible coflict
        if (is_plugin_active('copyscape/copyscape.php') or is_plugin_active('wordpress-copyscape-plugin/wp_copyscape.php')) {
            die('Plugin not activated since other active plugins using the Copyscape API have been detected. Activating more than one such plugin at the same time is not recommended due to compatibility issues.');
        }
    }

    copyscape_update_tbl();        // Create or update the table
    add_option('copyscape_activation_redirect', TRUE);        // Redirect to first-time setup if needed
}

/* Sets who can and who cannot use the plugin */
function copyscape_roleset()
{
    $roles = array('administrator', 'editor', 'author');
    global $wpdb;
    $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;
    $sql = 'select ' . COPYSCAPE_ROLE . ' from ' . $tbl_name;
    $setrole = $wpdb->get_var($sql, 0);

    foreach ($roles as $role) {
        get_role($role)->add_cap('use_copyscape');

        if ($role == $setrole)
            break;
    }
}

/* One-time redirect to First Time Wizard after activation */
function copyscape_redirect()
{
    if (isset($_POST['save_copyscape_wizard_second'])) {        // Submitted from second wizard menu
        global $wpdb;
        $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;
        $sql = $wpdb->prepare("update " . $tbl_name . " set " . COPYSCAPE_ROLE . " = %s", $_POST[COPYSCAPE_ROLE]);
        $wpdb->query($sql);
        $wpdb->query('update ' . $tbl_name . ' set ' . COPYSCAPE_AUTO . ' = ' . (isset($_POST[COPYSCAPE_AUTO]) ? 'TRUE' : 'FALSE'));
        $wpdb->query('update ' . $tbl_name . ' set ' . COPYSCAPE_UPDATE . ' = ' . (isset($_POST[COPYSCAPE_UPDATE]) ? 'TRUE' : 'FALSE'));
        $wpdb->query('update ' . $tbl_name . ' set ' . COPYSCAPE_INSTANT . ' = ' . (isset($_POST[COPYSCAPE_INSTANT]) ? 'TRUE' : 'FALSE'));
        $wpdb->query('update ' . $tbl_name . ' set ' . COPYSCAPE_WIZARD_SECOND . ' = TRUE');
        update_option(OP_COPYSCAPE_NOTICE, 'The Copyscape Plugin is ready for use. You may change the <a href="options-general.php?page=copyscape_menu">settings</a> at any time.');
        wp_redirect('edit.php');
        exit();
    }

    if (get_option('copyscape_activation_redirect', FALSE)) {        // Only when requested (on activate)
        delete_option('copyscape_activation_redirect');        // Do-once
        if (!isset($_GET['activate-multi'])) {        // Don't go to wizard if multiple plugins set at once
            global $wpdb;
            $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;
            $sql = 'select ' . COPYSCAPE_WIZARD_FIRST . ' from ' . $tbl_name;
            if ($wpdb->get_var($sql, 0) == FALSE) {        // Don't go to options if already did wizard
                wp_redirect('options-general.php?page=copyscape_menu');        // Sends to options page and wizard
                exit();
            }
        }
    }
}

/* Creates a table or updates it to a new version */
function copyscape_update_tbl()
{
    global $copyscape_tbl_version;
    global $wpdb;
    $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;

    if (($wpdb->get_var("show tables like '$tbl_name'") != $tbl_name) or (get_option(OP_COPYSCAPE_TBLVERSION) != $copyscape_tbl_version)) {        // Only if update needed
        $sql = 'CREATE TABLE ' . $tbl_name . ' (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			' . COPYSCAPE_USER . ' text NOT NULL,
			' . COPYSCAPE_KEY . ' text NOT NULL,
			' . COPYSCAPE_AUTO . ' boolean NOT NULL,
			' . COPYSCAPE_INSTANT . ' boolean NOT NULL,
			' . COPYSCAPE_UPDATE . ' boolean NOT NULL,
			' . COPYSCAPE_ROLE . ' text NOT NULL,
			' . COPYSCAPE_WIZARD_FIRST . ' boolean NOT NULL,
			' . COPYSCAPE_WIZARD_SECOND . ' boolean NOT NULL,
			UNIQUE KEY id (id)
			);';

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);        // Creating or updating the table as necessary
        $wpdb->insert($tbl_name, array(COPYSCAPE_USER => 'API username', COPYSCAPE_KEY => 'API key', COPYSCAPE_AUTO => TRUE, COPYSCAPE_INSTANT => TRUE, COPYSCAPE_UPDATE => FALSE, COPYSCAPE_WIZARD_FIRST => FALSE, COPYSCAPE_WIZARD_SECOND => FALSE, COPYSCAPE_ROLE => 'administrator'));

        update_option(OP_COPYSCAPE_TBLVERSION, $copyscape_tbl_version);
    }
}

/* Uninstalls the plugin */
function copyscape_uninstall()
{
    global $wpdb;
    $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;
    $query = $wpdb->query('DROP TABLE ' . $tbl_name);
}

/* Registers the JS for the button and adds the button to WordPress */
function copyscape_init()
{
    if (!current_user_can('use_copyscape'))
        return;

    wp_enqueue_media();

    wp_enqueue_script('copyscape-script', plugins_url('/copyscape.js', __FILE__), array('jquery'), '1.3.4', TRUE);
    
    wp_localize_script(
        'copyscape-script',
        'copyscape_info',
        array('ajax_url' => admin_url("admin-ajax.php"),
            'post_id' => get_the_ID())
    );


}

/* Inserts Copyscape button into the interface */
function copyscape_button()
{        // Adds the button
    wp_enqueue_script('copyscape-script');
}

/* Adds a Copyscape options menu to the menu list */
function copyscape_menu()
{        // works every time the menu is displayed
    add_options_page('Copyscape Options', 'Copyscape', 'manage_options', 'copyscape_menu', 'copyscape_options');
}

/* First time wizard setup and options page */
function copyscape_options()
{
    if (!current_user_can('manage_options'))        // Permission check
        wp_die(__('You do not have sufficient permissions to manage options for this site.'));

    global $wpdb;
    $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;
    $wiz = 'select ' . COPYSCAPE_WIZARD_FIRST . ',' . COPYSCAPE_WIZARD_SECOND . ' from ' . $tbl_name;

    $signup = '<a href="http://www.copyscape.com/redirect/?to=prosignup" target="_blank" style="text-decoration:none;vertical-align:middle;font-size:smaller">I don\'t have a Copyscape Account</a>';        // These two are optional links - only when not logged into API
    $getkey = '<a href="http://www.copyscape.com/redirect/?to=apiconfigure" target="_blank" style="text-decoration:none;vertical-align:middle;font-size:smaller">I don\'t know my API key</a>';
    $roles = array('Administrators Only' => 'administrator', 'Admins and Editors' => 'editor', 'Admins, Editors and Authors' => 'author');

    $response = NULL;

    if (isset($_POST['save_copyscape_wizard_first'])) {        // Submitted from first wizard menu
        if (!isset($_POST[COPYSCAPE_USER]) or !isset($_POST[COPYSCAPE_KEY])) {        // Form fields not found
            echo '<div id="message" class="updated"><p><strong>Form submit error!</strong></p></div>';        // This should never happen
        } else {
            $sql = $wpdb->prepare("update " . $tbl_name . " set " . COPYSCAPE_USER . " = %s, " . COPYSCAPE_KEY . " = %s", $_POST[COPYSCAPE_USER], $_POST[COPYSCAPE_KEY]);
            $wpdb->query($sql);

            $response = copyscape_request('balance');
            if (is_wp_error($response)) {
                echo '<div id="message" class="updated"><p><strong>Failed to connect to Copyscape API - ' . $response->get_error_message() . '<br>Please check your connection and try again!</strong></p></div>';
            } else {
                $result = copyscape_read_xml($response['body']);
                if (isset($result['error'])) {
                    echo '<div id="message" class="updated"><p><strong>Please ensure that you have entered a valid username and API key: ' . $result['error'] . '</strong></p></div>';
                } else {
                    echo '<div id="message" class="updated"><p><b>Successfully connected to the Copyscape API. Your balance is ' . number_format($result['total']) . ' credits ($' . number_format($result['value'], 2) . ')</b></p></div>';
                    $wpdb->query('update ' . $tbl_name . ' set ' . COPYSCAPE_WIZARD_FIRST . ' = TRUE');        // First wizard finished
                }
            }
        }
    }

    if ($wpdb->get_var($wiz, 0) == FALSE) {        // First wizard menu
        $sql = 'select ' . COPYSCAPE_USER . ',' . COPYSCAPE_KEY . ' from ' . $tbl_name;
        echo '<div class="wrap"><div style="float:left;padding-right:40px;">' .
            '<div class="icon32"><img src ="' . plugin_dir_url(__FILE__) . 'copyscapeicon.png"><br /></div>' .
            '<h2>Copyscape First Time Setup</h2>
			<form method="post" border="1px">';
        echo '<table class="form-table">';
        echo '<tr valign="top"><th scope="row" colspan = "2">Welcome to the official Copyscape plugin. To start using the plugin, please enter your Copyscape username and API key.</th></tr>';
        echo '<tr valign="top"><th scope="row"><label for="' . COPYSCAPE_USER . '">Username</label></th><td><input type="text" name="' . COPYSCAPE_USER . '" id="' . COPYSCAPE_USER . '" value="" placeholder="'. $wpdb->get_var($sql, 0) .'" />&nbsp;&nbsp;' . $signup . '</td></tr>';
        echo '<tr valign="top"><th scope="row"><label for="' . COPYSCAPE_KEY . '">API Key</label></th><td><input type="text" name="' . COPYSCAPE_KEY . '" id="' . COPYSCAPE_KEY . '" value="" placeholder="' . $wpdb->get_var($sql, 1) . '" />&nbsp;&nbsp;' . $getkey . '</td></tr>';
        echo '</table><br/><input class="button-primary" type="submit" name="save_copyscape_wizard_first" id="save_copyscape_wizard_first" value="Verify API Connection"></form></div></div>';
        return;
    }

    $publishinfo = 'A Copyscape check will automatically be performed when a post is published. If any matches are found, the post will be unpublished (moved to drafts), and an option to view the report will be presented.';
    $updateinfo = 'A Copyscape check will automatically be performed when a post is updated. If any matches are found, the post will be unpublished (moved to drafts), and an option to view the report will be presented.';
    $buttoninfo = 'A separate \'Copyscape Check\' button has been added to the Publish box of the WordPress editor. A Copyscape check will be performed when this button is clicked. If any matches are found, an option to view the report will be presented.';
    $gap = '&nbsp;&nbsp;&nbsp;&nbsp;';

    if (version_compare(get_bloginfo('version'), '5.0.0', '>=')) {
        $publishinfo = 'A Copyscape check will automatically be performed when a post is published. If any matches are found, the post will be published, and an option to view the report and/or move the post back to Drafts will be presented.';
        $updateinfo = 'A Copyscape check will automatically be performed when a post is updated. If any matches are found, the post will be updated, and an option to view the report and/or move the post back to Drafts will be presented.';
    }

    if ($wpdb->get_var($wiz, 1) == FALSE) {        // Second wizard menu
        $sql = 'select ' . COPYSCAPE_INSTANT . ',' . COPYSCAPE_AUTO . ',' . COPYSCAPE_UPDATE . ',' . COPYSCAPE_ROLE . ' from ' . $tbl_name;
        ?>

        <script type="text/JavaScript" language="JavaScript"> function ShowToggle(cl, id) {
                if (document.getElementsByClassName) {
                    var elems = document.getElementsByClassName(cl);
                    for (var i = 0; i < elems.length; i++) {
                        if (elems[i].style.display == 'none')
                            elems[i].style.display = 'table-row';
                        else elems[i].style.display = 'none';
                    }
                }
                else if (document.getElementById) {
                    if (document.getElementById(id).style.display == 'none')
                        document.getElementById(id).style.display = 'block';
                    else
                        document.getElementById(id).style.display = 'none';
                }
            }
        </script>

        <?php
        echo '<div class="wrap"><div style="float:left;padding-right:40px;">' .
            '<div class="icon32"><img src ="' . plugin_dir_url(__FILE__) . 'copyscapeicon.png"><br /></div>' .
            '<h2>Copyscape First Time Setup</h2><form method="post" border="1px">';

        echo '<table class="form-table"><tr valign="top"><tr valign="top"><th scope="row" colspan = "2">';
        echo 'The Copyscape plugin can be used in several ways, according to your needs.</th></tr>';
        echo '<tr valign="top"><th scope="row" colspan = "2">' . $buttoninfo . '</th></tr>';
        echo '<tr valign="top"><th scope="row" colspan = "2">Please select your preferences below (<a title = "Show detailed information" onclick ="javascript:ShowToggle(\'Info\',\'MoreInfo\')" href="javascript:;" >learn more</a>):</th></tr>';
        echo '</table>';

        echo '<div id="MoreInfo" style="display: none" ><br><table class="form-table"><tr valign="top"><th scope="row" colspan = "2"><p>' . $publishinfo . '</p><p>' . $updateinfo . '</p>';
        echo '</th></tr></table></div>';

        echo '<table class="form-table">';
        echo '<tr valign="top"><th scope="row" colspan = "2">' . $gap . '<input type="checkbox" name="' . COPYSCAPE_AUTO . '" id="' . COPYSCAPE_AUTO . '" title="' . $publishinfo . '" ' . ($wpdb->get_var($sql, 1) == TRUE ? 'checked' : 'unchecked') . ' />' . $gap . '<label for="' . COPYSCAPE_AUTO . '" title="' . $publishinfo . '">Check for copies automatically when I click \'Publish\'.</th></tr>';
        echo '<tr valign="top" style="display: none" class="Info"><th scope="row" colspan = "2" style = "padding-left:60px">' . $publishinfo . '</th></tr>';
        echo '<tr valign="top"><th scope="row" colspan = "2">' . $gap . '<input type="checkbox" name="' . COPYSCAPE_UPDATE . '" id="' . COPYSCAPE_UPDATE . '" title="' . $updateinfo . '" ' . ($wpdb->get_var($sql, 2) == TRUE ? 'checked' : 'unchecked') . ' />' . $gap . '<label for="' . COPYSCAPE_UPDATE . '" title="' . $updateinfo . '">Check for copies automatically when I click \'Update\'.</label></th></tr>';
        echo '<tr valign="top" style="display: none" class="Info"><th scope="row" colspan = "2" style = "padding-left:60px">' . $updateinfo . '</th></tr>';
        echo '<tr valign="top"><th scope="row"><label for="' . COPYSCAPE_ROLE . '">This plugin may be used by:</label></th><td><select id="' . COPYSCAPE_ROLE . '" name="' . COPYSCAPE_ROLE . '">';
        foreach ($roles as $key => $value)
            echo '<option value=' . $value . ' ' . ($wpdb->get_var($sql, 3) == $value ? 'selected="selected"' : '') . '>' . $key . '</option>';
        echo '</select></td></tr>';
        echo '</table><br/><input class="button-primary" type="submit" name="save_copyscape_wizard_second" id="save_copyscape_wizard_second" value="Continue"></form></div></div>';
        return;
    }

    if (isset($_POST['save_copyscape_settings'])) {        // Submitted from settings menu
        if (!isset($_POST[COPYSCAPE_USER]) or !isset($_POST[COPYSCAPE_KEY])) {        // Form fields not found - should never happen
            echo '<div id="message" class="updated"><p><strong>Form submit error!</strong></p></div>';
        } else {        // Saving everything
            $sql = $wpdb->prepare("update " . $tbl_name . " set " . COPYSCAPE_USER . " = %s, " . COPYSCAPE_KEY . " = %s, " . COPYSCAPE_ROLE . " = %s", $_POST[COPYSCAPE_USER], $_POST[COPYSCAPE_KEY], $_POST[COPYSCAPE_ROLE]);
            $wpdb->query($sql);
            $wpdb->query('update ' . $tbl_name . ' set ' . COPYSCAPE_AUTO . ' = ' . (isset($_POST[COPYSCAPE_AUTO]) ? 'TRUE' : 'FALSE'));
            $wpdb->query('update ' . $tbl_name . ' set ' . COPYSCAPE_UPDATE . ' = ' . (isset($_POST[COPYSCAPE_UPDATE]) ? 'TRUE' : 'FALSE'));

            $response = copyscape_request('balance');
            if (is_wp_error($response)) {
                echo '<div id="message" class="updated"><p><strong>Failed to connect to Copyscape API - ' . $response->get_error_message() . '<br>Please check your connection and try again!</strong></p></div>';
            } else {
                $result = copyscape_read_xml($response['body']);
                if (isset($result['error'])) {
                    echo '<div id="message" class="updated"><p><strong>Copyscape API - Failed to validate: ' . $result['error'] . '</strong></p></div>';
                } else echo '<div id="message" class="updated"><p><strong>Settings Saved!</strong></p></div>';
            }
        }
    }
    // Settings menu
    if (!$response)
        $response = copyscape_request('balance');
    $balancerow = '<tr valign="top"><th scope="row" colspan = "2"><label style="color:Red"><b>Not Connected to the Copyscape API</b></label></th></tr>';

    if (is_wp_error($response)) {
        echo '<div id="message" class="updated"><p><strong>Failed to connect to Copyscape API - ' . $response->get_error_message() . '<br>Please check your connection and try again!</strong></p></div>';
    } else {
        $result = copyscape_read_xml($response['body']);
        if (!isset($result['error'])) {        // Successfully connected to the Copyscape API
            $signup = '';
            $getkey = '';        // Don't show connection help links - already connected
            $balancerow = '<tr valign="top"><th scope="row" colspan = "2"><label style="color:Green"><b>Connected to the Copyscape API</b></label></th><td><b>Your balance is ' . number_format($result['total']) . ' credits ($' . number_format($result['value'], 2) . ').&nbsp;&nbsp;<a href="http://www.copyscape.com/redirect/?to=propurchase" target="_blank" style="text-decoration: none">Purchase credits</a></b></td></tr>';
        }
    }

    $sql = 'select ' . COPYSCAPE_USER . ',' . COPYSCAPE_KEY . ',' . COPYSCAPE_INSTANT . ',' . COPYSCAPE_AUTO . ',' . COPYSCAPE_UPDATE . ',' . COPYSCAPE_ROLE . ' from ' . $tbl_name;

    echo '<div class="wrap"><div style="float:left;padding-right:40px;">' .
        '<div class="icon32"><img src ="' . plugin_dir_url(__FILE__) . 'copyscapeicon.png"><br /></div>' .
        '<h2>Copyscape Settings</h2>
		<form method="post" border="1px"><table class="form-table">';

    echo $balancerow;        // Show connection status and balance

    echo '<tr valign="top"><th scope="row"><label for="' . COPYSCAPE_USER . '">Username</label></th><td><input type="text" name="' . COPYSCAPE_USER . '" id="' . COPYSCAPE_USER . '" value="' . $wpdb->get_var($sql, 0) . '" />&nbsp;&nbsp;' . $signup . '</td></tr>';
    echo '<tr valign="top"><th scope="row"><label for="' . COPYSCAPE_KEY . '">API Key</label></th><td><input type="text" name="' . COPYSCAPE_KEY . '" id="' . COPYSCAPE_KEY . '" value="' . $wpdb->get_var($sql, 1) . '" />&nbsp;&nbsp;' . $getkey . '</td></tr>';
    echo '<tr valign="top"><th scope="row"><label for="' . COPYSCAPE_AUTO . '" title="' . $publishinfo . '">Check on Publish</label></th><td><input type="checkbox" name="' . COPYSCAPE_AUTO . '" id="' . COPYSCAPE_AUTO . '" ' . ($wpdb->get_var($sql, 3) == TRUE ? 'checked' : 'unchecked') . ' title="' . $publishinfo . '" /> Check for copies when a post is published</td></tr>';
    echo '<tr valign="top"><th scope="row"><label for="' . COPYSCAPE_UPDATE . '" title="' . $updateinfo . '">Check on Update</label></th><td><input type="checkbox" name="' . COPYSCAPE_UPDATE . '" id="' . COPYSCAPE_UPDATE . '" ' . ($wpdb->get_var($sql, 4) == TRUE ? 'checked' : 'unchecked') . ' title="' . $updateinfo . '" /> Check for copies when a post is updated</td></tr>';

    echo '<tr valign="top"><th scope="row"><label for="' . COPYSCAPE_ROLE . '">This plugin may be used by:</label</th><td><select id="' . COPYSCAPE_ROLE . '" name="' . COPYSCAPE_ROLE . '">';
    foreach ($roles as $key => $value)
        echo '<option value=' . $value . ' ' . ($wpdb->get_var($sql, 5) == $value ? 'selected="selected"' : '') . '>' . $key . '</option>';
    echo '</select></td></tr>';

    echo '</table><br/>';

    echo '<input class="button-primary" type="submit" name="save_copyscape_settings" id="save_copyscape_settings" value="Save Changes"></form></div></div>';
}

/* Sends an API request to Copyscape API with given parameters. Returns the call result */
function copyscape_request($request, $params = array(), $text = NULL)
{
    global $wpdb;
    $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;

    $sql = 'select ' . COPYSCAPE_USER . ',' . COPYSCAPE_KEY . ' from ' . $tbl_name;

    $url = 'http://www.copyscape.com/api/?u=' . urlencode($wpdb->get_var($sql, 0)) . '&k=' . urlencode($wpdb->get_var($sql, 1)) . '&o=' . urlencode($request) . '&src=wordpress-plugin';        // Building the request URL

    foreach ($params as $param => $value)
        $url .= '&' . urlencode($param) . '=' . urlencode($value);

    $response = wp_remote_post($url, array(
            'method' => isset($text) ? 'POST' : 'GET',
            'timeout' => 20,
            'body' => isset($text) ? strip_tags($text) : NULL
        )
    );
    return $response;
}

/* Tracks post status change to detect publishing and updating posts */
function copyscape_post($new, $old, $post)
{
    if (isset($_GET['copyscape_publish_anyway']))
        return;        // Manual override, ignore any and all checks

    if (!current_user_can('use_copyscape') or !current_user_can('publish_posts'))        // Security check
        return;

    if (get_option(OP_COPYSCAPE_PROCESS, FALSE) != FALSE)        // Ignore changes while already processing request
        return;

    if ($new == 'inherit')        // Avoid working on unsaved text
        return;

    if (isset($_POST['save']) and $_POST['save'] == 'Copyscape Check') {        // Button press - must match the js
        unset($_POST['save']);        // Do-once
        copyscape_checkpost($post, 'button');
    } else if ($new == 'publish') {
        global $wpdb;
        $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;
        $sql = 'select ' . COPYSCAPE_AUTO . ',' . COPYSCAPE_UPDATE . ' from ' . $tbl_name;
        if ($old == 'publish') {
            if ($wpdb->get_var($sql, 1) == TRUE)
                copyscape_checkpost($post, 'update');
        } else if ($wpdb->get_var($sql, 0) == TRUE) {
            copyscape_checkpost($post, 'publish');
        }
    }

}

function ajax_copyscape_post()
{
    if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "copyscape_check") {
        $post_id = $_POST['copyscape_post_id'];
        $post = get_post($post_id);

//        if (get_option(OP_COPYSCAPE_PROCESS, FALSE) != FALSE){
//            echo "error";
//            die();
//        }


        $arr = array();

        if ($_POST['caller_button'] == 'check')
            $arr = ajax_copyscape_checkpost($post, 'button');
        else {
            global $wpdb;
            $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;
            $sql = 'select ' . COPYSCAPE_AUTO . ',' . COPYSCAPE_UPDATE . ' from ' . $tbl_name;

            if ((strpos($_POST['caller_button'], 'publish') !== false
                    || strpos($_POST['caller_button'], 'pub-private') !== false)
                && ($wpdb->get_var($sql, 0) == TRUE))
                $arr = ajax_copyscape_checkpost($post, 'publish');

            if ((strpos($_POST['caller_button'], 'updat') !== false
                    || strpos($_POST['caller_button'], 'upd-private') !== false)
                && ($wpdb->get_var($sql, 1) == TRUE))
                $arr = ajax_copyscape_checkpost($post, 'update');

        }
        echo json_encode($arr);

        die();
    }
}


/* Sends post text to the Copyscape API to check. Unpublishes post and gives the poster
	a choice to view matches or publish anyway if check finds any matches. $from is check initiator */
function copyscape_checkpost($post, $from)
{
    global $copyscape_full_comparisons;
    global $wpdb;
    $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;
    $post_info = get_post($post);

    update_option(OP_COPYSCAPE_PROCESS, TRUE);        // Processing request - ignore status changes until done

    session_start();
    $result = NULL;
    $choice = NULL;        // A non-NULL choice will override "post published" message
    $connected = FALSE;        // TRUE if connected to API
    $tooshort = 'At least 15 words are required to perform a search';        // The "sample too short" error
    $nocreds = 'Copyscape was not able to check your post - you have no search credits remaining.';
    $todrafts = 'Your post was not ' . ($from == 'publish' ? 'published' : 'updated') . ' and has instead been moved to Drafts.';
    $getcreds = '<a href="http://www.copyscape.com/redirect/?to=propurchase" target="_blank" style="text-decoration: none">Purchase credits</a>';
    $anyway = '<a href="' . get_edit_post_link($post) . '&amp;' . 'copyscape_publish_anyway=1' . '&amp;' . 'message=6' . '">' . ($from == 'publish' ? 'Publish' : 'Update') . ' Anyway</a>';
    $gap = '&nbsp;&nbsp;&nbsp;';

    if ($post_info->post_content == "") {
        if ($from == 'button') {
            update_option(OP_COPYSCAPE_REPLACE, "The post below is empty. Please write your post before running a Copyscape check.");        // Set choice msg
            update_option(OP_COPYSCAPE_POSTID, $post->ID);        // Save post ID in case of republish
        }
        return;        // Do not check empty post
    }

    if (strlen(trim($post_info->post_content)) < 45) {
        if ($from == 'button') {
            update_option(OP_COPYSCAPE_REPLACE, $tooshort);        // Set choice msg
            update_option(OP_COPYSCAPE_POSTID, $post->ID);        // Save post ID in case of republish
        }
        return;        // Do not check empty post
    }


    if (isset($_SESSION['copyscape_last']) and ($_SESSION['copyscape_last'] == $post_info->post_content) and isset($_SESSION['copyscape_report']) and (!isset($_SESSION['copyscape_report']['error']) or ($_SESSION['copyscape_report']['error'] == $tooshort))) {
        $result = $_SESSION['copyscape_report'];        // Failsafe: Do not do repeat search if text is same as last and no errors
        $connected = TRUE;
    } else {
        $response = copyscape_request('csearch', array('e' => 'UTF-8', 'c' => $copyscape_full_comparisons), $post_info->post_content);
        if (!is_wp_error($response)) {        // Successfully connected
            $result = copyscape_read_xml($response['body'], array(2 => array('result' => 'array')));
            $connected = TRUE;
        }
    }

    if (!$connected) {
        $choice = 'Your content was not checked because we were unable to connect to Copyscape API.<br>';
        if ($from == 'button')
            $choice .= '<br>Please check your connection and try again.';
        else $choice .= 'Please check your connection and try again.<br><br>' . $todrafts . $gap . $anyway;
    } else if (isset($result['error'])) {        // Error from Copyscape API
        if ($result['error'] == $tooshort) {        // At least 15 words are required
            $_SESSION['copyscape_report'] = $result;
            $_SESSION['copyscape_last'] = $post_info->post_content;
            if ($from == 'button')
                $choice = 'At least 15 words are required to perform a Copyscape search.';
            else return;        // Always allow post if less than 15 words
        } else if ($result['error'] == 'No search credits remaining - please purchase more') {        // Out of creds
            $choice = $nocreds . '<br><br>' . ($from == 'button' ? $getcreds : $todrafts . $gap . $getcreds . $gap . $anyway);
        } else $choice = 'Copyscape API response: ' . $result['error'] . ($from == 'button' ? '' : '<br><br>' . $todrafts . $gap . $anyway);
    } else        // Copyscape API didn't return an error
    {
        if ($result['count'] > 0) {        // Content duplicates detected
            $_SESSION['copyscape_report'] = $result;
            $_SESSION['copyscape_last'] = $post_info->post_content;
            $found = 'Copyscape found ' . $result['count'] . ' matches, with ' . $result['allwordsmatched'] . ' words covering ' . ($result['allpercentmatched'] == 100 ? '' : 'at least ') . $result['allpercentmatched'] . '% of your text.';
            $matches = '<a href="' . $result['allviewurl'] . '" target="_blank">View Matches</a>';

            $choice = $found . $gap . $matches;
            if ($from != 'button')
                $choice .= '<br><br>' . $todrafts . $gap . $anyway;
        } else if ($from == 'button') {        // No duplicates detected
            $choice = 'Copyscape has not detected any matches for the current post. (' . $result['querywords'] . ' words checked)';
        }
    }

    if ($choice != NULL) {        // Unpublish (unless button check) and present user with a choice
        update_option(OP_COPYSCAPE_REPLACE, $choice);        // Set choice msg
        update_option(OP_COPYSCAPE_POSTID, $post->ID);        // Save post ID in case of republish
        if ($from != 'button') {
            $update = get_post($post, 'ARRAY_A');
            $update['post_status'] = 'draft';
            $ret = wp_update_post($update);        // Unpublish post
        }
    }
}


/* Sends post text to the Copyscape API to check. Unpublishes post and gives the poster
	a choice to view matches or publish anyway if check finds any matches. $from is check initiator */
function ajax_copyscape_checkpost($post, $from)
{
    $notice_arr = [];

    global $copyscape_full_comparisons;
    global $wpdb;
    $tbl_name = $wpdb->prefix . COPYSCAPE_TBL;
    // $post_info = get_post($post);

    // update_option(OP_COPYSCAPE_PROCESS, TRUE);        // Processing request - ignore status changes until done

    session_start();
    $result = NULL;
    $choice = NULL;        // A non-NULL choice will override "post published" message
    $connected = FALSE;        // TRUE if connected to API
    $tooshort = 'At least 15 words are required to perform a Copyscape search';        // The "sample too short" error
    $nocreds = 'Copyscape was not able to check your post - you have no search credits remaining.';
    $getcreds_link_url = "http://www.copyscape.com/redirect/?to=propurchase";
    $getcreds_link_text = "Purchase credits";

    $gap = '   ';

    if ($_POST['post_content'] == "") {
        if ($from == 'button') {
            $notice_arr['message'] = "The post below is empty. Please write your post before running a Copyscape check.";        // Set choice msg
            // update_option(OP_COPYSCAPE_POSTID, $post->ID);        // Save post ID in case of republish
        }
        return array();        // Do not check empty post
    }

    if (isset($_SESSION['copyscape_last']) and ($_SESSION['copyscape_last'] == $_POST['post_content']) and isset($_SESSION['copyscape_report']) and (!isset($_SESSION['copyscape_report']['error']) or ($_SESSION['copyscape_report']['error'] == $tooshort))) {
        $result = $_SESSION['copyscape_report'];        // Failsafe: Do not do repeat search if text is same as last and no errors
        $connected = TRUE;
    } else {
        $response = copyscape_request('csearch', array('e' => 'UTF-8', 'c' => $copyscape_full_comparisons), $_POST['post_content']);
        if (!is_wp_error($response)) {        // Successfully connected
            $result = copyscape_read_xml($response['body'], array(2 => array('result' => 'array')));
            $connected = TRUE;
        }
    }

    if (!$connected) {
        $notice_arr['message'] = 'Your content was not checked because we were unable to connect to Copyscape API. ';
        $notice_arr['message'] .= 'Please check your connection and try again.';
        if ($from != 'button')
            $notice_arr['back_to_drafts'] = true;

    } else if (isset($result['error'])) {        // Error from Copyscape API
        if ($result['error'] == $tooshort) {
            // At least 15 words are required
            $_SESSION['copyscape_report'] = $result;
            $_SESSION['copyscape_last'] = $_POST['post_content'];
            if ($from == 'button')
                $notice_arr['message'] = 'At least 15 words are required to perform a Copyscape search.';
            else return array();        // Always allow post if less than 15 words

        } else if ($result['error'] == 'No Copyscape search credits remaining - please purchase more') {        // Out of creds
            $notice_arr['message'] = $nocreds;
            $notice_arr['link_url'] = $getcreds_link_url;
            $notice_arr['link_text'] = $getcreds_link_text;

            if ($from != 'button')
                $notice_arr['back_to_drafts'] = true;

        } else {

            $notice_arr['message'] = 'Copyscape API response: ' . $result['error'];
            if ($from != 'button')
                $notice_arr['back_to_drafts'] = true;
        }
    } else        // Copyscape API didn't return an error
    {
        if ($result['count'] > 0) {        // Content duplicates detected
            $_SESSION['copyscape_report'] = $result;
            $_SESSION['copyscape_last'] = $_POST['post_content'];
            $notice_arr['message'] = 'Copyscape found ' . $result['count'] . ' matches, with ' . $result['allwordsmatched'] . ' words covering ' . ($result['allpercentmatched'] == 100 ? '' : 'at least ') . $result['allpercentmatched'] . '% of your text.';
            $notice_arr['link_url'] = $result['allviewurl'];
            $notice_arr['link_text'] = "View Matches";

            if ($from != 'button')
                $notice_arr['back_to_drafts'] = true;

        } else if ($from == 'button') {        // No duplicates detected
            $notice_arr['message'] = 'Copyscape has not detected any matches for the current post. (' . $result['querywords'] . ' words checked)';
        }
    }


//    update_option(OP_COPYSCAPE_POSTID, $post->ID);        // Save post ID in case of republish
//    if (get_option(OP_COPYSCAPE_PROCESS, FALSE) != FALSE)
//        delete_option(OP_COPYSCAPE_PROCESS);

    return $notice_arr;
}


/* Republishes post on user clicking Publish Anyway */
function copyscape_override()
{
    if (isset($_GET['copyscape_publish_anyway'])) {
        if (!current_user_can('publish_posts'))        // Security check
            return;

        if (get_option(OP_COPYSCAPE_POSTID, NULL) != NULL) {        // The post user asked to republish
            $update = get_post(get_option(OP_COPYSCAPE_POSTID), 'ARRAY_A');
            $update['post_status'] = 'publish';
            $ret = wp_update_post($update);
            delete_option(OP_COPYSCAPE_POSTID);        // Shouldn't matter but for robustness
        }
    }
}

/* Replaces the post status change message with the contents of OP_COPYSCAPE_REPLACE */
function copyscape_updated_messages($messages)
{
    global $post;

    if (get_option(OP_COPYSCAPE_PROCESS, FALSE) != FALSE)
        delete_option(OP_COPYSCAPE_PROCESS);

    if (get_option(OP_COPYSCAPE_REPLACE, NULL) != NULL) {        // One time replace requested
        for ($i = 1; $i < count($messages[$post->post_type]); $i++)
            $messages[$post->post_type][$i] = '<strong>' . get_option(OP_COPYSCAPE_REPLACE) . '</strong>';
        delete_option(OP_COPYSCAPE_REPLACE);
    }

    return $messages;
}


/* Parsing XML into complex array */
function copyscape_read_xml($xml, $spec = NULL)
{
    global $copyscape_xml_data, $copyscape_xml_depth, $copyscape_xml_ref, $copyscape_xml_spec;
    $copyscape_xml_data = array();
    $copyscape_xml_depth = 0;
    $copyscape_xml_ref = array();
    $copyscape_xml_spec = $spec;

    $parser = xml_parser_create();

    xml_set_element_handler($parser, 'copyscape_xml_start', 'copyscape_xml_end');
    xml_set_character_data_handler($parser, 'copyscape_xml_data');

    if (!xml_parse($parser, $xml, TRUE))
        return FALSE;

    xml_parser_free($parser);

    return $copyscape_xml_data;
}

function copyscape_xml_start($parser, $name, $attribs)
{
    global $copyscape_xml_data, $copyscape_xml_depth, $copyscape_xml_ref, $copyscape_xml_spec;

    $copyscape_xml_depth++;

    $name = strtolower($name);

    if ($copyscape_xml_depth == 1)
        $copyscape_xml_ref[$copyscape_xml_depth] = &$copyscape_xml_data;
    else {
        if (!is_array($copyscape_xml_ref[$copyscape_xml_depth - 1]))
            $copyscape_xml_ref[$copyscape_xml_depth - 1] = array();

        if (@$copyscape_xml_spec[$copyscape_xml_depth][$name] == 'array') {
            if (!is_array(@$copyscape_xml_ref[$copyscape_xml_depth - 1][$name])) {
                $copyscape_xml_ref[$copyscape_xml_depth - 1][$name] = array();
                $key = 0;
            } else $key = 1 + max(array_keys($copyscape_xml_ref[$copyscape_xml_depth - 1][$name]));

            $copyscape_xml_ref[$copyscape_xml_depth - 1][$name][$key] = '';
            $copyscape_xml_ref[$copyscape_xml_depth] = &$copyscape_xml_ref[$copyscape_xml_depth - 1][$name][$key];
        } else {
            $copyscape_xml_ref[$copyscape_xml_depth - 1][$name] = '';
            $copyscape_xml_ref[$copyscape_xml_depth] = &$copyscape_xml_ref[$copyscape_xml_depth - 1][$name];
        }
    }
}

function copyscape_xml_end($parser, $name)
{
    global $copyscape_xml_depth, $copyscape_xml_ref;

    unset($copyscape_xml_ref[$copyscape_xml_depth]);

    $copyscape_xml_depth--;
}

function copyscape_xml_data($parser, $data)
{
    global $copyscape_xml_depth, $copyscape_xml_ref;

    if (is_string($copyscape_xml_ref[$copyscape_xml_depth]))
        $copyscape_xml_ref[$copyscape_xml_depth] .= esc_html($data);
}