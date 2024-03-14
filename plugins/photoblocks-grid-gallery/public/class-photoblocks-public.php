<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://machothemes.com
 * @since      1.0.0
 *
 * @package    Photoblocks
 * @subpackage Photoblocks/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Photoblocks
 * @subpackage Photoblocks/public
 * @author     MachoThemes <office@machothemes.net>
 */
class Photoblocks_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Gallery settings
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $settings    Gallery settings
     */
    private  $settings ;
    /**
     * Gallery values
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $defaults    Default values
     */
    private  $values ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version, $settings )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings = $settings;
        $this->values = array();
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Photoblocks_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Photoblocks_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/photoblocks-public.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'anime',
            plugin_dir_url( __FILE__ ) . 'js/anime.min.js',
            array(),
            $this->version,
            false
        );
        wp_enqueue_script(
            'packery',
            plugin_dir_url( __FILE__ ) . 'js/packery.pkgd.js',
            array( 'jquery' ),
            0,
            false
        );
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/photoblocks.public.js',
            array( 'jquery', 'anime', 'packery' ),
            $this->version,
            false
        );
    }
    
    /**
     * Handle shortcode
     *
     * @since    1.0.0
     */
    public function shortcode_handler( $atts )
    {
        global  $wpdb ;
        $data = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->photoblocks . ' WHERE id=%d', intval( $atts['id'] ) ), ARRAY_A );
        
        if ( $data == null ) {
            return "<small class='pb-gallery-error'>No gallery found with ID " . $atts['id'] . '</small>';
        } else {
            $gallery_values = json_decode( $data['data'], true );
            $gallery_values['site_url'] = get_site_url();
            if ( !array_key_exists( 'resize_images', $gallery_values ) ) {
                $gallery_values['resize_images'] = '1';
            }
            $this->values[$atts['id']] = array_merge( $this->settings->default_values(), $gallery_values );
            $this->values[$atts['id']]['id'] = $atts['id'];
            $gallery = $this->values[$atts['id']];
            foreach ( $atts as $k => $v ) {
                $this->values[$atts['id']][$k] = $v;
                $gallery[$k] = $v;
            }
            
            if ( $this->lightbox( $atts['id'] ) == 'fancybox' ) {
                wp_enqueue_script(
                    'fancybox3',
                    plugin_dir_url( __FILE__ ) . 'premium/jquery.fancybox.min.js',
                    array( 'jquery' ),
                    '3.5.6',
                    false
                );
                wp_enqueue_style(
                    'fancybox3',
                    plugin_dir_url( __FILE__ ) . 'premium/jquery.fancybox.min.css',
                    array(),
                    '3.5.6',
                    'all'
                );
            }
            
            
            if ( $this->lightbox( $atts['id'] ) == 'magnific' ) {
                wp_enqueue_script(
                    'magnific-popup',
                    plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.min.js',
                    array( 'jquery' ),
                    $this->version,
                    false
                );
                wp_enqueue_style(
                    'magnific-popup',
                    plugin_dir_url( __FILE__ ) . 'css/magnific-popup.css',
                    array(),
                    $this->version,
                    'all'
                );
            }
            
            $blocks = array();
            $blocks_arr = json_decode( $data['blocks'] );
            $geometry = array();
            
            if ( is_array( $blocks_arr ) ) {
                
                if ( isset( $this->values[$atts['id']]['shuffle'] ) && $this->values[$atts['id']]['shuffle'] == '1' ) {
                    foreach ( $blocks_arr as $b ) {
                        if ( $b->type == 'image' ) {
                            $geometry[] = $b->geometry;
                        }
                        unset( $b );
                    }
                    shuffle( $blocks_arr );
                    foreach ( $blocks_arr as &$b ) {
                        if ( $b->type == 'image' ) {
                            $b->geometry = array_shift( $geometry );
                        }
                        unset( $b );
                    }
                }
                
                $allowed_posts = array( 'image', 'empty' );
                $has_posts = false;
                foreach ( $blocks_arr as $b ) {
                    if ( !in_array( $b->type, $allowed_posts ) ) {
                        $b->type = 'empty';
                    }
                    $block = new PhotoBlock( $this->values[$atts['id']], $b, $this->settings );
                    $blocks[] = apply_filters( 'photoblock_process_block', $block );
                }
            }
            
            ob_start();
            include 'partials/photoblocks-public-display.php';
            $html = ob_get_clean();
            
            if ( $gallery['compress_html'] == 1 && !isset( $_GET['debug'] ) ) {
                $html = str_replace( "\n", '', $html );
                $html = str_replace( "\r", '', $html );
                $html = preg_replace( '/\\s+/', ' ', $html );
            }
            
            return $html;
        }
    
    }
    
    /**
     * return font for title
     *
     * @since   1.1.5
     */
    public function get_title_font( $gallery_id )
    {
        return $this->settings->get( $this->values[$gallery_id], 'caption_title_font' );
    }
    
    /**
     * return font for description
     *
     * @since   1.1.5
     */
    public function get_descr_font( $gallery_id )
    {
        return $this->settings->get( $this->values[$gallery_id], 'caption_description_font' );
    }
    
    public function fonts_to_load( $gallery_id )
    {
        $fonts = array();
        $title_font = $this->get_title_font( $gallery_id );
        $descr_font = $this->get_descr_font( $gallery_id );
        if ( $title_font ) {
            $fonts[] = $title_font;
        }
        if ( $descr_font && $descr_font != $title_font ) {
            $fonts[] = $descr_font;
        }
        if ( count( $fonts ) == 0 ) {
            return '';
        }
        $param = implode( '|', $fonts );
        $param = str_replace( ' ', '+', $param );
        $param = str_replace( ',', ':', $param );
        return 'https://fonts.googleapis.com/css?family=' . $param;
    }
    
    /**
     * return post blocks
     *
     * @since    1.1.0
     */
    public function populatePostBlocks( $gallery, &$blocks )
    {
    }
    
    /**
     * append new blocks repeating the current layout
     *
     * @since    1.1.0
     */
    private function appendBlocks( &$blocks, $count )
    {
        $skip_rows = 0;
        for ( $i = 0 ;  $i < count( $blocks ) ;  $i++ ) {
            $block = $blocks[$i];
            if ( $block->row() + $block->rowspan() > $skip_rows ) {
                $skip_rows = $block->row() + $block->rowspan();
            }
        }
        for ( $i = 0 ;  $i < $count ;  $i++ ) {
            $copied = $blocks[$i % count( $blocks )]->copy();
            $copied->geometry->row += $skip_rows;
            $blocks[] = $copied;
        }
        return $blocks;
    }
    
    /**
     * return CSS rules
     *
     * @since    1.0.0
     */
    public function css(
        $gallery_id,
        $property,
        $field,
        $mu = ''
    )
    {
        if ( empty($this->values[$gallery_id][$field]) ) {
            return '';
        }
        $value = $this->values[$gallery_id][$field];
        if ( $field == 'caption_title_font' || $field == 'caption_description_font' ) {
            $value = array_shift( explode( ',', $value ) );
        }
        echo  esc_html( $property ) . ': ' . esc_html( $value ) . esc_html( $mu ) . ";\n" ;
    }
    
    /**
     * return CSS gallery selector
     *
     * @since    1.0.0
     */
    public function css_id()
    {
        return $property . ': ' . $this->values[$field] . ";\n";
    }
    
    public function lightbox( $gallery_id )
    {
        $field = ( wp_is_mobile() ? 'lightbox_mobile' : 'lightbox' );
        return $this->settings->get( $this->values[$gallery_id], $field );
    }
    
    public function caption_effect( $gallery_id )
    {
        return $this->settings->get( $this->values[$gallery_id], 'caption_effect' );
    }
    
    public function loading_effect( $gallery_id )
    {
        return $this->settings->get( $this->values[$gallery_id], 'loading_effect' );
    }
    
    public function has_filters( $gallery_id )
    {
        $filters_raw = $this->settings->get( $this->values[$gallery_id], 'filters' );
        if ( empty($filters_raw) ) {
            return false;
        }
        $filters = explode( ';', $filters_raw );
        if ( !isset( $filters ) ) {
            return false;
        }
        return count( $filters ) > 0;
    }
    
    public function filters( $gallery_id )
    {
        if ( !$this->has_filters( $gallery_id ) ) {
            return array();
        }
        $filters = array();
        foreach ( explode( ';', $this->values[$gallery_id]['filters'] ) as $f ) {
            $filters[Photoblocks_Utils::slugify( $f )] = $f;
        }
        return $filters;
    }
    
    public function show_toggle( $gallery_id, $field )
    {
        return ( isset( $this->values[$gallery_id][$field] ) && $this->values[$gallery_id][$field] == '1' ? 'true' : 'false' );
    }
    
    public function resizer_url( $gallery_id )
    {
        if ( $this->values[$gallery_id]['resize_images'] == '1' ) {
            return plugins_url( 'pbm.php', __FILE__ );
        }
        return '';
    }
    
    public function fancybox_buttons( $gallery_id )
    {
        /*'slideShow',
        		'fullScreen',
        		'thumbs',
        		'share',
        		//'download',
        		//'zoom',
        		'close'*/
        $g = $this->values[$gallery_id];
        $options = array( "'close'" );
        if ( $g['fancybox_slideshow'] == '1' ) {
            $options[] = "'slideShow'";
        }
        if ( $g['fancybox_fullscreen'] == '1' ) {
            $options[] = "'fullScreen'";
        }
        if ( $g['fancybox_thumbnails'] == '1' ) {
            $options[] = "'thumbs'";
        }
        if ( $g['fancybox_social'] == '1' ) {
            $options[] = "'share'";
        }
        if ( $g['fancybox_download'] == '1' ) {
            $options[] = "'download'";
        }
        if ( $g['fancybox_zoom'] == '1' ) {
            $options[] = "'zoom'";
        }
        return implode( ', ', $options );
    }
    
    public function disable_zoom_on_photo( $gallery_id )
    {
        $settings = $this->values[$gallery_id];
        
        if ( $settings['fancybox_zoom'] == '1' ) {
            return 'zoom';
        } else {
            return false;
        }
    
    }
    
    public function hover_options_classes( $gallery_id )
    {
        $classes = array();
        if ( $this->values[$gallery_id]['hover_blur'] == '1' ) {
            $classes[] = 'pb-blur';
        }
        if ( $this->values[$gallery_id]['hover_lift'] == '1' ) {
            $classes[] = 'pb-lift';
        }
        return implode( ' ', $classes );
    }
    
    public function css_classes( $gallery_id )
    {
        $classes = array();
        if ( $this->settings->get( $this->values[$gallery_id], 'caption_background_show_empty' ) == '1' ) {
            $classes[] = 'show-empty-overlay';
        }
        return implode( ' ', $classes );
    }

}