<?php
/*
uploader.php, V 1.03, altm, 20.09.2013
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
Google Map V3 init Multimap support
released under GNU General Public License
*/
// check for rights
if ( !defined('ABSPATH'))
    die('You are not allowed to call this page directly.');
	
global $wpdb;

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
	<title>ATLsoft Uploader</title>	
	<base target="_self" />
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
		<script language="javascript" type="text/javascript">	
			var FileBrowserDialogue = {
				init : function () {
				// insert code for setting your custom onLoad.
				},
				mySubmit : function (fName) {
				tinyMCE.activeEditor.execCommand('mceGMapInsertContent',fName);
				tinyMCEPopup.close();
			}
		}
		tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);
	</script>
</head>	
<body>
<?php
	$action			= isset($_GET["action"]) ? $_GET["action"] : "none";			// no action by default
	$query			= isset($_GET["q"]) ? $_GET["q"] : "";							// no action by default
	$udir			= isset($_GET["udir"]) ? $_GET["udir"] : "./";					// same directory by default
	$input_field	= isset($_GET["input"]) ? $_GET["input"] : "";					
	$ReplaceFile	= isset($_GET["replace_file"]) ? $_GET["replace_file"] : "yes";	// replace the file by default		
	
	if($action=="gmap_tinymce_upload"){	
		if($query=="upload")	
			upload_content_file($udir, $input_field);
		else
			display_upload_form();	
		 }
?>
</body>
</html>

<?php

// displays the upload form
function display_upload_form()
{
global $wpdb;
	$upload_d = wp_upload_dir();
	$upload_dir =$upload_d['basedir'] ;
	?>
		<?php 
		$httpurl = plugins_url(). "/". GPX_GM_PLUGIN."/tinymce/";
		$purl = $_SERVER[REQUEST_URI] . "&q=upload&udir=$upload_dir";  
		
		?>
	<div class="panel_wrapper mceActionPanel">
	<form name="my_form" id="my_form" action="<?php echo $purl?>" method="post" enctype="multipart/form-data" onsubmit="document.getElementById('progress_div').style.visibility = 'visible'; return true;">
			<input style="padding:20px 0 0 0px ;width:270px"  type="file" name="upload_file" ID="upload_file" /><br />
			<input type="hidden"  id="udir" name="udir" value="<?php echo $upload_dir; ?>" />
			<input type="hidden"  id="input" name="input" value="<?php echo $input_field; ?>" />
			<input style="margin:20px 0 0 0px; width:120px; height:25px;" class="updateButton" type="submit" name="Upload File" value="<?php _e("upload file", GPX_GM_PLUGIN); ?>" style="width: 150px;" onclick="" />		
		
     <div id="progress_div" name="progress_div" style="visibility: hidden;">
        <img src="<?php echo $httpurl; ?>progress.gif" alt="wait..." style="padding-top: 5px;">
     </div>  			
	</form>
	</div>  
	<?php
}

function upload_content_file($DestPath, $input_field)
{
	global $ReplaceFile;
	$StatusMessage = "failed!";
	$ActualFileName = "";	
	$FileObject = $_FILES["upload_file"];																
	$type = substr ($FileObject['name'] ,strlen($FileObject['name'])-4, 4);
	$ftype = false;
	if($input_field == "mapFile"){
		if (strcasecmp($type, '.gpx') == 0)
			$ftype = true;
		if (strcasecmp($type, '.kml') == 0)
			$ftype = true;
		if (strcasecmp($type, '.kmz') == 0)
			$ftype = true;
		$StatusMessage = "Error! No valid file, only GPX or KML";		
	} 
	else if($input_field == "mapMarkerURL"){
		if (strcasecmp($type, '.jpg') == 0)
			$ftype = true;
		if (strcasecmp($type, '.png') == 0)
			$ftype = true;
		if (strcasecmp($type, '.gif') == 0)
			$ftype = true;
		$StatusMessage = "Error! No valid file, only JPG, PNG or GIF";		
	}
	if(!isset($FileObject) || $FileObject["size"]<=0 || !$ftype)
	{		
		ShowPopUp($StatusMessage);

		?>
		<script language="javascript" type="text/javascript">	
			tinyMCEPopup.close();
		</script>
		<?php
	}	
	else
	{	
		$ActualFileName = $DestPath . "/" . $FileObject['name'];										
		$StatusMessage = $ActualFileName;
		move_uploaded_file($FileObject['tmp_name'], $ActualFileName);
		$StatusMessage =  "File: " . $FileObject['name'] . " has been successfully uploaded!";		
		$upload_d = wp_upload_dir();
		$upload_dir = $upload_d['basedir'] ;
		$upload_url = $upload_d['baseurl'] ;
		$ActualFileName = $upload_url  . '/' .$FileObject['name'];	
		
		CloseWindow($ActualFileName);
	}	
}


function ShowPopUp($PopupText)
{
	echo "<script type=\"text/javascript\" language=\"javascript\">alert (\"$PopupText\");</script>
	";
}

function CloseWindow($ItemValue)
{
	?>
	<script language="javascript" type="text/javascript">	
		FileBrowserDialogue.mySubmit('<?php echo $ItemValue; ?>');
	</script>
	<?php
}
?>

