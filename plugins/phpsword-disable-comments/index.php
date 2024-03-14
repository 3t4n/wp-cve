<?php
/**
* Plugin Name: PhpSword Disable Comments
* Description: PhpSword Disable Comments is a WordPress plugin to completely disable comments from your WordPress website.
* Version: 1.1
* Author: Pradnyankur Nikam
* License: GPL3
*/
class PhpswDC {

public $PhpswDCOptions;

// construct function
public function PhpswDC(){
$this->PhpswDCOptions = get_option('PhpswDCOptions');
$this->register_settings_and_field();
}

// Adds a menu on left column inside WP admin panel.
public function PhpswDCNewAdminMenu(){
global $wp_version;
if(version_compare($wp_version, '3.9', '>=')){
$icon_url = 'dashicons-welcome-comments';
} else { $icon_url = plugins_url('images/phpswcf.png', __FILE__); }
add_menu_page('PhpSword Disable Comments', 'PhpSw Disable Comments', 'administrator', 'phpsword-disable-comments', array('PhpswDC', 'PhpswDCPluginPage'), $icon_url);
}

// PhpSword Disable Comments Plugin page
public function PhpswDCPluginPage(){
?>
<div id="wrap">
<?php $PhpswDCOptions = get_option('PhpswDCOptions'); ?>
<h2>PhpSword Disable Comments version <?php echo $PhpswDCOptions['PhpswDCVersion']; ?></h2>
<form action="options.php" method="post" id="PhpswDCForm">
	<?php
	PhpswDC::PhpswUpdateMessage();
	settings_fields('PhpswDCOptions');
	do_settings_sections(__FILE__);
	?>
	<p><input type="submit" name="submit" class="button-primary" value="Save Changes" /></p>
</form>
<br />
<hr />
<p><strong>Thank you for using PhpSword Disable Comments WordPress plugin.</strong></p>
<p> Share your experience by rating the plugin. Provide your valuable feedback and suggestions to improve the quality of this plugin.</p>
<p>Browse and install more Free WordPress Plugins for your website.</p>
</div>
<?php
}

// Register group, section and fields
public function register_settings_and_field()
{
register_setting('PhpswDCOptions', 'PhpswDCOptions', array($this, 'PhpswDCValidateSettings'));
// First Section
add_settings_section('PhpswDCSection', 'Comments Setting', array($this, 'PhpswDCSectionCB'), __FILE__);
add_settings_field('PhpswDCType', 'Comments Option: ', array($this, 'PhpswDCTypeSetting'), __FILE__, 'PhpswDCSection');
add_settings_field('PhpswDCFrom', 'Disable Comments for<br />(Custom Comments Setting): ', array($this, 'PhpswDCFromSetting'), __FILE__, 'PhpswDCSection');
// Second Section
add_settings_section('PhpswDCTPSection', 'Trackbacks &amp; Pingbacks Setting', array($this, 'PhpswDCTPSectionCB'), __FILE__);
add_settings_field('PhpswDCTPType', 'Trackbacks &amp; Pingbacks Option: ', array($this, 'PhpswDCTPTypeSetting'), __FILE__, 'PhpswDCTPSection');
add_settings_field('PhpswDCTPFrom', 'Disable Trackbacks &amp; Pingbacks for<br />(Custom Setting): ', array($this, 'PhpswDCTPFromSetting'), __FILE__, 'PhpswDCTPSection');
}

// PhpswDCSection callback function
public function PhpswDCSectionCB() { }

// PhpswDCTPSection callback function
public function PhpswDCTPSectionCB() { }

// Validate submitted settings and options
public function PhpswDCValidateSettings($PhpswDCOptions)
{
$PhpswDCOptions['PhpswDCVersion'] = $this->PhpswDCOptions['PhpswDCVersion'];
$PhpswDCOptions['PhpswDCVersionType'] = $this->PhpswDCOptions['PhpswDCVersionType'];

if(!empty($_POST['PhpswDCOptions']['PhpswDCType']))
{
$PhpswDCType = esc_html(trim($_POST['PhpswDCOptions']['PhpswDCType']));
$PhpswDCFrom = $_POST['PhpswDCOptions']['PhpswDCFrom'];

	switch($PhpswDCType){
	case 'open': $this->PhpswDCUpdateWPComDefaults('open');	break;
	case 'closed': $this->PhpswDCUpdateWPComDefaults('closed');	break;
	case 'custom': $this->PhpswDCUpdateWPComDefaults('custom',$PhpswDCFrom); break;
	default: '';
	}
	
$PhpswDCOptions['PhpswDCType'] = $_POST['PhpswDCOptions']['PhpswDCType'];
}
else { $PhpswDCOptions['PhpswDCType'] = $this->PhpswDCOptions['PhpswDCType']; }

if(!empty($_POST['PhpswDCOptions']['PhpswDCFrom']))
{
	if($_POST['PhpswDCOptions']['PhpswDCType']=='open'){	
	$PhpswDCOptions['PhpswDCFrom'] = array(); // Enable comments on all post type options
	} elseif($_POST['PhpswDCOptions']['PhpswDCType']=='closed'){	
	$post_types = get_post_types( '', 'names' );
	$PhpswDCOptions['PhpswDCFrom'] = $post_types;
	} elseif($_POST['PhpswDCOptions']['PhpswDCType']=='custom'){	
	$PhpswDCOptions['PhpswDCFrom'] = $_POST['PhpswDCOptions']['PhpswDCFrom']; // Only disable selected
	} else {}
} elseif($_POST['PhpswDCOptions']['PhpswDCType']=='closed'){
	$post_types = get_post_types( '', 'names' );
	$PhpswDCOptions['PhpswDCFrom'] = $post_types;
}
else { $PhpswDCOptions['PhpswDCFrom'] = $this->PhpswDCOptions['PhpswDCFrom']; }

if(!empty($_POST['PhpswDCOptions']['PhpswDCTPType']))
{
$PhpswDCTPType = esc_html(trim($_POST['PhpswDCOptions']['PhpswDCTPType']));
$PhpswDCTPFrom = $_POST['PhpswDCOptions']['PhpswDCTPFrom'];

	switch($PhpswDCTPType){
	case 'open': $this->PhpswDCUpdateWPTPDefaults('open');	break;
	case 'closed': $this->PhpswDCUpdateWPTPDefaults('closed');	break;
	case 'custom': $this->PhpswDCUpdateWPTPDefaults('custom',$PhpswDCTPFrom); break;
	default: '';
	}
	
$PhpswDCOptions['PhpswDCTPType'] = $_POST['PhpswDCOptions']['PhpswDCTPType'];
}
else { $PhpswDCOptions['PhpswDCTPType'] = $this->PhpswDCOptions['PhpswDCTPType']; }

if(!empty($_POST['PhpswDCOptions']['PhpswDCTPFrom']))
{
	if($_POST['PhpswDCOptions']['PhpswDCTPType']=='open'){	
	$PhpswDCOptions['PhpswDCTPFrom'] = array(); // Enable ping and trackback on all post type options
	} elseif($_POST['PhpswDCOptions']['PhpswDCTPType']=='closed'){	
	$post_types = get_post_types( '', 'names' );
	$PhpswDCOptions['PhpswDCTPFrom'] = $post_types;
	} elseif($_POST['PhpswDCOptions']['PhpswDCTPType']=='custom'){	
	$PhpswDCOptions['PhpswDCTPFrom'] = $_POST['PhpswDCOptions']['PhpswDCTPFrom']; // Only disable selected
	} else {}
} elseif($_POST['PhpswDCOptions']['PhpswDCTPType']=='closed'){
	$post_types = get_post_types( '', 'names' );
	$PhpswDCOptions['PhpswDCTPFrom'] = $post_types;
}
else { $PhpswDCOptions['PhpswDCTPFrom'] = $this->PhpswDCOptions['PhpswDCTPFrom']; }

return $PhpswDCOptions;
}

// Comments Option Field
public function PhpswDCTypeSetting(){
echo '<select name="PhpswDCOptions[PhpswDCType]">';
echo '<option value="none"';
if(!empty($this->PhpswDCOptions['PhpswDCType']) && $this->PhpswDCOptions['PhpswDCType']=='none'){ echo ' selected '; }
echo '>Please Select</option>';
echo '<option value="open"';
if(!empty($this->PhpswDCOptions['PhpswDCType']) && $this->PhpswDCOptions['PhpswDCType']=='open'){ echo ' selected '; }
echo '>Enable Comments</option>';
echo '<option value="closed"';
if(!empty($this->PhpswDCOptions['PhpswDCType']) && $this->PhpswDCOptions['PhpswDCType']=='closed'){ echo ' selected '; }
echo '>Disable Comments</option>';
echo '<option value="custom"';
if(!empty($this->PhpswDCOptions['PhpswDCType']) && $this->PhpswDCOptions['PhpswDCType']=='custom'){ echo ' selected '; }
echo '>Custom Comments Setting</option>';
echo '</select>';
echo '<p>
<strong>Please Note: </strong>"Enable Comments" or "Disable Comments" option will completely enable or disable comments from all pages &amp; post types. Also It will show / hide comments related menus, widget or links from website. To enable/disable comments on specific post or pages, select "Custom Comments Setting" option and modify following setting.<br />
</p>';
}

// Disable Comments From Field
public function PhpswDCFromSetting(){
	$post_types = get_post_types( '', 'names' );
	foreach($post_types as $post_type) {
	echo '<input type="checkbox" name="PhpswDCOptions[PhpswDCFrom][]" id="PhpswDCOptions[PhpswDCFrom]" 
	value="' . $post_type . '"';
	if(!empty($this->PhpswDCOptions['PhpswDCFrom']) && in_array($post_type, $this->PhpswDCOptions['PhpswDCFrom']))
	{ echo ' Checked'; } echo ' /> &nbsp;' . ucfirst($post_type) . ' &nbsp; &nbsp;';
	}
}

// Trackbacks & Pingbacks Setting Option Field
public function PhpswDCTPTypeSetting(){
echo '<select name="PhpswDCOptions[PhpswDCTPType]">';
echo '<option value="none"';
if(!empty($this->PhpswDCOptions['PhpswDCTPType']) && $this->PhpswDCOptions['PhpswDCTPType']=='none'){ echo ' selected '; }
echo '>Please Select</option>';
echo '<option value="open"';
if(!empty($this->PhpswDCOptions['PhpswDCTPType']) && $this->PhpswDCOptions['PhpswDCTPType']=='open'){ echo ' selected '; }
echo '>Enable Trackbacks &amp; Pingbacks</option>';
echo '<option value="closed"';
if(!empty($this->PhpswDCOptions['PhpswDCTPType']) && $this->PhpswDCOptions['PhpswDCTPType']=='closed'){ echo ' selected '; }
echo '>Disable Trackbacks &amp; Pingbacks</option>';
echo '<option value="custom"';
if(!empty($this->PhpswDCOptions['PhpswDCTPType']) && $this->PhpswDCOptions['PhpswDCTPType']=='custom'){ echo ' selected '; }
echo '>Custom Trackbacks &amp; Pingbacks Setting</option>';
echo '</select>';
}

// Disable Trackbacks & Pingbacks From Field
public function PhpswDCTPFromSetting(){
	$post_types = get_post_types( '', 'names' );
	foreach($post_types as $post_type) {
	echo '<input type="checkbox" name="PhpswDCOptions[PhpswDCTPFrom][]" id="PhpswDCOptions[PhpswDCTPFrom]" 
	value="' . $post_type . '"';
	if(!empty($this->PhpswDCOptions['PhpswDCTPFrom']) && in_array($post_type, $this->PhpswDCOptions['PhpswDCTPFrom']))
	{ echo ' Checked'; } echo ' /> &nbsp;' . ucfirst($post_type) . ' &nbsp; &nbsp;';
	}
}

public function PhpswDCUpdateWPComDefaults($status,$post_types=''){
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;

	if($status=='open'){
	update_option('default_comment_status', 'open'); // Set default comments option to open	
		$query = "UPDATE " . $wpdb->posts . " SET comment_status = 'open'"; // Enable comments on all post types
		dbDelta($query);
	} elseif($status=='closed'){
	update_option('default_comment_status', 'closed'); // Set default comments option to closed	
		$query = "UPDATE " . $wpdb->posts . " SET comment_status = 'closed'"; // Disable comments on all post types
		dbDelta($query);
	} elseif($status=='custom' && !empty($post_types)) {	
	update_option('default_comment_status', 'open'); // Set default comments option to open
		// First enable comments for all post types then, Disable comments on selected post types
		$query = "UPDATE " . $wpdb->posts . " SET comment_status = 'open'";
		dbDelta($query);
		foreach($post_types as $post_type) {
			$query = "UPDATE {$wpdb->posts} SET comment_status = 'closed' WHERE post_type = '{$post_type}'";
			dbDelta($query);
		}		
	} else {}	
}

public function PhpswDCUpdateWPTPDefaults($status,$post_types=''){
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;

	if($status=='open'){
	update_option('default_ping_status', 'open'); // Set default ping option to open	
		$query = "UPDATE " . $wpdb->posts . " SET ping_status = 'open'"; // Enable ping on all post types
		dbDelta($query);
	} elseif($status=='closed'){
	update_option('default_ping_status', 'closed'); // Set default ping option to closed	
		$query = "UPDATE " . $wpdb->posts . " SET ping_status = 'closed'"; // Disable ping on all post types
		dbDelta($query);
	} elseif($status=='custom' && !empty($post_types)) {	
	update_option('default_ping_status', 'open'); // Set default ping option to open
		// First enable ping for all post types then, Disable ping on selected post types
		$query = "UPDATE " . $wpdb->posts . " SET ping_status = 'open'";
		dbDelta($query);
		foreach($post_types as $post_type) {
			$query = "UPDATE {$wpdb->posts} SET ping_status = 'closed' WHERE post_type = '{$post_type}'";
			dbDelta($query);
		}		
	} else {}	
}

// Disable comments link on administrator tool-bar
public function PhpswDCToolbar($wp_admin_bar){
	$PhpswDCOptions = get_option('PhpswDCOptions');
	if(!empty($PhpswDCOptions['PhpswDCType']) && $PhpswDCOptions['PhpswDCType']=='closed')
	{ $wp_admin_bar->remove_node('comments'); }
}

// Disable comments menu on administrator left column
public function PhpswDCHideAdminMenu(){
	$PhpswDCOptions = get_option('PhpswDCOptions');
	if(!empty($PhpswDCOptions['PhpswDCType']) && $PhpswDCOptions['PhpswDCType']=='closed')
	{ remove_menu_page('edit-comments.php'); } // Since: 3.1.0
}

public function PhpswDCMetaBox(){
$PhpswDCOptions = get_option('PhpswDCOptions');

// Get list of all registered post types
$all_post_types = get_post_types( '', 'names' );
	$post_types = array();
	foreach($all_post_types as $all_post_type) {
	$post_types[] = $all_post_type;
	}

if($PhpswDCOptions['PhpswDCType']=='closed'){
	$PhpswDCOptions = get_option('PhpswDCOptions');
	// Disable meta box from all post types
	foreach($post_types as $post_type){
	remove_meta_box('commentstatusdiv', $post_type, 'normal');
	remove_meta_box('commentsdiv', $post_type, 'normal');	
	}
} elseif($PhpswDCOptions['PhpswDCType']=='custom'){
	// Check and disable for selected pages
	foreach($post_types as $post_type){
		if(!empty($PhpswDCOptions['PhpswDCFrom']) && in_array($post_type, $PhpswDCOptions['PhpswDCFrom'])){
		remove_meta_box('commentstatusdiv', $post_type, 'normal');
		remove_meta_box('commentsdiv', $post_type, 'normal');
		}
	}
} else {}

}

public function PhpswDCWidget(){
$PhpswDCOptions = get_option('PhpswDCOptions');
	if($PhpswDCOptions['PhpswDCType']=='closed'){
	unregister_widget('WP_Widget_Recent_Comments'); // Remove widget
	}
}

public function PhpswDCValidateCommentSetting(){
global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

if(is_singular()) {
$postid = get_the_ID();
$comment_status = get_post_field('comment_status',$postid);
$post_type = get_post_field('post_type',$postid);

$PhpswDCOptions = get_option('PhpswDCOptions');
if($PhpswDCOptions['PhpswDCType']=='open'){
	if ($comment_status=='closed') {
	// Enable comments on the post
		$query = "UPDATE " . $wpdb->posts . " SET comment_status = 'open' WHERE ID = {$postid} LIMIT 1"; 
		dbDelta($query);
	}
} elseif($PhpswDCOptions['PhpswDCType']=='closed'){
	if ($comment_status=='open') {
	// Disable comments on the post
		$query = "UPDATE " . $wpdb->posts . " SET comment_status = 'closed' WHERE ID = {$postid} LIMIT 1"; 
		dbDelta($query);
	}
} elseif($PhpswDCOptions['PhpswDCType']=='custom'){

} else {}

}
}

// Update message
public function PhpswUpdateMessage(){
	if($_GET['page'] == 'phpsword-disable-comments' && ($_GET['updated'] == 'true' || $_GET['settings-updated'] == 'true')){
	?>
	<div id="setting-error-settings_updated" class="updated settings-error">
		<p><strong>Settings saved.</strong></p>
	</div>
	<?php
	}
}

} // End: class PhpswDC

/**
* Functions used on various hooks
*/

// Save default values on plugin activation
function PhpswDCActivation(){
update_option('PhpswDCOptions', array('PhpswDCVersion' => '1.0', 'PhpswDCVersionType' => 'free', 'PhpswDCType' => 'none', 'PhpswDCFrom' => array(''), 'PhpswDCTPType' => 'none', 'PhpswDCTPFrom' => array('')));
}
register_activation_hook(__FILE__, 'PhpswDCActivation');

// Add plugin menu
add_action('admin_menu', array('PhpswDC', 'PhpswDCNewAdminMenu'));

// Instantiate the class
function InstantiatePhpswDC(){ new PhpswDC(); }
add_action('admin_init', 'InstantiatePhpswDC');

// Customize admin panel according to setting
add_action('admin_bar_menu', array('PhpswDC', 'PhpswDCToolbar'), 999 );
add_action('admin_menu', array('PhpswDC', 'PhpswDCHideAdminMenu'));
add_action('admin_menu', array('PhpswDC', 'PhpswDCMetaBox'));
add_action('admin_menu', array('PhpswDC', 'PhpswDCWidget'));

// Validate setting on front end
add_action('get_header', array('PhpswDC', 'PhpswDCValidateCommentSetting'));

?>