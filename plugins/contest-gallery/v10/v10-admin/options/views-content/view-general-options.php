<?php

echo <<<HEREDOC
<div class='cg_view_container'>
HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_rows_container' >
HEREDOC;

echo <<<HEREDOC
<p class="cg_view_options_rows_container_title">
    <strong>NOTE:</strong> General options are valid for all galleries.<br>
</p>
HEREDOC;

$CgEntriesOwnSlugNameDisabled = '';

if($galleryDbVersion<21){
    $CgEntriesOwnSlugNameDisabled = 'cg_disabled';
    echo <<<HEREDOC
<p class="cg_view_options_rows_container_title">
    <strong style="color:red;">NOTE:</strong> 
    General options are only available for galleries created or copied in version 21.0.0 and higher.
    <br>Copy this gallery or create a new one to release the full potential of plugin.<br>
    Real Custom Post Type pages with URLs behind the entries will be available and improved gallery frontend look.<br>
    Further Gallery and Entry options will get visible.
</p>
HEREDOC;
}

$CgEntriesOwnSlugNameMultisiteNote = '';
//if(is_multisite()){
    //$CgEntriesOwnSlugNameMultisiteNote = '<span class="cg_font_weight_500" style="color: red;">NOTE FOR MULTISITE:</span> The "slug name" is valid for all sites of a multisite.<br>';
//}

$CgEntriesOwnSlugNameYoursExample = '';
if(!empty($CgEntriesOwnSlugName)){
    $CgEntriesOwnSlugNameYoursExample = "<span class=\"cg_font_weight_500\">Yours example:</span> $bloginfo_wpurl/<span class=\"cg_font_weight_500\">$CgEntriesOwnSlugName</span>/contest-gallery-id-{gallery_id}/example-entry<br>";
}

echo <<<HEREDOC
        <div class='cg_view_options_row cg_margin_bottom_30' >
            <div class='cg_view_option cg_view_option_full_width $CgEntriesOwnSlugNameDisabled '>
                <div class='cg_view_option_title'>
                    <p>Own custom post type URL slug name for entries<br>
                    <span class="cg_view_option_title_note">
                    Default: <span class="cg_font_weight_500">contest-gallery</span><br>
                    <span class="cg_font_weight_500">Default example:</span> $bloginfo_wpurl/<span class="cg_font_weight_500">contest-gallery</span>/contest-gallery-id-{gallery_id}/example-entry<br>
                    $CgEntriesOwnSlugNameYoursExample
                    </span>
                    </p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" placeholder="Add own slug name" class="cg-long-input" id="CgEntriesOwnSlugName" name="CgEntriesOwnSlugName" maxlength="100" value="$CgEntriesOwnSlugName">
                     <input type="hidden" name="CgEntriesOwnSlugNameChanged" id="CgEntriesOwnSlugNameChanged" disabled value="true" >
                </div>
                <div class='cg_view_option_title'>
                    <p>
                    <span class="cg_view_option_title_note">
                    If empty then default <span class="cg_font_weight_500">contest-gallery</span> will be used.<br>
                    <span class="cg_font_weight_500" style="color: red;">NOTE:</span> Uses WordPress built in flush_rewrite_rules() function on change.<br>
                        <span class="cg_font_weight_500" style="color: red;">NOTE:</span> Links of already generated Contest Gallery entries will change.<br>The old links will not redirect. So if you shared some links already they will not redirect.<br>
                        $CgEntriesOwnSlugNameMultisiteNote
                    </span>
                    </p>
                </div>
            </div>
        </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;


