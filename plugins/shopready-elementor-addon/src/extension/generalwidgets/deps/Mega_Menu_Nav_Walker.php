<?php
namespace Shop_Ready\extension\generalwidgets\deps;

class Mega_Menu_Nav_Walker extends \Walker_Nav_Menu
{
    public $elementor_settings = [
        'w_menu_mega_menu_active' => '',
        'before_menu_drop_icon'  => '',
        'menu_drop_icon'         => '',
        'submenu_indicator_icon' => '',
        'first_label_indicator_icon' => '',
        'first_label_indicator_icon_enable' => true,
        'second_label_indicator_icon_enable' => true,
        'second_label_indicator_icon' => '',
        'bedge_enable' => '',
        'layout'                 => ''
    ]; 
    function __construct($settings = []) {

        if( is_array($settings) ) {
           $this->elementor_settings = $settings;
        }
      
    }
     // get menu setting
    public function get_item_meta($item_id){
        
        
        $content_id = esc_html(get_post_meta( $item_id, 'woo_ready_elementor_tpl_id', true ));
       
        $default = [

            "menu_id"            => null,
            "is_mega_menu"       => is_numeric( $content_id ) ? true : false,
            "content_id"         => $content_id,
            "menu_bedge"         => esc_html(get_post_meta( $item_id, 'woo_ready_elementor_menu_bedge', true )),
            "menu_bedge_color"   => esc_html(get_post_meta( $item_id, 'woo_ready_elementor_menu_bedge_color', true )),
            "menu_bedge_bgcolor" => esc_html(get_post_meta( $item_id, 'woo_ready_elementor_menu_bedge_bgcolor', true ))
            
        
        ];

        return $default;
    }

    public function is_megamenu($menu_slug = null){
       
        return $this->elementor_settings['w_menu_mega_menu_active'];
    }

    public function is_megamenu_item($item_meta, $menu){
       
        if( $this->is_megamenu($menu) && $item_meta['is_mega_menu'] == true && class_exists( 'Elementor\Plugin' ) ){
            return true;

        }
        
        return false;
    }

    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"woo-ready-dropdown woo-ready-megamenu-submenu\">\n";
    }
    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;


        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        // New
        $class_names .= ' woo-ready-megamenu-menu-item';
        $item_meta = $this->get_item_meta($item->ID);
      
        $is_megamenu_item = isset($args->menu) ? $this->is_megamenu_item($item_meta, $args->menu): false;
      

        if ($is_megamenu_item == true) {
            $class_names .= ' item-megamenu';
        }


        if (in_array('current-menu-item', $classes)) {
            $class_names .= ' active';
        }


        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        // New
        
        $data_attr = '';
       
        //
        $output .= $indent . '<li' . $id . $class_names . $data_attr . '>';
        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        $submenu_indicator = '';


       
        // New
        if ($depth === 0) {
            $atts['class'] = 'woo-ready-menu-nav-link';
        }

        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $atts['class']       .= ' woo-ready-menu-dropdown-toggle';
        }

        if (in_array('menu-item-has-children', $classes) || $is_megamenu_item == true) {

             $ind_icon = $this->get_first_indicator_icon($depth);
            if($ind_icon){
                $submenu_indicator = $ind_icon;
            }
          
        }

        if ($depth > 0) {
            $manual_class = array_values($classes)[0] .' '. 'dropdown-item';
            $atts ['class']= $manual_class;
        }
        if ( is_array($item->classes) && in_array( 'current-menu-item' , $item->classes ) ) {
            $atts['class'] .= ' active';
        }

      
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        $item_output = isset($args->before)? $args->before : '';
        // New

        //
        $item_output .= '<a'. $attributes .'>';
          
        if($this->elementor_settings['bedge_enable'] == 'yes' && isset($item_meta['menu_bedge']) && $item_meta['menu_bedge'] != ''){
      
            $badge_color     = $item_meta['menu_bedge_color'];
            $badge_bgcolor   = $item_meta['menu_bedge_bgcolor'];

            $badge_bgcolor_s = '';
            $badge_color_s   = '';

            if($badge_bgcolor == '#000000'){
                $badge_bgcolor = '';  
            }

            if($badge_color == '#000000'){
                $badge_color = '';  
            }

            if($badge_color !=''){
                $badge_color_s = "color:{$badge_color}";
            }

            if($badge_bgcolor !=''){
                $badge_bgcolor_s = ";background-color:{$badge_bgcolor}";
            }

            $badge_style  = "style={$badge_color_s}{$badge_bgcolor_s}";
            $item_output .= sprintf('<span class="badge badge-manu" %2$s> %1$s </span>',
                    esc_html($item_meta['menu_bedge']),
                    wp_kses_post($badge_style)
                );

}
        /** This filter is documented in wp-includes/post-template.php */
        if(isset($args->link_before)){
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        }
        $item_output .= $submenu_indicator . '</a>';
        if(isset($args->link_before)){
            $item_output .= $args->after;
        }
        
        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of {@see wp_nav_menu()} arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    public function get_first_indicator_icon($depth){

        if($depth === 0 && $this->elementor_settings['first_label_indicator_icon_enable'] == true){

            if( $this->elementor_settings['first_label_indicator_icon'] == '' && !is_array( $this->elementor_settings[ 'first_label_indicator_icon' ] ) ){
                return '<i class="fa fa-angle-down woo-ready-submenu-indicator"></i>';
            }else{
               return shop_ready_render_icons( $this->elementor_settings['first_label_indicator_icon'] , 'wready-icons wr-first-label-indicator');
            }
            
        }else{
            
             if($this->elementor_settings['second_label_indicator_icon_enable'] == true){

                if( $this->elementor_settings['second_label_indicator_icon'] == '' && !is_array( $this->elementor_settings[ 'second_label_indicator_icon' ] ) ){
                    return '<i class="fa fa-angle-right woo-ready-submenu-indicator"></i>';
                }else{
                   return shop_ready_render_icons( $this->elementor_settings['second_label_indicator_icon'] , 'wready-icons wr-nested-label-indicator');
                } 

             }
          
        }

        return false;
    }

    /**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
       
       
        if ($depth === 0) {
            
            if( $this->is_megamenu() ){
               
                $item_meta = $this->get_item_meta($item->ID);
               
                if( $item_meta['is_mega_menu'] && class_exists( 'Elementor\Plugin' ) ){
                 
                    $output .= '<div class="woo-ready-megamenu-submenu">';

                    if( is_numeric($item_meta['content_id']) ){
                        $elementor = \Elementor\Plugin::instance();
                        $output .= $elementor->frontend->get_builder_content_for_display( $item_meta['content_id'], true );
                    }else{
                        $output .= esc_html__('No content found', 'shopready-elementor-addon');
                    }

                    $output .= '</div>';
                } // end if
            }
            $output .= "</li>\n";
        }
    }
}