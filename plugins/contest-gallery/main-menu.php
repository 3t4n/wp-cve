<?php
if(!defined('ABSPATH')){exit;}

$siteURL = get_site_url()."/wp-admin/admin.php";
$uploadFolder = wp_upload_dir();

if(file_exists($uploadFolder['basedir'] . '/contest-gallery/cg-copying-gallery.txt')){
    unlink($uploadFolder['basedir'] . '/contest-gallery/cg-copying-gallery.txt');
}

$permalinkURL = get_site_url()."/wp-admin/admin.php";

global $wpdb;

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablename_options = $wpdb->prefix . "contest_gal1ery_options";

$cg_order = 'DESC';
if(!empty($_GET['cg_order']) && $_GET['cg_order']=='asc'){
    $cg_order = 'ASC';
}

$selectSQL = $wpdb->get_results( "SELECT * FROM $tablename_options ORDER BY id $cg_order" );

$imagesTotal = $wpdb->get_results(
    "SELECT GalleryID,COUNT(*) as count FROM $tablename GROUP BY GalleryID ORDER BY count DESC;"
);

$imagesActive = $wpdb->get_results(
    "SELECT GalleryID,COUNT(*) as count FROM $tablename WHERE Active = 1 GROUP BY GalleryID ORDER BY count DESC;"
);

$imagesActiveAndTotalSortedByGallery = array();

foreach ($imagesTotal as $image){

    if(empty($imagesActiveAndTotalSortedByGallery[$image->GalleryID])){
        $imagesActiveAndTotalSortedByGallery[$image->GalleryID] = array();
    }
    $imagesActiveAndTotalSortedByGallery[$image->GalleryID]['total'] = $image->count;

}

foreach ($imagesActive as $image){

    if(empty($imagesActiveAndTotalSortedByGallery[$image->GalleryID])){
        $imagesActiveAndTotalSortedByGallery[$image->GalleryID] = array();
    }
    $imagesActiveAndTotalSortedByGallery[$image->GalleryID]['active'] = $image->count;

}

$arrayNew = array(
    '824f6b8e4d606614588aa97eb8860b7e',
    'add4012c56f21126ba5a58c9d3cffcd7',
    'bfc5247f508f427b8099d17281ecd0f6',
    'a29de784fb7699c11bf21e901be66f4e',
    'e5a8cb2f536861778aaa2f5064579e29',
    '36d317c7fef770852b4ccf420855b07b'
);

echo "<input type='hidden' id='cgGetVersionForUrlJs' value='".cg_get_version()."' />";

$cgPro = false;
$cgProStyle = '';
// Check start von hier:
$p_cgal1ery_reg_code = get_option("p_cgal1ery_reg_code");
$p_c1_k_g_r_8 = get_option("p_c1_k_g_r_9");

if((!empty($p_cgal1ery_reg_code) AND $p_cgal1ery_reg_code!='1') OR (!empty($p_c1_k_g_r_8) AND $p_c1_k_g_r_8!='1')){
    $cgPro = true;
    $cgProStyle = 'width: 169px;';
}

if($cgPro){
    if(!file_exists($uploadFolder['basedir'].'/contest-gallery/changes-messages-frontend/pro-check.txt')){
        if (!is_dir($uploadFolder['basedir'].'/contest-gallery/changes-messages-frontend')) {
            mkdir($uploadFolder['basedir'].'/contest-gallery/changes-messages-frontend', 0755);
        }
        file_put_contents($uploadFolder['basedir'].'/contest-gallery/changes-messages-frontend/pro-check.txt','true');
    }
}

echo '<div id="cgMainMenuTable">';

echo "<div id='cgDocumentation' style='width:100%;'>";
echo "<a href='https://www.contest-gallery.com/documentation/' target='_blank'><span>";
echo "Contest Gallery documentation";
echo "</span></a>";
echo "</div>";
echo "<table style='box-shadow: 2px 4px 12px rgba(0,0,0,.08);border-radius:8px;background-color:#ffffff;width:100%;' >";
echo "<tr>";
$cgLink = '';
if($cgPro){
    $cgPro = '<br><a href="https://www.contest-gallery.com" target="_blank">www.contest-gallery.com</a>';
}
echo "<td style='padding:5px 0 5px 20px;overflow:hidden;$cgProStyle'><p style='display:inline;font-size: 13px;font-weight: bold;'>Contest Gallery$cgPro</p></td>";


if($cgPro){
    // echo "<td style='padding-left:23px;overflow:hidden;'><p style='display:inline;font-size: 13px;font-weight: bold;'>You are using PRO version. For any issues on PRO version please contact <a href='mailto:support-pro@contest-gallery.com' target='_blank'>support-pro@contest-gallery.com</a></p></td>";
}
else{
    echo "<td style='padding-left:80px;overflow:hidden;text-align: right; padding-right: 33px;'><p style='display:inline;font-size: 13px;font-weight: bold;'>PRO version key enter: <a href='https://www.contest-gallery.com/pro-version-area/' target='_blank'>www.contest-gallery.com/pro-version-area</a></p></td>";
}
echo "</tr>";

###NORMAL###
if($cgPro){
    $plugin_data = get_plugin_data( __DIR__.'/index.php' );
    $plugin_version = $plugin_data['Version'];


    $keyToSend = '';

    $p_cgal1ery_reg_code = get_option("p_cgal1ery_reg_code");
    $p_c1_k_g_r_8_real = get_option("p_c1_k_g_r_9");
    $p_c1_k_g_r_8 = md5($p_c1_k_g_r_8_real);


    $arrayNew = array(
        '824f6b8e4d606614588aa97eb8860b7e',
        'add4012c56f21126ba5a58c9d3cffcd7',
        'bfc5247f508f427b8099d17281ecd0f6',
        'a29de784fb7699c11bf21e901be66f4e',
        'e5a8cb2f536861778aaa2f5064579e29',
        '36d317c7fef770852b4ccf420855b07b'
    );

    if(in_array($p_c1_k_g_r_8, $arrayNew)){
        $keyToSend = $p_c1_k_g_r_8_real;
    }else{
        $keyToSend = $p_cgal1ery_reg_code;
    }

    /*  $p_cgal1ery_pro_version_main_key_to_show = '';
      if(strlen($keyToSend)>3){
          foreach (str_split(substr($keyToSend,0,strlen($keyToSend)-3)) as $value){
              $p_cgal1ery_pro_version_main_key_to_show .= 'x';
          }
          $p_cgal1ery_pro_version_main_key_to_show .= substr($keyToSend,strlen($keyToSend)-3,3);
      }*/

    $cgLinkTextForProVersionNote = '<a href="https://www.contest-gallery.com/pro-version-area/?key='.$keyToSend.'&current-version='.$plugin_version.'&upgrade-from-normal-version=true" target="_blank">www.contest-gallery.com/pro-version-area</a>';
    echo '<tr><td style="padding:5px 20px 5px 20px;position:relative;" colspan="2"><div id="cgDownloadProperProVersionInfoMainMenu">In order to continue to use PRO version functions<br> you require to change your PRO version.<br> Please do it here:<br>'.$cgLinkTextForProVersionNote.'<br><span style="font-weight:normal;">(It will take you two minutes)</span></div></td></tr>';
}
###NORMAL-END###

echo "</table>";
echo "<br/>";

if (!empty($_GET['option_id']) AND !empty($_POST['cg_delete_gallery'])) {

    echo "<p id='cg_changes_saved' style='font-size:18px;'><strong>Gallery deleted</strong></p>";

}

// Die nexte ID des Option Tables ermitteln
$last = $wpdb->get_row("SHOW TABLE STATUS LIKE '$tablename_options'");
$nextID = $last->Auto_increment;

$cg_order_desc_selected = '';
$cg_order_asc_selected = '';

if($cg_order=='DESC'){
    $cg_order_desc_selected = 'selected';
}

if($cg_order=='ASC'){
    $cg_order_asc_selected = 'selected';
}


if(count($selectSQL)){
    echo "<div id='cgViewControl' class='cg-main-menu'>
<div class='cg_order'><select id='cgOrderSelect'>
<option value='desc' $cg_order_desc_selected>id descend (order)</option>
<option value='asc' $cg_order_asc_selected>id ascend (order)</option>
</select>
<a href='?page=".cg_get_version()."/index.php&cg_order=desc'  class='cg_load_backend_link cg_hide cg_load_main_menu_desc'></a>
<a href='?page=".cg_get_version()."/index.php&cg_order=asc'  class='cg_load_backend_link cg_hide cg_load_main_menu_asc'></a>
</div>";
    echo "<div style='margin-left: auto;padding-right: 20px;'>";
    echo '<form action="?page='.cg_get_version().'/index.php&option_id='.$nextID.'&edit_gallery=true" method="POST" class="cg_load_backend_submit cg_load_backend_create_gallery" >';
    echo '<input type="hidden" name="cg_create" value="true">';
    echo '<input type="hidden" name="option_id" value="'.$nextID.'">';
    echo '<input type="hidden" name="create" value="true">
<input type="hidden" name="page" value="'.cg_get_version().'/index.php">
    <input class=\'cg_backend_button cg_button_new_gallery\' name="" value="New gallery" type="Submit"></form>';
    echo "</div>";
    echo "</div>";
}else{
    echo '<form action="?page='.cg_get_version().'/index.php&option_id='.$nextID.'&edit_gallery=true" method="POST" class="cg_load_backend_submit cg_load_backend_create_gallery" >';
    echo '<input type="hidden" name="cg_create" value="true">';
    echo '<input type="hidden" name="option_id" value="'.$nextID.'">';
    echo '<input type="hidden" name="create" value="true">
<input type="hidden" name="page" value="'.cg_get_version().'/index.php">';
    echo "<button type='submit'  id='cgCreateFirstGalleryButton'>
                <div class='cg_sub_2'>Add entries, edit contact form, see available shortcodes</div>
                <div class='cg_sub_1'>Create new gallery</div>
                <div class='cg_sub_3'></div>
            </button>";
    echo '</form>';
}

$unix = time();

foreach($selectSQL as $value){

    $option_id = $value -> id;
        $galleryDbVersion = $value->Version;
    $GalleryName = $value -> GalleryName;
    $ContestEnd = $value->ContestEnd;
        if(floatval($galleryDbVersion)>=21.1){
            $ContestEnd = 0;
        }
    $ContestEndTime = $value->ContestEndTime;
    $Version = $value->Version;
    $FbLike = $value->FbLike;
    $AllowRating = $value->AllowRating;

    $cgCopyForV14ExplanationRequired = 0;
    if(intval($galleryDbVersion)<14){
        $cgCopyForV14ExplanationRequired = 1;
    }

        if(intval($galleryDbVersion)>=21){
            $Version = $value->VersionDecimal;
        }else{
            $Version = intval($value->Version);
        }

    if ($option_id % 2 != 0) {
        $backgroundColor = "#DFDFDF";
    } else {
        $backgroundColor = "#ECECEC";
    }

    echo "<div class='table_gallery_info'>";

    $phpDateOffset = date('Z');
    echo "<input type='hidden' id='cgPhpDateOffset' value='$phpDateOffset'>";

    if($GalleryName){$GalleryName="<strong>$GalleryName</strong>";}
    else {$GalleryName="";}


    echo "<div class='td_gallery_info_content'>";

    echo "<div class='td_gallery_info_name'><p>Gallery name<br>$GalleryName</p><a class='cg_load_backend_link' href=\"?page=".cg_get_version()."/index.php&edit_options=true&option_id=".$option_id."&cg_go_to=cgEditGalleryNameLink\" ><div class='td_gallery_info_name_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div></a></div>";
    echo "<div class='td_gallery_info_shortcode'><p>Gallery shortcode<br><strong><span class='td_gallery_info_name_span'>[cg_gallery id=\"".$option_id."\"]</span></strong></p><div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div></div>";
    // echo "<div class='cg_shortcode_parent tg_gallery_info_shortcode'>Shortcode: <span class='cg_main_menu_shortcode cg_shortcode_copy_text'>[cg_gallery id=\"".$option_id."\"]</span><div class=\"cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip\"></div></div>";

    echo "</div>";

    $imagesTotal = 0;
    $imagesActive = 0;

    if(!empty($imagesActiveAndTotalSortedByGallery[$option_id])){
        $imagesTotal = $imagesActiveAndTotalSortedByGallery[$option_id]['total'];
        if(!empty($imagesActiveAndTotalSortedByGallery[$option_id]['active'])){
            $imagesActive = $imagesActiveAndTotalSortedByGallery[$option_id]['active'];
        }
    }

    echo "<div class='td_gallery_info_buttons'>";

    echo "<div class='td_gallery_info'>";

    echo "<div class='td_gallery_info_label'>Total files <strong>".$imagesTotal."</strong></div>";
    echo "<div class='td_gallery_info_label'>Activated files <strong>".$imagesActive."</strong></div>";

    if(($AllowRating==1 OR ($AllowRating>=12 && $AllowRating<=20)) OR $AllowRating==2 OR $FbLike==1){

        $VotingConfigurationString = '';

        if($AllowRating==2){
            $VotingConfigurationString = 'via 1 star';
        }

        if($AllowRating==1 OR ($AllowRating>=12 && $AllowRating<=20)){
            $VotingConfigurationString = 'via multiple stars';
        }

        if($FbLike==1){
            if(!empty($VotingConfigurationString)){
                $VotingConfigurationString .= ', via FbLike button';
            }else{
                $VotingConfigurationString = 'via FbLike button';
            }
        }

        echo "<div class='td_gallery_info_label'>Voting <strong>$VotingConfigurationString</strong></div>";

    }

    if((($unix > $ContestEndTime && $ContestEnd == 1) or $ContestEnd == 2) && intval($Version)<10){
        echo "<div class='td_gallery_info_label'><strong>contest ended</strong></div>";
    }

    if((($unix>=$ContestEndTime && $ContestEnd==1) OR $ContestEnd==2) && intval($Version)>=10){
        echo "<div  id='cgContestEnded$option_id' class='td_gallery_info_label cg-contest-ended'><input type='hidden' class='cg-contest-end-time' value='$ContestEndTime'><strong>contest ended</strong></div>";
    }

    echo "</div>";


    echo "<div class='td_gallery_buttons'>";

    // EDIT GALLERY

    echo '<div class="cg_button_edit"><p><a href="?page='.cg_get_version().'/index.php&option_id='.$option_id.'&edit_gallery=true" class="cg_load_backend_link" ><input class=\'cg_backend_button\' name="" value="Edit gallery" type="button" ></a></p></div>';


    // COPY GALLERY

    // NO cg_load_backend_submit class here for form!!!!
    echo '<div class="cg_button_copy"><p><form action="?page='.cg_get_version().'/index.php&edit_gallery=true" method="POST" class="cg_load_backend_copy_gallery" >';
    echo '<input type="hidden" name="cg_copy" value="true" >';
    echo '<input type="hidden" name="cg_copy_id" value="'.$option_id.'" >';
    echo '<input type="hidden" name="cg_copy_start" class="cg_copy_start" value="0" >';
    echo '<input type="hidden" name="option_id_next_gallery" class="option_id_next_gallery" value="0" >';
    echo '<input type="hidden" name="id_to_copy" value="'.$option_id.'" >';
    echo '<input type="hidden" name="edit_gallery_hidden_post" >';

    if(intval($Version)<7){
        echo '<input type="hidden" name="copy_v7" value="true" >';
        $cgCheckCopy = 'cgCheckCopyPrevV7';
        $cg_copy_submit = 'cg_backend_button cg_copy_submit';
    }else{
        $cgCheckCopy = 'cgCheckCopy';
        $cg_copy_submit = 'cg_backend_button cg_copy_submit';
    }

    $prevV7text = '';

    if(intval($Version)<7){
        $prevV7text = '<div class="cg-copy-prev-7-text cg_hide"><br><a href="https://www.contest-gallery.com/copy-galleries-created-before-version-7-with-images-new/" target="_blank">Copying galleries created before version 7 might need some server configuration</a><br><br></div>';
    }

    echo $prevV7text;

    echo '<input type="hidden" name="page" value="'.cg_get_version().'/index.php"><input name="" value="Copy gallery" type="Submit" id="cgCopySubmit'.$option_id.'" class="'.$cg_copy_submit.'" data-cg-version-to-copy="'.(intval($Version)).'"
        data-cg-copy-fb-on="'.$FbLike.'" data-cg-copy-for-v14-explanation="'.$cgCopyForV14ExplanationRequired.'" data-cg-copy-id="'.$option_id.'"></form></p></div>';

    // DELETE GALLERY

    echo '<div class="cg_button_delete"><p><form action="?page='.cg_get_version().'/index.php" method="GET"  class="cg_load_backend_submit" >
            <input type="hidden" name="option_id" value="'.$option_id.'">';
    echo '<input  type="hidden" name="cg_delete_gallery" value="true"><input class=\'cg_backend_button\' type="button" value="Delete gallery" onClick="return cgJsClassAdmin.mainMenu.functions.cgCheckDelete('.$option_id.','.(intval($Version)).',this)"></form></p></div>';

    echo "</div>";
    echo "</div>";

    echo "</div>";

    $option_id++;
}

echo "<br/>";


echo '</div>';

echo "<div id='cgCopyMessageContainer' class='cg_hide'>";

echo "<div id='cgCopyMessageDiv'>";

echo "<div id='cgCopyMessageClose'>";


echo "</div>";



echo "<div id='cgCopyMessageContent'>";

echo "<p id='cgCopyMessageContentHeader'>";
echo "Copy Gallery ID 88 ?";
echo "</p>";
echo "<p class='cg_copy_type_options_container'>";
echo "<input type='radio' class='cg_copy_type' id='cg_copy_type_options' name='cg_copy_type' checked value='cg_copy_type_options' />";
echo "<label for='cg_copy_type_options'>Copy options and forms only</label>";
echo "</p>";
echo "<p class='cg_copy_type_options_and_images_container'>";
echo "<input type='radio' class='cg_copy_type' id='cg_copy_type_options_and_images' name='cg_copy_type' value='cg_copy_type_options_and_images' />";
echo "<label for='cg_copy_type_options_and_images'>Copy files, options and forms</label>";
echo "</p>";
echo "<p class='cg_copy_type_all_container'>";
echo "<input type='radio' class='cg_copy_type' id='cg_copy_type_all' name='cg_copy_type' value='cg_copy_type_all' />";
echo "<label for='cg_copy_type_all' class='cg_copy_type_all_label'>Copy everything (options, forms, files, votes and comments)
<span id='cg_copy_for_v14_explanation' class='cg_hide'><br><br><strong><span class='cg_color_red'>NOTE:</span> \"Registration options\", \"Login options\" and \"Registration form\"  will be not copied. For new galleries created or copied since plugin version 14 \"Registration options\", \"Login options\" and \"Registration form\" are general for all galleries.</strong><br></span>
<span id='cg_copy_type_all_fb_hint' class='cg_hide'><br><strong> Facebook likes and shares will be not copied</strong></span>
</label>";
echo "</p>";
echo "<p  id=\"cgCopyMessageSubmitContainer\" >";
echo '<input class=\'cg_backend_button cg_backend_button_gallery_action\' value="Copy" type="button" id="cgCopyMessageSubmit" data-cg-copy-id="" style="text-align:center;width:70px;background:linear-gradient(0deg, #f1f1f1 50%, #f1f1f1 50%);margin-left:auto;">';
echo "</p>";

echo "</div>";


echo "</div>";
echo "</div>";

echo "<div id='cgCopyInProgressOnSubmit' class='cg_hide'>";
echo "<h2>In progress ...</h2>";
echo "<p><strong>Do not cancel</strong></p>";
echo "</div>";



?>