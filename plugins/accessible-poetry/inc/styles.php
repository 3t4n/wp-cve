<?php

function acwp_contrast_customcolors_output() {
    $custom_contrast = get_option('acwp_contrast_custom');
    $custom_titles = get_option('acwp_titles_customcolors');
    $custom_links = get_option('acwp_links_customcolors');
    
    $bg_option = get_option('acwp_contrast_bgs');
    $txt_option = get_option('acwp_contrast_txt');
    $contrast_background_color = $bg_option != '' ? 'background-color: ' . $bg_option . ';' : '';
    $contrast_background_color_important = $bg_option != '' ? 'background-color: ' . $bg_option . ' !important;' : '';
    $contrast_text_color = $txt_option != '' ? 'color: ' . $txt_option . ';' : '';
    $contrast_text_color_important = $txt_option != '' ? 'color: ' . $txt_option . ' !important;' : '';
    $contrast_lnk = get_option('acwp_contrast_links');
    
    $titles_bg_option = get_option('acwp_titles_bg');
    $titles_background_color = $titles_bg_option != '' ? 'background-color:' . $titles_bg_option . ';' : '';
    $titles_background_color_important = $titles_bg_option != '' ? 'background-color:' . $titles_bg_option . ' !important;' : '';
    
    $titles_txt_option = get_option('acwp_titles_txt');
    $titles_text_color = $titles_txt_option != '' ? 'color:' . $titles_txt_option . ';' : '';
    $titles_text_color_important = $titles_txt_option != '' ? 'color:' . $titles_txt_option . ' !important;' : '';
    
    $links_bg_option = get_option('acwp_links_bg');
    $links_background_color = $links_bg_option != '' ? 'background-color:' . $links_bg_option . ';' : '';
    $links_background_color_important = $links_bg_option != '' ? 'background-color:' . $links_bg_option . ' !important;' : '';

    $links_txt = get_option('acwp_links_txt');
    $links_text_color = $links_txt != '' ? 'color:' . $links_txt . ';' : '';
    $links_text_color_important = $links_txt != '' ? 'color:' . $links_txt . ' !important;' : '';

    if(
        $custom_contrast == 'yes' ||
        $custom_titles == 'yes' ||
        $custom_links == 'yes'
    ) :
    ?>
<style>
<?php if( $custom_contrast == 'yes' ) : ?>
    body.acwp-contrast-custom.acwp-contrast * {
    <?php 
        echo $contrast_background_color;
        echo $contrast_text_color;
    ?>
    }
    body.acwp-contrast-custom.acwp-contrast.acwp-contrast-hardcss * {
    <?php 
        echo $contrast_background_color_important;
        echo $contrast_text_color_important;
    ?>
    }
    
    <?php if( $contrast_lnk != '' ) : ?>
        body.acwp-contrast-custom.acwp-contrast button,
        body.acwp-contrast-custom.acwp-contrast a {
            color: <?php echo $contrast_lnk;?>;
        }
    <?php endif; // close $contrast_lnk ?>
<?php endif; // close acwp_contrast_custom ?>
        
<?php if( 
    $custom_titles == 'yes' &&
    ($titles_bg != '' || $titles_txt != '') 
) : ?>
    body.acwp-marktitles.acwp-titles-custom h1,
    body.acwp-marktitles.acwp-titles-custom h2,
    body.acwp-marktitles.acwp-titles-custom h3 {
        <?php
        echo $titles_background_color;
        echo $titles_text_color;
        ?>
    }
    body.acwp-marktitles.acwp-titles-custom.acwp-titles-hardcss h1,
    body.acwp-marktitles.acwp-titles-custom.acwp-titles-hardcss h2,
    body.acwp-marktitles.acwp-titles-custom.acwp-titles-hardcss h3 {
        <?php
        echo $titles_background_color_important;
        echo $titles_text_color_important;
        ?>
    }
<?php endif; // close acwp_titles_customcolors ?>
        
<?php if(
    $custom_links == 'yes' &&
    ( $links_bg != '' || $links_txt != '' )
) : ?>
    body.acwp-marklinks.acwp-links-custom a,
    body.acwp-marklinks.acwp-links-custom button {
        <?php 
        echo $links_background_color;
        echo $links_text_color;
        ?>
    }
    body.acwp-marklinks.acwp-links-custom.acwp-links-hardcss a,
    body.acwp-marklinks.acwp-links-custom.acwp-links-hardcss button {
        <?php 
        echo $links_background_color_important;
        echo $links_text_color_important;
        ?>
    }
<?php endif; // close acwp_links_customcolors ?>
</style>
<?php
    endif;
}
add_filter( 'wp_head', 'acwp_contrast_customcolors_output');
