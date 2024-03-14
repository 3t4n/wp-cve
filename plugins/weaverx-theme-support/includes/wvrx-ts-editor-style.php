<?php
// ======================================= MCE CSS FILE GENERATION ================================

/**
 *    Generate and save mcecss and Gutenberg style file.
 *
 *    This function generates custom CSS files for the tinyMCE and Gutenberg editors. It allows the
 *    editors to display close to WYSIWYG viewing in the visual editors.
 *
 * @param string $usename - name of the css file to save to
 * @param string $editor - mce or gutenberg
 *
 * @since 3.0
 * @since 4.0 for Gutenberg
 *
 * @uses - weaverx_ts_output_edit_style to generate the css
 *
 */
function weaverx_ts_save_editor_css(string $usename, string $editor): string
{

    if (!current_user_can('edit_theme_options')) {
        return '';
    }

    $save_dir = weaverx_f_uploads_base_dir() . 'weaverx-subthemes';
    $save_url = weaverx_f_uploads_base_url() . 'weaverx-subthemes';

    $theme_dir_exists = wp_mkdir_p($save_dir);    // direct to wp mkdir
    if (!$theme_dir_exists) {
        weaverx_f_file_access_fail(__('Unable to create directory to save editor style file. Probably a file system permission problem. Directory', 'weaver-xtreme' /*adm*/) . $save_dir);
        return '';
    }

    $theme_dir_writable = $theme_dir_exists;

    if (!@is_writable($save_dir)) {        // direct php access
        weaverx_f_file_access_fail(__('Directory not writable to save editor style file. Probably a file system permission problem. Directory: ', 'weaver-xtreme' /*adm*/) . $save_dir);
        return '';
    }

    $filename = $save_dir . '/' . $usename;    // we will add txt

    if (!$theme_dir_writable || !$theme_dir_exists || !($handle = fopen($filename, 'w'))) {    // Direct php
        weaverx_f_file_access_fail(__('Unable to create editor style file. Probably a file system permission problem. File: ', 'weaver-xtreme' /*adm*/) . $filename);
        return '';
    }

    fwrite($handle, weaverx_ts_output_edit_style($editor));        // this is where the real work happens

    if (!fclose($handle)) {
        weaverx_f_file_access_fail(__('Unable to create editor css file. Probably a file system permission problem. File: ', 'weaver-xtreme' /*adm*/) . $filename);
        return '';
    }

    return $save_url . '/' . $usename;
}

//--

/**
 * The following two actions are invoked to generate the custom WP editor (tinyMCE or Gutenberg) CSS
 *
 * We will always generate both versions since it doesn't hurt to have them even if they don't get used.
 *
 * @since: 4.0
 *
 */
function weaverx_ts_save_mcecss(): void
{
    weaverx_ts_save_editor_css('editor-style-wvrx.css', 'mce');            // generate mce version
}

add_action('weaverx_save_mcecss', 'weaverx_ts_save_mcecss');        // theme support plugin saved editor css in file

function weaverx_ts_save_gutenberg_css(): void
{
    weaverx_ts_save_editor_css('gutenberg-editor-style-wvrx.css', 'gutenberg');            // generate gutenberg version
}

add_action('weaverx_save_gutenberg_css', 'weaverx_ts_save_gutenberg_css');        // theme support plugin saved editor css in file

/**
 * Get option value for content cascade
 *
 * @since:    4.0
 *
 */
function weaverx_t_get_cascade_opt($option, $default): string
{
    // we need to get the option from the wrapper -> container -> content CSS cascade

    $val = weaverx_getopt_default("content$option", $default);    // content 1st
    if ($val != $default)
        return $val;
    $val = weaverx_getopt_default("container$option", $default);    // then container
    if ($val != $default)
        return $val;
    return weaverx_getopt_default("wrapper$option", $default);    // wrapper or default last
}

//--

/**
 * Set the font size multiplier for title fonts
 *
 * @param string $font_size The name of the font size
 *
 * @returns float The title font multiplier
 *
 * @since 4.0
 *
 */
function weaverx_ts_fontsize_mult(string $font_size, $type = 'standard'): float|int
{
    // font multiplier for titles
    // uses same font size tags as normal, but with different multipliers

    switch ($font_size) {        // find conversion factor
        case 'xxs-font-size':
            $title_fontmult = 0.875;
            $std_fontmult = 0.625;
            break;
        case 'xs-font-size':
            $title_fontmult = 1;
            $std_fontmult = 0.75;
            break;
        case 's-font-size':
            $title_fontmult = 1.25;
            $std_fontmult = 0.875;
            break;
        case 'm-font-size':
            $title_fontmult = 1.5;
            $std_fontmult = 1.0;
            break;
        case 'l-font-size':
            $title_fontmult = 1.875;
            $std_fontmult = 1.125;
            break;
        case 'xl-font-size':
            $title_fontmult = 2.25;
            $std_fontmult = 1.25;
            break;
        case 'xxl-font-size':
            $title_fontmult = 2.625;
            $std_fontmult = 1.5;
            break;
        default:
            $title_fontmult = 2.25;
            $std_fontmult = 1;
            break;
    }

    if ($type == 'standard')
        return $std_fontmult;
    return $title_fontmult;
}


/**
 * Generate the CSS for the tinyMCE and Gutenberg fixup CSS files.
 *
 * This function does all the work to generate tinyMCE or gutenberg specific editor CSS
 * so the editor's visual mode matches the actual styling to match the theme's styling.
 *
 * @since 4.0
 *
 * @todo    @@@@@ Add content header (h1-h6) styling
 *
 */
function weaverx_ts_output_edit_style($editor = 'mce'): string
{

    $put = sprintf("/* WARNING: Do not edit this file. It is dynamically generated. Any edits you make will be overwritten. */
/* This file generated using %s %s subtheme: %s */\n", WEAVERX_THEMENAME, WEAVERX_VERSION, weaverx_getopt('themename'));

    if ($editor == 'mce') {
        $content_body = '.mceContentBody ';
        $selector = ".mce-content-body,body";
        $selector_tr = ".mce-content-body,body,tr,td,label,th,thead th";
        $selector_font = $selector_tr;
        $selector_bg = $selector;
    } else {
        $content_body = '.mce-content-body ';
        $selector = ".mce-content-body, *[class^='wp-block-'],.mce-content-body p ";
        $selector_tr = ".mce-content-body,.mce-content-body *[class^='wp-block-'],.mce-content-body p,.mce-content-body tr,.mce-content-body td,.mce-content-body label,.mce-content-body th,.mce-content-body thead th ";
        $selector_font = $selector_tr . ',.editor-block-list__block:not(.is-multi-selected) .wp-block-paragraph';
        $selector_bg = '.edit-post-visual-editor';
    }


    //  		====== WIDTHS ======


    if (($twidth = weaverx_getopt_default('theme_width_int', WEAVERX_THEME_WIDTH))) {
        /*  figure out a good width - we will please most of the users, most of the time
            We're going to assume that mostly people will use the default layout -
            we can't actually tell if the editor will be for a page or a post at this point.
            And let's just assume the default sidebar widths.
        */


        if ($editor == 'mce') {
            //  calculate theme width based on default layout and width of sidebars.
            $sb_layout = weaverx_getopt_default('layout_default', 'right');
            switch ($sb_layout) {
                case 'left':
                case 'left-top':
                    $sb_width = weaverx_getopt_default('left_sb_width_int', 25);
                    break;
                case 'split':
                case 'split-top':
                    $sb_width = weaverx_getopt_default('left_split_sb_width_int', 25)
                        . weaverx_getopt_default('right_split_sb_width_int', 25);
                    break;
                case 'one-column':
                    $sb_width = 0;
                    break;
                default:            // right
                    $sb_width = weaverx_getopt_default('right_sb_width_int', 25);
                    break;
            }

            $content_w = $twidth - ($twidth * (float)($sb_width / 95.0));   // .95 by trial and error

            $put .= "html $content_body{max-width:96%;width:" . $content_w . "px;}\n";
            $put .= "#content html $content_body {max-width:96%;width:96%;}\n";
        } else {
            $put .= "@media screen and ( min-width: 770px ) {
    .edit-post-visual-editor .editor-post-title,.edit-post-visual-editor .editor-block-list__block{max-width:{$twidth}px;}}\n";
        }
    }

    // 		====== FONTS ======

    // ** FONT FAMILY

    $font_family = weaverx_t_get_cascade_opt('_font_family', 'inherit');

    if ($font_family != 'inherit') {        // found a font {
        // these are not translatable - the values are used to define the actual font definition

        $fonts = weaverx_ts_get_font_family();

        if (isset($fonts[$font_family])) {
            $font = $fonts[$font_family];
        } else {
            $font = "Arial,'Helvetica Neue',Helvetica,sans-serif";   // fallback
            // scan Google Fonts
            $gfonts = weaverx_getopt_array('fonts_added');
            if (!empty($gfonts)) {
                foreach ($gfonts as $gfont) {
                    $slug = sanitize_title($gfont['name']);
                    if ($slug == $font_family) {
                        $font = str_replace('font-family:', '', $gfont['family']);//'Papyrus';
                        break;
                    }
                }
            }
        }
        $put .= "$selector_font{font-family:$font;}\n";
        if ($editor == 'gutenberg')
            $put .= ".wp-block-heading {font-family:$font;}\n"; // override Gutenberg defaults for 1-3
    }


    // ** FONT SIZE **

    if (($base_font_px = weaverx_getopt('site_fontsize_int')) == '')
        $base_font_px = 16;
    $base_font_px = (float)$base_font_px;

    $font_size = weaverx_t_get_cascade_opt('_font_size', 'default');

    $fontmult = weaverx_ts_fontsize_mult($font_size, 'standard');

    $em_font_size = ($base_font_px / 16.0) * $fontmult;
    $put .= "$selector_font{font-size:" . $em_font_size . "em;}\n";


    // ** FONT STYLE / WEIGHT **
    // valusew will be 'on' or 'off' or '', unless checkbox
    // wrapper_bold, wrapper_italic, container_bold, container_italic, content_bold, content_italic, page_title_italic, page_title_normal (weight - checkbox)
    // content_h_italic, content_h_normal (weight, checkbox)

    $font_style = weaverx_t_get_cascade_opt('_italic', '');
    if ($font_style != '') {
        $italic = ($font_style == 'on') ? 'italic' : 'normal';
        $put .= "$selector_font{font-style:$italic;}\n";
    }

    $font_style = weaverx_t_get_cascade_opt('_bold', '');
    if ($font_style != '') {
        $italic = ($font_style == 'on') ? 'bold' : 'normal';
        $put .= "$selector_font{font-weight:$italic;}\n";
    }


    // ====== BG COLOR ====

    /* need to handle bg color of content area - need to do the cascade */


    if (($val = weaverx_getopt_default('editor_bgcolor', 'inherit')) && $val != 'inherit') {            /* alt bg color */
        $put .= "$selector_bg{background:" . $val . ";padding:10px;}\n";
    } else if (($val = weaverx_getopt_default('content_bgcolor', 'inherit')) && $val != 'inherit') {    /* #content */
        $put .= "$selector_bg{background:" . $val . ";padding:10px;}\n";
    } else if (($val = weaverx_getopt_default('container_bgcolor', 'inherit')) && $val != 'inherit') { /* #container */
        $put .= "$selector_bg{background:" . $val . ";padding:10px;}\n";
    } else if (($val = weaverx_getopt_default('wrapper_bgcolor', 'inherit')) && $val != 'inherit') {    /* #wrapper */
        $put .= "$selector_bg{background:" . $val . ";padding:10px;}\n";
    } else if (($val = weaverx_getopt_default('body_bgcolor', 'inherit')) && $val != 'inherit') {    /* Outside BG */
        $put .= "$selector_bg{background:" . $val . ";padding:10px;}\n";
    } else if (($name = weaverx_getopt('themename')) && strpos($name, 'Transparent Dark') !== false) {    // fix in V3.0.5
        $put .= "$selector_bg{background:#222;}\n";
    } else if (($name = weaverx_getopt('themename')) && strpos($name, 'Transparent Light') !== false) {
        $put .= "$selector_bg{background:#ccc;}\n";
    }


    // ==== text color ====

    $text_color = weaverx_t_get_cascade_opt('_color', 'inherit');        // text color
    if ($val != 'inherit')
        $put .= "$selector_tr{color:" . $text_color . ";}\n";


    // Headings - gutenberg only
    // content_h_font_family
    // ** header font size **
    // content_h_font_size (font size name)
    // ** FONT STYLE / WEIGHT **
    // valusew will be 'on' or 'off' or '', unless checkbox
    // wrapper_bold, wrapper_italic, container_bold, container_italic, content_bold, content_italic, page_title_italic, page_title_normal (weight - checkbox)
    // content_h_italic, content_h_normal (weight, checkbox)

    if ($editor == 'gutenberg') {

        // ---- font family

        $font_family = weaverx_getopt_default('content_h_font_family', 'inherit');

        if ($font_family != 'inherit') {        // found a font {
            $fonts = weaverx_ts_get_font_family();
            if (isset($fonts[$font_family])) {    // overide default
                $font = $fonts[$font_family];
                $put .= ".wp-block-heading,.wp-block-heading h4,.wp-block-heading h5,.wp-block-heading h6,.mce-content-body h1,.mce-content-body h2,.mce-content-body h3,.mce-content-body h4,.mce-content-body h5,.mce-content-body h6{font-family:$font;}\n";
            }
        }

        // we will just use the default font size for the headings - not worth it to calculate 6 sizes here...

        // ---- bold

        if (weaverx_getopt_checked('content_h_normal'))        // bold off?
            $put .= ".wp-block-heading h1,.wp-block-heading h2,.wp-block-heading h3,.wp-block-heading h4,.wp-block-heading h5,.wp-block-heading h6,.mce-content-body h1,.mce-content-body h2,.mce-content-body h3,.mce-content-body h4,.mce-content-body h5,.mce-content-body h6{font-weight:normal;}\n";

        // ---- italic

        $italic = weaverx_getopt('content_h_italic');
        if ($italic == 'off')
            $put .= ".wp-block-heading h1,.wp-block-heading h2,.wp-block-heading h3,.wp-block-heading h4,.wp-block-heading h5,.wp-block-heading h6,.mce-content-body h1,.mce-content-body h2,.mce-content-body h3,.mce-content-body h4,.mce-content-body h5,.mce-content-body h6{font-style:normal;}\n";
        else if ($italic == 'on')
            $put .= ".wp-block-heading h1,.wp-block-heading h2,.wp-block-heading h3,.wp-block-heading h4,.wp-block-heading h5,.wp-block-heading h6,.mce-content-body h1,.mce-content-body h2,.mce-content-body h3,.mce-content-body h4,.mce-content-body h5,.mce-content-body h6{font-style:italic;}\n";

        // ---- text color

        $color = weaverx_getopt_default('content_h_color', 'inherit');
        if ($color != 'inherit') {
            $put .= ".wp-block-heading h1,.wp-block-heading h2,.wp-block-heading h3,.wp-block-heading h4,.wp-block-heading h5,.wp-block-heading h6,.mce-content-body h1,.mce-content-body h2,.mce-content-body h3,.mce-content-body h4,.mce-content-body h5,.mce-content-body h6{color: $color;}\n";
        }

        $bgcolor = weaverx_getopt_default('content_h_bgcolor', 'inherit');
        if ($bgcolor != 'inherit') {
            $put .= ".wp-block-heading h1,.wp-block-heading h2,.wp-block-heading h3,.wp-block-heading h4,.wp-block-heading h5,.wp-block-heading h6,.mce-content-body h1,.mce-content-body h2,.mce-content-body h3,.mce-content-body h4,.mce-content-body h5,.mce-content-body h6{background-color: $bgcolor;}\n";
        }
        $css = $color = weaverx_getopt_default('content_h_bgcolor_css', 'inherit');
        if ($css) {
            $put .= ".wp-block-heading h1,.wp-block-heading h2,.wp-block-heading h3,.wp-block-heading h4,.wp-block-heading h5,.wp-block-heading h6,.mce-content-body h1,.mce-content-body h2,.mce-content-body h3,.mce-content-body h4,.mce-content-body h5,.mce-content-body h6$css\n";
        }

        // ---- editor spacing fix-up - not perfect, but closer to reality

        $put .= ".wp-block-heading h1,.wp-block-heading h2,.wp-block-heading h3,.wp-block-heading h4,.wp-block-heading h5,.wp-block-heading h6{line-height:1;margin-bottom:-.5em;margin-top:0;}\n";

    }


    // ====== PAGE TITLE (Don't use post values) ======

    // page_title_bgcolor, page_title_color, page_title_font_size, page_title_font_family, page_title_normal (weight/checkbox), page_title_italic

    if ($editor == 'gutenberg') {            // Gutenberg shows the page title
        $font_family = weaverx_getopt_default('page_title_font_family', 'inherit');
        if ($font_family != 'inherit') {
            $fonts = weaverx_ts_get_font_family();
            if (isset($fonts[$font_family])) {
                $font = $fonts[$font_family];
                $put .= ".edit-post-layout .editor-post-title textarea{font-family:$font !important;}\n";
            }
        }

        $title_color = weaverx_getopt_default('page_title_color', 'inherit');
        if ($title_color == 'inherit')
            $title_color = $text_color;
        $put .= ".edit-post-layout .editor-post-title textarea{color:$title_color !important;}\n";


        $title_bgcolor = weaverx_getopt_default('page_title_bgcolor', 'inherit');
        if ($title_bgcolor != 'inherit')
            $put .= ".edit-post-layout .editor-post-title textarea{background-color:$title_bgcolor !important;}\n";


        $title_css = weaverx_getopt_default('page_title_bgcolor_css', 'inherit');
        if ($title_css)
            $put .= ".edit-post-layout .editor-post-title textarea$title_css\n";


        $font_size = weaverx_getopt_default('page_title_font_size', 'xl-font-size-title');
        $em_fontmult = weaverx_ts_fontsize_mult($font_size, 'title');
        $em_font_size = ($base_font_px / 16.0) * $em_fontmult;
        $put .= ".edit-post-layout .editor-post-title{font-size:" . $em_font_size . "em;} /* size: $font_size mult:$em_fontmult em-size:$em_font_size */\n";

        if (weaverx_getopt_checked('page_title_normal'))        // bold off?
            $put .= ".edit-post-layout .editor-post-title{font-weight:normal;}\n";

        $italic = weaverx_getopt('page_title_italic');
        if ($italic == 'off')
            $put .= ".edit-post-layout .editor-post-title{font-style:normal;}\n";
        else if ($italic == 'on')
            $put .= ".edit-post-layout .editor-post-title{font-style:italic;}\n";

        // bar under some titles
        if (($val = (int)weaverx_getopt('page_title_underline_int'))) {
            if ($title_color == '' || $title_color == 'inherit') $title_color = '#222'; /* if they want a border, this is the fallback color */
            $put .= ".edit-post-layout .editor-post-title{border-bottom: {$val}px solid $title_color;}\n";
        }
    }


    // ====== OTHER ELEMENTS ======

    if (($val = weaverx_getopt('input_bgcolor'))) {    // input area
        $put .= "input, textarea, ins, pre{background:" . $val . ";}\n";
    }

    if (($val = weaverx_getopt('input_color'))) {
        $put .= "input, textarea, ins, del, pre {color:" . $val . ";}\n";
    }

    if (($val = weaverx_getopt('contentlink_color'))) {    // link
        $put .= "a {color:" . $val . ";}\n";
    }

    if (($val = weaverx_getopt('contentlink_hover_color'))) {
        $put .= "a:hover {color:" . $val . ";}\n";
    }


//  ====== TABLES ======

    /*  weaverx_tables  */
    $table = weaverx_getopt('weaverx_tables');

    if ($table == 'wide') {    // make backward compatible with 1.4 and before when Twenty Ten was default
        $put .= "{$content_body}table {border: 1px solid #e7e7e7 !important;margin: 0 -1px 24px 0;text-align: left;width: 100%%;}
tr th, thead th {color: #888;font-size: 12px;font-weight: bold;line-height: 18px;padding: 9px 24px;}
$content_body tr td {border-style:none !important; border-top: 1px solid #e7e7e7 !important; padding: 6px 24px;}
tr.odd td {background: rgba(0,0,0,0.1);}\n";
    } elseif ($table == 'bold') {
        $put .= "{$content_body}table {border: 2px solid #888 !important;}
tr th, thead th {font-weight: bold;}
{$content_body}tr td {border: 1px solid #888 !important;}\n";
    } elseif ($table == 'noborders') {
        $put .= "{$content_body}table {border-style:none !important;}
{$content_body}tr th, {$content_body}thead th {font-weight: bold;border-bottom: 1px solid #888 !important;background-color:transparent;}
{$content_body}tr td {border-style:none !important;}\n";
    } elseif ($table == 'fullwidth') {
        $put .= "table {width:100%%;}
tr th, thead th {font-weight:bold;}\n";
    } elseif ($table == 'plain') {
        $put .= "{$content_body}table {border: 1px solid #888 !important;text-align:left;margin: 0 0 0 0;width:auto;}
tr th, thead th {color: inherit;background:none;font-weight:normal;line-height:normal;padding:4px;}
{$content_body}tr td {border: 1px solid #888 !important; padding:4px;}\n";
    }


    // ====== LISTS ======

    if (($val = weaverx_getopt('contentlist_bullet'))) {    // list bullet
        if ($val != '' && $val != 'disc') {
            if ($val != 'custom') {
                $put .= "ul {list-style-type:$val;}\n";
            }
        }
    }

    // ====== images ======

    // these work for both tinyMCD and Gutenberg

    if (($val = weaverx_getopt('caption_color'))) {    // image caption, border color, width
        $put .= ".wp-caption p.wp-caption-text,.wp-caption-dd {color:$val;}\n";
    }

    if (($val = weaverx_getopt('media_lib_border_color'))) {
        $put .= ".wp-caption, img {background:$val;}\n";
    }
    if (($val = weaverx_getopt('media_lib_border_int'))) {
        $caplr = $val - 5;
        if ($caplr < 0)
            $caplr = 0;
        $put .= "img {padding:{$val}px;}\n";
        $put .= sprintf(".wp-caption {padding: %dpx %dpx %dpx %dpx;}\n", $val, $caplr, $val, $caplr);
    }
    if (weaverx_getopt_checked('show_img_shadows')) {
        $put .= 'img {box-shadow: 0 0 2px 1px rgba(0,0,0,0.25) !important;}' . "\n";
    }


    // ====== <hr> ======

    if (($color = weaverx_getopt('hr_color')) && $color != 'inherit') {
        $put .= "hr{background-color:$color;}\n";
    }
    if (($css = weaverx_getopt('hr_color_css'))) {
        $put .= "hr$css\n";
    }

    // ====== LINKS - link_color, link_strong, link_em, link_u, link_u_h, link_hover_color ======

    $put .= 'a{';

    if (($val = weaverx_getopt('link_color')))
        $put .= "color:$val;";

    $val = weaverx_getopt('link_strong');
    if ($val == 'on')
        $put .= "font-weight:bold;";
    else if ($val == 'off')
        $put .= "font-weight:normal;";

    $val = weaverx_getopt('link_em');
    if ($val == 'on')
        $put .= "font-style:italic;";
    else if ($val == 'off')
        $put .= "font-style:normal;";

    if (($val = weaverx_getopt('link_u')))
        $put .= "text-decoration:underline;";

    $put .= '}a:hover{';

    if (($val = weaverx_getopt('link_hover_color')))
        $put .= "color:$val;";
    if (($val = weaverx_getopt('link_u_h')))
        $put .= "text-decoration:underline;";
    $put .= "}\n";

    return $put;
}


/**
 * Get list of fonts
 *
 * @return array
 * @since 4.0
 *
 */
function weaverx_ts_get_font_family(): array
{
    return array(
        'sans-serif' => 'Arial,sans-serif',
        'arialBlack' => '"Arial Black",sans-serif',
        'arialNarrow' => '"Arial Narrow",sans-serif',
        'lucidaSans' => '"Lucida Sans",sans-serif',
        'trebuchetMS' => '"Trebuchet MS", "Lucida Grande",sans-serif',
        'verdana' => 'Verdana, Geneva,sans-serif',
        'alegreya-sans' => "'Alegreya Sans', sans-serif",
        'roboto' => "'Roboto', sans-serif",
        'roboto-condensed' => "'Roboto Condensed', sans-serif",
        'source-sans-pro' => "'Source Sans Pro', sans-serif",


        'serif' => 'TimesNewRoman, "Times New Roman",serif',
        'cambria' => 'Cambria,serif',
        'garamond' => 'Garamond,serif',
        'georgia' => 'Georgia,serif',
        'lucidaBright' => '"Lucida Bright",serif',
        'palatino' => '"Palatino Linotype",Palatino,serif',
        'alegreya' => "'Alegreya', serif",
        'roboto-slab' => "'Roboto Slab', serif",
        'source-serif-pro' => "'Source Serif Pro', serif",

        'monospace' => '"Courier New",Courier,monospace',
        'consolas' => 'Consolas,monospace',
        'inconsolata' => "'Inconsolata', monospace",
        'roboto-mono' => "'Roboto Mono', monospace",

        'papyrus' => 'Papyrus,cursive,serif',
        'comicSans' => '"Comic Sans MS",cursive,serif',
        'handlee' => "'Handlee', cursive",

        'open-sans' => "'Open Sans', sans-serif",
        'open-sans-condensed' => "'Open Sans Condensed', sans-serif",
        'droid-sans' => "'Droid Sans', sans-serif",
        'droid-serif' => "'Droid Serif', serif",
        'exo-2' => "'Exo 2', sans-serif",
        'lato' => "'Lato', sans-serif",
        'lora' => "'Lora', serif",
        'arvo' => "'Arvo', serif",
        'archivo-black' => "'Archivo Black', sans-serif",
        'vollkorn' => "'Vollkorn', serif",
        'ultra' => "'Ultra', serif",
        'arimo' => "'Arimo', serif",
        'tinos' => "'Tinos', serif"
    );

}


// and filter to use the generated file...

add_filter('weaverx_mce_css', 'weaverx_ts_mce_css');

function weaverx_ts_mce_css($default_style)
{
    // build mce edit css path if we've generated the editor css
    $updir = wp_upload_dir();
    // make relative for https: - doesn't work right...
    // return parse_url(trailingslashit($updir['baseurl']) . 'weaverx-subthemes/style-weaverxt.css',PHP_URL_PATH);

    $dir = trailingslashit($updir['basedir']) . 'weaverx-subthemes/editor-style-wvrx.css';

    $path = trailingslashit($updir['baseurl']) . 'weaverx-subthemes/editor-style-wvrx.css';

    if (!@file_exists($dir))
        return $default_style;

    if (is_ssl())
        $path = str_replace('http:', 'https:', $path);

    if (!$default_style)
        return $path;
    else
        return $default_style . ',' . $path;
}
