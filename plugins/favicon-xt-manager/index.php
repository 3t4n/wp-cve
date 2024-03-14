<?php
/*
Plugin Name: Favicon XT-Manager
Plugin URI: http://xtthemes.com/favicon-xt-manager/
Description: Favicon XT-Manager is an easy to use WordPress plugin to add a favicon to your WordPress website. A favicon is the little icon that browsers display next to a page's title on a browser tab, or in the address bar next to its URL. By using this simple plugin you will be able to easily upload a favicon to your Wordpress site.
Author: Chris Bennett
Author URI: http://xtthemes.com/
Version: 1.0
License: GPLv2
*/

class phpxtFaviconXtManager {

public $phpxtFMOptions;

// construct function
function phpxtFaviconXtManager(){
$this->phpxtFMOptions = get_option('phpxtFaviconXtManagerOptions');
$this->register_settings_and_field();
}

// Favicon XT-Manager Plugins page
function phpxtFaviconXtManagerPage(){
?>
<div id="wrap">
<?php $phpxtFaviconXtManagerOptions = get_option('phpxtFaviconXtManagerOptions'); ?>
<h2>Favicon XT-Manager</h2>
version <?php echo $phpxtFaviconXtManagerOptions['phpxtFMVersion']; ?>
	<form action="options.php" method="post" id="phpxtFaviconXtManagerForm" enctype="multipart/form-data">
	<?php
	phpxtFaviconXtManager::phpxtUpdateMessage();
	settings_fields('phpxtFaviconXtManagerOptions');
	do_settings_sections(__FILE__);
	?>
	<p>
	<strong>Notice: </strong>Before uploading a favicon image to your Wordpress site please take note of the following tips.<br />
	<strong>Standard Resolutions: </strong>16x16, 32x32, 48x48, 96x96, 180x180 OR 192x192 | <strong>File Formats: </strong>.png, .gif or .ico | <strong>Image Size: </strong>Use 25kb maximum size or less to minimize website loading time.<br />
	</p>
	<p><input type="submit" name="submit" class="button-primary" value="Save Changes" /></p>
	</form>
<hr />
<p><strong>Thank you for installing and trying our Favicon XT-Manager WordPress plugin.</strong><br />
<ul>
<li>Share your experience by rating the plugin.</li>
<li>Your valuable feedback and suggestions are important for us and other users to improve the ongoing development and quality of this plugin.</li>
<li>We welcome you to try this plugin in different WordPress versions and add your vote in the Compatibility section, it helps other users to check the compatibility and to download the appropriate version.</li>
<li>Browse and install more as they become available <a href="http://xtthemes.com/favicon-xt-manager/" title="More Free WordPress Plugins can be found at xtthemes.com">Free WordPress Plugins for your Wordpress website.</a></li>
</ul>
</p>
</div>
<?php
}

// Register group, section and fields
public function register_settings_and_field()
{
register_setting('phpxtFaviconXtManagerOptions', 'phpxtFaviconXtManagerOptions', array($this, 'phpxtFMValidateSettings'));
add_settings_section('phpxtFMSection', 'Favicon XT-Manager Setting', array($this, 'phpxtFMSectionCB'), __FILE__);
add_settings_field('phpxtFavStatus', 'Select Favicon Display: ', array($this, 'FavStatusSetting'), __FILE__, 'phpxtFMSection');
add_settings_field('phpxtFavImg1', 'Select Favicon Image 1: ', array($this, 'phpxtFavImg1Setting'), __FILE__, 'phpxtFMSection');
add_settings_field('phpxtFavImg2', 'Select Favicon Image 2: ', array($this, 'phpxtFavImg2Setting'), __FILE__, 'phpxtFMSection');
add_settings_field('phpxtActiveFavicon', 'Choose Your Favicon: ', array($this, 'phpxtActiveFaviconSetting'), __FILE__, 'phpxtFMSection');
}

// phpxtFMSection callback function
public function phpxtFMSectionCB()
{
}

// Validate submitted images and upload
public function phpxtFMValidateSettings($phpxtFMOptions)
{
$phpxtFMOptions['phpxtFMVersion'] = $this->phpxtFMOptions['phpxtFMVersion'];
$phpxtFMOptions['phpxtFMVersionType'] = $this->phpxtFMOptions['phpxtFMVersionType'];
$override = array('test_form' => false);
if(!empty($_FILES['phpxtFavImg1']['tmp_name'])){
	$file1 = wp_handle_upload($_FILES['phpxtFavImg1'], $override); $phpxtFMOptions['FavURL1'] = $file1['url'];
} else { $phpxtFMOptions['FavURL1'] = $this->phpxtFMOptions['FavURL1']; }
if(!empty($_FILES['phpxtFavImg2']['tmp_name'])) {
	$file2 = wp_handle_upload($_FILES['phpxtFavImg2'], $override); $phpxtFMOptions['FavURL2'] = $file2['url'];
} else { $phpxtFMOptions['FavURL2'] = $this->phpxtFMOptions['FavURL2']; }
return $phpxtFMOptions;
}

// Enable or Disable favicon field
function FavStatusSetting(){
echo '<select name="phpxtFaviconXtManagerOptions[phpxtFavStatus]">';
echo '<option value="1"';
if(!empty($this->phpxtFMOptions['phpxtFavStatus']) && ($this->phpxtFMOptions['phpxtFavStatus']=='1'))
{ echo ' Selected '; } echo '>Enable</option>';
echo '<option value="2"';
if(!empty($this->phpxtFMOptions['phpxtFavStatus']) && ($this->phpxtFMOptions['phpxtFavStatus']=='2'))
{ echo ' Selected '; } echo '>Disable</option>';
echo '</select>';
}

// Favicon image 1 upload field
function phpxtFavImg1Setting(){
echo '<input type="file" name="phpxtFavImg1" id="phpxtFavImg1" />';
echo '&nbsp; &nbsp; &nbsp;';
if(isset($this->phpxtFMOptions['FavURL1'])){
echo "<img src=\"".$this->phpxtFMOptions['FavURL1']."\" alt=\"Favicon Image 1\" width=\"32\" height=\"32\" />";
} else { echo "<img src=\"".plugins_url()."/favicon-xt-manager/images/favicon.png\" alt=\"Favicon Image 1\" width=\"32\" height=\"32\" />"; }
}

// Favicon image 2 upload field
function phpxtFavImg2Setting(){
echo '<input type="file" name="phpxtFavImg2" id="phpxtFavImg2" />';
echo '&nbsp; &nbsp; &nbsp;';
if(isset($this->phpxtFMOptions['FavURL2'])){
echo "<img src=\"".$this->phpxtFMOptions['FavURL2']."\" alt=\"Favicon Image 2\" width=\"32\" height=\"32\" />";
} else { echo "<img src=\"".plugins_url()."/favicon-xt-manager/images/favicon.png\" alt=\"Favicon Image 1\" width=\"32\" height=\"32\" />"; }
}

// Select favicon image field
function phpxtActiveFaviconSetting(){
echo '<input type="radio" name="phpxtFaviconXtManagerOptions[phpxtActiveFavicon]" id="phpxtFaviconXtManagerOptions[phpxtActiveFavicon]" value="img1"';
if(isset($this->phpxtFMOptions['phpxtActiveFavicon']) && $this->phpxtFMOptions['phpxtActiveFavicon']=='img1')
{ echo ' Checked'; } echo '/> Favicon Image 1';
echo '&nbsp; &nbsp; &nbsp;';
echo '<input type="radio" name="phpxtFaviconXtManagerOptions[phpxtActiveFavicon]" id="phpxtFaviconXtManagerOptions[phpxtActiveFavicon]" value="img2"';
if(isset($this->phpxtFMOptions['phpxtActiveFavicon']) && $this->phpxtFMOptions['phpxtActiveFavicon']=='img2')
{ echo ' Checked'; } echo '/> Favicon Image 2';
}

// Adds a menu on left column.
function phpxtFaviconXtManagerMenu(){
add_menu_page('PFavicon XT-Manager', 'Xt-Favicon', 'administrator', 'xtthemes-favicon-manager', array('phpxtFaviconXtManager', 'phpxtFaviconXtManagerPage'));
}

// Display Favicon on the website
function phpxtShowFavicon(){
$phpxtFMOptions = get_option('phpxtFaviconXtManagerOptions');
	// If favicon display enabled
	if($phpxtFMOptions['phpxtFavStatus'] == '1'){
		$ActiveFav = $phpxtFMOptions['phpxtActiveFavicon'];
		switch($ActiveFav){
		case 'default': $phpxtFavUrl = plugins_url().'/favicon-xt-manager/images/favicon.png'; break;
		case 'img1': $phpxtFavUrl = $phpxtFMOptions['FavURL1']; break;
		case 'img2': $phpxtFavUrl = $phpxtFMOptions['FavURL2']; break;
		default: $phpxtFavUrl = plugins_url().'/favicon-xt-manager/images/favicon.png'; break;
		}
	echo '<link rel="shortcut icon" href="' . $phpxtFavUrl . '" />';
	}
}

// Update message
function phpxtUpdateMessage(){
if($_GET['page'] == 'xtthemes-favicon-manager' && ($_GET['updated'] == 'true' || $_GET['settings-updated'] == 'true')){
?>
<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>
<?php
}
}

} // End: class phpxtFaviconXtManager

function phpxtFaviconXtManagerActivation(){
$DefaultFavUrl = plugins_url().'/favicon-xt-manager/images/favicon.png';
update_option('phpxtFaviconXtManagerOptions', array('phpxtFMVersion' => '1.0', 'phpxtFMVersionType' => 'free', 'phpxtFavStatus' => '1', 'phpxtActiveFavicon' => 'default', 'FavURL1' => $DefaultFavUrl, 'FavURL2' => $DefaultFavUrl));
}
register_activation_hook(__FILE__,'phpxtFaviconXtManagerActivation');

function InstantiatephpxtFaviconXtManager(){
new phpxtFaviconXtManager();
}
add_action('admin_init', 'InstantiatephpxtFaviconXtManager');

function RegisterphpxtFaviconXtManagerMenu(){
phpxtFaviconXtManager::phpxtFaviconXtManagerMenu();
}
add_action('admin_menu', 'RegisterphpxtFaviconXtManagerMenu');

function phpxtDisplayFavicon(){
phpxtFaviconXtManager::phpxtShowFavicon();
}
add_action('wp_head', 'phpxtDisplayFavicon');
add_action('admin_head', 'phpxtDisplayFavicon');
?>