<?php

    $GalleryID = @$_GET['option_id'];
    $cgImageId = @$_GET['cg_image_id'];
    $wpImageId = @$_GET['cg_image_wp_id'];

	// Tabellennamen ermitteln, GalleryID wurde als Shortcode bereits �bermittelt.
	global $wpdb;

	$tablenameOptions = $wpdb->prefix."contest_gal1ery_options";
	$tablename = $wpdb->prefix."contest_gal1ery";
    $table_posts = $wpdb->prefix."posts";

    $cgVersion = $wpdb->get_var("SELECT Version FROM $tablenameOptions WHERE id = '$GalleryID'");
    $wpImage = $wpdb->get_row("SELECT * FROM $table_posts WHERE ID = '$wpImageId'");
    $cgImage = $wpdb->get_row("SELECT * FROM $tablename WHERE id = '$cgImageId'");

    $wpImageUrl = $wpImage->guid;

    $content_url = wp_upload_dir();
    $content_url = $content_url['baseurl']; // Pfad zum Bilderordner angeben

    $thumbUrl = wp_get_attachment_image_src($wpImageId, 'large');
    $thumbUrl = $thumbUrl[0];


echo "<div id='cg_rotate_image' style='padding: 20px 20px;'>";


echo "<form action='?page=".cg_get_version()."/index.php&option_id=$GalleryID&edit_gallery=true' method='POST' data-cg-submit-message='Changes Saved' class='cg_load_backend_submit'>";
echo "<input type='hidden' name='cg_image_rotate_save_values' value='true' >";
echo "<input type='hidden' name='cg_image_rotate_id' value='$cgImageId' >";
/*
echo "<h2>Gallery view original image source:</h2>";
echo "<div id='cgRotateSource'><img src='".plugins_url()."/contest-gallery/v10/v10-admin/gallery/rotate_icon.svg'>";
echo "</div>";
echo "<a id='cgResetSource' href='#cgResetSource'>Reset to standard</a>";

/*$mainSourceContainerHeight = '';

if($cgImage->Width >= $cgImage->Height){
 //   $mainSourceContainerHeight = $cgImage->Width;
}
else{
   // $mainSourceContainerHeight = $cgImage->Height;
}*/
/*
echo "<div id='cgImgSourceContainerMain' >";
echo "<div id='cgImgSourceContainer'>";
echo "<input type='hidden' id='rSource' name='rSource' value='0' >";
echo "<img id='cgImgSource' class='cg".$cgImage->rSource."degree' src='$wpImageUrl' />";
echo "</div>";
echo "</div>";*/

echo "<h2 style='margin-bottom: 0;'>Image rotation gallery view:</h2>";
echo "<p style='margin-top: 5px;margin-bottom:15px;' ><b>NOTE:</b> Image rotation is CSS based. The original image source will not be rotated.</p>";
echo "<div id='cgRotateThumb'><img src='".plugins_url()."/".cg_get_version()."/v10/v10-css/rotate_icon.svg'>";
echo "</div>";
echo "<input type='button' id='cgResetThumb' class='cg_backend_button_gallery_action' value='Reset to original'/>";
echo "<div id='cgImgThumbContainerMain' >";
echo "<div id='cgImgThumbContainer'>";
echo "<input type='hidden' id='rThumb' name='rThumb' value='".$cgImage->rThumb."' >";
echo "<img id='cgImgThumb' src='$thumbUrl' class='cg".$cgImage->rThumb."degree' style='max-width:300px;'/>";
echo "</div>";
echo "</div>";


echo "<div id='cg_rotate_save_changes'>";

echo '<input class="cg_backend_button cg_backend_button_gallery_action" type="submit" name="submit" value="Save changes and go back to gallery" id="cg_gallery_backend_submit">';


echo "</div>";

echo "</form>";

echo "<div id='cg_rotate_do_not_save_changes'>";


?>