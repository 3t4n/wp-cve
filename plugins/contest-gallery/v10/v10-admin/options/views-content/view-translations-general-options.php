<?php

echo <<<HEREDOC
<div class='cg_view_container cg_view_container_translations'>
HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_rows_container'>
         <p class='cg_view_options_rows_container_title' style="line-height:25px;"><strong>Translations here will replace language files translations.</strong><br>HTML tags can not be used in translations.</p>
</div>
HEREDOC;

include(__DIR__ ."/../../../../check-language-general.php");

$cgShortcodeCopy = '<span class="td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip td_gallery_translation_edit"></span>';

include(__DIR__.'/translations-options/translations-menu-bar-edit-profile-area.php');

echo <<<HEREDOC
</div>
HEREDOC;


