<?php

if( !defined( 'ABSPATH') ) exit();

class Hybrid_Gallery_Panel
{
    // Function Grid Shortcode Editor
    // ======================================================
    
    static function grid($args)
    {
        // Set defaults atts
        $def = array(
            'ids' => '',
            'layout' => 'grid',
            'size' => 'equal',
            'metro_style' => 1,
            'cols' => 3,
            'ratio_w' => 1,
            'ratio_h' => 1,
            'gap_x' => 10,
            'gap_y' => 10,
            'rowheight' => 240,
            'max_rowheight' => '-1',
            'lastrow' => 'nojustify',
            'img_hover' => 'false',
            'img_filter' => 'false',
            'formats' => 'false',
            'color' => '#b90000',
            'style' => 1,
            'meta_title' => 'false',
            'meta_descr' => 'false',
            'click_action' => 'lb',
            'buttons' => 'false',
            'link_tg' => 'same',
            'lightbox' => 'mp',
            'pagination' => 'false',
            'pg_type' => 'more',
            'pg_ajax' => 'false',
            'pg_posts' => 10,
            'filter' => 'false',
            'animation' => 'fadeInUp',
            'preloader' => 1,
            'loader_delay' => 300,
            'ct_w_vl' => 100,
            'ct_w_un' => 'pc',
            'ct_align' => 'none',
            'custom_class' => '',
            'custom_id' => '',
            'res_1_ss' => 1024,
            'res_1_w' => 'auto',
            'res_1_w_vl' => 100,
            'res_1_w_un' => 'pc',
            'res_1_cols' => 'auto',
            'res_1_align' => 'auto',
            'res_2_ss' => 800,
            'res_2_w' => 'auto',
            'res_2_w_vl' => 100,
            'res_2_w_un' => 'pc',
            'res_2_cols' => 'auto',
            'res_2_align' => 'auto',
            'res_3_ss' => 768,
            'res_3_w' => 'auto',
            'res_3_w_vl' => 100,
            'res_3_w_un' => 'pc',
            'res_3_cols' => 'auto',
            'res_3_align' => 'auto',
            'res_4_ss' => 600,
            'res_4_w' => 'auto',
            'res_4_w_vl' => 100,
            'res_4_w_un' => 'pc',
            'res_4_cols' => 'auto',
            'res_4_align' => 'auto',
            'res_5_ss' => 480,
            'res_5_w' => 'auto',
            'res_5_w_vl' => 100,
            'res_5_w_un' => 'pc',
            'res_5_cols' => 'auto',
            'res_5_align' => 'auto'
        );

        // configure resolution args
        $res = $args['res'];
        $res = str_replace("#", '"', $res);
        $res = json_decode($res, true);
        
        if (is_array($res)) {
            foreach ($res as $res_item => $res_key) {
                $res_num++;
            
                // get res screen args
                $res_args = $res[$res_item];
            
                // res screen
                $res_ss        = 'res_' . $res_num . '_ss';
                $args[$res_ss] = $res_item;
            
                // res screen
                $res_cols        = 'res_' . $res_num . '_cols';
                $args[$res_cols] = $res_args['cols'];
            
                // res width
                $res_w        = 'res_' . $res_num . '_w';
                $args[$res_w] = $res_args['w'];
            
                // res width (value)
                $res_w_vl        = 'res_' . $res_num . '_w_vl';
                $args[$res_w_vl] = $res_args['w_vl'];
            
                // res unit (unit)
                $res_w_un        = 'res_' . $res_num . '_w_un';
                $args[$res_w_un] = $res_args['w_un'];
            
                // res unit (align)
                $res_align        = 'res_' . $res_num . '_align';
                $args[$res_align] = $res_args['align'];
            }
        }
        
        // unset excess keys 
        $removeKeys = array(
            'panel',
            'action',
            'nonce',
            'res'
        );

        foreach ($removeKeys as $key) {
            unset($args[$key]);
        }
        
        // override new atts
        if (is_array($args)) {
            $def = array_merge($def, $args);
        }
        
        if ($def['ids']) {
            $pids = explode(',', $def['ids']);
        }
        
        $options = array(
            array(
                "type" => "cols",
                "size" => "image-buttons",
                "action" => "start",
                "class" => "hybgl-preview-table"
            ),
            array(
                "id" => "image-selector",
                "type" => "img_selector",
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "image-preview-panel",
                "class" => "hybgl-panel-preview"
            ),
            array(
                "id" => "preview",
                "type" => "preview",
                "options" => $pids
            ),
            array(
                "type" => "cols",
                "action" => "end",
            ),
            array(
                "attr" => "ids",
                "id" => "ids",
                "class" => "hybgl-field-class-ids",
                "type" => "text",
                "default" => $def['ids']
            ),
            array(
                "id" => "tab-switcher",
                "type" => "tab-switcher",
                "tabs" => array(
                    "general" => esc_html__("General", "hybrid-gallery"),
                    "image" => esc_html__("Image", "hybrid-gallery"),
                    "pagination" => esc_html__("Pagination", "hybrid-gallery"),
                    "extra" => esc_html__("Extra", "hybrid-gallery"),
                    "container" => esc_html__("Container", "hybrid-gallery"),
                    "responsive" => esc_html__("Responsive", "hybrid-gallery")
                )
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "name" => esc_html__("Layout", "hybrid-gallery"),
                "description" => esc_html__("Select layout", "hybrid-gallery"),
                "attr" => "layout",
                "id" => "layout",
                "type" => "select",
                "options" => array(
                    "grid" => esc_html__("Grid", "hybrid-gallery"),
                    "masonry" => esc_html__("Masonry", "hybrid-gallery"),
                    "metro" => esc_html__("Metro", "hybrid-gallery"),
                    "justified" => esc_html__("Justified", "hybrid-gallery")
                ),
                "default" => $def['layout']
            ),
            array(
                "name" => esc_html__("Metro: style", "hybrid-gallery"),
                "description" => esc_html__("Select a metro style", "hybrid-gallery"),
                "attr" => "metro_style",
                "id" => "metro-style",
                "type" => "select",
                "options" => array(
                    1 => esc_html__("Style #1", "hybrid-gallery"),
                    2 => esc_html__("Style #2", "hybrid-gallery"),
                    3 => esc_html__("Style #3", "hybrid-gallery")
                ),
                "default" => $def['metro_style'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => "metro"
                )
            ),
            array(
                "name" => esc_html__("Image size", "hybrid-gallery"),
                "description" => esc_html__("Select image size", "hybrid-gallery"),
                "attr" => "size",
                "id" => "size",
                "type" => "select",
                "options" => array(
                    "equal" => esc_html__("Equal", "hybrid-gallery"),
                    "fixed" => esc_html__("Fixed", "hybrid-gallery"),
                ),
                "default" => $def['size'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array(
                        "grid",
                        "metro"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Ratio width", "hybrid-gallery"),
                "description" => esc_html__("Images: aspect ratio: width", "hybrid-gallery"),
                "attr" => "ratio_w",
                "id" => "ratio_w",
                "type" => "number",
                "default" => $def['ratio_w'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array(
                        "grid",
                        "metro"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Ratio height", "hybrid-gallery"),
                "description" => esc_html__("Images: aspect ratio: height", "hybrid-gallery"),
                "attr" => "ratio_h",
                "id" => "ratio_h",
                "type" => "number",
                "default" => $def['ratio_h'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array(
                        "grid",
                        "metro"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "description" => esc_html__("Number of columns in a row", "hybrid-gallery"),
                "attr" => "cols",
                "id" => "cols",
                "type" => "select",
                "options" => array(
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['cols'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array(
                        "grid",
                        "masonry",
                        "metro"
                    )
                )
            ),
            array(
                "name" => esc_html__("Row height", "hybrid-gallery"),
                "description" => esc_html__("Height of rows in a pixel", "hybrid-gallery"),
                "attr" => "rowheight",
                "id" => "rowheight",
                "type" => "number",
                "default" => $def['rowheight'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => "justified"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Max row height", "hybrid-gallery"),
                "description" => esc_html__("Maximum row height in pixel. Note that it can crop images. Set '-1' to disable it.", "hybrid-gallery"),
                "attr" => "max_rowheight",
                "id" => "max-rowheight",
                "type" => "number",
                "default" => $def['max_rowheight'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => "justified"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Last row", "hybrid-gallery"),
                "description" => esc_html__("If last row contains less images, you can hide it or set another option.", "hybrid-gallery"),
                "attr" => "lastrow",
                "id" => "lastrow",
                "type" => "select",
                "options" => array(
                    "nojustify" => esc_html__("No Justify", "hybrid-gallery"),
                    "justify" => esc_html__("Justify", "hybrid-gallery"),
                    "hide" => esc_html__("Hide", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                ),
                "default" => $def['lastrow'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => "justified"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Gap: X", "hybrid-gallery"),
                "description" => esc_html__("Space between columns", "hybrid-gallery"),
                "attr" => "gap_x",
                "id" => "gap-x",
                "type" => "number",
                "default" => $def['gap_x']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Gap: Y", "hybrid-gallery"),
                "description" => esc_html__("Space between rows", "hybrid-gallery"),
                "attr" => "gap_y",
                "id" => "gap-y",
                "type" => "number",
                "default" => $def['gap_y']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "name" => esc_html__("Formats", "hybrid-gallery"),
                "description" => esc_html__("Show items in their formats (image, video...)", "hybrid-gallery"),
                "attr" => "formats",
                "id" => "formats",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery"),
                ),
                "default" => $def['formats'],
            ),
            array(
                "name" => esc_html__("Color", "hybrid-gallery"),
                "description" => esc_html__("Set custom color for gallery", "hybrid-gallery"),
                "attr" => "color",
                "id" => "color",
                "type" => "color",
                "hex" => "#b90000",
                "default" => $def['color']
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Hover Effect", "hybrid-gallery"),
                "description" => esc_html__("Add hover effect on images", "hybrid-gallery"),
                "attr" => "img_hover",
                "id" => "img-hover",
                "type" => "select",
                "options" => array(
                    'false' => esc_html__("Off", "hybrid-gallery"),
                    1 => esc_html__("Zoom-In", "hybrid-gallery"),
                    2 => esc_html__("Zoom-Out", "hybrid-gallery"),
                    3 => esc_html__("Zoom-In + Rotate", "hybrid-gallery"),
                    4 => esc_html__("Zoom-Out + Rotate", "hybrid-gallery"),
                    5 => esc_html__("Shine", "hybrid-gallery"),
                    6 => esc_html__("Circle", "hybrid-gallery"),
                ),
                "default" => $def['img_hover'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Image Filter", "hybrid-gallery"),
                "description" => esc_html__("Add image filter", "hybrid-gallery"),
                "attr" => "img_filter",
                "id" => "img-filter",
                "type" => "select",
                "options" => array(
                    'false' => esc_html__("Off", "hybrid-gallery"),
                    1 => esc_html__("blur", "hybrid-gallery"),
                    2 => esc_html__("brightness", "hybrid-gallery"),
                    3 => esc_html__("contrast", "hybrid-gallery"),
                    4 => esc_html__("grayscale", "hybrid-gallery"),
                    5 => esc_html__("hue-rotate", "hybrid-gallery"),
                    6 => esc_html__("invert", "hybrid-gallery"),
                    7 => esc_html__("opacity", "hybrid-gallery"),
                    8 => esc_html__("saturate", "hybrid-gallery"),
                    9 => esc_html__("sepia", "hybrid-gallery"),
                ),
                "default" => $def['img_filter'],
            ),
            array(
                "type" => "cols",
                "action" => "end",
            ),
            array(
                "name" => esc_html__("Style", "hybrid-gallery"),
                "description" => esc_html__("Select a style", "hybrid-gallery"),
                "attr" => "style",
                "id" => "style",
                "type" => "select",
                "options" => array(
                    1 => esc_html__("Style #1", "hybrid-gallery"),
                    2 => esc_html__("Style #2", "hybrid-gallery"),
                    3 => esc_html__("Style #3", "hybrid-gallery"),
                    4 => esc_html__("Style #4", "hybrid-gallery"),
                ),
                "default" => $def['style']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Title", "hybrid-gallery"),
                "description" => esc_html__("Show image title", "hybrid-gallery"),
                "attr" => "meta_title",
                "id" => "meta-title",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery"),
                ),
                "default" => $def['meta_title'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Description", "hybrid-gallery"),
                "description" => esc_html__("Show image description", "hybrid-gallery"),
                "attr" => "meta_descr",
                "id" => "meta-descr",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery"),
                ),
                "default" => $def['meta_descr'],
            ),
            array(
                "type" => "cols",
                "action" => "end",
            ),
            array(
                "name" => esc_html__("Image: click action", "hybrid-gallery"),
                "description" => esc_html__("Do an action after click on image", "hybrid-gallery"),
                "attr" => "click_action",
                "id" => "click-action",
                "type" => "select",
                "options" => array(
                    "lb" => esc_html__("Lightbox", "hybrid-gallery"),
                    "link" => esc_html__("Link", "hybrid-gallery"),
                    "lb_link" => esc_html__("Lightbox & Link", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery")
                ),
                "default" => $def['click_action'],
                "dependency" => array(
                    "id" => "style",
                    "value" => array(1, 2, 3, 4)
                )
            ),
            array(
                "name" => esc_html__("Open link in", "hybrid-gallery"),
                "description" => esc_html__("Select the link target", "hybrid-gallery"),
                "attr" => "link_tg",
                "id" => "link-tg",
                "type" => "select",
                "options" => array(
                    'same' => esc_html__("Same tab", "hybrid-gallery"),
                    'new' => esc_html__("New tab", "hybrid-gallery")
                ),
                "default" => $def['link_tg'],
                "dependency" => array(
                    "id" => "click-action",
                    "value" => array(
                        "link",
                        "lb_link"
                    )
                )
            ),
            array(
                "name" => esc_html__("Image: buttons", "hybrid-gallery"),
                "description" => esc_html__("Buttons: lightbox, link", "hybrid-gallery"),
                "attr" => "buttons",
                "id" => "buttons",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("Show", "hybrid-gallery"),
                    "false" => esc_html__("Hide", "hybrid-gallery")
                ),
                "default" => $def['buttons'],
                "dependency" => array(
                    "id" => "click-action",
                    "value" => array(
                        "lb",
                        "link"
                    )
                )
            ),
            array(
                "name" => esc_html__("Lightbox", "hybrid-gallery"),
                "description" => esc_html__("Select lightbox. Upgrade to PRO to unlock all lightboxes", "hybrid-gallery"),
                "attr" => "lightbox",
                "id" => "lightbox",
                "type" => "select",
                "options" => array(
                    "mp" => "Magnific Popup",
                    "cb" => "Colorbox",
                    "lg" => "lightGallery",
                    "pp" => "prettyPhoto",
                    "fyb" => "fancyBox",
                    "ilb" => "iLightBox",
                    "lc" => "Lightcase",
                ),
                "default" => $def['lightbox'],
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "name" => esc_html__("Pagination", "hybrid-gallery"),
                "description" => esc_html__("Break images into pages", "hybrid-gallery"),
                "attr" => "pagination",
                "id" => "pagination",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "true" => esc_html__("On", "hybrid-gallery")
                ),
                "default" => $def['pagination']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Pagination type", "hybrid-gallery"),
                "description" => esc_html__("Select pagination type", "hybrid-gallery"),
                "attr" => "pg_type",
                "id" => "pg-type",
                "type" => "select",
                "options" => array(
                    "num" => esc_html__("Numeric", "hybrid-gallery"),
                    "classic" => esc_html__("Classic", "hybrid-gallery"),
                    "more" => esc_html__("Show More", "hybrid-gallery"),
                    "scroll" => esc_html__("Scroll", "hybrid-gallery")
                ),
                "default" => $def['pg_type'],
                "dependency" => array(
                    "id" => "pagination",
                    "value" => "true"
                )
            ),
            array(
                "name" => esc_html__("Pagination: AJAX", "hybrid-gallery"),
                "description" => esc_html__("Get new images without page load", "hybrid-gallery"),
                "attr" => "pg_ajax",
                "id" => "pg-ajax",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery")
                ),
                "default" => $def['pg_ajax'],
                "dependency" => array(
                    "id" => "pg-type",
                    "value" => array(
                        "num",
                        "classic"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Images per page", "hybrid-gallery"),
                "description" => esc_html__("Number of images to show per page.", "hybrid-gallery"),
                "attr" => "pg_posts",
                "id" => "pg-posts",
                "type" => "number",
                "default" => $def['pg_posts'],
                "dependency" => array(
                    "id" => "pagination",
                    "value" => "true"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "name" => esc_html__("Filter", "hybrid-gallery"),
                "description" => esc_html__("Break images into categories", "hybrid-gallery"),
                "attr" => "filter",
                "id" => "filter",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "true" => esc_html__("On", "hybrid-gallery")
                ),
                "default" => $def['filter']
            ),
            array(
                "name" => esc_html__("Animation", "hybrid-gallery"),
                "description" => esc_html__("Add animation to images", "hybrid-gallery"),
                "attr" => "animation",
                "id" => "animation",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "bounce" => "bounce",
                    "flash" => "flash",
                    "pulse" => "pulse",
                    "rubberBand" => "rubberBand",
                    "shake" => "shake",
                    "swing" => "swing",
                    "tada" => "tada",
                    "wobble" => "wobble",
                    "jello" => "jello",
                    "bounceIn" => "bounceIn",
                    "bounceInDown" => "bounceInDown",
                    "bounceInLeft" => "bounceInLeft",
                    "bounceInRight" => "bounceInRight",
                    "bounceInRight" => "bounceInRight",
                    "bounceInUp" => "bounceInUp",
                    "fadeIn" => "fadeIn",
                    "fadeInDown" => "fadeInDown",
                    "fadeInDownBig" => "fadeInDownBig",
                    "fadeInLeft" => "fadeInLeft",
                    "fadeInLeftBig" => "fadeInLeftBig",
                    "fadeInRight" => "fadeInRight",
                    "fadeInRightBig" => "fadeInRightBig",
                    "fadeInUp" => "fadeInUp",
                    "fadeInUpBig" => "fadeInUpBig",
                    "flip" => "flip",
                    "flipInX" => "flipInX",
                    "flipInY" => "flipInY",
                    "lightSpeedIn" => "lightSpeedIn",
                    "rotateIn" => "rotateIn",
                    "rotateInDownLeft" => "rotateInDownLeft",
                    "rotateInDownRight" => "rotateInDownRight",
                    "rotateInUpLeft" => "rotateInUpLeft",
                    "rotateInUpRight" => "rotateInUpRight",
                    "slideInUp" => "slideInUp",
                    "slideInDown" => "slideInDown",
                    "slideInLeft" => "slideInLeft",
                    "slideInRight" => "slideInRight",
                    "zoomIn" => "zoomIn",
                    "zoomInDown" => "zoomInDown",
                    "zoomInLeft" => "zoomInLeft",
                    "zoomInRight" => "zoomInRight",
                    "zoomInUp" => "zoomInUp",
                    "hinge" => "hinge",
                    "rollIn" => "rollIn"
                ),
                "default" => $def['animation']
            ),
            array(
                "name" => esc_html__("Preloader", "hybrid-gallery"),
                "description" => esc_html__("Show animation while loading Gallery", "hybrid-gallery"),
                "attr" => "preloader",
                "id" => "preloader",
                "type" => "select",
                "options" => array(
                    1 => esc_html__("Preloader", "hybrid-gallery") . " #1",
                    2 => esc_html__("Preloader", "hybrid-gallery") . " #2",
                    3 => esc_html__("Preloader", "hybrid-gallery") . " #3",
                    4 => esc_html__("Preloader", "hybrid-gallery") . " #4",
                    5 => esc_html__("Preloader", "hybrid-gallery") . " #5",
                    6 => esc_html__("Preloader", "hybrid-gallery") . " #6",
                    7 => esc_html__("Preloader", "hybrid-gallery") . " #7",
                    8 => esc_html__("Preloader", "hybrid-gallery") . " #8",
                    9 => esc_html__("Preloader", "hybrid-gallery") . " #9",
                    10 => esc_html__("Preloader", "hybrid-gallery") . " #10"
                ),
                "default" => $def['preloader']
            ),
            array(
                "name" => esc_html__("Loader Delay", "hybrid-gallery"),
                "description" => esc_html__("Enter loader delay time in milliseconds.", "hybrid-gallery"),
                "attr" => "loader_delay",
                "id" => "loader-delay",
                "type" => "number",
                "default" => $def['loader_delay']
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-8"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Gallery width", "hybrid-gallery"),
                "attr" => "ct_w_vl",
                "id" => "ct-w-vl",
                "type" => "number",
                "default" => $def['ct_w_vl']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "ct_w_un",
                "id" => "ct-w-un",
                "type" => "select",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['ct_w_un']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Gallery аlign", "hybrid-gallery"),
                "attr" => "ct_align",
                "id" => "ct-align",
                "type" => "select",
                "options" => array(
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['ct_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Extra class", "hybrid-gallery"),
                "description" => esc_html__("Add extra class to Gallery", "hybrid-gallery"),
                "attr" => "custom_class",
                "id" => "custom-class",
                "type" => "text",
                "default" => $def['custom_class'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Add id", "hybrid-gallery"),
                "description" => esc_html__("Add id to Gallery", "hybrid-gallery"),
                "attr" => "custom_id",
                "id" => "custom-id",
                "type" => "text",
                "default" => $def['custom_id'],
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Screen (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_1_ss",
                "id" => "res-1-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_1_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Gallery width", "hybrid-gallery"),
                "attr" => "res_1_w",
                "id" => "res-1-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_1_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_1_w_vl",
                "id" => "res-1-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_1_w_vl'],
                "dependency" => array(
                    "id" => "res-1-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_1_w_un",
                "id" => "res-1-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_1_w_un'],
                "dependency" => array(
                    "id" => "res-1-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_1_cols",
                "id" => "res-1-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_1_cols'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array(
                        "grid",
                        "masonry",
                        "metro"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Gallery align", "hybrid-gallery"),
                "attr" => "res_1_align",
                "id" => "res-1-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_1_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Screen (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_2_ss",
                "id" => "res-2-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_2_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Gallery width", "hybrid-gallery"),
                "attr" => "res_2_w",
                "id" => "res-2-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_2_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_2_w_vl",
                "id" => "res-2-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_2_w_vl'],
                "dependency" => array(
                    "id" => "res-2-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_2_w_un",
                "id" => "res-2-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_2_w_un'],
                "dependency" => array(
                    "id" => "res-2-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_2_cols",
                "id" => "res-2-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_2_cols'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array(
                        "grid",
                        "masonry",
                        "metro"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Gallery align", "hybrid-gallery"),
                "attr" => "res_2_align",
                "id" => "res-2-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_2_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Screen (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_3_ss",
                "id" => "res-3-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_3_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Gallery width", "hybrid-gallery"),
                "attr" => "res_3_w",
                "id" => "res-3-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_3_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_3_w_vl",
                "id" => "res-3-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_3_w_vl'],
                "dependency" => array(
                    "id" => "res-3-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_3_w_un",
                "id" => "res-3-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_3_w_un'],
                "dependency" => array(
                    "id" => "res-3-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_3_cols",
                "id" => "res-3-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_3_cols'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array(
                        "grid",
                        "masonry",
                        "metro"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Gallery align", "hybrid-gallery"),
                "attr" => "res_3_align",
                "id" => "res-3-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_3_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Screen (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_4_ss",
                "id" => "res-4-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_4_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Gallery width", "hybrid-gallery"),
                "attr" => "res_4_w",
                "id" => "res-4-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_4_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_4_w_vl",
                "id" => "res-4-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_4_w_vl'],
                "dependency" => array(
                    "id" => "res-4-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_4_w_un",
                "id" => "res-4-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_4_w_un'],
                "dependency" => array(
                    "id" => "res-4-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_4_cols",
                "id" => "res-4-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_4_cols'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array(
                        "grid",
                        "masonry",
                        "metro"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Gallery align", "hybrid-gallery"),
                "attr" => "res_4_align",
                "id" => "res-4-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_4_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Screen (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_5_ss",
                "id" => "res-5-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_5_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Gallery width", "hybrid-gallery"),
                "attr" => "res_5_w",
                "id" => "res-5-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_5_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_5_w_vl",
                "id" => "res-5-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_5_w_vl'],
                "dependency" => array(
                    "id" => "res-5-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_5_w_un",
                "id" => "res-5-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_5_w_un'],
                "dependency" => array(
                    "id" => "res-5-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_5_cols",
                "id" => "res-5-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_5_cols'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array(
                        "grid",
                        "masonry",
                        "metro"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Gallery align", "hybrid-gallery"),
                "attr" => "res_5_align",
                "id" => "res-5-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_5_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "end"
            )
        );
        hybrid_gallery_framework($options, 'grid');
    }


    // Function Carousel Shortcode Editor
    // ======================================================
    
    static function carousel($args)
    {
        // Set defaults atts
        $def = array(
            'ids' => '',
            'size' => 'equal',
            'ratio_w' => 4,
            'ratio_h' => 3,
            'columns' => 3,
            'cm' => false,
            'gap' => 10,
            'nav' => 'false',
            'dots' => 'false',
            'formats' => 'false',
            'color' => '#b90000',
            'animation' => 'fadeInUp',
            'preloader' => 1,
            'loader_delay' => 300,
            'img_hover' => 'false',
            'img_filter' => 'false',
            'style' => 1,
            'meta_title' => 'false',
            'meta_descr' => 'false',
            'click_action' => 'lb',
            'buttons' => 'false',
            'link_tg' => 'same',
            'lightbox' => 'mp',
            'ct_w_vl' => 100,
            'ct_w_un' => 'pc',
            'ct_align' => 'none',
            'custom_class' => '',
            'custom_id' => '',
            'res_1_ss' => 1024,
            'res_1_w' => 'auto',
            'res_1_w_vl' => 100,
            'res_1_w_un' => 'pc',
            'res_1_cols' => 'auto',
            'res_1_align' => 'auto',
            'res_2_ss' => 800,
            'res_2_w' => 'auto',
            'res_2_w_vl' => 100,
            'res_2_w_un' => 'pc',
            'res_2_cols' => 'auto',
            'res_2_align' => 'auto',
            'res_3_ss' => 768,
            'res_3_w' => 'auto',
            'res_3_w_vl' => 100,
            'res_3_w_un' => 'pc',
            'res_3_cols' => 'auto',
            'res_3_align' => 'auto',
            'res_4_ss' => 600,
            'res_4_w' => 'auto',
            'res_4_w_vl' => 100,
            'res_4_w_un' => 'pc',
            'res_4_cols' => 'auto',
            'res_4_align' => 'auto',
            'res_5_ss' => 480,
            'res_5_w' => 'auto',
            'res_5_w_vl' => 100,
            'res_5_w_un' => 'pc',
            'res_5_cols' => 'auto',
            'res_5_align' => 'auto'
        );
        
        // configure resolution args
        $res = $args['res'];
        $res = str_replace("#", '"', $res);
        $res = json_decode($res, true);
        
        foreach ($res as $res_item => $res_key) {
            $res_num++;
            
            // get res screen args
            $res_args = $res[$res_item];
            
            // res screen
            $res_ss        = 'res_' . $res_num . '_ss';
            $args[$res_ss] = $res_item;
            
            // res screen
            $res_cols        = 'res_' . $res_num . '_cols';
            $args[$res_cols] = $res_args['cols'];
            
            // res width
            $res_w        = 'res_' . $res_num . '_w';
            $args[$res_w] = $res_args['w'];
            
            // res width (value)
            $res_w_vl        = 'res_' . $res_num . '_w_vl';
            $args[$res_w_vl] = $res_args['w_vl'];
            
            // res unit (unit)
            $res_w_un        = 'res_' . $res_num . '_w_un';
            $args[$res_w_un] = $res_args['w_un'];
            
            // res unit (align)
            $res_align        = 'res_' . $res_num . '_align';
            $args[$res_align] = $res_args['align'];
        }
        
        // unset excess keys 
        $removeKeys = array(
            'panel',
            'action',
            'nonce',
            'res'
        );

        foreach ($removeKeys as $key) {
            unset($args[$key]);
        }
        
        // override new atts
        if (is_array($args)) {
            $def = array_merge($def, $args);
        }
        
        if ($def['ids']) {
            $pids = explode(',', $def['ids']);
        }
        
        $options = array(
            array(
                "type" => "cols",
                "size" => "image-buttons",
                "action" => "start",
                "class" => "hybgl-preview-table"
            ),
            array(
                "id" => "image-selector",
                "type" => "img_selector",
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "image-preview-panel",
                "class" => "hybgl-panel-preview"
            ),
            array(
                "id" => "preview",
                "type" => "preview",
                "options" => $pids
            ),
            array(
                "type" => "cols",
                "action" => "end",
            ),
            array(
                "attr" => "ids",
                "id" => "ids",
                "class" => "hybgl-field-class-ids",
                "type" => "text",
                "default" => $def['ids']
            ),
            array(
                "id" => "tab-switcher",
                "type" => "tab-switcher",
                "tabs" => array(
                    "general" => esc_html__("General", "hybrid-gallery"),
                    "image" => esc_html__("Image", "hybrid-gallery"),
                    "extra" => esc_html__("Extra", "hybrid-gallery"),
                    "container" => esc_html__("Container", "hybrid-gallery"),
                    "responsive" => esc_html__("Responsive", "hybrid-gallery")
                )
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "name" => esc_html__("Size", "hybrid-gallery"),
                "description" => esc_html__("Select image size", "hybrid-gallery"),
                "attr" => "size",
                "id" => "size",
                "type" => "select",
                "options" => array(
                    "equal" => esc_html__("Equal", "hybrid-gallery"),
                    "fixed" => esc_html__("Fixed", "hybrid-gallery"),
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                ),
                "default" => $def['size']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Ratio width", "hybrid-gallery"),
                "description" => esc_html__("Images: aspect ratio: width", "hybrid-gallery"),
                "attr" => "ratio_w",
                "id" => "ratio_w",
                "type" => "number",
                "default" => $def['ratio_w'],
                "dependency" => array(
                    "id" => "size",
                    "value" => array(
                        "equal",
                        "fixed"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Ratio height", "hybrid-gallery"),
                "description" => esc_html__("Images: aspect ratio: height", "hybrid-gallery"),
                "attr" => "ratio_h",
                "id" => "ratio_h",
                "type" => "number",
                "default" => $def['ratio_h'],
                "dependency" => array(
                    "id" => "size",
                    "value" => array(
                        "equal",
                        "fixed"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "description" => esc_html__("Number of columns in a row", "hybrid-gallery"),
                "attr" => "columns",
                "id" => "columns",
                "type" => "select",
                "options" => array(
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['columns']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Gap", "hybrid-gallery"),
                "description" => esc_html__("Space between columns", "hybrid-gallery"),
                "attr" => "gap",
                "id" => "gap",
                "type" => "number",
                "default" => $def['gap']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "name" => esc_html__("Center mode", "hybrid-gallery"),
                "description" => esc_html__("Image that in center will have a different look", "hybrid-gallery"),
                "attr" => "cm",
                "id" => "cm",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "true" => esc_html__("On", "hybrid-gallery")
                ),
                "default" => $def['cm']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Navigation", "hybrid-gallery"),
                "description" => esc_html__("Show navigation", "hybrid-gallery"),
                "attr" => "nav",
                "id" => "nav",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "true" => esc_html__("On", "hybrid-gallery")
                ),
                "default" => $def['nav']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Dots", "hybrid-gallery"),
                "description" => esc_html__("Show dots", "hybrid-gallery"),
                "attr" => "dots",
                "id" => "dots",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "true" => esc_html__("On", "hybrid-gallery")
                ),
                "default" => $def['dots']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "name" => esc_html__("Formats", "hybrid-gallery"),
                "description" => esc_html__("Show items in their formats (image, video...)", "hybrid-gallery"),
                "attr" => "formats",
                "id" => "formats",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery"),
                ),
                "default" => $def['formats'],
            ),
            array(
                "name" => esc_html__("Color", "hybrid-gallery"),
                "description" => esc_html__("Set custom color for carousel", "hybrid-gallery"),
                "attr" => "color",
                "id" => "color",
                "type" => "color",
                "hex" => "#b90000",
                "default" => $def['color']
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Hover Effect", "hybrid-gallery"),
                "description" => esc_html__("Add hover effect on images", "hybrid-gallery"),
                "attr" => "img_hover",
                "id" => "img-hover",
                "type" => "select",
                "options" => array(
                    'false' => esc_html__("Off", "hybrid-gallery"),
                    1 => esc_html__("Zoom-In", "hybrid-gallery"),
                    2 => esc_html__("Zoom-Out", "hybrid-gallery"),
                    3 => esc_html__("Zoom-In + Rotate", "hybrid-gallery"),
                    4 => esc_html__("Zoom-Out + Rotate", "hybrid-gallery"),
                    5 => esc_html__("Shine", "hybrid-gallery"),
                    6 => esc_html__("Circle", "hybrid-gallery"),
                ),
                "default" => $def['img_hover'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Image Filter", "hybrid-gallery"),
                "description" => esc_html__("Add image filter", "hybrid-gallery"),
                "attr" => "img_filter",
                "id" => "img-filter",
                "type" => "select",
                "options" => array(
                    'false' => esc_html__("Off", "hybrid-gallery"),
                    1 => esc_html__("blur", "hybrid-gallery"),
                    2 => esc_html__("brightness", "hybrid-gallery"),
                    3 => esc_html__("contrast", "hybrid-gallery"),
                    4 => esc_html__("grayscale", "hybrid-gallery"),
                    5 => esc_html__("hue-rotate", "hybrid-gallery"),
                    6 => esc_html__("invert", "hybrid-gallery"),
                    7 => esc_html__("opacity", "hybrid-gallery"),
                    8 => esc_html__("saturate", "hybrid-gallery"),
                    9 => esc_html__("sepia", "hybrid-gallery"),
                ),
                "default" => $def['img_filter'],
            ),
            array(
                "type" => "cols",
                "action" => "end",
            ),
            array(
                "name" => esc_html__("Style", "hybrid-gallery"),
                "description" => esc_html__("Select a style", "hybrid-gallery"),
                "attr" => "style",
                "id" => "style",
                "type" => "select",
                "options" => array(
                    1 => esc_html__("Style #1", "hybrid-gallery"),
                    2 => esc_html__("Style #2", "hybrid-gallery"),
                    3 => esc_html__("Style #3", "hybrid-gallery"),
                ),
                "default" => $def['style']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Title", "hybrid-gallery"),
                "description" => esc_html__("Show image title", "hybrid-gallery"),
                "attr" => "meta_title",
                "id" => "meta-title",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery"),
                ),
                "default" => $def['meta_title'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Description", "hybrid-gallery"),
                "description" => esc_html__("Show image description", "hybrid-gallery"),
                "attr" => "meta_descr",
                "id" => "meta-descr",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery"),
                ),
                "default" => $def['meta_descr'],
            ),
            array(
                "type" => "cols",
                "action" => "end",
            ),
            array(
                "name" => esc_html__("Image: click action", "hybrid-gallery"),
                "description" => esc_html__("Do an action after click on image", "hybrid-gallery"),
                "attr" => "click_action",
                "id" => "click-action",
                "type" => "select",
                "options" => array(
                    "lb" => esc_html__("Lightbox", "hybrid-gallery"),
                    "link" => esc_html__("Link", "hybrid-gallery"),
                    "lb_link" => esc_html__("Lightbox & Link", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery")
                ),
                "default" => $def['click_action'],
                "dependency" => array(
                    "id" => "style",
                    "value" => array(1, 2, 3, 4)
                )
            ),
            array(
                "name" => esc_html__("Open link in", "hybrid-gallery"),
                "description" => esc_html__("Select the link target", "hybrid-gallery"),
                "attr" => "link_tg",
                "id" => "link-tg",
                "type" => "select",
                "options" => array(
                    'same' => esc_html__("Same tab", "hybrid-gallery"),
                    'new' => esc_html__("New tab", "hybrid-gallery")
                ),
                "default" => $def['link_tg'],
                "dependency" => array(
                    "id" => "click-action",
                    "value" => array(
                        "link",
                        "lb_link"
                    )
                )
            ),
            array(
                "name" => esc_html__("Image: buttons", "hybrid-gallery"),
                "description" => esc_html__("Buttons: lightbox, link", "hybrid-gallery"),
                "attr" => "buttons",
                "id" => "buttons",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("Show", "hybrid-gallery"),
                    "false" => esc_html__("Hide", "hybrid-gallery")
                ),
                "default" => $def['buttons'],
                "dependency" => array(
                    "id" => "click-action",
                    "value" => array(
                        "lb",
                        "link"
                    )
                )
            ),
            array(
                "name" => esc_html__("Lightbox", "hybrid-gallery"),
                "description" => esc_html__("Select lightbox. Upgrade to PRO to unlock all lightboxes", "hybrid-gallery"),
                "attr" => "lightbox",
                "id" => "lightbox",
                "type" => "select",
                "options" => array(
                    "mp" => "Magnific Popup",
                    "cb" => "Colorbox",
                    "lg" => "lightGallery",
                    "pp" => "prettyPhoto",
                    "fyb" => "fancyBox",
                    "ilb" => "iLightBox",
                    "lc" => "Lightcase",
                ),
                "default" => $def['lightbox'],
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "name" => esc_html__("Animation", "hybrid-gallery"),
                "description" => esc_html__("Select animation", "hybrid-gallery"),
                "attr" => "animation",
                "id" => "animation",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "bounce" => "bounce",
                    "flash" => "flash",
                    "pulse" => "pulse",
                    "rubberBand" => "rubberBand",
                    "shake" => "shake",
                    "swing" => "swing",
                    "tada" => "tada",
                    "wobble" => "wobble",
                    "jello" => "jello",
                    "bounceIn" => "bounceIn",
                    "bounceInDown" => "bounceInDown",
                    "bounceInLeft" => "bounceInLeft",
                    "bounceInRight" => "bounceInRight",
                    "bounceInRight" => "bounceInRight",
                    "bounceInUp" => "bounceInUp",
                    "fadeIn" => "fadeIn",
                    "fadeInDown" => "fadeInDown",
                    "fadeInDownBig" => "fadeInDownBig",
                    "fadeInLeft" => "fadeInLeft",
                    "fadeInLeftBig" => "fadeInLeftBig",
                    "fadeInRight" => "fadeInRight",
                    "fadeInRightBig" => "fadeInRightBig",
                    "fadeInUp" => "fadeInUp",
                    "fadeInUpBig" => "fadeInUpBig",
                    "flip" => "flip",
                    "flipInX" => "flipInX",
                    "flipInY" => "flipInY",
                    "lightSpeedIn" => "lightSpeedIn",
                    "rotateIn" => "rotateIn",
                    "rotateInDownLeft" => "rotateInDownLeft",
                    "rotateInDownRight" => "rotateInDownRight",
                    "rotateInUpLeft" => "rotateInUpLeft",
                    "rotateInUpRight" => "rotateInUpRight",
                    "slideInUp" => "slideInUp",
                    "slideInDown" => "slideInDown",
                    "slideInLeft" => "slideInLeft",
                    "slideInRight" => "slideInRight",
                    "zoomIn" => "zoomIn",
                    "zoomInDown" => "zoomInDown",
                    "zoomInLeft" => "zoomInLeft",
                    "zoomInRight" => "zoomInRight",
                    "zoomInUp" => "zoomInUp",
                    "hinge" => "hinge",
                    "rollIn" => "rollIn"
                ),
                "default" => $def['animation']
            ),
            array(
                "name" => esc_html__("Preloader", "hybrid-gallery"),
                "description" => esc_html__("Show animation while loading Carousel", "hybrid-gallery"),
                "attr" => "preloader",
                "id" => "preloader",
                "type" => "select",
                "options" => array(
                    1 => esc_html__("Preloader", "hybrid-gallery") . " #1",
                    2 => esc_html__("Preloader", "hybrid-gallery") . " #2",
                    3 => esc_html__("Preloader", "hybrid-gallery") . " #3",
                    4 => esc_html__("Preloader", "hybrid-gallery") . " #4",
                    5 => esc_html__("Preloader", "hybrid-gallery") . " #5",
                    6 => esc_html__("Preloader", "hybrid-gallery") . " #6",
                    7 => esc_html__("Preloader", "hybrid-gallery") . " #7",
                    8 => esc_html__("Preloader", "hybrid-gallery") . " #8",
                    9 => esc_html__("Preloader", "hybrid-gallery") . " #9",
                    10 => esc_html__("Preloader", "hybrid-gallery") . " #10"
                ),
                "default" => $def['preloader']
            ),
            array(
                "name" => esc_html__("Loader Delay", "hybrid-gallery"),
                "description" => esc_html__("Enter loader delay time in milliseconds.", "hybrid-gallery"),
                "attr" => "loader_delay",
                "id" => "loader-delay",
                "type" => "number",
                "default" => $def['loader_delay']
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-8"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Carousel width", "hybrid-gallery"),
                "attr" => "ct_w_vl",
                "id" => "ct-w-vl",
                "type" => "number",
                "default" => $def['ct_w_vl']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "ct_w_un",
                "id" => "ct-w-un",
                "type" => "select",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['ct_w_un']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Carousel align", "hybrid-gallery"),
                "attr" => "ct_align",
                "id" => "ct-align",
                "type" => "select",
                "options" => array(
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['ct_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Extra Class", "hybrid-gallery"),
                "description" => esc_html__("Add extra class to Carousel", "hybrid-gallery"),
                "attr" => "custom_class",
                "id" => "custom-class",
                "type" => "text",
                "default" => $def['custom_class'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Add id", "hybrid-gallery"),
                "description" => esc_html__("Add id to Carousel", "hybrid-gallery"),
                "attr" => "custom_id",
                "id" => "custom-id",
                "type" => "text",
                "default" => $def['custom_id'],
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_1_ss",
                "id" => "res-1-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_1_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Carousel width", "hybrid-gallery"),
                "attr" => "res_1_w",
                "id" => "res-1-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_1_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_1_w_vl",
                "id" => "res-1-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_1_w_vl'],
                "dependency" => array(
                    "id" => "res-1-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_1_w_un",
                "id" => "res-1-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_1_w_un'],
                "dependency" => array(
                    "id" => "res-1-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_1_cols",
                "id" => "res-1-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_1_cols'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Carousel align", "hybrid-gallery"),
                "attr" => "res_1_align",
                "id" => "res-1-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_1_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_2_ss",
                "id" => "res-2-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_2_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Carousel width", "hybrid-gallery"),
                "attr" => "res_2_w",
                "id" => "res-2-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_2_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_2_w_vl",
                "id" => "res-2-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_2_w_vl'],
                "dependency" => array(
                    "id" => "res-2-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_2_w_un",
                "id" => "res-2-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_2_w_un'],
                "dependency" => array(
                    "id" => "res-2-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_2_cols",
                "id" => "res-2-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_2_cols'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Carousel align", "hybrid-gallery"),
                "attr" => "res_2_align",
                "id" => "res-2-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_2_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_3_ss",
                "id" => "res-3-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_3_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Carousel width", "hybrid-gallery"),
                "attr" => "res_3_w",
                "id" => "res-3-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_3_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_3_w_vl",
                "id" => "res-3-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_3_w_vl'],
                "dependency" => array(
                    "id" => "res-3-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_3_w_un",
                "id" => "res-3-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_3_w_un'],
                "dependency" => array(
                    "id" => "res-3-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_3_cols",
                "id" => "res-3-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_3_cols'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Carousel align", "hybrid-gallery"),
                "attr" => "res_3_align",
                "id" => "res-3-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_3_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_4_ss",
                "id" => "res-4-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_4_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Carousel width", "hybrid-gallery"),
                "attr" => "res_4_w",
                "id" => "res-4-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_4_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_4_w_vl",
                "id" => "res-4-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_4_w_vl'],
                "dependency" => array(
                    "id" => "res-4-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_4_w_un",
                "id" => "res-4-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_4_w_un'],
                "dependency" => array(
                    "id" => "res-4-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_4_cols",
                "id" => "res-4-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_4_cols'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Carousel align", "hybrid-gallery"),
                "attr" => "res_4_align",
                "id" => "res-4-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_4_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_5_ss",
                "id" => "res-5-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_5_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-5"
            ),
            array(
                "name" => esc_html__("Carousel width", "hybrid-gallery"),
                "attr" => "res_5_w",
                "id" => "res-5-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_5_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_5_w_vl",
                "id" => "res-5-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_5_w_vl'],
                "dependency" => array(
                    "id" => "res-5-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_5_w_un",
                "id" => "res-5-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_5_w_un'],
                "dependency" => array(
                    "id" => "res-5-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Columns", "hybrid-gallery"),
                "attr" => "res_5_cols",
                "id" => "res-5-cols",
                "type" => "select",
                "mode" => "res",
                "point" => "cols",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "1" => esc_html__("1 Column", "hybrid-gallery"),
                    "2" => esc_html__("2 Columns", "hybrid-gallery"),
                    "3" => esc_html__("3 Columns", "hybrid-gallery"),
                    "4" => esc_html__("4 Columns", "hybrid-gallery"),
                    "5" => esc_html__("5 Columns", "hybrid-gallery"),
                    "6" => esc_html__("6 Columns", "hybrid-gallery")
                ),
                "default" => $def['res_5_cols'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Carousel align", "hybrid-gallery"),
                "attr" => "res_5_align",
                "id" => "res-5-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_5_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "end"
            )
        );
        hybrid_gallery_framework($options, 'carousel');
    }


    // Function Slider Shortcode Editor
    // ======================================================

    static function slider($args)
    {
        // Set defaults atts
        $def = array(
            'ids' => '',
            'layout' => 1,
            'size' => 'fixed',
            'ratio_w' => 2,
            'ratio_h' => 1,
            'nav' => false,
            'nav_pos' => 1,
            'dots' => false,
            'dots_pos' => 1,
            'thumbs_w' => 80,
            'thumbs_h' => 80,
            'thumbs_gap' => 10,
            'formats' => 'false',
            'color' => '#b90000',
            'lightbox' => 'false',
            'lb_pos' => 1,
            'lb_type' => 'mp',
            'meta_title' => 'false',
            'meta_descr' => 'false',
            'meta_animation' => 'slideInUp',
            'animation_main' => 'fadeIn',
            'animation_child' => 'zoomIn',
            'preloader' => 1,
            'loader_delay' => 300,
            'ct_w_vl' => 100,
            'ct_w_un' => 'pc',
            'ct_align' => 'none',
            'custom_class' => '',
            'custom_id' => '',
            'res_1_ss' => 1024,
            'res_1_w' => 'auto',
            'res_1_w_vl' => 100,
            'res_1_w_un' => 'pc',
            'res_1_align' => 'auto',
            'res_2_ss' => 800,
            'res_2_w' => 'auto',
            'res_2_w_vl' => 100,
            'res_2_w_un' => 'pc',
            'res_2_align' => 'auto',
            'res_3_ss' => 768,
            'res_3_w' => 'auto',
            'res_3_w_vl' => 100,
            'res_3_w_un' => 'pc',
            'res_3_align' => 'auto',
            'res_4_ss' => 600,
            'res_4_w' => 'auto',
            'res_4_w_vl' => 100,
            'res_4_w_un' => 'pc',
            'res_4_align' => 'auto',
            'res_5_ss' => 480,
            'res_5_w' => 'auto',
            'res_5_w_vl' => 100,
            'res_5_w_un' => 'pc',
            'res_5_align' => 'auto'
        );

        // configure resolution args
        $res = $args['res'];
        $res = str_replace("#", '"', $res);
        $res = json_decode($res, true);
        
        foreach ($res as $res_item => $res_key) {
            $res_num++;
            
            // get res screen args
            $res_args = $res[$res_item];
            
            // res screen
            $res_ss        = 'res_' . $res_num . '_ss';
            $args[$res_ss] = $res_item;
            
            // res screen
            $res_cols        = 'res_' . $res_num . '_cols';
            $args[$res_cols] = $res_args['cols'];
            
            // res width
            $res_w        = 'res_' . $res_num . '_w';
            $args[$res_w] = $res_args['w'];
            
            // res width (value)
            $res_w_vl        = 'res_' . $res_num . '_w_vl';
            $args[$res_w_vl] = $res_args['w_vl'];
            
            // res unit (unit)
            $res_w_un        = 'res_' . $res_num . '_w_un';
            $args[$res_w_un] = $res_args['w_un'];
            
            // res unit (align)
            $res_align        = 'res_' . $res_num . '_align';
            $args[$res_align] = $res_args['align'];
        }
        
        // unset excess keys 
        $removeKeys = array(
            'panel',
            'action',
            'nonce',
            'res'
        );

        foreach ($removeKeys as $key) {
            unset($args[$key]);
        }
        
        // override new atts
        if (is_array($args)) {
            $def = array_merge($def, $args);
        }
        
        if ($def['ids']) {
            $pids = explode(',', $def['ids']);
        }

        $options = array(
            array(
                "type" => "cols",
                "size" => "image-buttons",
                "action" => "start",
                "class" => "hybgl-preview-table"
            ),
            array(
                "id" => "image-selector",
                "type" => "img_selector",
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "image-preview-panel",
                "class" => "hybgl-panel-preview"
            ),
            array(
                "id" => "preview",
                "type" => "preview",
                "options" => $pids
            ),
            array(
                "type" => "cols",
                "action" => "end",
            ),
            array(
                "attr" => "ids",
                "id" => "ids",
                "class" => "hybgl-field-class-ids",
                "type" => "text",
                "default" => $def['ids']
            ),
            array(
                "id" => "tab-switcher",
                "type" => "tab-switcher",
                "tabs" => array(
                    "general" => esc_html__("General", "hybrid-gallery"),
                    "image" => esc_html__("Image", "hybrid-gallery"),
                    "extra" => esc_html__("Extra", "hybrid-gallery"),
                    "container" => esc_html__("Container", "hybrid-gallery"),
                    "responsive" => esc_html__("Responsive", "hybrid-gallery")
                )
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "name" => esc_html__("Layout", "hybrid-gallery"),
                "description" => esc_html__("Select layout", "hybrid-gallery"),
                "attr" => "layout",
                "id" => "layout",
                "type" => "select",
                "options" => array(
                    "1" => esc_html__("Normal", "hybrid-gallery"),
                    "2" => esc_html__("Thumbnails: Horizontal", "hybrid-gallery"),
                    "3" => esc_html__("Thumbnails: Vertical", "hybrid-gallery")
                ),
                "default" => $def['layout']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Thumbnails width", "hybrid-gallery"),
                "description" => esc_html__("Input thumbnails width in pixels", "hybrid-gallery"),
                "attr" => "thumbs_w",
                "id" => "thumbs-w",
                "type" => "number",
                "default" => $def['thumbs_w'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array("2", "3")
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Thumbnails height", "hybrid-gallery"),
                "description" => esc_html__("Input thumbnails height in pixels", "hybrid-gallery"),
                "attr" => "thumbs_h",
                "id" => "thumbs-h",
                "type" => "number",
                "default" => $def['thumbs_h'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array("2", "3")
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Thumbnails gap", "hybrid-gallery"),
                "description" => esc_html__("Space between thumbnails", "hybrid-gallery"),
                "attr" => "thumbs_gap",
                "id" => "thumbs-gap",
                "type" => "number",
                "default" => $def['thumbs_gap'],
                "dependency" => array(
                    "id" => "layout",
                    "value" => array("2", "3")
                )
            ),
            array(
                "type" => "cols",
                "action" => "end",
            ),
            array(
                "name" => esc_html__("Size", "hybrid-gallery"),
                "description" => esc_html__("Select image size", "hybrid-gallery"),
                "attr" => "size",
                "id" => "size",
                "type" => "select",
                "options" => array(
                    "fixed" => esc_html__("Fixed", "hybrid-gallery"),
                    "equal" => esc_html__("Equal", "hybrid-gallery"),
                    "adaptive" => esc_html__("Adaptive", "hybrid-gallery")
                ),
                "default" => $def['size']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Ratio width", "hybrid-gallery"),
                "description" => esc_html__("Images: aspect ratio: width", "hybrid-gallery"),
                "attr" => "ratio_w",
                "id" => "ratio_w",
                "type" => "number",
                "default" => $def['ratio_w'],
                "dependency" => array(
                    "id" => "size",
                    "value" => array(
                        "fixed",
                        "equal"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Ratio height", "hybrid-gallery"),
                "description" => esc_html__("Images: aspect ratio: height", "hybrid-gallery"),
                "attr" => "ratio_h",
                "id" => "ratio_h",
                "type" => "number",
                "default" => $def['ratio_h'],
                "dependency" => array(
                    "id" => "size",
                    "value" => array(
                        "fixed",
                        "equal"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Navigation", "hybrid-gallery"),
                "description" => esc_html__("Show navigation", "hybrid-gallery"),
                "attr" => "nav",
                "id" => "nav",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "true" => esc_html__("On", "hybrid-gallery")
                ),
                "default" => $def['nav']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Dots", "hybrid-gallery"),
                "description" => esc_html__("Show dots", "hybrid-gallery"),
                "attr" => "dots",
                "id" => "dots",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "true" => esc_html__("On", "hybrid-gallery")
                ),
                "default" => $def['dots']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Navigation position", "hybrid-gallery"),
                "description" => esc_html__("Select navigation position", "hybrid-gallery"),
                "attr" => "nav_pos",
                "id" => "nav-pos",
                "type" => "select",
                "options" => array(
                    1 => esc_html__("Position 1", "hybrid-gallery"),
                    2 => esc_html__("Position 2", "hybrid-gallery"),
                    3 => esc_html__("Position 3", "hybrid-gallery")
                ),
                "default" => $def['nav_pos'],
                "dependency" => array(
                    "id" => "nav",
                    "value" => array(
                        "true"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Dots position", "hybrid-gallery"),
                "description" => esc_html__("Select dots position", "hybrid-gallery"),
                "attr" => "dots_pos",
                "id" => "dots-pos",
                "type" => "select",
                "options" => array(
                    1 => esc_html__("Position 1", "hybrid-gallery"),
                    2 => esc_html__("Position 2", "hybrid-gallery"),
                    3 => esc_html__("Position 3", "hybrid-gallery")
                ),
                "default" => $def['dots_pos'],
                "dependency" => array(
                    "id" => "dots",
                    "value" => array(
                        "true"
                    )
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "name" => esc_html__("Formats", "hybrid-gallery"),
                "description" => esc_html__("Show elements in their formats (image, video...)", "hybrid-gallery"),
                "attr" => "formats",
                "id" => "formats",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery"),
                ),
                "default" => $def['formats'],
            ),
            array(
                "name" => esc_html__("Color", "hybrid-gallery"),
                "description" => esc_html__("Set custom color for carousel", "hybrid-gallery"),
                "attr" => "color",
                "id" => "color",
                "type" => "color",
                "hex" => "#b90000",
                "default" => $def['color']
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "name" => esc_html__("Title", "hybrid-gallery"),
                "description" => esc_html__("Show image title", "hybrid-gallery"),
                "attr" => "meta_title",
                "id" => "meta-title",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery"),
                ),
                "default" => $def['meta_title'],
            ),
            array(
                "name" => esc_html__("Description", "hybrid-gallery"),
                "description" => esc_html__("Show image description", "hybrid-gallery"),
                "attr" => "meta_descr",
                "id" => "meta-descr",
                "type" => "select",
                "options" => array(
                    "true" => esc_html__("On", "hybrid-gallery"),
                    "false" => esc_html__("Off", "hybrid-gallery"),
                ),
                "default" => $def['meta_descr'],
            ),
            array(
                "name" => esc_html__("Animation: title", "hybrid-gallery"),
                "description" => esc_html__("Select animation for image title", "hybrid-gallery"),
                "attr" => "meta_animaton",
                "id" => "meta-animation",
                "type" => "select",
                "options" => array(
                    "false" => "None",
                    "bounce" => "bounce",
                    "flash" => "flash",
                    "pulse" => "pulse",
                    "rubberBand" => "rubberBand",
                    "shake" => "shake",
                    "swing" => "swing",
                    "tada" => "tada",
                    "wobble" => "wobble",
                    "jello" => "jello",
                    "bounceIn" => "bounceIn",
                    "bounceInDown" => "bounceInDown",
                    "bounceInLeft" => "bounceInLeft",
                    "bounceInRight" => "bounceInRight",
                    "bounceInRight" => "bounceInRight",
                    "bounceInUp" => "bounceInUp",
                    "fadeIn" => "fadeIn",
                    "fadeInDown" => "fadeInDown",
                    "fadeInDownBig" => "fadeInDownBig",
                    "fadeInLeft" => "fadeInLeft",
                    "fadeInLeftBig" => "fadeInLeftBig",
                    "fadeInRight" => "fadeInRight",
                    "fadeInRightBig" => "fadeInRightBig",
                    "fadeInUp" => "fadeInUp",
                    "fadeInUpBig" => "fadeInUpBig",
                    "flip" => "flip",
                    "flipInX" => "flipInX",
                    "flipInY" => "flipInY",
                    "lightSpeedIn" => "lightSpeedIn",
                    "rotateIn" => "rotateIn",
                    "rotateInDownLeft" => "rotateInDownLeft",
                    "rotateInDownRight" => "rotateInDownRight",
                    "rotateInUpLeft" => "rotateInUpLeft",
                    "rotateInUpRight" => "rotateInUpRight",
                    "slideInUp" => "slideInUp",
                    "slideInDown" => "slideInDown",
                    "slideInLeft" => "slideInLeft",
                    "slideInRight" => "slideInRight",
                    "zoomIn" => "zoomIn",
                    "zoomInDown" => "zoomInDown",
                    "zoomInLeft" => "zoomInLeft",
                    "zoomInRight" => "zoomInRight",
                    "zoomInUp" => "zoomInUp",
                    "hinge" => "hinge",
                    "rollIn" => "rollIn"
                ),
                "default" => $def['meta_animation'],
                "dependency" => array(
                    "id" => "meta-title",
                    "value" => "true"
                )
            ),
            array(
                "name" => esc_html__("Lightbox", "hybrid-gallery"),
                "description" => esc_html__("Enable lightbox", "hybrid-gallery"),
                "attr" => "lightbox",
                "id" => "lightbox",
                "type" => "select",
                "options" => array(
                    "false" => esc_html__("Off", "hybrid-gallery"),
                    "true" => esc_html__("On", "hybrid-gallery")
                ),
                "default" => $def['lightbox']
            ),
            array(
                "name" => esc_html__("Lightbox position", "hybrid-gallery"),
                "description" => esc_html__("Select lightbox position", "hybrid-gallery"),
                "attr" => "lb_pos",
                "id" => "lb-pos",
                "type" => "select",
                "options" => array(
                    1 => esc_html__("Position 1", "hybrid-gallery"),
                    2 => esc_html__("Position 2", "hybrid-gallery"),
                ),
                "default" => $def['lb_pos'],
                "dependency" => array(
                    "id" => "lightbox",
                    "value" => array(
                        "true"
                    )
                )
            ),
            array(
                "name" => esc_html__("Lightbox type", "hybrid-gallery"),
                "description" => esc_html__("Select lightbox type", "hybrid-gallery"),
                "attr" => "lb_type",
                "id" => "lb-type",
                "type" => "select",
                "options" => array(
                    "mp" => "Magnific Popup",
                    "cb" => "Colorbox",
                    "lg" => "lightGallery",
                    "pp" => "prettyPhoto",
                    "fyb" => "fancyBox",
                    "ilb" => "iLightBox",
                    "lc" => "Lightcase",
                ),
                "default" => $def['lb_type'],
                "dependency" => array(
                    "id" => "lightbox",
                    "value" => "true"
                )
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Animation: images", "hybrid-gallery"),
                "description" => esc_html__("Select animation for images", "hybrid-gallery"),
                "attr" => "animation_main",
                "id" => "animation_main",
                "type" => "select",
                "options" => array(
                    "false" => "None",
                    "bounce" => "bounce",
                    "flash" => "flash",
                    "pulse" => "pulse",
                    "rubberBand" => "rubberBand",
                    "shake" => "shake",
                    "swing" => "swing",
                    "tada" => "tada",
                    "wobble" => "wobble",
                    "jello" => "jello",
                    "bounceIn" => "bounceIn",
                    "bounceInDown" => "bounceInDown",
                    "bounceInLeft" => "bounceInLeft",
                    "bounceInRight" => "bounceInRight",
                    "bounceInRight" => "bounceInRight",
                    "bounceInUp" => "bounceInUp",
                    "fadeIn" => "fadeIn",
                    "fadeInDown" => "fadeInDown",
                    "fadeInDownBig" => "fadeInDownBig",
                    "fadeInLeft" => "fadeInLeft",
                    "fadeInLeftBig" => "fadeInLeftBig",
                    "fadeInRight" => "fadeInRight",
                    "fadeInRightBig" => "fadeInRightBig",
                    "fadeInUp" => "fadeInUp",
                    "fadeInUpBig" => "fadeInUpBig",
                    "flip" => "flip",
                    "flipInX" => "flipInX",
                    "flipInY" => "flipInY",
                    "lightSpeedIn" => "lightSpeedIn",
                    "rotateIn" => "rotateIn",
                    "rotateInDownLeft" => "rotateInDownLeft",
                    "rotateInDownRight" => "rotateInDownRight",
                    "rotateInUpLeft" => "rotateInUpLeft",
                    "rotateInUpRight" => "rotateInUpRight",
                    "slideInUp" => "slideInUp",
                    "slideInDown" => "slideInDown",
                    "slideInLeft" => "slideInLeft",
                    "slideInRight" => "slideInRight",
                    "zoomIn" => "zoomIn",
                    "zoomInDown" => "zoomInDown",
                    "zoomInLeft" => "zoomInLeft",
                    "zoomInRight" => "zoomInRight",
                    "zoomInUp" => "zoomInUp",
                    "hinge" => "hinge",
                    "rollIn" => "rollIn"
                ),
                "default" => $def['animation_main']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Animation: thumbnails", "hybrid-gallery"),
                "description" => esc_html__("Select animation for thumbnails", "hybrid-gallery"),
                "attr" => "animation_child",
                "id" => "animation_child",
                "type" => "select",
                "options" => array(
                    "false" => "None",
                    "bounce" => "bounce",
                    "flash" => "flash",
                    "pulse" => "pulse",
                    "rubberBand" => "rubberBand",
                    "shake" => "shake",
                    "swing" => "swing",
                    "tada" => "tada",
                    "wobble" => "wobble",
                    "jello" => "jello",
                    "bounceIn" => "bounceIn",
                    "bounceInDown" => "bounceInDown",
                    "bounceInLeft" => "bounceInLeft",
                    "bounceInRight" => "bounceInRight",
                    "bounceInRight" => "bounceInRight",
                    "bounceInUp" => "bounceInUp",
                    "fadeIn" => "fadeIn",
                    "fadeInDown" => "fadeInDown",
                    "fadeInDownBig" => "fadeInDownBig",
                    "fadeInLeft" => "fadeInLeft",
                    "fadeInLeftBig" => "fadeInLeftBig",
                    "fadeInRight" => "fadeInRight",
                    "fadeInRightBig" => "fadeInRightBig",
                    "fadeInUp" => "fadeInUp",
                    "fadeInUpBig" => "fadeInUpBig",
                    "flip" => "flip",
                    "flipInX" => "flipInX",
                    "flipInY" => "flipInY",
                    "lightSpeedIn" => "lightSpeedIn",
                    "rotateIn" => "rotateIn",
                    "rotateInDownLeft" => "rotateInDownLeft",
                    "rotateInDownRight" => "rotateInDownRight",
                    "rotateInUpLeft" => "rotateInUpLeft",
                    "rotateInUpRight" => "rotateInUpRight",
                    "slideInUp" => "slideInUp",
                    "slideInDown" => "slideInDown",
                    "slideInLeft" => "slideInLeft",
                    "slideInRight" => "slideInRight",
                    "zoomIn" => "zoomIn",
                    "zoomInDown" => "zoomInDown",
                    "zoomInLeft" => "zoomInLeft",
                    "zoomInRight" => "zoomInRight",
                    "zoomInUp" => "zoomInUp",
                    "hinge" => "hinge",
                    "rollIn" => "rollIn"
                ),
                "default" => $def['animation_child']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "name" => esc_html__("Preloader", "hybrid-gallery"),
                "description" => esc_html__("Show animation while loading Slider", "hybrid-gallery"),
                "attr" => "preloader",
                "id" => "preloader",
                "type" => "select",
                "options" => array(
                    1 => esc_html__("Preloader", "hybrid-gallery") . " #1",
                    2 => esc_html__("Preloader", "hybrid-gallery") . " #2",
                    3 => esc_html__("Preloader", "hybrid-gallery") . " #3",
                    4 => esc_html__("Preloader", "hybrid-gallery") . " #4",
                    5 => esc_html__("Preloader", "hybrid-gallery") . " #5",
                    6 => esc_html__("Preloader", "hybrid-gallery") . " #6",
                    7 => esc_html__("Preloader", "hybrid-gallery") . " #7",
                    8 => esc_html__("Preloader", "hybrid-gallery") . " #8",
                    9 => esc_html__("Preloader", "hybrid-gallery") . " #9",
                    10 => esc_html__("Preloader", "hybrid-gallery") . " #10"
                ),
                "default" => $def['preloader']
            ),
            array(
                "name" => esc_html__("Loader Delay", "hybrid-gallery"),
                "description" => esc_html__("Enter loader delay time in milliseconds.", "hybrid-gallery"),
                "attr" => "loader_delay",
                "id" => "loader-delay",
                "type" => "number",
                "default" => $def['loader_delay']
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-8"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Slider width", "hybrid-gallery"),
                "attr" => "ct_w_vl",
                "id" => "ct-w-vl",
                "type" => "number",
                "default" => $def['ct_w_vl']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "ct_w_un",
                "id" => "ct-w-un",
                "type" => "select",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['ct_w_un']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Slider align", "hybrid-gallery"),
                "attr" => "ct_align",
                "id" => "ct-align",
                "type" => "select",
                "options" => array(
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['ct_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Extra class", "hybrid-gallery"),
                "description" => esc_html__("Add extra class to Slider", "hybrid-gallery"),
                "attr" => "custom_class",
                "id" => "custom-class",
                "type" => "text",
                "default" => $def['custom_class'],
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Add id", "hybrid-gallery"),
                "description" => esc_html__("Add id to Slider", "hybrid-gallery"),
                "attr" => "custom_id",
                "id" => "custom-id",
                "type" => "text",
                "default" => $def['custom_id'],
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "start"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_1_ss",
                "id" => "res-1-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_1_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Slider width", "hybrid-gallery"),
                "attr" => "res_1_w",
                "id" => "res-1-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_1_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_1_w_vl",
                "id" => "res-1-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_1_w_vl'],
                "dependency" => array(
                    "id" => "res-1-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_1_w_un",
                "id" => "res-1-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_1_w_un'],
                "dependency" => array(
                    "id" => "res-1-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Slider align", "hybrid-gallery"),
                "attr" => "res_1_align",
                "id" => "res-1-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_1_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_2_ss",
                "id" => "res-2-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_2_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Slider width", "hybrid-gallery"),
                "attr" => "res_2_w",
                "id" => "res-2-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_2_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_2_w_vl",
                "id" => "res-2-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_2_w_vl'],
                "dependency" => array(
                    "id" => "res-2-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_2_w_un",
                "id" => "res-2-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_2_w_un'],
                "dependency" => array(
                    "id" => "res-2-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Slider align", "hybrid-gallery"),
                "attr" => "res_2_align",
                "id" => "res-2-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_2_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_3_ss",
                "id" => "res-3-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_3_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Slider width", "hybrid-gallery"),
                "attr" => "res_3_w",
                "id" => "res-3-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_3_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_3_w_vl",
                "id" => "res-3-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_3_w_vl'],
                "dependency" => array(
                    "id" => "res-3-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_3_w_un",
                "id" => "res-3-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_3_w_un'],
                "dependency" => array(
                    "id" => "res-3-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Slider align", "hybrid-gallery"),
                "attr" => "res_3_align",
                "id" => "res-3-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_3_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_4_ss",
                "id" => "res-4-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_4_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Slider width", "hybrid-gallery"),
                "attr" => "res_4_w",
                "id" => "res-4-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_4_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_4_w_vl",
                "id" => "res-4-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_4_w_vl'],
                "dependency" => array(
                    "id" => "res-4-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_4_w_un",
                "id" => "res-4-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_4_w_un'],
                "dependency" => array(
                    "id" => "res-4-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Slider align", "hybrid-gallery"),
                "attr" => "res_4_align",
                "id" => "res-4-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_4_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-2"
            ),
            array(
                "name" => esc_html__("Size (<= px)", "hybrid-gallery"),
                "description" => esc_html__("If screen width is less or equal than", "hybrid-gallery"),
                "attr" => "res_5_ss",
                "id" => "res-5-ss",
                "type" => "number",
                "mode" => "res",
                "point" => "screen",
                "default" => $def['res_5_ss']
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-2"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-6"
            ),
            array(
                "name" => esc_html__("Slider width", "hybrid-gallery"),
                "attr" => "res_5_w",
                "id" => "res-5-w",
                "type" => "select",
                "mode" => "res",
                "point" => "width",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "custom" => esc_html__("Custom", "hybrid-gallery")
                ),
                "default" => $def['res_5_w']
            ),
            array(
                "type" => "cols",
                "action" => "start",
                "size" => "col-9"
            ),
            array(
                "name" => esc_html__("Value", "hybrid-gallery"),
                "attr" => "res_5_w_vl",
                "id" => "res-5-w-vl",
                "type" => "number",
                "mode" => "res",
                "point" => "value",
                "default" => $def['res_5_w_vl'],
                "dependency" => array(
                    "id" => "res-5-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-3"
            ),
            array(
                "name" => esc_html__("Unit", "hybrid-gallery"),
                "attr" => "res_5_w_un",
                "id" => "res-5-w-un",
                "type" => "select",
                "mode" => "res",
                "point" => "unit",
                "options" => array(
                    "px" => esc_html__("PX", "hybrid-gallery"),
                    "pc" => "%"
                ),
                "default" => $def['res_5_w_un'],
                "dependency" => array(
                    "id" => "res-5-w",
                    "value" => "custom"
                )
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "cols",
                "action" => "change",
                "size" => "col-4"
            ),
            array(
                "name" => esc_html__("Slider align", "hybrid-gallery"),
                "attr" => "res_5_align",
                "id" => "res-5-align",
                "type" => "select",
                "mode" => "res",
                "point" => "align",
                "options" => array(
                    "auto" => esc_html__("Auto", "hybrid-gallery"),
                    "none" => esc_html__("None", "hybrid-gallery"),
                    "left" => esc_html__("Left", "hybrid-gallery"),
                    "center" => esc_html__("Center", "hybrid-gallery"),
                    "right" => esc_html__("Right", "hybrid-gallery")
                ),
                "default" => $def['res_5_align']
            ),
            array(
                "type" => "cols",
                "action" => "end"
            ),
            array(
                "type" => "tab",
                "action" => "end"
            )
        );
        hybrid_gallery_framework($options, 'slider');
    }
}