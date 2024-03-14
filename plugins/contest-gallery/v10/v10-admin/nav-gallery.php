<?php
	
	global $wpdb;
	$tablename = $wpdb->prefix."contest_gal1ery";
	//$proUploads = $wpdb->get_var( "SELECT COUNT(*) FROM $tablename WHERE id > '0' ");

	if(!get_option("p_cgal1ery_reminder_time")){
		add_option( "p_cgal1ery_reminder_time", time() );
	}

cg_shortcode_interval_configuration_container($GalleryID,$cgProFalse);

cg_add_fields_pressed_after_content_modification($GalleryID);

echo "<div id='cgDocumentation' class='cg_do_not_remove_when_ajax_load'>";
echo "<a href='https://www.contest-gallery.com/documentation/' target='_blank'><span>";
echo "Contest Gallery documentation";
echo "</span></a>";
echo "</div>";
echo "<input type='hidden' id='cgGetVersionForUrlJs' value='".cg_get_version()."' />";

###NORMAL###
if(!empty($cgProVersion)){// check with no empty!
    include('normal/download-proper-pro-version-info-general-headers-area.php');
}
###NORMAL-END###

echo "<div id='cg_shortcode_table' class='cg_shortcode_table cg_do_not_remove_when_ajax_load'>";

	if(empty($GalleryName)){$GalleryName="";}
		$versionColor = "#444";

if(empty($cgProVersionLink)){
    $cgProVersionLink = '';
}

$galeryNR = $GalleryID;

    include("nav-shortcode.php");

echo "</div>";


if(intval($galleryDbVersion)>=21){
    if(get_post_status( $optionsSQL->WpPageParent ) == 'trash'){
        echo "<a href='".get_bloginfo('wpurl') . "/wp-admin/edit.php?post_status=trash&post_type=contest-gallery' target='_blank' class='cg_entry_page_url' style='margin-bottom: 15px;'>";
        echo "cg_gallery page <b>moved to trash</b> - can be restored";
        echo "</a>";
    }else{
        $permalink = get_permalink($optionsSQL->WpPageParent);
        if($permalink===false){
            echo "<a href='#' target='_blank' class='cg_entry_page_url cg_disabled_background_color_e0e0e0' style='margin-bottom: 15px;'>";
            echo "cg_gallery page <b>deleted</b> - can be corrected in \"Edit options\" >>> \"Status, repair...\"";
            echo "</a>";
        }else{
/*            echo "<a href='".$permalink."' target='_blank' class='cg_entry_page_url' style='margin-bottom: 15px;'>";
            echo "cg_gallery";
            echo "</a>";*/
        }
    }
}

if(intval($galleryDbVersion)>=21){
    if(get_post_status( $optionsSQL->WpPageParentUser ) == 'trash'){
        echo "<a href='".get_bloginfo('wpurl') . "/wp-admin/edit.php?post_status=trash&post_type=contest-gallery' target='_blank' class='cg_entry_page_url' style='margin-bottom: 15px;'>";
        echo "cg_gallery_user page <b>moved to trash</b> - can be restored";
        echo "</a>";
    }else{
        $permalink = get_permalink($optionsSQL->WpPageParentUser);
        if($permalink===false){
            echo "<a href='#' target='_blank' class='cg_entry_page_url cg_disabled_background_color_e0e0e0' style='margin-bottom: 15px;'>";
            echo "cg_gallery_user page <b>deleted</b> - can be corrected in \"Edit options\" >>> \"Status, repair...\"";
            echo "</a>";
        }else{
/*            echo "<a href='".$permalink."' target='_blank' class='cg_entry_page_url' style='margin-bottom: 15px;'>";
            echo "cg_gallery_user";
            echo "</a>";*/
        }
    }
}

if(intval($galleryDbVersion)>=21){
    if(get_post_status( $optionsSQL->WpPageParentNoVoting ) == 'trash'){
        echo "<a href='".get_bloginfo('wpurl') . "/wp-admin/edit.php?post_status=trash&post_type=contest-gallery' target='_blank' class='cg_entry_page_url' style='margin-bottom: 15px;'>";
        echo "cg_gallery_no_voting page <b>moved to trash</b> - can be restored";
        echo "</a>";
    }else{
        $permalink = get_permalink($optionsSQL->WpPageParentNoVoting);
        if($permalink===false){
            echo "<a href='#' target='_blank' class='cg_entry_page_url cg_disabled_background_color_e0e0e0' style='margin-bottom: 15px;'>";
            echo "cg_gallery_no_voting page <b>deleted</b> - can be corrected in \"Edit options\" >>> \"Status, repair...\"";
            echo "</a>";
        }else{
/*            echo "<a href='".$permalink."' target='_blank' class='cg_entry_page_url' style='margin-bottom: 15px;'>";
            echo "cg_gallery_no_voting";
            echo "</a>";*/
        }
    }
}

if(intval($galleryDbVersion)>=21){
    if(get_post_status( $optionsSQL->WpPageParentWinner ) == 'trash'){
        echo "<a href='".get_bloginfo('wpurl') . "/wp-admin/edit.php?post_status=trash&post_type=contest-gallery' target='_blank' class='cg_entry_page_url' style='margin-bottom: 15px;'>";
        echo "cg_gallery_winner page <b>moved to trash</b> - can be restored";
        echo "</a>";
    }else{
        $permalink = get_permalink($optionsSQL->WpPageParentWinner);
        if($permalink===false){
            echo "<a href='#' target='_blank' class='cg_entry_page_url cg_disabled_background_color_e0e0e0' style='margin-bottom: 15px;'>";
            echo "cg_gallery_winner page <b>deleted</b> - can be corrected in \"Edit options\" >>> \"Status, repair...\"";
            echo "</a>";
        }else{
/*            echo "<a href='".$permalink."' target='_blank' class='cg_entry_page_url' style='margin-bottom: 15px;'>";
            echo "cg_gallery_winner";
            echo "</a>";*/
        }
    }
}

	echo "</div>";

	//fef050 fcd729
	echo "<table class='cg_do_not_remove_when_ajax_load' style='background-color:#ffffff;padding:15px 0;width:100%;box-shadow: 2px 4px 12px rgba(0,0,0,.08);border-radius:8px;' >";
	echo "<tr>";
	echo "<td align='center'><div><a href='?page=".cg_get_version()."/index.php'  class='cg_load_backend_link cg_load_backend_link_back_to_menu'><input class='cg_backend_button cg_backend_button_back ' type='button' value='Back to menu' ></a></div></td>";
	echo "<td align='center'><div><a id='cgEditOptionsButton' href='?page=".cg_get_version()."/index.php&edit_options=true&option_id=$GalleryID' class='cg_load_backend_link'><input type='button' class='cg_backend_button cg_backend_button_general' value='Edit options' /></a></div></td>";
	echo "<td align='center'><div><a href='?page=".cg_get_version()."/index.php&define_upload=true&option_id=$GalleryID'  class='cg_load_backend_link'><input type='button' class='cg_backend_button cg_backend_button_general' value='Edit contact form'  /></a></div></td>";
	echo "<td align='center'><div>";

		//echo "<form method='POST' action='?page=".cg_get_version()."/index.php&create_user_form=true&option_id=$GalleryID'><input type='hidden' name='option_id' value='$GalleryID'><input type='submit' value='PRO users management' style='text-align:center;width:180px;background:linear-gradient(0deg, #ffbe4e 50%, #ffbe4e 50%);' /></form><br/>";
		echo "<a href='?page=".cg_get_version()."/index.php&create_user_form=true&option_id=$GalleryID'  class='cg_load_backend_link'><input class='cg_backend_button cg_backend_button_general'  type='button' value='Edit registration form'  /></a>";
		


	echo "</div></td>"; 
	echo "</tr>";
	
	echo "</table>";


    if(!empty($isEditOptions)){
        include('nav-users-management-with-status-and-repair.php');
    }else{
        include('nav-users-management.php');
    }


?>