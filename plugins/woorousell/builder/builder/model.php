<?php

/**
 * Builder Model Class
 *
 * @author 		MojofyWP
 * @package 	builder/builder
 * 
 */

if ( !class_exists( 'WRSL_Builder_Model' ) ) {
    class WRSL_Builder_Model
    {
        /**
         * Hook prefix
         *
         * @access private
         * @var string
         */
        private  $_hook_prefix = null ;
        /**
         * Class Constructor
         *
         * @access private
         */
        function __construct()
        {
            // setup variables
            $this->_hook_prefix = wrsl()->plugin_hook() . 'builder/model/';
        }
        
        /**
         * Register Carousel Mojo
         *
         * @access public
         */
        public function register_woorousell()
        {
            register_post_type( 'wrsl', apply_filters( wrsl()->plugin_hook() . 'register_woorousell', array(
                'labels'            => array(
                'name'               => __( 'WoorouSell', WRSL_SLUG ),
                'singular_name'      => __( 'WoorouSell', WRSL_SLUG ),
                'menu_name'          => __( 'WoorouSell', WRSL_SLUG ),
                'all_items'          => __( 'WoorouSell', WRSL_SLUG ),
                'add_new'            => __( 'Add New WoorouSell', WRSL_SLUG ),
                'add_new_item'       => __( 'Add New WoorouSell', WRSL_SLUG ),
                'edit_item'          => __( 'Edit WoorouSell', WRSL_SLUG ),
                'edit'               => __( 'Edit', WRSL_SLUG ),
                'new_item'           => __( 'New WoorouSell', WRSL_SLUG ),
                'view_item'          => __( 'View WoorouSell', WRSL_SLUG ),
                'search_items'       => __( 'Search WoorouSell', WRSL_SLUG ),
                'not_found'          => __( 'No WoorouSell Found', WRSL_SLUG ),
                'not_found_in_trash' => __( 'No WoorouSell found in Trash', WRSL_SLUG ),
                'view'               => __( 'View WoorouSell', WRSL_SLUG ),
            ),
                'public'            => false,
                'show_ui'           => false,
                'capability_type'   => 'post',
                'hierarchical'      => false,
                'rewrite'           => false,
                'supports'          => array( 'title', 'editor' ),
                'query_var'         => false,
                'can_export'        => true,
                'show_in_nav_menus' => false,
            ) ) );
        }
        
        /**
         * Retrieve Carousel Value
         *
         * @access public
         * @return string
         */
        public function get_values( $c_id = 0 )
        {
            $values = array();
            $carousel_type = wrslb_get_meta( array(
                'id'      => $c_id,
                'key'     => 'carousel_type',
                'default' => 'post',
                'esc'     => 'attr',
            ) );
            $default = wrsl_default_meta( $carousel_type );
            if ( !empty($default) && is_array( $default ) ) {
                foreach ( $default as $key => $d_value ) {
                    
                    if ( $key == 'columns' ) {
                        $values[$key] = wrslb_get_meta( array(
                            'id'      => $c_id,
                            'key'     => $key,
                            'default' => $d_value,
                            'esc'     => null,
                        ) );
                    } else {
                        $values[$key] = wrslb_get_meta( array(
                            'id'      => $c_id,
                            'key'     => $key,
                            'default' => $d_value,
                            'esc'     => 'attr',
                        ) );
                    }
                
                }
            }
            return apply_filters(
                $this->_hook_prefix . 'get_values',
                $values,
                $c_id,
                $this
            );
        }
        
        /**
         * Enqueue scripts
         *
         * @access public
         */
        public function enqueue_scripts()
        {
            $wrsl = wrsl();
            wp_enqueue_script(
                'bxslider',
                $wrsl->plugin_url( 'assets/js/jquery.bxslider.min.js' ),
                array( 'jquery' ),
                '4.2.5'
            );
            wp_enqueue_script(
                'woorousell',
                $wrsl->plugin_url( 'assets/js/plugin.js' ),
                array( 'jquery' ),
                WRSL_VERSION
            );
            wp_localize_script( 'woorousell', 'WRSLL', apply_filters( $wrsl->plugin_hook() . 'localize_args', array(
                'MobileWidth'  => 380,
                'tablet1Width' => 550,
                'tablet2Width' => 730,
                'LaptopWidth'  => 910,
            ) ) );
        }
        
        /**
         * Retrieve component object
         *
         * @access public
         * @return object
         */
        public function get_component( $id = 0, $values = array() )
        {
            $component = new WRSL_Products_Controller( array(
                'widget_id'                => ( !empty($id) ? 'woorousell-' . $id : null ),
                'component_id'             => $id,
                'total_col'                => ( isset( $values['total_col'] ) ? $values['total_col'] : null ),
                'post_type'                => 'product',
                'taxonomy'                 => 'product_cat',
                'box_style'                => ( isset( $values['box_style'] ) ? $values['box_style'] : null ),
                'text_style'               => ( isset( $values['text_style'] ) ? $values['text_style'] : null ),
                'category'                 => ( isset( $values['category'] ) ? $values['category'] : null ),
                'category_relation'        => ( isset( $values['category_relation'] ) ? $values['category_relation'] : null ),
                'show_media'               => ( isset( $values['show_media'] ) && $values['show_media'] == 'on' ? true : false ),
                'show_titles'              => ( isset( $values['show_titles'] ) && $values['show_titles'] == 'on' ? true : false ),
                'show_excerpts'            => ( isset( $values['show_excerpts'] ) && $values['show_excerpts'] == 'on' ? true : false ),
                'excerpt_length'           => ( isset( $values['excerpt_length'] ) ? $values['excerpt_length'] : null ),
                'show_price'               => ( isset( $values['show_price'] ) && $values['show_price'] == 'on' ? true : false ),
                'show_badges'              => ( isset( $values['show_badges'] ) && $values['show_badges'] == 'on' ? true : false ),
                'show_ratings'             => ( isset( $values['show_ratings'] ) && $values['show_ratings'] == 'on' ? true : false ),
                'show_buy_button'          => ( isset( $values['show_buy_button'] ) && $values['show_buy_button'] == 'on' ? true : false ),
                'posts_per_page'           => ( isset( $values['posts_per_page'] ) ? $values['posts_per_page'] : null ),
                'order'                    => ( isset( $values['order'] ) ? $values['order'] : null ),
                'filter_by'                => ( isset( $values['filter_by'] ) ? $values['filter_by'] : null ),
                'filter_price_range_max'   => ( isset( $values['filter_price_range_max'] ) ? $values['filter_price_range_max'] : 0 ),
                'filter_price_range_min'   => ( isset( $values['filter_price_range_min'] ) ? $values['filter_price_range_min'] : 0 ),
                'filter_price_range_from'  => ( isset( $values['filter_price_range_from'] ) ? $values['filter_price_range_from'] : 0 ),
                'filter_price_range_until' => ( isset( $values['filter_price_range_until'] ) ? $values['filter_price_range_until'] : 0 ),
                'hide_on_sale'             => ( isset( $values['hide_on_sale'] ) && ($values['hide_on_sale'] == 'on' || $values['hide_on_sale'] == 'only-sale') ? $values['hide_on_sale'] : false ),
                'hide_oos'                 => ( isset( $values['hide_oos'] ) && $values['hide_oos'] == 'on' ? true : false ),
                'related_products'         => ( isset( $values['related_products'] ) && $values['related_products'] == 'on' ? true : false ),
                'col_bg'                   => ( isset( $values['col_bg'] ) ? $values['col_bg'] : null ),
                'price_bg'                 => ( isset( $values['price_bg'] ) ? $values['price_bg'] : null ),
                'sale_badge_bg'            => ( isset( $values['sale_badge_bg'] ) ? $values['sale_badge_bg'] : null ),
                'content_align'            => ( isset( $values['content_align'] ) ? $values['content_align'] : null ),
                'c_speed'                  => ( isset( $values['c_speed'] ) ? $values['c_speed'] : null ),
                'c_moveone'                => ( isset( $values['c_moveone'] ) && $values['c_moveone'] == 'on' ? true : false ),
                'c_slidemargin'            => ( isset( $values['c_slidemargin'] ) ? $values['c_slidemargin'] : null ),
                'c_adaptiveheight'         => ( isset( $values['c_adaptiveheight'] ) && $values['c_adaptiveheight'] == 'on' ? true : false ),
                'c_adaptiveheightspeed'    => ( isset( $values['c_adaptiveheightspeed'] ) ? $values['c_adaptiveheightspeed'] : null ),
                'c_touchenabled'           => ( isset( $values['c_touchenabled'] ) && $values['c_touchenabled'] == 'on' ? true : false ),
                'c_swipethreshold'         => ( isset( $values['c_swipethreshold'] ) ? $values['c_swipethreshold'] : null ),
                'c_auto'                   => ( isset( $values['c_auto'] ) && $values['c_auto'] == 'on' ? true : false ),
                'c_pause'                  => ( isset( $values['c_pause'] ) ? $values['c_pause'] : null ),
                'c_autohover'              => ( isset( $values['c_autohover'] ) && $values['c_autohover'] == 'on' ? true : false ),
                'c_autodelay'              => ( isset( $values['c_autodelay'] ) ? $values['c_autodelay'] : null ),
                'c_ticker'                 => ( isset( $values['c_ticker'] ) && $values['c_ticker'] == 'on' ? true : false ),
                'c_ticker_hover'           => ( isset( $values['c_ticker_hover'] ) && $values['c_ticker_hover'] == 'on' ? true : false ),
                'controller_type'          => ( isset( $values['controller_type'] ) ? $values['controller_type'] : null ),
                'controller_icon'          => ( isset( $values['controller_icon'] ) ? $values['controller_icon'] : null ),
            ) );
            return apply_filters(
                $this->_hook_prefix . 'get_component',
                $component,
                $id,
                $values,
                $this
            );
        }
        
        /**
         * Apply inline styling
         *
         * @access public
         */
        public function apply_inline_styling( $id = 0, $values = array() )
        {
            global  $woorousell_fs ;
            if ( !empty($values['col_bg']) ) {
                wrsl_inline_styles( '#woorousell-' . $id, 'background', array(
                    'selectors'  => array( '.wrsl-prosingle-wrapper:not(.wrsl-with-overlay)' ),
                    'background' => array(
                    'color' => $values['col_bg'],
                ),
                ) );
            }
            if ( !empty($values['btn_color']) ) {
                
                if ( $values['text_style'] == 'regular' ) {
                    
                    if ( $values['carousel_type'] == 'product' && ($values['box_style'] == 'style-2' || $values['box_style'] == 'style-4' || $values['box_style'] == 'style-8') ) {
                        wrsl_inline_styles( '#woorousell-' . $id, 'background', array(
                            'selectors'  => array( '.button' ),
                            'background' => array(
                            'color' => 'none',
                        ),
                        ) );
                        wrsl_inline_styles( '#woorousell-' . $id, 'border', array(
                            'selectors' => array( '.button' ),
                            'border'    => array(
                            'color' => $values['btn_color'],
                        ),
                        ) );
                        wrsl_inline_styles( '#woorousell-' . $id, 'color', array(
                            'selectors' => array( '.button' ),
                            'color'     => $values['btn_color'],
                        ) );
                        wrsl_inline_styles( '#woorousell-' . $id, 'background', array(
                            'selectors'  => array( '.button:hover' ),
                            'background' => array(
                            'color' => $values['btn_color'],
                        ),
                        ) );
                        
                        if ( 'dark' != wrsl_is_light_or_dark( $values['btn_color'] ) ) {
                            wrsl_inline_styles( '#woorousell-' . $id, 'color', array(
                                'selectors' => array( '.button:hover' ),
                                'color'     => '#373737',
                            ) );
                        } else {
                            wrsl_inline_styles( '#woorousell-' . $id, 'color', array(
                                'selectors' => array( '.button:hover' ),
                                'color'     => '#fff',
                            ) );
                        }
                    
                    } else {
                        wrsl_inline_styles( '#woorousell-' . $id, 'background', array(
                            'selectors'  => array( '.button' ),
                            'background' => array(
                            'color' => $values['btn_color'],
                        ),
                        ) );
                        wrsl_inline_styles( '#woorousell-' . $id, 'css', array(
                            'selectors' => array( '.button:hover' ),
                            'css'       => 'opacity: 0.75;',
                        ) );
                        
                        if ( 'dark' != wrsl_is_light_or_dark( $values['btn_color'] ) ) {
                            wrsl_inline_styles( '#woorousell-' . $id, 'color', array(
                                'selectors' => array( '.button' ),
                                'color'     => '#373737',
                            ) );
                        } else {
                            wrsl_inline_styles( '#woorousell-' . $id, 'color', array(
                                'selectors' => array( '.button:hover' ),
                                'color'     => '#fff',
                            ) );
                        }
                        
                        if ( $values['carousel_type'] == 'product' && $values['box_style'] == 'style-1' ) {
                            wrsl_inline_styles( '#woorousell-' . $id, 'border', array(
                                'selectors' => array( '.button' ),
                                'border'    => array(
                                'color' => $values['btn_color'],
                            ),
                            ) );
                        }
                    }
                
                } else {
                    wrsl_inline_styles( '#woorousell-' . $id, 'background', array(
                        'selectors'  => array( '.button' ),
                        'background' => array(
                        'color' => $values['btn_color'],
                    ),
                    ) );
                    wrsl_inline_styles( '#woorousell-' . $id, 'border', array(
                        'selectors' => array( '.button' ),
                        'border'    => array(
                        'color' => $values['btn_color'],
                    ),
                    ) );
                    wrsl_inline_styles( '#woorousell-' . $id, 'css', array(
                        'selectors' => array( '.button:hover' ),
                        'css'       => 'opacity: 0.75;',
                    ) );
                    
                    if ( 'dark' != wrsl_is_light_or_dark( $values['btn_color'] ) ) {
                        wrsl_inline_styles( '#woorousell-' . $id, 'color', array(
                            'selectors' => array( '.button' ),
                            'color'     => '#373737',
                        ) );
                    } else {
                        wrsl_inline_styles( '#woorousell-' . $id, 'color', array(
                            'selectors' => array( '.button' ),
                            'color'     => '#fff',
                        ) );
                    }
                
                }
            
            }
            // end - btn_color
            if ( !empty($values['controller_color']) ) {
                wrsl_inline_styles( '#woorousell-' . $id, 'background', array(
                    'selectors'  => array( 'button.wrsl-carousel-to-prev', 'button.wrsl-carousel-to-next' ),
                    'background' => array(
                    'color' => esc_attr( $values['controller_color'] ),
                ),
                ) );
            }
            
            if ( isset( $values['controller_color'] ) && 'dark' == wrsl_is_light_or_dark( $values['controller_color'] ) ) {
                wrsl_inline_styles( '#woorousell-' . $id, 'color', array(
                    'selectors' => array( 'button.wrsl-carousel-to-prev', 'button.wrsl-carousel-to-next' ),
                    'color'     => '#ffffff',
                ) );
            } else {
                wrsl_inline_styles( '#woorousell-' . $id, 'color', array(
                    'selectors' => array( 'button.wrsl-carousel-to-prev', 'button.wrsl-carousel-to-next' ),
                    'color'     => '#373737',
                ) );
            }
            
            // execute inline styling
            add_action( 'get_footer', 'wrsl_apply_inline_styles', 100 );
        }
        
        /**
         * sample function
         *
         * @access public
         * @return string
         */
        public function sample_func()
        {
            $output = '';
            return apply_filters( $this->_hook_prefix . 'sample_func', $output, $this );
        }
    
    }
    // end - class WRSL_Builder_Model
}

// end - !class_exists('WRSL_Builder_Model')