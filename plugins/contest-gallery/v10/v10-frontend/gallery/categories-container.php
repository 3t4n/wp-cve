<?php

$cg_cat_checkbox_checked = 'cg_cat_checkbox_checked';

if($ShowCatsUnchecked==1){
    $cg_cat_checkbox_checked = 'cg_cat_checkbox_unchecked';
}

$heredoc = <<<HEREDOC
<div id="cgCatSelectAreaContainer$galeryIDuserForJs" class="cg-cat-select-area-container $cgFeControlsStyle">
<div id="cgCatSelectArea$galeryIDuserForJs" class="cg-cat-select-area $cgFeControlsStyle">
       <label class="$cg_cat_checkbox_checked cg_select_cat_label cg_hover_effect">
            <span class="cg_select_cat" ></span>
            <span class="cg_select_cat_check_icon"></span>
       </label>
</div>
    <div class="cg-cat-select-area-show-more cg_hide" data-cg-tooltip="$language_ShowAllCategories"></div>
    <div class="cg-cat-select-area-show-less cg_hide"  data-cg-tooltip="$language_ShowLessCategories"></div>
</div>
HEREDOC;

echo $heredoc;

?>