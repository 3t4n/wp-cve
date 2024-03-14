<?php

// View order here

//$selectGalleryLookOrder = $wpdb->get_results( "SELECT ThumbLookOrder, HeightLookOrder, RowLookOrder  FROM $tablenameOptions WHERE id = '$galeryID'" );

$ContestEnd = $options['general']['ContestEnd'];
if(floatval($options['general']['Version'])>=21.1){
    $ContestEnd = 0;
}
$ContestEndTime = $options['general']['ContestEndTime'];
$ContestStart = $options['general']['ContestStart'];
if(floatval($options['general']['Version'])>=21.1){
    $ContestStart = 0;
}
$ContestStartTime = $options['general']['ContestStartTime'];
$AllowSort = $options['general']['AllowSort'];
$FullSizeGallery = $options['general']['FullSizeGallery'];// full window mode
$FullSize = $options['general']['FullSize'];// full window mode
$FullScreenGallery = 0;
$Search = $options['pro']['Search'];
$GalleryUpload = $options['pro']['GalleryUpload'];
$CatWidget = $options['pro']['CatWidget'];
$ShowOther = $options['pro']['ShowOther'];

$categoriesCheck = false;
$categoriesCount = 0;

if($ShowOther==1){
    $categoriesCount++;
}

if(!empty($jsonCategories) && $CatWidget==1){
    foreach($jsonCategories as $categoryID => $category){
        if($category['Active']==1){
            $categoriesCount++;
        }
        if($categoriesCount>=2){// show when two categories will be displayed!!!
            $categoriesCheck = true;
            break;
        }
    }
}


$SliderFullWindow = (!empty($options['pro']['SliderFullWindow'])) ? 1 : 0;

if(empty($options['visual']['AllowSortOptions'])){
    $AllowSortOptionsArray = explode(',','custom,date-desc,date-asc,rate-desc,rate-asc,rate-average-desc,rate-average-asc,comment-desc,comment-asc,random');
}else{
    if(strpos($options['visual']['AllowSortOptions'],',')!==false){
        $AllowSortOptionsArray = explode(',',$options['visual']['AllowSortOptions']);
    }else{
        $AllowSortOptionsArray = array();
        $AllowSortOptionsArray[] = $options['visual']['AllowSortOptions'];
    }
}

if((!$isUserGallery && !empty($options['pro']['GalleryUploadOnlyUser']) && !$isModernOptions)){
    $GalleryUpload = 0;
}

$isInGalleryUpload = false;

if(($GalleryUpload==1 && !$isUserGallery) OR ($GalleryUpload==1 && $isUserGallery && is_user_logged_in())){
    $isInGalleryUpload = true;
}


// Order of views will be determined


if($isOnlyUploadForm || $isOnlyContactForm){
    echo "<div class='cg_thumbs_and_categories_control cg_gallery_control_element'>";
        include('gallery-upload-form.php');
    echo "</div>";
}else{
if(true){
//if($GalleryUpload==1 OR $AllowSort == 1 OR $LooksCount>1 or $categoriesCheck or $Search==1 or $FullSizeGallery==1 OR $options['general']['RandomSortButton']==1){

    if($GalleryUpload==1 OR $AllowSort == 1 OR $LooksCount>1 or $FullSizeGallery==1 OR $options['general']['RandomSortButton']==1 or $Search==1 ){

        echo "<div class='cg_gallery_view_sort_control' id='cgGalleryViewSortControl$galeryIDuserForJs' >";

        $pluginsUrl = plugins_url();

        echo "<div class='cg_sort_div'>";
        if($isInGalleryUpload  && empty($isOnlyGalleryWinner)){

            echo "<div data-cg-tooltip='$language_ImageUpload' class='cg-gallery-upload cg_gallery_control_element cg_hover_effect $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs'>";

            echo "</div>";
        }

        if($Search==1){
            echo "<div id='cgSearchInputDiv$galeryIDuserForJs' class='cg_search_input_div cg_gallery_control_element $cgFeControlsStyle'>";
            echo "<input  type='text' id='cgSearchInput$galeryIDuserForJs' autocomplete='off' placeholder='$language_Search' class='cg_search_input' data-cg-gid='$galeryIDuserForJs' />";
            echo "</div>";
        }

        if($AllowSort == 1 OR $options['general']['RandomSortButton']==1){

            if($AllowSort == 1){

                $selected = '';
                echo '<label class="cg_select_order_label cg_gallery_control_element cg_hover_effect"><select data-cg-tooltip="'.$language_SortBy.'" id="cg_select_order'.$galeryIDuserForJs.'" class="cg_select_order cg_gallery_control_element '.$cgFeControlsStyle.'">';


                if(in_array('custom',$AllowSortOptionsArray) && !$isUserGallery){// no custom in user gallery, might be included when new gallery created, correction here, might be already set in galleries
                    echo '<option value="custom" class="cg_custom" '.$selected.'>'.$language_Custom.'</option>';
                }

                if(in_array('date-desc',$AllowSortOptionsArray)){
                    echo '<option value="date-desc" class="cg_date_descend" '.$selected.'>'.$language_DateDescend.'</option>';
                }

                if(in_array('date-asc',$AllowSortOptionsArray)){
                    echo '<option value="date-asc"  class="cg_date_ascend" '.$selected.'>'.$language_DateAscend.'</option>';
                }

                // if($options['general']['RandomSortButton']==1){
             if($options['general']['HideUntilVote']!=1){
                        if($options['general']['AllowRating']==2 || ($options['pro']['IsModernFiveStar']==0 && ($options['general']['AllowRating']==1 OR $options['general']['AllowRating']>=12))){
                            if(
                                empty($isOnlyGalleryNoVoting) ||
                                ($isOnlyGalleryNoVoting && $RatingVisibleForGalleryNoVoting)
                            ){
                                if(in_array('rate-desc',$AllowSortOptionsArray)){
                                    echo '<option value="rate-desc" class="cg_rating_descend" '.$selected.'>'.$language_RatingDescend.'</option>';
                                }
                                if(in_array('rate-asc',$AllowSortOptionsArray)){
                                    echo '<option value="rate-asc"  class="cg_rating_ascend" '.$selected.'>'.$language_RatingAscend.'</option>';
                                }
                            }
                        }
                        if((!empty($options['pro']['IsModernFiveStar']) && ($options['general']['AllowRating']==1 OR $options['general']['AllowRating']>=12))){
                            if(
                                empty($isOnlyGalleryNoVoting) ||
                                ($isOnlyGalleryNoVoting && $RatingVisibleForGalleryNoVoting)
                            ){
                                if(in_array('rate-desc',$AllowSortOptionsArray) && $options['general']['AllowRating']==2){
                                    echo '<option value="rate-desc" class="cg_rating_descend" '.$selected.'>'.$language_RatingDescend.'</option>';
                                }
                                if(in_array('rate-asc',$AllowSortOptionsArray) && $options['general']['AllowRating']==2){
                                    echo '<option value="rate-asc"  class="cg_rating_ascend" >'.$language_RatingAscend.'</option>';
                                }

/*                                if(in_array('rate-average-desc',$AllowSortOptionsArray)){
                                    echo '<option value="8" class="cg_rating_descend_average" '.$selected.'>'.$language_RatingAverageDescend.'</option>';
                                }

                                if(in_array('rate-average-asc',$AllowSortOptionsArray)){
                                    echo '<option value="9"  class="cg_rating_ascend_average" '.$selected.'>'.$language_RatingAverageAscend.'</option>';
                                }*/

                                if(in_array('rate-sum-desc',$AllowSortOptionsArray)){
                                    echo '<option value="rate-sum-desc" class="cg_rating_descend_sum" '.$selected.'>'.$language_RatingSumDescend.'</option>';
                                }

                                if(in_array('rate-sum-asc',$AllowSortOptionsArray)){
                                    echo '<option value="rate-sum-asc"  class="cg_rating_ascend_sum" '.$selected.'>'.$language_RatingSumAscend.'</option>';
                                }

                            }
                        }
                    }

                // if($options['general']['RandomSortButton']==1){
                    if($options['general']['AllowComments']>=1){

                        if(in_array('comment-desc',$AllowSortOptionsArray)){
                            echo '<option value="comment-desc" class="cg_comments_descend" '.$selected.'>'.$language_CommentsDescend.'</option>';
                        }

                        if(in_array('comment-asc',$AllowSortOptionsArray)){
                            echo '<option value="comment-asc"  class="cg_comments_ascend" '.$selected.'>'.$language_CommentsAscend.'</option>';
                        }

                    }
                    if(in_array('random',$AllowSortOptionsArray)){

                        if($options['general']['RandomSort']==1){
                            $selected = 'selected';
                        }

                        echo '<option value="random"  class="cg_random_sort" '.$selected.'>'.$language_RandomSortSorting.'</option>';
                    }



                echo '</select></label>';

            }

            if($options['general']['RandomSortButton']==1){
                echo "<div class='cg-lds-dual-ring cg_random_button_loader $cgFeControlsStyle cg_gallery_control_element'></div>";
                echo "<span data-cg-tooltip='$language_RandomSortIcon' class='cg_random_button cg_hover_effect cg_hide cg_gallery_control_element $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs'></span>";
            }

        }


        echo "</div>";


        echo "</div>";

    }else{
        echo "<div class='cg_gallery_view_sort_control' id='cgGalleryViewSortControl$galeryIDuserForJs' >";// has to be done if absolute all "Gallery view" options are deactivated
            echo "<div class='cg_sort_div'>";

            echo "</div>";
        echo "</div>";
    }


    echo "<div class='cg_thumbs_and_categories_control cg_gallery_control_element'>";

    include('categories-container.php');

    $showThumbsControl = false;

    if(!empty($options['visual']['EnableSwitchStyleGalleryButton'])){
        $showThumbsControl = true;
    }

    if(count($orderGalleries)>1){

        foreach($orderGalleries as $key => $value){

            if($value=="SliderLookOrder" AND $options['general']['SliderLook'] == 1 AND ($options['general']['HeightLook'] == 1 or $options['general']['RowLook'] == 1 or $options['general']['ThumbLook'] == 1 or $options['visual']['BlogLook'] == 1)){
                $showThumbsControl=true;
                break;
            }
            if($value=="ThumbLookOrder" AND $options['general']['ThumbLook'] == 1 AND ($options['general']['HeightLook'] == 1 or $options['general']['RowLook'] == 1 or $options['general']['SliderLook'] == 1 or $options['visual']['BlogLook'] == 1)){
                $showThumbsControl=true;
                break;
            }
            if($value=="HeightLookOrder" AND $options['general']['HeightLook'] == 1 AND ($options['general']['ThumbLook'] == 1 or $options['general']['RowLook'] == 1 or $options['general']['SliderLook'] == 1 or $options['visual']['BlogLook'] == 1)){
                $showThumbsControl=true;
                break;
            }
            if($value=="RowLookOrder" AND $options['general']['RowLook'] == 1  AND ($options['general']['ThumbLook'] == 1 or $options['general']['HeightLook'] == 1 or $options['general']['SliderLook'] == 1 or $options['visual']['BlogLook'] == 1)){
                $showThumbsControl=true;
                break;
            }
            if($value=="BlogLookOrder" AND $options['visual']['BlogLook'] == 1  AND ($options['general']['ThumbLook'] == 1 or $options['general']['HeightLook'] == 1 or $options['general']['SliderLook'] == 1 or $options['general']['RowLook'] == 1)){
                $showThumbsControl=true;
                break;
            }

        }

        $i = 0;
        if($showThumbsControl){

            echo "<div class='cg_gallery_thumbs_control'>";

            foreach($orderGalleries as $key => $value){

                if($i==0){$cg_hide_on='';$cg_hide_off='cg_hide';}else{$cg_hide_on='cg_hide';$cg_hide_off='';}

                if($value=="SliderLookOrder" AND $options['general']['SliderLook'] == 1 AND ($options['general']['HeightLook'] == 1 or $options['general']['RowLook'] == 1 or $options['general']['ThumbLook'] == 1 or $options['visual']['BlogLook'] == 1)){
                    $i++;

                    //echo "<option value='1' $selected_look_thumb>View $i</option>";
                    //echo "<a href='$siteURL&1=1&2=".$getOrder."&3=".$start."'><img title='Thumb view' src='$selected_look_thumb' style='float:left;margin-left:5px;' /></a> ";
                    echo "<span data-cg-tooltip='$language_SliderView' class='$cgFeControlsStyle cg_view_switcher cg_view_slider cg_hover_effect  cg_view_switcher_off $cg_hide_off' ></span>";
                    echo "<span id='cg_view_switcher_on_$key' data-cg-tooltip='$language_SliderView' class='$cgFeControlsStyle cg_view_switcher cg_hover_effect cg_view_slider cg_view_switcher_on $cg_hide_on' ></span>";
                }

                if($value=="ThumbLookOrder" AND $options['general']['ThumbLook'] == 1 AND ($options['general']['HeightLook'] == 1 or $options['general']['RowLook'] == 1 or $options['general']['SliderLook'] == 1 or $options['visual']['BlogLook'] == 1)){
                    $i++;

                    //echo "<option value='1' $selected_look_thumb>View $i</option>";
                    //echo "<a href='$siteURL&1=1&2=".$getOrder."&3=".$start."'><img title='Thumb view' src='$selected_look_thumb' style='float:left;margin-left:5px;' /></a> ";
                    echo "<span data-cg-tooltip='$language_ThumbView' class='$cgFeControlsStyle cg_view_switcher cg_hover_effect cg_view_thumb cg_view_switcher_off $cg_hide_off' ></span>";
                    echo "<span  id='cg_view_switcher_on_$key' data-cg-tooltip='$language_ThumbView' class='$cgFeControlsStyle cg_hover_effect cg_view_switcher cg_view_thumb cg_view_switcher_on $cg_hide_on' ></span>";
                }
                if($value=="HeightLookOrder" AND $options['general']['HeightLook'] == 1 AND ($options['general']['ThumbLook'] == 1 or $options['general']['RowLook'] == 1 or $options['general']['SliderLook'] == 1 or $options['visual']['BlogLook'] == 1)){
                    $i++;

                    //echo "<option value='2' $selected_look_height>View $i</option>";
                    //echo "<a href='$siteURL&1=2&2=".$getOrder."&3=".$start."'><img title='Height view' src='$selected_look_height' style='float:left;margin-left:5px;'></a> ";
                    echo "<span data-cg-tooltip='$language_HeightView' class='$cgFeControlsStyle cg_view_switcher cg_hover_effect cg_view_height cg_view_switcher_off $cg_hide_off' ></span>";
                    echo "<span  id='cg_view_switcher_on_$key' data-cg-tooltip='$language_HeightView' class='$cgFeControlsStyle cg_view_switcher cg_hover_effect cg_view_height cg_view_switcher_on $cg_hide_on' ></span>";
                }

                if($value=="RowLookOrder" AND $options['general']['RowLook'] == 1  AND ($options['general']['ThumbLook'] == 1 or $options['general']['HeightLook'] == 1 or $options['general']['SliderLook'] == 1 or $options['visual']['BlogLook'] == 1)){
                    $i++;
                    //echo "<option value='3' $selected_look_row>View $i</option>";
                    //echo "<a href='$siteURL&1=3&2=".$getOrder."&3=".$start."'><img title='Row view' src='$selected_look_row' style='float:left;margin-left:5px;'></a> ";
                    echo "<span data-cg-tooltip='$language_RowView' class='$cgFeControlsStyle cg_view_switcher cg_view_row cg_hover_effect cg_view_switcher_off $cg_hide_off' ></span>";
                    echo "<span  id='cg_view_switcher_on_$key' data-cg-tooltip='$language_RowView' class='$cgFeControlsStyle cg_view_switcher cg_hover_effect cg_view_row cg_view_switcher_on $cg_hide_on' ></span>";
                }

                if($value=="BlogLookOrder" AND $options['visual']['BlogLook'] == 1  AND ($options['general']['ThumbLook'] == 1 or $options['general']['HeightLook'] == 1 or $options['general']['SliderLook'] == 1 or $options['general']['RowLook'] == 1)){
                    $i++;
                    //echo "<option value='3' $selected_look_row>View $i</option>";
                    //echo "<a href='$siteURL&1=3&2=".$getOrder."&3=".$start."'><img title='Row view' src='$selected_look_row' style='float:left;margin-left:5px;'></a> ";
                    echo "<span data-cg-tooltip='$language_BlogView' class='$cgFeControlsStyle cg_view_switcher cg_hover_effect cg_view_blog cg_view_switcher_off $cg_hide_off' ></span>";
                    echo "<span  id='cg_view_switcher_on_$key' data-cg-tooltip='$language_BlogView' class='$cgFeControlsStyle cg_view_switcher cg_hover_effect cg_view_blog cg_view_switcher_on $cg_hide_on' ></span>";
                }

            }

            if(!empty($options['visual']['EnableSwitchStyleGalleryButton'])){

                $switchColorsTooltipStyleText = $language_BrightStyle;
                if($cgFeControlsStyle == 'cg_fe_controls_style_white'){
                    $switchColorsTooltipStyleText = $language_DarkStyle;
                }

                echo "<div class='cg_switch_colors cg_gallery_control_element cg_hover_effect'  data-cg-tooltip='$switchColorsTooltipStyleText' data-cg-gid='$galeryIDuserForJs'></div>";
            }

            //echo "<span class='cg_view_switcher_stabilize' style='width:40px;height:40px;display:inline-block;margin:0;padding:0;'></span>";
            echo "</div>";

        }


    }


    if($FullScreenGallery == 1 or $FullSizeGallery==1 or $SliderFullWindow=1){

        echo "<div class='cg-fullsize-div'>";

        if($FullScreenGallery==1){
            echo  "<div class='cg_hover_effect cg-center-image-fullscreen' data-cg-gid='$galeryIDuserForJs'></div>";
        }

        echo "<div class='cg_hover_effect cg-center-image-close-fullwindow cg_hide $cgFeControlsStyle' id='cgCenterImageClose$galeryIDuserForJs' data-cg-gid='$galeryIDuserForJs' data-cg-tooltip='$language_CloseView'></div>";

        if($FullSizeGallery==1){
            echo "<div class='cg_hover_effect cg-center-image-fullwindow cg_hover_effect cg_gallery_control_element $cgFeControlsStyle' id='cgCenterImageFullwindowHeader$galeryIDuserForJs' data-cg-gid='$galeryIDuserForJs' data-cg-tooltip='$language_FullWindow'></div>";
        }

        // have to be both true because full screen (full size) does not work without full window (full size gallery)
        if($FullSizeGallery==1 && $FullSize==1){
            echo "<div class='cg_hover_effect cg-fullscreen-button cg-header-controls-show-only-full-window cg_hide $cgFeControlsStyle' id='cgFullScreenButton$galeryIDuserForJs' data-cg-gid='$galeryIDuserForJs' data-cg-tooltip='$language_FullScreen'></div>";
        }

        // because of float right at the end
        echo "<div class='cg_hover_effect cg-fullwindow-configuration-button cg-header-controls-show-only-full-window cg_hide $cgFeControlsStyle' id='cgCenterImageFullWindowConfiguration$galeryIDuserForJs' data-cg-gid='$galeryIDuserForJs' data-cg-tooltip='$language_SearchOrSort'></div>";

       /* if($isInGalleryUpload){
            echo "<div class='cg-gallery-upload cg-header-controls-show-only-full-window cg_hide $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs'>";
            echo "</div>";
        }*/

        echo "</div>";

    }

    if($GalleryUpload){
        include('gallery-upload-form.php');
    }

    echo "</div>";

}
}

