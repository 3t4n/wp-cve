<?php

if($cgVersion<7){
    echo "<div style='width:100%;text-align:center;font-size:20px;'>";
    echo "<p style='font-size:16px;'>    
        <strong>Please create a new gallery</strong><br> Galleries created before update to version 7 have old logic and will not supported anymore.<br> You can also copy an old gallery.</p></div>";
}

if(!empty($categories)){

// form start has to be done after get data!!!
    echo "<form id='cgCategoriesForm' action='?page=".cg_get_version()."/index.php' method='POST'>";

    // authentification
    echo "<input type='hidden' name='cgGalleryHash' value='".md5(wp_salt( 'auth').'---cngl1---'.$GalleryID)."'>";
    echo "<input type='hidden' name='GalleryID' value='$GalleryID'>";

    echo "<table style='$CatWidgetColor;' id='cgCatWidgetTable'>";
    echo "<tr>";
    // !IMPORTANT Has to be here for action call!!!!
    echo "<input type='hidden' name='action' value='post_cg_gallery_save_categories_changes'>";

    echo "<td style='padding-left:20px;padding-right:10px;padding-top: 8px; padding-bottom: 15px;'>";

    echo '<div id="cgSaveCategoriesLoader" class="cg-lds-dual-ring-div-gallery-hide cg-lds-dual-ring-div-gallery-hide-mainCGallery cg_hide">
    <div class="cg-lds-dual-ring-gallery-hide cg-lds-dual-ring-gallery-hide-mainCGallery">
    </div>
</div>';

    //echo "<p id='cg_changes_saved_categories' style='font-size:18px;display:none;'><strong>Changes saved</strong></p>";
$editCategoriesLink = '<a id="cgEditGalleriesButton" href="?page='.cg_get_version().'/index.php&define_upload=true&option_id='.$GalleryID.'&cg_go_to=cgSelectCategoriesField">
<span>Edit categories</span></a>';
    echo "<div style='float:left;display:inline-block;'>
$editCategoriesLink
</div>";
    echo "<div style='float:right;display:inline;width:50%;height:30px;text-align:right;line-height: 24px;'>
<strong style='margin-right:10px;'>Show categories widget in frontend</strong><input type='checkbox' name='CatWidget' id='CatWidget' value='on' $CatWidgetChecked /><br>
Show categories unchecked on page load<span style='margin-right:10px;'></span><input type='checkbox' name='ShowCatsUnchecked' id='ShowCatsUnchecked' value='on' $ShowCatsUnchecked /><br>
Show categories unfolded<span style='margin-right:10px;'></span><input type='checkbox' name='ShowCatsUnfolded' id='ShowCatsUnfolded' value='on' $ShowCatsUnfolded />
</div>";
    echo "<input type='hidden' name='Category[Continue]'/>";

    echo "<div style='clear:left;width: 100%;' >";
        echo "<br><strong>NOTE:</strong> files of unchecked categories will be not displayed in frontend";
    echo "</div>";
    echo "<div style='clear:left;display:flex;flex-wrap: wrap;width: 100%;' id='cgCategoriesCheckContainer'>";

    $countCategories = count($categories);
    $counterCategories = 1;
    $counterCheckedCategories = 0;

    $activatedImagesCount = 0;

    foreach($categories as $category){

        $checked = '';
        $checkedClass = "class='cg_checked'";

        $imagesInCategoryCount = 0;

        if($category->Active==1){
            $counterCheckedCategories++;
            $checked = "checked='checked'";
            $checkedClass = '';
            foreach($countCategoriesObject as $categoryCountObject){

                if($categoryCountObject->Category == $category->id){
                    $categoryCountObject->Checked = true;
                }

            }
        }

        foreach($countCategoriesObject as $categoryCountObject){

            if($categoryCountObject->Category == $category->id){
                $getMethod = "Category".$categoryCountObject->Category;
                $imagesInCategoryCount = $categoryCountObject->$getMethod;
                $activatedImagesCount = $activatedImagesCount + $imagesInCategoryCount;
            }

        }

        $imagesInCategoryCountColor = 'green';

        if($imagesInCategoryCount == 0){
            $imagesInCategoryCountColor = 'red';
        }

        echo "<div class='cg_category_checkbox_images_area' >".$category->Name." (<span style='color:$imagesInCategoryCountColor;'>$imagesInCategoryCount</span>): <input class='cg-categories-check' data-cg-category-id='".$category->id."' data-cg-images-in-category-count='".$imagesInCategoryCount."' type='checkbox' name='Category[]' $checkedClass $checked value='".$category->id."' style='height:16px;width:16px;'/></div>";

        if($counterCategories==$countCategories){

            $checked = '';
            $checkedClass = "class='cg_checked'";

            if($ShowOther==1){
                $counterCheckedCategories++;
                $checked = "checked='checked'";
                $checkedClass = '';
            }

            $imagesInCategoryCount = 0;
            foreach($countCategoriesObject as $categoryCountObject){

                if($categoryCountObject->Category === '0'){
                    if($ShowOther==1){
                        $categoryCountObject->Checked = true;
                    }
                    $getMethod = "Category".$categoryCountObject->Category;
                    $imagesInCategoryCount = $categoryCountObject->$getMethod;
                    $activatedImagesCount = $activatedImagesCount + $imagesInCategoryCount;

                }

                }

            $imagesInCategoryCountColor = 'green';

            if($imagesInCategoryCount == 0){
                $imagesInCategoryCountColor = 'red';
            }

            echo "<div class='cg_category_checkbox_images_area' >Other (<span style='color:$imagesInCategoryCountColor;'>$imagesInCategoryCount</span>): <input type='checkbox' class='cg-categories-check'  data-cg-category-id='0' data-cg-images-in-category-count='".$imagesInCategoryCount."' name='Category[ShowOther]' value='1' $checkedClass $checked style='height:16px;width:16px;'/></div> ";
        }

        $counterCategories++;

    }

    $totalCountActiveImages = 0;

    foreach($countCategoriesObject as $categoryCountObject){

        if(!empty($categoryCountObject->Checked)){
            $getMethod = "Category".$categoryCountObject->Category;
            $totalCountActiveImages = $totalCountActiveImages+intval($categoryCountObject->$getMethod);
        }

    }

    $totalCountActiveImagesStyleColor = 'green';

    if($totalCountActiveImages==0){
        $totalCountActiveImagesStyleColor = 'red';
    }
                
    echo "<input type='hidden' id='cgActivatedImagesCount' value='$activatedImagesCount' />";

    echo '<div class="cg_category_checkbox_images_area" style="width: 100%;padding-top:5px;display:flex;margin-right: 0;">
<div style="width: 50%;display:flex;">
<div style="margin-right:5px;font-weight:bold;">Total activated files shown in frontend:</div>
<div id="cgCategoryTotalActiveImagesValue" style="font-weight:bold;padding-right: 10px;font-size: 16px;color:'.$totalCountActiveImagesStyleColor.';">'.$totalCountActiveImages.'</div>
</div>
<div style="width: 50%;display:flex;justify-content: flex-end;">
<span class="cg_save_categories_form cg_image_action_href"><span class="cg_backend_button_gallery_action" style="min-width: 220px;font-weight: bold;">Save categories changes</span></span>
</div>
                </div>';
    echo "</div>";

cg_total_images_shown_in_frontend_zero();

    echo "<td>";

    echo "</tr>";

    echo "</table>";
    echo "<br>";
    echo "</form>";
}

cg_preview_images_to_delete_container($GalleryID);

cg_sort_gallery_files_container($GalleryID,$optionsSQL->Version);

cg_multiple_files_for_post_container();

$assign_fields_png = plugins_url('/../../../v10/v10-css/assign-fields.png', __FILE__);

?>
<script>
    cgJsClassAdmin.gallery.vars.assign_fields_png = <?php echo json_encode($assign_fields_png);?>;
    cgJsClassAdmin.gallery.vars.categories = <?php echo json_encode($categories);?>;
    if(cgJsClassAdmin.gallery.vars.categories){
        var categoriesObject = {};
        cgJsClassAdmin.gallery.vars.categories.forEach(function (value,index){
            categoriesObject[value.id] = value.Name;
        });
        cgJsClassAdmin.gallery.vars.categories = categoriesObject;
    }else{
        cgJsClassAdmin.gallery.vars.categories = {};
    }

    cgJsClassAdmin.gallery.vars.upload_form_inputs = <?php echo json_encode($upload_form_inputs);?>;

    var newInputsObjectByOrder = {};

    cgJsClassAdmin.gallery.vars.upload_form_inputs.forEach(function (value,index){
        newInputsObjectByOrder[value.Field_Order] = value;
    });

    cgJsClassAdmin.gallery.vars.upload_form_inputs = newInputsObjectByOrder;

</script>
<?php

// form start has to be done after get data!!!
echo "<form id='cgGalleryForm' action='?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=$step&start=$start&edit_gallery=true' method='POST'>";

$totalCountActiveImagesForHiddenInput = 0;

if(!empty($categories)){
    $totalCountActiveImagesForHiddenInput = $totalCountActiveImages;
}

// !IMPORTANT Has to be here if form submit!!!
echo "<input type='hidden' id='cgTotalCountActiveImagesHiddenInput' value='$totalCountActiveImagesForHiddenInput' disabled>";


// !IMPORTANT Has to be here if form submit!!!
echo "<input type='hidden' id='cgGalleryFormSubmit' name='cgGalleryFormSubmit' value='1' disabled>";

// this check is needed otherwise message will be shown that no images found
if(!empty($isNewGalleryCreated)){
    echo "<input type='hidden' id='cgIsNewGalleryCreated' value='1'>";
}


// !IMPORTANT Has to be here for action call!!!!
echo "<input type='hidden' name='action' value='post_cg_gallery_view_control_backend'>";

// Check if steps were changed then reset to 0
echo "<input type='hidden' id='cgStepsChanged' name='cgStepsChanged' disabled value='1'>";

// authentification
echo "<input type='hidden' name='cgGalleryHash' value='".md5(wp_salt( 'auth').'---cngl1---'.$GalleryID)."'>";

// real gallery id mitschicken
echo "<input type='hidden' name='cg_id' id='cgBackendGalleryId' value='".$GalleryID."'>";

// where to start
echo "<input type='hidden' id='cgStartValue' name='cg_start' value='$start'>";

// how many to show
echo "<input type='hidden' id='cgStepValue' name='cg_step' value='$step'>";

// what order
echo "<input type='hidden' id='cgOrderValue' name='cg_order' value='$order'>";
echo "<input type='hidden' id='cgAllowRating' value='$AllowRating'>";

// image link value has to be loaded at the beginning!
echo "<input type='hidden' id='cg_rating_star_on' value='$starOn' >";
echo "<input type='hidden' id='cg_rating_star_off' value='$starOff' >";

if($isAjaxCall){
    echo "<input type='hidden' id='cg_files_count_total' value='$rows' >";
}else{
    echo "<input type='hidden' id='cg_files_count_total' value='0' >";
}

$cgVersionScripts = cg_get_version_for_scripts();

echo "<input type='hidden' name='cgVersionScripts' id='cgVersionScripts' value='$cgVersionScripts'>";

echo "<div class='cgViewControl $cg_hide_is_new_gallery' id='cgViewControl'>";

echo "<div id='cg_view_control_top'>";

echo "<div class='cg_order'>";

$orderByAverage = '';

$orderByAverageWithManip = '';

$orderByRatingOneStarWithManip = '';
$orderByRatingMultipleStarsWithManip = '';

if($Manipulate==1){
    $orderByRatingOneStarWithManip = '<option value="rating_desc_with_manip" id="cg_rating_desc_with_manip">Rating descend with manipulation</option>
            <option value="rating_asc_with_manip" id="cg_rating_asc_with_manip">Rating ascend with manipulation</option>';
    $orderByRatingMultipleStarsWithManip = '<option value="rating_desc_with_manip" id="cg_rating_desc_with_manip">Rating quantity (amount of votes) descend with manipulation</option>
            <option value="rating_asc_with_manip" id="cg_rating_asc_with_manip">Rating quantity (amount of votes) ascend with manipulation</option>';
}

// since point based system sorting by average is deprecated
/*if(($AllowRating==1 OR ($AllowRating >= 12 AND $AllowRating <=20)) && $checkTablenameIPentries>=1){
    $orderByAverage = '<option value="rating_desc_average" id="cg_rating_desc_average">Rating average descend without manipulation (longer loading possible if many images or votes)</option>
        <option value="rating_asc_average" id="cg_rating_asc_average">Rating average ascend without manipulation (longer loading possible if many images or votes)</option>';
}*/

// since point based system sorting by average is deprecated
/*if(($AllowRating==1 OR ($AllowRating >= 12 AND $AllowRating <=20)) && $Manipulate==1 && $checkTablenameIPentries>=1){
    $orderByAverageWithManip = '<option value="rating_desc_average_with_manip" id="cg_rating_desc_average_with_manip">Rating average descend with manipulation (longer loading possible if many images or votes)</option>
        <option value="rating_asc_average_with_manip" id="cg_rating_asc_average_with_manip">Rating average ascend with manipulation (longer loading possible if many images or votes)</option>';
}*/

$orderBySum = '';
$orderBySumWithManip = '';

if(($AllowRating==1 OR ($AllowRating >= 12 AND $AllowRating <=20)) && $checkTablenameIPentries>=1){
    $orderBySum = '<option value="rating_desc_sum" id="cg_rating_desc_sum">Rating sum descend without manipulation (longer loading possible if many images or votes)</option>
        <option value="rating_asc_sum" id="cg_rating_asc_sum">Rating sum ascend without manipulation (longer loading possible if many images or votes)</option>';
}
if(($AllowRating==1 OR ($AllowRating >= 12 AND $AllowRating <=20)) && $Manipulate==1 && $checkTablenameIPentries>=1){
    $orderBySumWithManip = '<option value="rating_desc_sum_with_manip" id="cg_rating_desc_sum_with_manip">Rating sum descend with manipulation (longer loading possible if many images or votes)</option>
        <option value="rating_asc_sum_with_manip" id="cg_rating_asc_sum_with_manip">Rating sum ascend with manipulation (longer loading possible if many images or votes)</option>';
}

//$selectFormInput = $wpdb->get_results( "SELECT id, Field_Type, Field_Order, Field_Content FROM $tablename_f_input WHERE GalleryID = '$GalleryID' AND (Field_Type = 'check-f' OR Field_Type = 'text-f' OR Field_Type = 'comment-f' OR Field_Type ='email-f' OR Field_Type ='select-f'  OR Field_Type ='selectc-f' OR Field_Type ='url-f' OR Field_Type ='date-f') ORDER BY Field_Order ASC" );

$selectFormInputOptGroup = '';

if(count($selectFormInput)){
    foreach($selectFormInput as $selectFormInputRow){

        if($selectFormInputRow->Field_Type == 'text-f'){

            if(empty($selectFormInputOptGroup)){
                $selectFormInputOptGroup .= '<optgroup label="Custom fields" id="cgOrderSelectCustomFields">';
            }

            $selectFormInputRowFieldContentUnserialized = unserialize($selectFormInputRow->Field_Content);
            $selectFormInputRowTitel = $selectFormInputRowFieldContentUnserialized["titel"];
            $selectFormInputOptGroup .= '<option value="cg_input_for_id_'.$selectFormInputRow->id.'_id_desc" id="cg_input_'.$selectFormInputRow->id.'_descend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' descend</option>';
            $selectFormInputOptGroup .= '<option value="cg_input_for_id_'.$selectFormInputRow->id.'_id_asc" id="cg_input_'.$selectFormInputRow->id.'_ascend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' ascend</option>';

        }

        if($selectFormInputRow->Field_Type == 'comment-f'){

            if(empty($selectFormInputOptGroup)){
                $selectFormInputOptGroup .= '<optgroup label="Custom fields" id="cgOrderSelectCustomFields">';
            }

            $selectFormInputRowFieldContentUnserialized = unserialize($selectFormInputRow->Field_Content);
            $selectFormInputRowTitel = $selectFormInputRowFieldContentUnserialized["titel"];
            $selectFormInputOptGroup .= '<option value="cg_textarea_for_id_'.$selectFormInputRow->id.'_id_desc" id="cg_textarea_'.$selectFormInputRow->id.'_descend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' descend</option>';
            $selectFormInputOptGroup .= '<option value="cg_textarea_for_id_'.$selectFormInputRow->id.'_id_asc" id="cg_textarea_'.$selectFormInputRow->id.'_ascend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' ascend</option>';

        }

        if($selectFormInputRow->Field_Type == 'select-f'){

            if(empty($selectFormInputOptGroup)){
                $selectFormInputOptGroup .= '<optgroup label="Custom fields" id="cgOrderSelectCustomFields">';
            }

            $selectFormInputRowFieldContentUnserialized = unserialize($selectFormInputRow->Field_Content);
            $selectFormInputRowTitel = $selectFormInputRowFieldContentUnserialized["titel"];
            $selectFormInputOptGroup .= '<option value="cg_select_for_id_'.$selectFormInputRow->id.'_id_desc" id="cg_select_'.$selectFormInputRow->id.'_descend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' descend</option>';
            $selectFormInputOptGroup .= '<option value="cg_select_for_id_'.$selectFormInputRow->id.'_id_asc" id="cg_select_'.$selectFormInputRow->id.'_ascend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' ascend</option>';

        }

        if($selectFormInputRow->Field_Type == 'selectc-f'){

            if(empty($selectFormInputOptGroup)){
                $selectFormInputOptGroup .= '<optgroup label="Custom fields" id="cgOrderSelectCustomFields">';
            }

            $selectFormInputRowFieldContentUnserialized = unserialize($selectFormInputRow->Field_Content);
            $selectFormInputRowTitel = $selectFormInputRowFieldContentUnserialized["titel"];
            $selectFormInputOptGroup .= '<option value="cg_categories_for_id_'.$selectFormInputRow->id.'_id_desc" id="cg_categories_'.$selectFormInputRow->id.'_descend" data-cg-input-fields-class="cg_select_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' descend</option>';
            $selectFormInputOptGroup .= '<option value="cg_categories_for_id_'.$selectFormInputRow->id.'_id_asc" id="cg_categories_'.$selectFormInputRow->id.'_ascend" data-cg-input-fields-class="cg_select_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' ascend</option>';

        }

        if($selectFormInputRow->Field_Type == 'email-f'){

            if(empty($selectFormInputOptGroup)){
                $selectFormInputOptGroup .= '<optgroup label="Custom fields" id="cgOrderSelectCustomFields">';
            }

            $selectFormInputRowFieldContentUnserialized = unserialize($selectFormInputRow->Field_Content);
            $selectFormInputRowTitel = $selectFormInputRowFieldContentUnserialized["titel"];
            $selectFormInputOptGroup .= '<option value="cg_email_registered_users_for_id_'.$selectFormInputRow->id.'_id_desc" id="cg_email_registered_users_'.$selectFormInputRow->id.'_descend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' descend (Registered users)</option>';
            $selectFormInputOptGroup .= '<option value="cg_email_registered_users_for_id_'.$selectFormInputRow->id.'_id_asc" id="cg_email_registered_users_'.$selectFormInputRow->id.'_ascend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' ascend (Registered users)</option>';
            $selectFormInputOptGroup .= '<option value="cg_email_unregistered_users_for_id_'.$selectFormInputRow->id.'_id_desc" id="cg_email_unregistered_users_'.$selectFormInputRow->id.'_descend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' descend (Unregistered users)</option>';
            $selectFormInputOptGroup .= '<option value="cg_email_unregistered_users_for_id_'.$selectFormInputRow->id.'_id_asc" id="cg_email_unregistered_users_'.$selectFormInputRow->id.'_ascend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' ascend (Unregistered users)</option>';

        }

        if($selectFormInputRow->Field_Type == 'date-f'){

            if(empty($selectFormInputOptGroup)){
                $selectFormInputOptGroup .= '<optgroup label="Custom fields" id="cgOrderSelectCustomFields">';
            }

            $selectFormInputRowFieldContentUnserialized = unserialize($selectFormInputRow->Field_Content);
            $selectFormInputRowTitel = $selectFormInputRowFieldContentUnserialized["titel"];
            $selectFormInputOptGroup .= '<option value="cg_date_for_id_'.$selectFormInputRow->id.'_id_desc" id="cg_date_'.$selectFormInputRow->id.'_descend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' descend</option>';
            $selectFormInputOptGroup .= '<option value="cg_date_for_id_'.$selectFormInputRow->id.'_id_asc" id="cg_date_'.$selectFormInputRow->id.'_ascend" data-cg-input-fields-class="cg_input_by_search_sort_'.$selectFormInputRow->id.'">'.$selectFormInputRowTitel.' ascend</option>';

        }

    }

}

if(!empty($selectFormInputOptGroup)){
    $selectFormInputOptGroup .= '</optgroup>';
}

$selectFurtherFieldsOptGroup = '<optgroup label="Further fields" id="cgOrderSelectFurtherFields">';
$selectFurtherFieldsOptGroup .= '<option value="cg_for_id_wp_username_desc" id="cg_for_id_wp_username_descend" data-cg-input-fields-class="cg_for_id_wp_username_by_search_sort">WP username descend</option>';
$selectFurtherFieldsOptGroup .= '<option value="cg_for_id_wp_username_asc" id="cg_for_id_wp_username_ascend" data-cg-input-fields-class="cg_for_id_wp_username_by_search_sort">WP username ascend</option>';
$selectFurtherFieldsOptGroup .= '</optgroup>';


$orderByRatingOneStarWithoutManip = '';
$orderByRatingMultipleStarsWithoutManip = '';

if($AllowRating==2){
    echo <<<HEREDOC
	<select id="cgOrderSelect">
		<optgroup label="General" id="cgOrderSelectGeneral">
		    <option value="custom" id="cg_custom">Custom</option>
            <option value="date_desc" id="cg_date_desc">Date descend</option>
            <option value="date_asc" id="cg_date_asc">Date ascend</option>
            <option value="rating_desc" id="cg_rating_desc">Rating descend without manipulation</option>
            <option value="rating_asc" id="cg_rating_asc">Rating ascend without manipulation</option>
            $orderByRatingOneStarWithManip
            <option value="comments_desc" id="cg_comments_desc">Comments descend</option>
            <option value="comments_asc" id="cg_comments_asc">Comments ascend</option>
        </optgroup>
            $selectFormInputOptGroup
            $selectFurtherFieldsOptGroup
	</select>
HEREDOC;
}else if($AllowRating>=12 && $AllowRating<=20){
    echo <<<HEREDOC
	<select id="cgOrderSelect">
		<optgroup label="General" id="cgOrderSelectGeneral">
            <option value="custom" id="cg_custom">Custom</option>
            <option value="date_desc" id="cg_date_desc">Date descend</option>
            <option value="date_asc" id="cg_date_asc">Date ascend</option>
            $orderBySum
            $orderBySumWithManip
            <option value="rating_desc" id="cg_rating_desc">Rating quantity (amount of votes) descend without manipulation</option>
            <option value="rating_asc" id="cg_rating_asc">Rating quantity (amount of votes) ascend without manipulation</option>
            $orderByRatingMultipleStarsWithManip
            <option value="comments_desc" id="cg_comments_desc">Comments descend</option>
            <option value="comments_asc" id="cg_comments_asc">Comments ascend</option>
        </optgroup>
            $selectFormInputOptGroup
            $selectFurtherFieldsOptGroup
	</select>
HEREDOC;
}else{
    echo <<<HEREDOC
	<select id="cgOrderSelect">
		<optgroup label="General" id="cgOrderSelectGeneral">
            <option value="custom" id="cg_custom">Custom</option>
            <option value="date_desc" id="cg_date_desc">Date descend</option>
            <option value="date_asc" id="cg_date_asc">Date ascend</option>
            $orderByRatingMultipleStarsWithManip
            <option value="comments_desc" id="cg_comments_desc">Comments descend</option>
            <option value="comments_asc" id="cg_comments_asc">Comments ascend</option>
        </optgroup>
            $selectFormInputOptGroup
            $selectFurtherFieldsOptGroup
	</select>
HEREDOC;
}

$heredoc = <<<HEREDOC
	<span class="cg-info-icon"><strong>info</strong></span>
<span class="cg-info-container cg-info-container-gallery-order" style="display: none;">Custom fields can be added in "Edit contact form".
<br>Supported custom fields for sorting are:<br><b>Input, Textarea, Select, Select categories, Date, Email</b></span>
HEREDOC;
echo $heredoc;

?>

    <script>

        var gid = <?php echo json_encode($GalleryID);?>;
        var cgOrder_BG = localStorage.getItem('cgOrder_BG_'+gid);

        if(cgOrder_BG){
            // fallback to go sure if old order options are activated
            if(cgOrder_BG=='rating_desc_average' || cgOrder_BG=='rating_asc_average' || cgOrder_BG=='rating_desc_average_with_manip' || cgOrder_BG=='rating_asc_average_with_manip'){
                cgOrder_BG = 'date_desc';
            }
            var id = 'cg_'+cgOrder_BG;
            var el = document.getElementById(id);
            if(el){
                el.setAttribute("selected", "selected");
            }
        }

    </script>

<?php


echo "</div>";

echo "<div id='cg_sort_files_div'>";

?>
        <div style="margin-left: 20px; display: flex; align-items: center; justify-content: center;font-weight:bold;" id="cg_sort_files_form_button" class="cg_backend_button_gallery_action cg_hide">Sort entries</div>
<?php
echo "</div>";


echo "<div id='cgPicsPerSite' class='cg_pics_per_site'>";
echo"<div class='cg_show_all_pics_text'>&nbsp;&nbsp;Show pics per Site:</div>";
echo "<div data-cg-step-value='10' class='cg_hover_effect cg_step' id='cg_step_10'><a href=\"?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=10&start=$i&edit_gallery=true\">10</a></div>";
echo "<div data-cg-step-value='20' class='cg_hover_effect cg_step' id='cg_step_20'><a href=\"?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=20&start=$i&edit_gallery=true\">20</a></div>";
echo "<div data-cg-step-value='30' class='cg_hover_effect cg_step' id='cg_step_30'><a href=\"?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=30&start=$i&edit_gallery=true\">30</a></div>";
if($max_input_vars>=2000){
    echo "<div data-cg-step-value='50' class='cg_hover_effect cg_step' id='cg_step_50'><a href=\"?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=50&start=$i&edit_gallery=true\">50</a></div>";
}
?>

    <script>

        var gid = <?php echo json_encode($GalleryID);?>;
        var cgStep_BG = localStorage.getItem('cgStep_BG_'+gid);

        if(!cgStep_BG){
            cgStep_BG = 10;
        }

        var id = 'cg_step_'+cgStep_BG;
        var el = document.getElementById(id);
        el.classList.add('cg_step_selected');

    </script>

<?php

echo "</div>";

echo "</div>";


echo "<div style='margin-top: 10px;' class='cg_search'>";

echo "<span id='cgSearchInputSpan'><input id='cgSearchInput' placeholder='search' name='cg_search' value='$search'>
<span id='cgSearchInputButton' class='cg_hide'>Search</span>
<span id='cgSearchInputClose' class='cg_hide'>X</span>
</span>";

$checkCookieIdOrIP = '';

/*if($pro_options->RegUserUploadOnly=='2'){
    $checkCookieIdOrIP = ", Cookie ID";
}else if($pro_options->RegUserUploadOnly=='3'){
    $checkCookieIdOrIP = ", IP";
}*/

echo '<span class="cg-info-icon"><strong>info</strong></span>
    <span class="cg-info-container cg-info-container-gallery-user" style="display: none;margin-top: 55px !important;margin-left: 180px !important;">Search by fields content, categories,
file name, entry id, EXIF data (if available), user email, user nickname'.$checkCookieIdOrIP.'<br><br><strong>Pay attention!<br>Database queries with searched value takes longer then without searched value</strong></span>';
?>

    <script>

        var gid = <?php echo json_encode($GalleryID);?>;
        var cgSearch_BG = localStorage.getItem('cgSearch_BG_'+gid);

        if(cgSearch_BG){
            var id = 'cgSearchInput';
            var el = document.getElementById(id);
            if(el){
                el.value  = cgSearch_BG;
                el.classList.add('cg_searched_value');
            }
            var id = 'cgSearchInputClose';
            var el = document.getElementById(id);

            if(el){
                el.classList.remove('cg_hide');
            }

            var id = 'cgSearchInputButton';
            var el = document.getElementById(id);
            if(el){el.classList.remove('cg_hide');}

        }else{
            var id = 'cgSearchInput';
            var el = document.getElementById(id);
            if(el){el.classList.remove('cg_searched_value');}

            var id = 'cgSearchInputClose';
            var el = document.getElementById(id);
            if(el){el.classList.add('cg_hide');}

            var id = 'cgSearchInputButton';
            var el = document.getElementById(id);
            if(el){el.classList.add('cg_hide');}

        }

    </script>

<?php


echo "</div>";


echo "<div id='cgShowOnlyWinners' class='cg_show_only'>";
echo "<div>Show winners only:</div><div><input type='checkbox' id='cgShowOnlyWinnersCheckbox' name='cg_show_only_winners' value='true' /></div>";
echo "</div>";

echo "<div id='cgShowOnlyActive' class='cg_show_only'>";
echo "<div>Show active only:</div><div><input type='checkbox' id='cgShowOnlyActiveCheckbox' name='cg_show_only_active' value='true' /></div>";
echo "</div>";

echo "<div id='cgShowOnlyInactive' class='cg_show_only'>";
echo "<div>Show inactive only:</div><div><input type='checkbox' id='cgShowOnlyInactiveCheckbox' name='cg_show_only_inactive' value='true' /></div>";
echo "</div>";

echo "<div class='cg_image_checkbox_container_view_control '>
<div class=\"cg_hover_effect cg_image_action_href cg_image_checkbox cg_image_checkbox_activate_all\">
<div class=\"cg_image_checkbox_action\">Activate all</div>
<div class=\"cg_image_checkbox_icon\"></div>
</div>

<div class=\"cg_hover_effect cg_image_action_href cg_image_checkbox cg_image_checkbox_deactivate_all\">
<div class=\"cg_image_checkbox_action\">Deactivate all</div>
<div class=\"cg_image_checkbox_icon\"></div>
</div>

<div class=\"cg_hover_effect cg_image_action_href cg_image_checkbox cg_image_checkbox_delete_all\" >
<div class=\"cg_image_checkbox_action\">Delete all</div>
<div class=\"cg_image_checkbox_icon\" style=\"margin-left: 50px;\"></div>
</div>

<div class=\"cg_hover_effect cg_image_action_href cg_image_checkbox cg_image_checkbox_winner_all\">
<div class=\"cg_image_checkbox_action\">Winner all</div>
<div class=\"cg_image_checkbox_icon\"></div>
</div>

<div class=\"cg_hover_effect cg_image_action_href cg_image_checkbox cg_image_checkbox_not_winner_all\">
<div class=\"cg_image_checkbox_action\">Not winner all</div>
<div class=\"cg_image_checkbox_icon\"></div>
</div>

    </div>";

/*
<div class=\"cg_image_action_href cg_image_checkbox cg_image_checkbox_move_all\" >
<div class=\"cg_image_checkbox_action\">Move all</div>
<div class=\"cg_image_checkbox_icon\" style=\"margin-left: 50px;\"></div>
</div>*/

echo "</div>";




// if cgBackendHash then backend reload must be done
if(!empty($isAjaxGalleryCall) && empty($_POST['cgBackendHash'])){// if ajax send without this name, then must be under version 10.9.9.null.null

    echo "<div id='cgStepsNavigationTop'></div>";

    echo "<ul id='cgSortable' style='width:100%;padding:20px;background-color:#fff;margin-bottom:0px !important;margin-bottom:0px;border: thin solid black;margin-top:0px;'>";

    echo "<p style='text-align: center;'><b>New Contest Gallery Version detected. Please refresh this page one time manually.</b></p>";

    echo "</ul>";

    echo "<div id='cgStepsNavigationBottom'></div>";

    die;

}


if($isAjaxCall){


    echo "<div id='cgStepsNavigationTop' class='cg_steps_navigation'>";
    for ($i = 0; $rows > $i; $i = $i + $step) {

        $anf = $i + 1;
        $end = $i + $step;

        if ($end > $rows) {
            $end = $rows;
        }

        if ($anf == $nr1 AND ($start+$step) > $rows AND $start==0) {
            continue;
            echo "<div data-cg-start='$i' class='cg_step cg_step_selected'><a href=\"?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=$step&start=$i&edit_gallery=true\">$anf-$end</a></div>";
        }
        elseif ($anf == $nr1 AND ($start+$step) > $rows AND $anf==$end) {

            echo "<div data-cg-start='$i' class='cg_step cg_step_selected'><a href=\"?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=$step&start=$i&edit_gallery=true\">$end</a></div>";
        }
        elseif ($anf == $nr1 AND ($start+$step) > $rows) {

            echo "<div data-cg-start='$i' class='cg_step cg_step_selected'><a href=\?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=$step&start=$i&edit_gallery=true\">$anf-$end</a></div>";
        }

        elseif ($anf == $nr1) {
            echo "<div data-cg-start='$i' class='cg_step cg_step_selected'><a href=\"?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=$step&start=$i&edit_gallery=true\">$anf-$end</a></div>";
        }

        elseif ($anf == $end) {
            echo "<div data-cg-start='$i' class='cg_step'><a href=\"?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=$step&start=$i&edit_gallery=true\">$end</a></div>";
        }

        else {
            echo "<div data-cg-start='$i' class='cg_step'><a href=\"?page=".cg_get_version()."/index.php&option_id=$GalleryID&step=$step&start=$i&edit_gallery=true\">$anf-$end</a></div>";
        }
    }
    echo "</div>";

}
echo '<div id="cgGalleryLoader" class="cg-lds-dual-ring-div-gallery-hide cg-lds-dual-ring-div-gallery-hide-mainCGallery '.$cg_hide_is_new_gallery.'">
    <div class="cg-lds-dual-ring-gallery-hide cg-lds-dual-ring-gallery-hide-mainCGallery">
    </div>
</div>';

?>