<?php
/*
Plugin Name: Formilla Live Chat
Plugin URI: http://www.formilla.com
Description: Formilla.com Live Chat brings chat, offline email, and real-time visitor monitoring features to your WordPress website.
Version: 1.3.1
Author: Formilla.com
Author URI: http://www.formilla.com/
*/

$plugurldir = get_option('siteurl').'/'.PLUGINDIR.'/formilla-live-chat/';
$formilla_domain = 'FormillaLiveChat';
load_plugin_textdomain($formilla_domain, false, 'wp-content/plugins/formilla-live-chat');
add_action('init', 'formilla_init');
add_action('wp_footer', 'formilla_chat_script', 1000);
add_action('wp_ajax_save_formilla_settings', 'save_formilla_settings');
add_filter('plugin_action_links', 'formilla_plugin_actions', 10, 2);
add_filter('plugin_row_meta', 'formilla_plugin_links',10,2);
register_uninstall_hook(__FILE__, 'formilla_chat_uninstall');

define('FORMILLA_DASH', "https://www.formilla.com/live-chat/live-chat-now.aspx");
define('FORMILLA_REG', "https://www.formilla.com/sign-up.aspx?u=wp");

function formilla_init() {
    if(function_exists('current_user_can') && current_user_can('manage_options')) {
        add_action('admin_menu', 'formilla_add_settings_page');
        add_action('admin_menu', 'formilla_create_menu');
    }
}

function save_formilla_settings() {
	$nonce = $_GET['_wpnonce'];

	if (!wp_verify_nonce($nonce,'update-options'))
	{
		echo "Error";
	    die( 'Security check' );
	}
	else
	{
	    $formillaID = trim($_GET['FormillaID']);

		$succeeded = add_option('FormillaID', $formillaID);

		if(!$succeeded)
		{
			update_option('FormillaID', $formillaID);
		}

		echo "Success";
		die(); // this is required to return a proper result
	}
}

/**
* iframe in the live chat page if a FormillaID exists for this user.
* Otherwise, iframe in the registration page.
*/
function Formilla_dashboard() {
	?>
	<br /> <br />
    <img src="<?php echo plugin_dir_url( __FILE__ ).'main-logo.png'; ?>"/>

    <?php

    if(!get_option('FormillaID'))
    {
    ?>
    	   <form method="post" id="optionsform" action="options.php">
				<div class="error settings-error" id="setting-error-invalid_admin_email" style="margin: 4px 0px 5px 0px; width: 1100px;">
					<p style="padding:0px;">
						<?php echo '<a href="'.FORMILLA_REG.'"';?> target="_blank">Sign Up</a> and save the Chat ID you receive to activate your account.<br/><br/>
						<?php wp_nonce_field('update-options') ?>
						<label for="FormillaID">
						<input type="text" name="FormillaID" id="FormillaID" value="<?php echo(esc_attr(get_option('FormillaID'))) ?>" style="width:300px" />
						<input type="hidden" name="page_options" value="FormillaID" />
						<input type="submit" onclick="saveFormillaSettings();return false;" name="formillaSettingsSubmit" id="formillaSettingsSubmit" value="<?php _e('Save Settings') ?>" class="button-primary" />
					</p>
				</div>
		   </form>
		   <p id="successMessage" style="display:none; color:green;">Your settings were saved successfully.  Your chat widget should now appear on your website!</p>
		   <p id="failureMessage" style="display:none; color:red;">There was an error saving your settings.  Please try again.</p>

	<?php
	    }
	?>

		<div class="metabox-holder" id="formillaLinks" <?php  if(!get_option('FormillaID')){echo 'style="display:none"';} ?> >
			<div class="postbox">
				<div style="padding:10px;">
				<?php echo '<a href="'.FORMILLA_DASH.'"';?> target="_blank">Launch</a> Formilla.com and start chatting!
				<br/><br/>
				<a href="http://www.formilla.com/live-chat-help.aspx" target="_blank">View</a> our Help Center if you have any questions.
				<br/><br/>
				<a href="options-general.php?page=formilla-live-chat">Modify</a> my Formilla Chat ID.
				</div>
			</div>
		</div>


    <script>
    	function saveFormillaSettings()
    	{
    		if(!verifyFormillaID())
    		{
				alert('You entered an invalid Chat ID.  Please try again.');
				return false;
    		}

			var data = { action: 'save_formilla_settings' };

			jQuery.post(ajaxurl + '?' + jQuery('#optionsform').serialize(), data, function(response)
			{
				if(response == 'Success')
				{
					jQuery('#optionsform').hide();
					jQuery('#failureMessage').hide();
					jQuery('#successMessage').show();
					jQuery('#formillaLinks').slideDown(600);
					setTimeout('jQuery("#successMessage").slideUp(1000)', 10000);
				}
				else
				{
					jQuery('#failureMessage').show();
				}
			});
		}

		function verifyFormillaID() {
		    if(jQuery('#FormillaID').val().trim().length != 36)
		    	return false;
		    else
		    	return true;
		}

	</script>
	<?php
}

/**
* The actual Formilla script to create the chat button on the wordpress site.
*/
function formilla_chat_script() {
    global $current_user;

    if(get_option('FormillaID')) {
        echo("\n\n <div id=\"formillachat\" style=\"z-index:100 \"></div><div id=\"formillawindowholder\"><span style=\"display:none\"></span></div><script type=\"text/javascript\">");
		  echo("      (function () { ");
		    echo("      var head = document.getElementsByTagName(\"head\").item(0); ");
		    echo("      var script = document.createElement('script'); ");
		    echo("      var src = (document.location.protocol == \"https:\" ? 'https://www.formilla.com/scripts/feedback.js' : 'http://www.formilla.com/scripts/feedback.js');");
		    echo("      script.setAttribute(\"type\", \"text/javascript\"); script.setAttribute(\"src\", src); script.setAttribute(\"async\", true); ");
		    echo("      var complete = false; ");

		    echo("      script.onload = script.onreadystatechange = function () { ");
		    echo("        if (!complete && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) { ");
		    echo("          complete = true; ");
		    echo("          Formilla.guid = '".esc_attr(get_option('FormillaID'))."';");
		    echo("          Formilla.loadFormillaChatButton(); ");
		    echo("            }");
		    echo("      }; ");

		    echo("      head.appendChild(script); ");
		    echo("  })(); ");
    		echo(" </script> ");
    }
}

function formilla_plugin_links($links, $file) {
	$base = plugin_basename(__FILE__);
	if ($file == $base) {
		$links[] = '<a href="options-general.php?page=formilla-live-chat">' . __('Settings','formilla_widget') . '</a>';
	}
	return $links;
}

function formilla_plugin_actions($links, $file) {
    static $this_plugin;
    if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if($file == $this_plugin && function_exists('admin_url')) {

        if(trim(get_option('FormillaID')) == "") {
        	$settings_link = '<a href="'.admin_url('admin.php?page=Formilla_dashboard').'">'.__('Get Started').'</a>';
        }
        else {
        	$settings_link = '<a href="'.admin_url('options-general.php?page=formilla-live-chat').'">'.__('Settings').'</a>';
        }

        array_unshift($links, $settings_link);
    }
    return($links);
}

/**
* Formilla Live Chat Settings page.  Once user acivates Formilla account, Formilla Chat ID must be entered here to activate chat)
*/
function formilla_add_settings_page() {
    function formilla_settings_page() {
        global $formilla_domain, $plugurldir; ?>
<div class="wrap">
        <?php screen_icon() ?>
    <img src="<?php echo plugin_dir_url( __FILE__ ).'main-logo.png'; ?>"/>
    <div class="metabox-holder meta-box-sortables ui-sortable pointer">
        <div class="postbox" style="float:left;width:40em;margin-right:10px">
            <div class="inside" style="padding: 0 10px">
                <form method="post" action="options.php">
                    <p style="text-align:center">
                    <?php wp_nonce_field('update-options') ?>
                    <p><label for="FormillaID">Activate Formilla Live Chat by entering the Chat ID received when registering.

                    <?php
						if(trim(get_option('FormillaID')) == "") {
					?>
							If you don't have an account, click <a href="admin.php?page=Formilla_dashboard">here</a> to get started.
					<?php
						}
					?>
                    <input type="text" name="FormillaID" id="FormillaID" value="<?php echo(esc_attr(get_option('FormillaID'))) ?>" style="width:100%" /></p>
                    <p class="submit" style="padding:0"><input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="FormillaID" />
                        <input type="submit" name="formillaSettingsSubmit" id="formillaSettingsSubmit" value="<?php _e('Save Settings') ?>" class="button-primary" /> </p>
               </form>
            </div>
        </div>
    </div>
</div>

    <?php }
    add_submenu_page('options-general.php', __('Formilla Chat Settings'), __('Formilla Chat Settings'), 'manage_options', 'formilla-live-chat', 'formilla_settings_page');
}

function formilla_create_menu() {
    //create new top-level menu
    add_menu_page('Account Configuration', 'Formilla Chat', 'administrator', 'Formilla_dashboard', 'Formilla_dashboard', plugin_dir_url( __FILE__ ).'logo.png');
    add_submenu_page('Formilla_dashboard', 'Dashboard', 'Dashboard', 'administrator', 'Formilla_dashboard', 'Formilla_dashboard');
}

function formilla_chat_uninstall() {
    if(get_option('FormillaID')) {
	    delete_option( 'FormillaID');
	}
}
?>