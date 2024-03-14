<?php
if (!defined('WPINC')) {
    die;
}

// Value
$lgx_brand_align        = $lgx_generator_meta['lgx_header_align'];
$lgx_header_title       = $lgx_generator_meta['lgx_header_title'];
$lgx_header_subtitle    = $lgx_generator_meta['lgx_header_subtitle'];
?>

<div class="lgx_app_header lax_app_text_<?php echo $lgx_brand_align;?>">
    <?php echo (!empty($lgx_header_title) ? '<h2 class="lgx_app_header_title">'.$lgx_header_title.'</h2>' : '' );?>
    <?php echo (!empty($lgx_header_subtitle) ? '<div class="lgx_app_header_subtitle">'.$lgx_header_subtitle.'</div>' : '' );?>
</div> <!--//.HEADER END-->