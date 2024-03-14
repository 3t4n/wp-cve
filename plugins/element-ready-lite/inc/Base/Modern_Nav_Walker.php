<?php
namespace Element_Ready\Base;

class Modern_Nav_Walker extends \Walker_Nav_Menu {
    private $counter = 0;

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $sub_menu_cls = 'element-ready-dropdown element-ready-submenu-section ';
        $sub_menu_cls .= 'sub-menu-class';
        $output .= "$indent<ul class=\"$sub_menu_cls\">\n";
    }

     public function get_item_meta($item_id)
    {

        $is_mega_menu                     = get_post_meta($item_id, 'element_ready_mega_menu_post_item_mega_menu_enable', true);
        $is_off_canvas                    = get_post_meta($item_id, 'element_ready_mega_menu_post_item_mega_offcanvas_enable', true);
        $is_mobile_menu                   = get_post_meta($item_id, 'element_ready_mega_menu_post_item_mega_is_mobile_menu', true);
        $content_id                       = get_post_meta($item_id, 'element_ready_mega_menu_post_item_mega_content_id', true);
        $submenu_content_type             = get_post_meta($item_id, 'element_ready_mobile_submenu_content_type', true);
        $megamenu_width_type              = get_post_meta($item_id, 'element_ready_megamenu_width_type', true);
        $vertical_megamenu_position       = get_post_meta($item_id, 'element_ready_vertical_megamenu_position_type', true);


        $default = [

            "menu_id"                    => null,
            "is_mobile_mega_menu"        => $is_mobile_menu,
            "is_mega_menu"               => $is_mega_menu,
            "is_off_canvas"              => $is_off_canvas,
            "submenu_content_type"       => $submenu_content_type,
            "megamenu_width_type"        => $megamenu_width_type,
            "vertical_megamenu_position" => $vertical_megamenu_position,
            "content_id"                 => $content_id,

        ];

        return $default;
    }
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = str_repeat("\t", $depth);
        $classes = empty($item->classes) ? array() : (array) $item->classes;
      
        if($depth === 0 ){
             $this->counter++;
        }
        
        $classes[] = 'menu-item-' . $item->ID;
        $item_meta = $this->get_item_meta($item->ID);
        if($depth == 0){
            if ($item_meta['is_mega_menu'] == 'yes' && class_exists('Elementor\Plugin')) {
                $classes[] = 'element-ready-mega-menu-item';
            }


           $is_mega_menu = get_post_meta($item->ID, 'element_ready_mega_menu_post_item_mega_menu_enable', true);
            if ($is_mega_menu == 'on' || $is_mega_menu == 'yes') {
                $classes[] = 'er-mega-menu-builder';
            }
        }
        $class_names = join(" ",$classes);
        
        if (in_array('menu-item-has-children', $classes)) {
            if ($depth == 0) {
                $class_names .= " dropdown";
            } else {
                $class_names .= " dropdown-submenu ";
            }
        }

        if (in_array('current-menu-item', $classes)) {
            $class_names .= ' active';
        }

        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "$indent<li $class_names>";
        if ($depth === 0) {
            $output .= "<span class='menu-number'>" . $this->counter . "</span>";
        }
        $output .= "<a class='s_item' href='" . $item->url . "'>" . $item->title . "</a>";
    }

    function end_el(&$output, $item, $depth = 0, $args = array()) {
        $itemid = $this->get_item_meta($item->ID);
        $is_mega_menu = get_post_meta($item->ID, 'element_ready_mega_menu_post_item_mega_menu_enable', true);
        if($is_mega_menu){
            $elementor = \Elementor\Plugin::instance();
            $content = $elementor->frontend->get_builder_content_for_display($itemid['content_id']);
            $output .= "<ul class='er-mega-menu-wrapperer'>";
            $output .="<li class='er-mega-menu-item'>";
            $output .= $content;
            $output .="</li>";
            $output .= "</ul>";
        }
        

        $indent = str_repeat("\t", $depth);
        $output .= "</li>\n";
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}