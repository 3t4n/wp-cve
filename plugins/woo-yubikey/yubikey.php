<?php
/*
Plugin Name: Woo Yubikey Two-Factor Authentication
Plugin URI: https://apb360.com/yubikey-plugin/
Description: Yubikey Multi-Factor Authentication with One-time Passwords for Wordpress & Woocommerce.
Author: Adam Lyons
Version: 2.3
Author URI: https://apb360.com
Compatibility : WordPress 5.1

----------------------------------------------------------------------------
    Thanks to the following contributor(s) :

	Henrik Schack, http://schack.dk/
	Original Plugin Code

	Phil Massyn, http://www.massyn.net/
	Ideas & code contribution to the separate admin/optionspage.
	
	Simon Josefsson, http://blog.josefsson.org/
	Ideas.
	
	Uwe Moosheimer, http://www.earchitectheaven.com/
	Ideas.

----------------------------------------------------------------------------

    Copyright 2009  Henrik Schack  (email : henrik@schack.dk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

    Copyright 2017  Adam Lyons (email : adam@apb360.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/**
 * Add One-time Password field to login form.
 */
function yubikey_loginform() {
  echo "<p>";
  echo "<label><a href=\"https://www.yubico.com/\" target=\"_blank\" title=\"".__('If You don\'t have a Yubikey enabled for Your Wordpress account, leave this field empty.','yubikey')."\">".__('Yubikey OTP (optional)','yubikey')."</a>  ";
  echo "<input autocomplete=\"off\" type=\"text\" placeholder=\"Touch the key...\" name=\"otp\" id=\"user_email\" class=\"input\" value=\"\" size=\"20\"/></label>";
  echo "</p>";
}

/**
 * Add One-time Password field to register form.
 */
function yubikey_registerform() {
  echo "<p>";
  echo "<label>".__('Yubikey OTP (Optional)','yubikey')."<br />";
  echo "<input type=\"text\" name=\"otp\" id=\"user_pass\" class=\"input\" value=\"\" size=\"20\" tabindex=\"99\"/></label>";
  echo "</p>";
}


/**
 * loginform info used in the case where PHP is missing vital functions and therefore can't use the plugin.
 */
function yubikey_loginform_functionsmissing() {
  echo "<p style=\"font-size: 12px;width: 97%;padding: 3px;\">";
  echo __('Yubikey authentication has been disabled, Your PHP installation is missing one or more vital functions. Both the Curl & Hash functions must be present for this plugin to work.','yubikey');
  echo "</p>";
}

/**
 * loginform info used in the case where no API ID or Key has been setup.
 */
function yubikey_loginform_apiinfomissing() {
  echo "<p style=\"font-size: 12px;width: 97%;padding: 3px;\">";
  echo __('Yubikey authentication has been disabled, Yubico API ID or key hasn\'t been setup.','yubikey');
  echo "</p>";
}


/**
 * Optionspage for editing Yubikey global options (Yubico API ID & Key) 
 */
function yubikey_options_page() {	
?>    
<div class="wrap">
	<h2><?php _e('Yubikey Plugin Options','yubikey');?></h2>
	<form name="yubikey" method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="yubico_api_id,yubico_api_key" />
	    <table class="form-table">
	    	<?php woo_yubikey_PHP4_Check();?>
			<tr valign="top">
				<th scope="row"><label for="yubico_api_id"><?php _e('Yubico API ID','yubikey');?></label></th>
				<td><input name="yubico_api_id" type="text" id="yubico_api_id" class="code" value="<?php echo get_option('yubico_api_id') ?>" size="40" /><br /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="yubico_api_key"><?php _e('Yubico API key','yubikey');?></label></th>
				<td><input name="yubico_api_key" type="text" id="yubico_api_key" class="code" value="<?php echo get_option('yubico_api_key'); ?>" size="40" /><br /></td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td><span class="description"><?php _e('Get Yubico ID &amp; API key at the <a href="https://upgrade.yubico.com/getapikey/">Yubico.com website</a>','yubikey');?></span></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Save Changes', 'yubikey' ) ?>" />
		</p>

	</form>
</div>
<?php
}

/**
 * Display a warning if the PHP installation is to old.
 * To be removed later on when PHP4 is completely dead.
 */
function woo_yubikey_PHP4_Check($globaloptions=true) {
	if (version_compare(PHP_VERSION, '5.0.0', '<')){
		$errormessage=__('WARNING: You appear to be using PHP4, PHP5 or newer is required for the Yubikey plugin to work.','yubikey');
		if ($globaloptions) {
			echo "<tr valign=\"top\">";
			echo "<th scope=\"row\">&nbsp;</th>";
			echo "<td><strong>".$errormessage."</strong></td>";
			echo "</tr>";
		} else {
			echo "<tr>";
			echo "<th>&nbsp;</th>";
			echo "<td><strong>".$errormessage."</strong></td>";
			echo "</tr>";
		}
	}
}

/**
 * Attach a Yubikey options page to the settings menu
 */
function yubikey_admin() {
	add_options_page('Yubikey', 'Yubikey', 'manage_options', 'yubikey', 'yubikey_options_page');
}

/**
 * Login form handling.
 * Do OTP check if user has been setup to do so.
 * @param wordpressuser
 * @return loginstatus
 */
function yubikey_check_otp($user) {
	// Get user specific settings
	$yubikeyserver	=trim(get_user_option('yubikey_server',$user->ID));
	$yubikey_key_id	=trim(get_user_option('yubikey_key_id',$user->ID));
	$yubikey_key_id2=trim(get_user_option('yubikey_key_id2',$user->ID));
	$yubikey_key_id3=trim(get_user_option('yubikey_key_id3',$user->ID));

	// Get the global API ID/KEY
	$yubico_api_id	=trim(get_option('yubico_api_id'));
	$yubico_api_key	=trim(get_option('yubico_api_key'));

	if (!empty($yubikeyserver) && $yubikeyserver!='disabled' && empty($_POST['otp'])) {
		$error=new WP_Error();
		$error->add('empty_yubikeyotp', __('<strong>ERROR</strong>: The Yubikey OTP field is empty.','yubikey'));
		return $error;
	}

	$otp=sanitize_key(trim($_POST['otp']));
	$keyid=substr($otp,0,12);

/*
ccccccggjvtkdbejcecfginkktgvvcghndndubjndicd
ccccccggjvtknibrrltchukgjdgljdnjghufccrdfdcv
ccccccggjvtkfefkrbjndbngnhcruucdckhgucllcbkj

*/

	if ($yubikeyserver=='yubico') {
		// Does keyid match ?
		if (strtoupper($yubikey_key_id)!=strtoupper($keyid) && strtoupper($yubikey_key_id2)!=strtoupper($keyid) && strtoupper($yubikey_key_id3)!=strtoupper($keyid)) {
			return false;
		}
		// is OTP valid ?
		if (yubikey_verify_otp($otp,$yubico_api_id,$yubico_api_key)) {
			return $user;
		} elseif ($yubico_api_key2 !='' && yubikey_verify_otp($otp,$yubico_api_id,$yubico_api_key2)) {
			return $user;
		} elseif ($yubico_api_key3 !='' && yubikey_verify_otp($otp,$yubico_api_id,$yubico_api_key3)) {
			return $user;
		} else {
			return false;
		}
	}
	return $user;
}

/**
 * User registration.
 * Add Yubikey information to newly created profile.
 * @param user_id
 */
function yubikey_user_register($user_id) {
	// Get the global API ID/KEY
	$yubico_api_id	=trim(get_option('yubico_api_id'));
	$yubico_api_key	=trim(get_option('yubico_api_key'));

	$otp=sanitize_key(trim($_POST['otp']));
	// Only add Yubikey ID to profile if key is valid
	if (yubikey_verify_otp($otp,$yubico_api_id,$yubico_api_key)) {
		update_user_option($user_id,'yubikey_key_id',substr($otp,0,12),true);
		update_user_option($user_id,'yubikey_server','yubico',true);
	} else {
		update_user_option($user_id,'yubikey_server','disabled',true);
	}	
}	

/**
 * Extend personal profile page with Yubikey settings.
 */
function yubikey_profile_personal_options() {
	global $user_id, $is_profile_page;
	$yubikeyserver  =get_user_option('yubikey_server',$user_id);
	$yubikey_key_id =get_user_option('yubikey_key_id',$user_id);
	$yubikey_key_id2=get_user_option('yubikey_key_id2',$user_id);
	$yubikey_key_id3=get_user_option('yubikey_key_id3',$user_id);

	echo "<h3>".__('Yubikey settings','yubikey')."</h3>";

	echo '<table class="form-table" >';
	echo '<tbody>';
	woo_yubikey_PHP4_Check(false);
	echo '<tr>';
	echo '<th scope="row" style="">'.__('Yubikey authentication','yubikey').'</th>';
	echo '<td>';

	echo '<div><input name="yubikey_server" id="yubikeyserver_disabled" value="disabled" class="tog" type="radio" style="margin: 10px;"';
	if ($yubikeyserver == 'disabled' || $yubikeyserver=='') {
		echo ' checked="checked"';
	}
	echo '/>';
	echo '<label for="yubikeyserver_disabled" style="margin-left: 10px;"> '.__('Disabled (No YubiKey)','yubikey').'</label>';
	echo '</div>';

	echo '<div><input name="yubikey_server" id="yubikeyserver_yubico" value="yubico" class="tog" type="radio" style="margin: 10px;"';
	if ($yubikeyserver=='yubico'){
		echo ' checked="checked"';
	}
	echo '/>';
	echo '<label for="yubikeyserver_yubico"> '.__('Use Yubico server','yubikey').'</label>';
	echo '</div>';

	echo '</td>';
	echo '</tr>';

	//if ($is_profile_page || IS_PROFILE_PAGE) {
		echo '<tr>';
		echo '<th><label for="yubikey_key_id">'.__('Key ID 1','yubikey').'</label></th>';
		echo '<td><input name="yubikey_key_id" id="yubikey_key_id1" value="'.$yubikey_key_id.'" type="text"/><span class="description">'.__(' First 12 chars from your key output, just press the Yubikey button in this field','yubikey').'</span><br /></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th><label for="yubikey_key_id2">'.__('Key ID 2','yubikey').'</label></th>';
		echo '<td><input name="yubikey_key_id2" id="yubikey_key_id2" value="'.$yubikey_key_id2.'" type="text"/><span class="description"> '.__(' Optional','yubikey').'</span><br /></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th><label for="yubikey_key_id3">'.__('Key ID 3','yubikey').'</label></th>';
		echo '<td><input name="yubikey_key_id3" id="yubikey_key_id3" value="'.$yubikey_key_id3.'" type="text"/><span class="description"> '.__(' Optional','yubikey').'</span><br /></td>';
		echo '</tr>';		
	//}

	echo '</tbody></table>';
}

/**
 * Extend profile page with ability to enable/disable Yubikey authentication requirement.
 */
function yubikey_edit_user_profile() {
	global $user_id;
	$yubikeyserver=get_user_option('yubikey_server',$user_id);
	$yubikey_key_id=get_user_option('yubikey_key_id',$user_id);

	// Only enable Yubikey settings if user has a key ID attached to profile
	if (strlen($yubikey_key_id)==12) {
		echo "<h3>".__('Yubikey settings','yubikey')."</h3>";

		echo '<table class="form-table">';
		echo '<tbody>';
		woo_yubikey_PHP4_Check(false);
		echo '<tr>';
		echo '<th scope="row">'.__('Yubikey authentication','yubikey').'</th>';
		echo '<td>';

		echo '<div><input name="yubikey_server" id="yubikeyserver_disabled" value="disabled" class="tog" type="radio"';
		if ($yubikeyserver == 'disabled' || $yubikeyserver=='') {
			echo ' checked="checked"';
		}
		echo '/>';
		echo '<label for="yubikeyserver_disabled"> '.__('Disabled (No YubiKeys)','yubikey').'</label>';
		echo '</div>';

		echo '<div><input name="yubikey_server" id="yubikeyserver_yubico" value="yubico" class="tog" type="radio"';
		if ($yubikeyserver=='yubico'){
			echo ' checked="checked"';
		}
		echo '/>';
		echo '<label for="yubikeyserver_yubico"> '.__('Use Yubico server','yubikey').'</label>';
		echo '</div>';

		echo '</td>';
		echo '</tr>';

		echo '</tbody></table>';
	}
}

/**
 * Form handling of Yubikey options added to personal profile page (user editing own profile)
 */
function yubikey_personal_options_update() {
	global $user_id;
	$yubikeyserver	 =sanitize_text_field(trim($_POST['yubikey_server']));
	$yubikey_key_id	 =sanitize_key(substr(trim($_POST['yubikey_key_id']),0,12));
	$yubikey_key_id2 =sanitize_key(substr(trim($_POST['yubikey_key_id2']),0,12));
	$yubikey_key_id3 =sanitize_key(substr(trim($_POST['yubikey_key_id3']),0,12));
	// Simple check to prevent trouble.
	// If yubikey key id isn't the right length we disable OTP checking
	if (strlen($yubikey_key_id)==12) {
		update_user_option($user_id,'yubikey_server',$yubikeyserver,true);
		update_user_option($user_id,'yubikey_key_id',$yubikey_key_id,true);
	} else {
		update_user_option($user_id,'yubikey_server','disabled',true);
	}
	if (strlen($yubikey_key_id2)==12) {
		update_user_option($user_id,'yubikey_key_id2',$yubikey_key_id2,true);
	} else {
		update_user_option($user_id,'yubikey_key_id2','',true);	
	}	
	if (strlen($yubikey_key_id3)==12) {
		update_user_option($user_id,'yubikey_key_id3',$yubikey_key_id3,true);
	} else {
		update_user_option($user_id,'yubikey_key_id3','',true);	
	}	
}

/**
 * Form handling of Yubikey options on edit profile page (admin user editing other user)
 */
function yubikey_edit_user_profile_update() {
	global $user_id;
	$yubikeyserver	 =sanitize_text_field(trim($_POST['yubikey_server']));
	update_user_option($user_id,'yubikey_server',$yubikeyserver,true);
}

/**
 * Verify HMAC-SHA1 signatur on result received from Yubico server
 * @param String $response Data from Yubico
 * @param String $yubico_api_key Shared API key
 * @return Boolean Does the signature match ?
 */
function yubikey_verify_hmac($response,$yubico_api_key) {
	$lines=explode("\n",$response);
	// Create array from data
	foreach ($lines as $line) {
  		$lineparts=explode("=",$line,2);
  		$result[$lineparts[0]]=trim($lineparts[1]);
	}
	// Sort array Alphabetically based on keys
	ksort($result);
	// Grab the signature sent by server, and delete
	$signatur=$result['h'];
	unset($result['h']);
	// Build new string to calculate hmac signature on
	$datastring='';
	foreach ($result as $key=>$value) {
		$datastring!='' ? $datastring.="&" : $datastring.='';
		$datastring.=$key."=".$value;
	}
	$hmac = base64_encode(hash_hmac('sha1',$datastring,base64_decode($yubico_api_key), TRUE));
	return $hmac==$signatur;
}

/**
 * Call the Auth API at Yubico server
 *
 * @param String $otp One-time Password entered by user
 * @param String $yubico_id ID at Yubico server
 * @param String $yubico_api_key Shared API key
 * @return Boolean Is the password OK ?
 */
function yubikey_verify_otp($otp,$yubico_api_id,$yubico_api_key){
        $nonce = md5(uniqid(rand()));
        $url = "https://api.yubico.com/wsapi/2.0/verify?id=$yubico_api_id&otp=$otp&nonce=$nonce&timestamp=1&sl=1&timeout=10";

        $response = wp_remote_get($url);
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                $headers = $response['headers']; // array of http header lines
                $body    = trim($response['body']); // use the content
        }

        if (yubikey_verify_hmac($body, $yubico_api_key)) {
                if(!preg_match("/status=([a-zA-Z0-9_]+)/", $body, $result)) {
                        return false;
                }
                if ($result[1]=='OK') {
                        return true;
                }
        }
        return false;
}

/**
 * Localization of the plugin description
 * @param string The string to process
 * @return string The string to display
 */
function yubikey_plugin_description($string) {
	if (trim($string) == 'Yubikey Multi-Factor Authentication with One-time Passwords for Wordpress.') {
		$string = __('Yubikey Multi-Factor Authentication with One-time Passwords for Wordpress. ','yubikey');
		$string .= __('Get Your own Yubikey at <a href="http://yubico.com/products/order/">Yubico.com</a>','yubikey');
	}
	return $string;
}

// Initialization and Hooks
add_action('personal_options_update','yubikey_personal_options_update');
add_action('profile_personal_options','yubikey_profile_personal_options');

#add_action('edit_user_profile','yubikey_edit_user_profile');
#add_action('edit_user_profile_update','yubikey_edit_user_profile_update');
add_action('edit_user_profile','yubikey_profile_personal_options');
add_action('edit_user_profile_update','yubikey_profile_personal_options');


add_filter('pre_kses', 'yubikey_plugin_description' );

add_action('admin_menu','yubikey_admin');

add_action( 'woocommerce_edit_account_form_start', 'yubikey_profile_personal_options' );
add_action( 'woocommerce_save_account_details', 'yubikey_personal_options_update' );

// If vital functions are missing in the PHP installation we don't enable the
// wp_authenticate_user filter.
//if (function_exists('curl_init') && function_exists('hash_hmac')) {
if (function_exists('hash_hmac')) {
	// If API ID & Key hasn't been setup we don't enable the wp_authenticate_user filter.
	if (intval(get_option('yubico_api_id')) && strlen(trim(get_option('yubico_api_key')))) {
		add_action('login_form', 'yubikey_loginform');
		add_filter('wp_authenticate_user','yubikey_check_otp');
		// User registration functions
		add_action('user_register','yubikey_user_register');
		add_action('register_form','yubikey_registerform');		
	} else {
		add_action('login_form', 'yubikey_loginform_apiinfomissing');
	}
} else {
	// Loginbox telling stuff is missing in the PHP installation
	add_action('login_form', 'yubikey_loginform_functionsmissing');
}
load_plugin_textdomain('yubikey', false , dirname(plugin_basename(__FILE__)).'/lang');
?>
