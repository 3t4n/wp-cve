<?php
/*

Plugin Name: Theme File Duplicator
Plugin URI: http://wordpress.org/extend/plugins/theme-file-duplicator/
Description: Clone template files from the WP backend. Go to Appearance -> Add Page Template
Version: 1.3
Author: Jon Schwab
Author URI: http://www.ancillaryfactory.com
License: GPL2





Copyright 2011    (email : jsschwab@aoa.org)

    This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See th
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

 
$plugin = plugin_basename(__FILE__); 

function duplicator_admin_actions() {
	$page = add_submenu_page( 'themes.php', 'Add Page Template', 'Add Page Template', 'edit_themes', 'copy_theme_file', 'file_duplicator_admin' );
}

add_action('admin_menu', 'duplicator_admin_actions');

if (isset($_POST['newFile'])) {
	add_action('admin_head', 'duplicationProcess');
}


function file_duplicator_admin() {   
	$theme_data = get_theme_data(get_stylesheet_uri());
?>
<!-- Success Messages -->
<?php if (!empty($_POST['newFile'])) { 
	$newFile = str_replace(' ', '-', $_POST['newFile']);
	$newFile = filter_var($newFile, FILTER_SANITIZE_URL);
	?>
	<!-- <div class="updated fade"><p><strong><?php print $newFile . ' added to ' . $theme_data['Title'] . ' theme. <a href="' . admin_url('theme-editor.php') . '">Take a look</a>.'; ?></strong></p></div>  -->
<?php } ?>



<!-- End Success Messages -->


<div class="wrap"> 
  <div id="icon-edit-pages" class="icon32" style="float:left"></div>
<h2>Add new page template</h2>

<form id="duplicateFile" method="post" action="admin.php?page=copy_theme_file" style="padding:20px 20px">
	
	<label for="currentFile"><strong>Template file to copy:</strong></label><br/>
	<select name="currentFile" id="currentFile">
		<?php 
		$files = scandir(TEMPLATEPATH);
		foreach ($files as $file) {
			if ( preg_match( '(\.php$|\.css$|\.js$)', $file) ) {
				echo '<option value="' . $file . '">' . $file . '</option>';
			}
		}
		
		?>
	</select>
	
	<?php if ( !empty($theme_data['Template']) ) {   // check for parent theme ?> 
		&nbsp;<em>(from <?php print ucfirst($theme_data['Template']); ?>)</em>
	<?php } ?>
	
	<br/><br/>
	
	<input type="checkbox" name="addTemplateID" id="addTemplateID"  checked="checked" />&nbsp;
	<label for="addTemplateID"><strong>Add custom page template header</strong>&nbsp; <a href="http://codex.wordpress.org/Pages#Creating_Your_Own_Page_Templates" style="font-size:10px" target="_blank">What's this?</a></label>
	
	<br/>
	
	<div id="templateNameWrapper">
		<br/>
		<label for="newTemplateName"><strong>New template name:</strong></label><br/>
		<input type="text" name="newTemplateName" id="newTemplateName" style="font-size:16px;padding:5px;width:250px" >
	</div>
	
	<br/>
	
	<label for="newFile"><strong>New filename:</strong></label><br/>
	<input type="text" name="newFile" id="newFile" style="font-size:16px;padding:5px;text-align:right;width:250px" value=".php"/>
	<span style="display:none;color:#BA1714;font-weight:bold;background:#F7D9D9;padding:5px" id="fileNameError">Please enter a valid filename.</span>
	
	<br/><br/>
	
	<input type="submit" name="duplicateFileSubmit" value="Make a new file" class="button-primary" style="position:relative;top:-2px"/>

</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#currentFile option[value='page.php']").attr('selected','selected');
		
		jQuery('#addTemplateID').click(function() {
			if (jQuery("#addTemplateID").is(":checked"))
			{
				jQuery("#templateNameWrapper").show("fast");
			} else {   
				jQuery("#templateNameWrapper").hide("fast");
			}
		});
		
		jQuery('#duplicateFile').submit(function() {
			var filename = jQuery('#newFile').val();
			
			filenameCheck = /([0-9a-z_-]+[\.][0-9a-z_-]{1,3})$/.test(filename);
			//alert(filenameCheck);
			
			if ( filenameCheck == false ) {
				jQuery('#fileNameError').show();
				return false;
			}			
		});
		
		jQuery('#newFile').keyup(function() {
			jQuery('#fileNameError').hide();
		});
		
		
	});
</script>

<?php } 


function duplicationProcess() {
	
	$theme_data = get_theme_data(get_stylesheet_uri()); 
	$newTemplateName = trim($_POST['newTemplateName']);
	$templateIdentifier = '<?php
/*
Template Name: '. $newTemplateName . '
*/
?>

';
	$newFile = str_replace(' ', '-', $_POST['newFile']);
	$newFile = filter_var($newFile, FILTER_SANITIZE_URL);
	
	$fileToCopy = $_POST['currentFile'];
	$templateDirectory = TEMPLATEPATH . '/'. $fileToCopy;
	
	
	$newFilePath = STYLESHEETPATH . '/' . $newFile;

	
	$currentFile = fopen($templateDirectory,"r");
	$pageTemplate = fread($currentFile,filesize($templateDirectory));
	fclose($currentFile);
	
	$newTemplateFile = fopen($newFilePath,"w");
	
	if ( isset($_POST['addTemplateID']) && !empty($newTemplateName) ) {  // only write identifier if checked
		fwrite($newTemplateFile, $templateIdentifier);
	}
	
	$written = fwrite($newTemplateFile, $pageTemplate);
	fclose($newTemplateFile);
	
	// success/error message
	if ( $written != false ) { ?>
		<div class="updated fade"><p><strong><?php print $newFile . ' added to ' . $theme_data['Title'] . ' theme. <a href="' . admin_url('theme-editor.php') . '">Take a look</a>.'; ?></strong></p></div>
	<?php } else { ?>
		<div class="error"><p><strong>ERROR: Unable to create new theme file</strong></p></div>
	<?php } 
	
}