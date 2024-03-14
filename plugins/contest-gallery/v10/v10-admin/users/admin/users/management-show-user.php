<?php

//include('get-data-management.php');

global $wpdb;

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablename_contest_gal1ery_options = $wpdb->prefix . "contest_gal1ery_options";
$tablename_contest_gal1ery_create_user_entries = $wpdb->prefix . "contest_gal1ery_create_user_entries";
$tablename_contest_gal1ery_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";
$tablenameCreateUserForm = $wpdb->prefix . "contest_gal1ery_create_user_form";
$table_usermeta = $wpdb->base_prefix . "usermeta";
$wpUsers = $wpdb->base_prefix . "users";

$is_frontend = true;
include(__DIR__ . "/../../../../../check-language-general.php");

$wpUserId = absint($_GET['wp_user_id']);

if(!current_user_can('manage_options')){
    echo "Logged in user have to be able to manage_options to edit user data.";die;
}

if(!empty($_POST['cg_input_image_upload_file_to_delete_wp_id'])){// then image must be removed!
    $WpProfileImage = $wpdb->get_row($wpdb->prepare("SELECT WpUpload, WpUserId FROM $tablename WHERE WpUserId = %d && IsProfileImage = 1",[$wpUserId]));

    $wpdb->query($wpdb->prepare(
        "
        DELETE FROM $tablename WHERE WpUserId = %d && IsProfileImage = %d 
    ",
        $wpUserId, 1
    ));
    // source and database _posts table entry  will be deleted
    wp_delete_attachment($WpProfileImage->WpUpload);
}

/*Save user data here*/
if(isset($_POST['get-data-management'])){
    include("get-data-management.php");
    echo "<div id='cg_changes_saved' style='font-size:18px;'>Data saved<br><br></div>";
}

$userEntries = [];
$userEntriesNew = [];

if(!empty($_GET['wp_user_meta_entries']) OR !empty($_POST['wp_user_meta_entries'])){
    $userEntriesNew = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $table_usermeta WHERE (user_id  = %d) AND (meta_key LIKE '%cg_custom_field_id_%')",[$wpUserId]));

    $selectUserForm = $wpdb->get_results("SELECT * FROM $tablenameCreateUserForm WHERE GeneralID = '1' && 
                (Field_Type = 'user-text-field' OR Field_Type = 'user-comment-field' OR Field_Type = 'user-select-field') 
        ORDER BY Field_Order ASC");
    $selectUserFormSorted = [];
    foreach($selectUserForm as $formField){
        $selectUserFormSorted['cg_custom_field_id_'.$formField->id] = $formField;
    }
}else{
    $userEntries = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_contest_gal1ery_create_user_entries WHERE wp_user_id = %d ORDER BY id ASC",[$wpUserId]));
}

/*echo "<pre>";
print_r($userEntries);
echo "</pre>";*/

$wpUserEntry = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $wpUsers WHERE ID = %d",[$wpUserId]));

$wpUserLogin = $wpUserEntry->user_login;
$wpUserEmail = $wpUserEntry->user_email;

$GalleryName = '';
$GalleryName = $wpdb->get_var($wpdb->prepare( "SELECT GalleryName FROM $tablename_contest_gal1ery_options WHERE id = %d",[$GalleryID]));

$GalleryNameOrId = '';
if($GalleryName){$GalleryNameOrId="$GalleryName";}
else {$GalleryNameOrId = "id $GalleryID";}


echo "<div style='width:100%;background-color:#fff;padding-bottom:15px;box-shadow: 2px 4px 12px rgba(0,0,0,.08);border-radius: 8px;' id='cg-search-results-container'>";
echo "<div  id='cgManagementShowUsers' style='clear:both;padding: 25px 20px 10px;'>";

echo "<form method='POST' action='?page=".cg_get_version()."/index.php&users_management=true&option_id=$GalleryID&wp_user_id=$wpUserId&edit_registration_entries=true'  data-cg-submit-message='Changes Saved' class='cg_load_backend_submit'>";
echo "<input type='hidden' name='get-data-management' value='true' >";

if(!empty($_GET['wp_user_meta_entries']) OR !empty($_POST['wp_user_meta_entries'])){
    echo "<input type='hidden' name='wp_user_meta_entries' value='true' >";
}

echo "<div style='margin-bottom:10px;' id='cg-user-$wpUserId'>";
echo "<div style='float:left;display:inline;width:50%;border-bottom: 1px dotted #DFDFDF;'><strong>Username</strong></div>";
echo "<div style='float:right;display:inline;width:50%;text-align:right;border-bottom: 1px dotted #DFDFDF;'><a href=".get_edit_user_link($wpUserId)."><button type=\"button\" class='cg-show-fields'>Edit Wordpress Profile</button></a></div>";
echo "<div>$wpUserLogin</div>";
echo "</div>";

echo "<div style='margin-bottom:10px;'>";
echo "<div style='float:left;display:block;width:100%;border-bottom: 1px dotted #DFDFDF;'><strong>User e-mail</strong></div>";
echo "<div>$wpUserEmail</div>";
echo "</div>";

$selectProfileImage = $wpdb->get_row("SELECT * FROM $tablenameCreateUserForm WHERE GeneralID = '1' && 
                (Field_Type = 'profile-image')");

if(!empty($selectProfileImage)){
    echo "<div style='margin-bottom:10px;'>";

        echo "<div style='display:inline-block;'>";
        echo "<strong>$selectProfileImage->Field_Name</strong>";
        echo "</div>";

        echo "<div>";

    $WpUpload = $wpdb->get_var($wpdb->prepare( "SELECT WpUpload FROM $tablename WHERE WpUserId = %d AND IsProfileImage = 1",[$wpUserId]));

        if(!empty($WpUpload)){
            echo "<input type='hidden' name='cg_input_image_upload_file_to_delete_wp_id' value='$WpUpload' id='cg_input_image_upload_file_to_delete_wp_id' disabled />";
            $imgSrcLarge=wp_get_attachment_image_src($WpUpload, 'large');
            if(!empty($imgSrcLarge)){
                $imgSrcLarge=$imgSrcLarge[0];
                echo "<div id='cg_input_image_upload_file_preview' >";
                    echo "<div id='cg_input_image_upload_file_preview_img' style='background: url($imgSrcLarge) no-repeat;'>";
                    echo "</div>";
                echo "</div>";
                echo "<input type='button' id='cg_input_image_upload_file_to_delete_button' class='button cg-button-remove-profile-image cg-button-remove-profile-image-management-show-user' value='$language_RemoveProfileImage'>";
            }
            echo '<div id="cg_profile_image_removed" class="cg_hide">...</div>';
        }else{
            echo '<div>...</div>';
        }

        echo "</div>";
    echo "</div>";

}

foreach($userEntriesNew as $entry){

    if(!empty($selectUserFormSorted[$entry->meta_key])){

        $fieldTitle = contest_gal1ery_convert_for_html_output($selectUserFormSorted[$entry->meta_key]->Field_Name);
        $fieldName = $entry->meta_key;
        $formFieldType = $selectUserFormSorted[$entry->meta_key]->Field_Type;
        $userFieldContent = contest_gal1ery_convert_for_html_output($entry->meta_value);

        echo "<div style='margin-bottom:10px;'>";

            echo "<div style='float:left;display:inline;'>";
            echo "<strong>$fieldTitle:</strong>";
            echo "</div>";

        echo "<div>";

        if($formFieldType=="user-text-field"){
            $userFieldContent = html_entity_decode(stripslashes($userFieldContent));
        }

        if($formFieldType=="user-comment-field"){
            echo "<textarea name='$fieldName' style='width:100%;height:100px;'>$userFieldContent</textarea>";
        }else{
            echo "<input type='text' name='$fieldName' value='$userFieldContent' style='width:100%;' />";
        }

        echo "</div>";
        echo "</div>";

    }

}

foreach($userEntries as $entry){

    $id = $entry->id;
    $formFieldId = $entry->f_input_id;
    $formFieldIdGalleryID = $entry->GalleryID;
    $formFieldType = $entry->Field_Type;
    $formFieldRow = $wpdb->get_row("SELECT * FROM $tablename_contest_gal1ery_create_user_form WHERE id = '$formFieldId'");
    $formFieldName = $formFieldRow->Field_Name;
    $formFieldRowContent = $formFieldRow->Field_Content;
    $formFieldChecked = $entry->Checked;
    $formFieldVersion = $entry->Version;
    $formFieldContent = html_entity_decode(stripslashes($entry->Field_Content));

    $checkAgreementBorder = ($formFieldType=='user-check-agreement-field') ? "border-bottom: 1px dotted #DFDFDF;" : "";

    echo "<div style='margin-bottom:10px;'>";
    if($formFieldType!="user-html-field" && $formFieldType!="user-robot-field" && $formFieldType!="main-user-name" && $formFieldType!="main-mail"){

        echo "<div style='float:left;display:inline;width:50%;$checkAgreementBorder'>";
        echo "<strong>$formFieldName:</strong>";
        echo "</div>";
        echo "<div style='float:right;display:inline;width:50%;text-align:right;$checkAgreementBorder'>";
        echo "<a href='?page=".cg_get_version()."/index.php&create_user_form=true&option_id=$formFieldIdGalleryID' ><button type=\"button\" class='cg-show-fields'>Gallery - $formFieldIdGalleryID</button></a>";

        echo "</div>";

    }


    echo "<div>";

    $userFieldContent = ($formFieldType=='user-check-agreement-field') ? $formFieldRowContent : $formFieldContent;
    $userFieldDisabled = ($formFieldType=='user-check-agreement-field') ? 'disabled' : '';

    if($formFieldType=="user-text-field"){
        $userFieldContent = html_entity_decode(stripslashes($userFieldContent));
        echo "<input type='text' name='Entry_Field_Content[$id]' value='$userFieldContent' style='width:100%;' />";
    }

    if($formFieldType=="user-select-field"){
        $userFieldContent = html_entity_decode(stripslashes($userFieldContent));
        echo "<input type='text' name='Entry_Field_Content[$id]' value='$userFieldContent' style='width:100%;' />";
    }

    if($formFieldType=="user-comment-field"){
        $userFieldContent = html_entity_decode(stripslashes($userFieldContent));
        echo "<textarea type='comment' name='Entry_Field_Content[$id]' style='width:100%;height:100px;'>$userFieldContent</textarea>";
    }
    if($formFieldType=="user-check-agreement-field"){
        $userFieldContent = html_entity_decode(stripslashes($userFieldContent));
        $checked = '';
        $checkedText = '';
        if($formFieldChecked==1 OR empty($formFieldVersion)){
            $checked = 'checked';
            $checkedText = 'checked';
        }else{
            $checked = '';
            $checkedText = 'not checked';
        }
        echo "<input type='checkbox' $checked disabled /> $checkedText<br>";
    }


    echo "</div>";
    echo "</div>";

}


echo "<div style='height:30px;' id='cg_go_to_save_button'>";
echo "<input type='submit' value='Save data' class='cg_backend_button_gallery_action' style='float:right;text-align:center;width:80px;'>";
echo "</div>";


echo "</form>";



    echo "</div>";
echo "</div>";
