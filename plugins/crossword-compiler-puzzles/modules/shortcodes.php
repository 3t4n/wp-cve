<?php

add_shortcode('crossword', 'ccpuz_shortcode_handler');

//[crossword id="123"] or [crossword]
function ccpuz_shortcode_handler($atts, $content = null) {
    global $post;

    $post_id = (isset($atts["id"])) ? (int) $atts["id"] : (int) @$post->ID;
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log(">ccpuz_shortcode_handler post_id = $post_id\n" . print_r($atts, true));
    if ($post_id <= 0) {
        return "";
    }
    $js_url = get_post_meta($post_id, 'ccpuz_js_url', true);
    $js_run = get_post_meta($post_id, 'ccpuz_js_run', true);
    if (empty($js_url)) {
        #old version
        $js_url = get_post_meta($post_id, 'js_url', true);
        $js_run = get_post_meta($post_id, 'js_run', true);
    }
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log("1.ccpuz_shortcode_handler post_id = $post_id\n$js_url\n" . $js_run);

    $out = '';

    $out .= '<script src="' . $js_url . '"></script>' . $js_run . '<div id="CrosswordCompilerPuz"></div>';


    //$out = '<iframe class="ccpuz_preview_iframe" src="'.admin_url('admin-ajax.php').'?action=ccpuz_preview_local&id='.$post_id.'" frameborder=0 scrolling="no" width="100%" />';
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log("<ccpuz_shortcode_handler post_id = $post_id\n" . $out);
    return $out;
}
