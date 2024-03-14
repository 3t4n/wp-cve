<?php

include(__DIR__.'/elements/documentation.php');

	echo "<input type='hidden' id='cgGetVersionForUrlJs' value='".cg_get_version()."' />";

    ###NORMAL###
    if(!empty($cgProVersion)){// check with no empty!
        include('normal/download-proper-pro-version-info-general-headers-area.php');
    }
    ###NORMAL-END###

cg_shortcode_interval_configuration_container($GalleryID,$cgProFalse);

	echo "<div  id='cg_shortcode_table' class='cg_shortcode_table cg_do_not_remove_when_ajax_load'>";

if(empty($GalleryName)){$GalleryName="";}
	
		$versionColor = "#444";

if(empty($cgProVersionLink)){
    $cgProVersionLink = '';
}
$galeryNR = $GalleryID;

include("nav-shortcode.php");

	echo "</div>";
	echo "</div>";
	
	echo "<table  class='cg_do_not_remove_when_ajax_load' style='background-color:#ffffff;padding:15px;width:100%; box-shadow: 2px 4px 12px rgba(0,0,0,.08);border-radius:8px;' >";
	echo "<tr>";
	echo "<td align='center'><div><a href='?page=".cg_get_version()."/index.php&option_id=$GalleryID&edit_gallery=true' class='cg_load_backend_link cg_load_backend_link_back_to_gallery' ><input class='cg_backend_button cg_backend_button_back'  type='submit' value='Back to gallery'  /></a><br/></div></td>";
	echo "<td align='center'><div><a id='cgEditOptionsButton' href='?page=".cg_get_version()."/index.php&edit_options=true&option_id=$GalleryID' class='cg_load_backend_link'><input type='submit' class='cg_backend_button cg_backend_button_general'  value='Edit options'  /></a><br/></div></td>";
	echo "<td align='center'><div><a href='?page=".cg_get_version()."/index.php&option_id=$GalleryID&define_upload=true' class='cg_load_backend_link'><input type='submit' class='cg_backend_button cg_backend_button_general'  value='Edit contact form' /></form><br/></div></td>";
	echo "<td align='center'><div>";
		echo "<a href='?page=".cg_get_version()."/index.php&create_user_form=true&option_id=$GalleryID' class='cg_load_backend_link'><input type='hidden' name='option_id' value='$GalleryID'><input class='cg_backend_button cg_backend_button_general'  type='submit' value='Edit registration form'  /></a>";

	echo "</div></td>"; 
	
	echo "</tr>";
	
	echo "</table>";

    if(!empty($isEditOptions)){
        include('nav-users-management-with-status-and-repair.php');
    }else{
        include('nav-users-management.php');
    }


?>