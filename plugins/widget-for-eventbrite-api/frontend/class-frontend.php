<?php

/**
 * The public-facing functionality of the plugin.
 *
 *
 */
namespace WidgetForEventbriteAPI\FrontEnd;

use  ActionScheduler_Store ;
use  stdClass ;
use  WidgetForEventbriteAPI\Includes\ICS ;
use  WidgetForEventbriteAPI\Includes\Template_Loader ;
use  WidgetForEventbriteAPI\Includes\Eventbrite_Query ;
use  WidgetForEventbriteAPI\Includes\Twig ;
class FrontEnd
{
    /**
     * The ID of this plugin.
     *
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     */
    private  $version ;
    /**
     * @var \WidgetForEventbriteAPI\Includes\Utilities $utilities Object for utilities.
     */
    private  $utilities ;
    /**
     * @var \Freemius $freemius Object for freemius.
     */
    private  $freemius ;
    /**
     * @var \WidgetForEventbriteAPI\Includes\Template_Loader $template_loader Object for template loader.
     */
    private  $template_loader ;
    /**
     * @var \WidgetForEventbriteAPI\Includes\Eventbrite_Query $eventbrite_query Object for eventbrite query.
     */
    private  $events ;
    /**
     * Initialize the class and set its properties.
     *
     */
    public function __construct( $plugin_name, $version, $utilities = null )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->utilities = $utilities;
        global  $wfea_fs ;
        $this->freemius = $wfea_fs;
    }
    
    public static function default_args()
    {
        /**
         * @var \Freemius $wfea_fs Object for freemius.
         */
        global  $wfea_fs ;
        $defaults = array(
            'booknow'        => 'true',
            'booknow_text'   => esc_html__( 'Register »', 'widget-for-eventbrite-api' ),
            'cssid'          => '',
            'css_class'      => '',
            'date'           => 'true',
            'debug'          => 'false',
            'excerpt'        => 'true',
            'layout'         => 'widget',
            'length'         => 50,
            'limit'          => 5,
            'newtab'         => 'false',
            'order_by'       => '',
            'readmore'       => 'true',
            'readmore_text'  => esc_html__( 'Read More »', 'widget-for-eventbrite-api' ),
            'status'         => 'live',
            'thumb'          => 'true',
            'thumb_align'    => 'eaw-aligncenter',
            'thumb_default'  => 'https://dummyimage.com/600x400/f0f0f0/ccc',
            'thumb_original' => 'false',
            'thumb_width'    => 300,
            'tickets'        => 'false',
            'widgetwrap'     => 'true',
        );
        // Allow plugins/themes developer to filter the default arguments.
        return apply_filters( 'eawp_shortcode_default_args', $defaults );
    }
    
    public static function get_cal_locale()
    {
        $locale = str_replace( '_', '-', strtolower( get_locale() ) );
        $parts = explode( '-', $locale );
        if ( $parts[0] == $parts[1] ) {
            $locale = $parts[0];
        }
        return apply_filters( 'wfea_cal_locale', $locale );
    }
    
    public function add_shortcode()
    {
        add_filter( 'aioseo_conflicting_shortcodes', function ( $conflicting_shortcodes ) {
            $conflicting_shortcodes['Display Eventbrite Events'] = 'wfea';
            return $conflicting_shortcodes;
        } );
        add_shortcode( 'wfea', array( $this, 'build_shortcode' ) );
    }
    
    /**
     * @param $initial_atts
     *
     * @return array
     */
    public function build_query_args_from_atts( $initial_atts )
    {
        // force default for short date to be true modal
        if ( isset( $initial_atts['layout'] ) && 'short_date' == $initial_atts['layout'] ) {
            if ( !isset( $initial_atts['long_description_modal'] ) ) {
                $initial_atts['long_description_modal'] = 'true';
            }
        }
        $sc_atts = shortcode_atts( self::default_args(), $initial_atts, 'wfea' );
        $atts = array();
        $atts['booknow'] = $this->shortcode_bool( $sc_atts['booknow'] );
        $atts['booknow_text'] = wp_kses_post( urldecode( $sc_atts['booknow_text'] ) );
        $atts['cssid'] = sanitize_html_class( $sc_atts['cssid'] );
        $atts['css_class'] = sanitize_html_class( $sc_atts['css_class'] );
        $atts['date'] = $this->shortcode_bool( $sc_atts['date'] );
        $atts['debug'] = $this->shortcode_bool( $sc_atts['debug'] );
        $atts['display_private'] = false;
        $atts['excerpt'] = $this->shortcode_bool( $sc_atts['excerpt'] );
        $atts['layout'] = sanitize_text_field( $sc_atts['layout'] );
        $atts['length'] = (int) $sc_atts['length'];
        $atts['limit'] = (int) $sc_atts['limit'];
        $atts['newtab'] = $this->shortcode_bool( $sc_atts['newtab'] );
        $atts['order_by'] = sanitize_text_field( $sc_atts['order_by'] );
        $atts['readmore'] = $this->shortcode_bool( $sc_atts['readmore'] );
        $atts['readmore_text'] = wp_kses_post( urldecode( $sc_atts['readmore_text'] ) );
        $atts['status'] = sanitize_text_field( $sc_atts['status'] );
        $atts['thumb'] = $this->shortcode_bool( $sc_atts['thumb'] );
        $atts['thumb_align'] = sanitize_text_field( $sc_atts['thumb_align'] );
        $atts['thumb_default'] = esc_url( $sc_atts['thumb_default'] );
        $atts['thumb_original'] = $this->shortcode_bool( $sc_atts['thumb_original'] );
        $atts['thumb_width'] = (int) $sc_atts['thumb_width'];
        $atts['tickets'] = $this->shortcode_bool( $sc_atts['tickets'] );
        $atts['widgetwrap'] = $this->shortcode_bool( $sc_atts['widgetwrap'] );
        // Query arguments.
        $query = array(
            'nopaging' => true,
            'limit'    => $atts['limit'],
            'layout'   => $atts['layout'],
        );
        
        if ( !empty($atts['status']) ) {
            $atts['status'] = strtolower( $atts['status'] );
            $atts['status'] = str_replace( 'cancelled', 'canceled', $atts['status'] );
            $query['status'] = $atts['status'];
        }
        
        if ( !empty($atts['order_by']) ) {
            
            if ( 'asc' === strtolower( $atts['order_by'] ) ) {
                $query['order_by'] = 'start_asc';
            } elseif ( 'desc' === strtolower( $atts['order_by'] ) ) {
                $query['order_by'] = 'start_desc';
            } elseif ( 'created_desc' === strtolower( $atts['order_by'] ) ) {
                $query['order_by'] = 'created_desc';
            } elseif ( 'created_asc' === strtolower( $atts['order_by'] ) ) {
                $query['order_by'] = 'created_asc';
            } elseif ( 'published_desc' === strtolower( $atts['order_by'] ) ) {
                $query['order_by'] = 'published_desc';
            } elseif ( 'published_asc' === strtolower( $atts['order_by'] ) ) {
                $query['order_by'] = 'published_asc';
            }
        
        }
        $query['thumb_original'] = $atts['thumb_original'];
        $query['display_private'] = $atts['display_private'];
        return array( $atts, $query );
    }
    
    public function build_shortcode( $initial_atts )
    {
        list( $atts, $query ) = $this->build_query_args_from_atts( $initial_atts );
        global  $wfea_instance_counter ;
        $wfea_instance_counter++;
        // Allow plugins/themes developer to filter the query.
        $query = apply_filters( 'eawp_shortcode_query_arguments', $query );
        $atts = apply_filters( 'eawp_shortcode_atts', $atts );
        // Perform the query.
        $this->events = new Eventbrite_Query( $query );
        $html = '';
        $admin_msg = '<div class="wfea error">' . esc_html__( '[Display Eventbrite Plugin] Admin Notice! ( this shows to admins only ): ', 'widget-for-eventbrite-api' ) . '</div>';
        
        if ( is_wp_error( $this->events->api_results ) ) {
            
            if ( current_user_can( 'manage_options' ) ) {
                $error_string = $this->utilities->get_api_error_string( $this->events->api_results );
                $html .= $admin_msg . '<div class="wfea error">' . $error_string . '</div>';
                if ( $atts['debug'] ) {
                    $html .= $this->get_debug_output();
                }
            }
        
        } else {
            ob_start();
            $this->check_valid_att( $initial_atts );
            $theme = wp_get_theme();
            $this->template_loader = new Template_Loader();
            $uniqid = uniqid();
            $this->template_loader->set_template_data( array(
                'template_loader' => $this->template_loader,
                'events'          => $this->events,
                'args'            => $atts,
                'template'        => strtolower( $theme->template ),
                'plugin_name'     => $this->plugin_name,
                'utilities'       => $this->utilities,
                'unique_id'       => $uniqid,
                'instance'        => $wfea_instance_counter,
                'event'           => new stdClass(),
            ) );
            $template_found = $this->template_loader->get_template_part( 'layout_' . $atts['layout'] );
            if ( false == $template_found ) {
                
                if ( current_user_can( 'manage_options' ) ) {
                    $layouts = 'widget,card';
                    $plan_title = esc_html__( 'Free', 'widget-for-eventbrite-api' );
                    $err_msg = $admin_msg . '<div class="wfea error">' . esc_html__( 'Selected LAYOUT="', 'widget_for_eventbrite_api' ) . esc_html( $atts['layout'] ) . esc_html__( '" Not found in any paths. Your plan is ', 'widget_for_eventbrite_api' ) . esc_html( $plan_title ) . esc_html__( ' and includes these layouts ', 'widget_for_eventbrite_api' ) . esc_html( $layouts ) . esc_html__( ' and any custom developed layouts you have made.', 'widget_for_eventbrite_api' ) . '<br><br>' . esc_html__( 'Paths checked are:', 'widget_for_eventbrite_api' ) . '<br>' . implode( '<br>', $this->template_loader->get_file_paths() );
                    '</div>';
                    echo  wp_kses_post( $err_msg ) ;
                }
            
            }
            if ( $atts['debug'] ) {
                echo  wp_kses_post( $this->get_debug_output() ) ;
            }
            $html .= ob_get_clean();
            $html = apply_filters( 'eawp_shortcode_markup', $html );
            // Restore original Post Data.
            wp_reset_postdata();
        }
        
        return $html;
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     */
    public function enqueue_scripts()
    {
        /**  @var \Freemius $wfea_fs freemius SDK. */
        global  $wfea_fs ;
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/frontend.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }
    
    /**
     * Register the stylesheets for the frontend.
     */
    public function enqueue_styles()
    {
        /**  @var \Freemius $wfea_fs freemius SDK. */
        global  $wfea_fs ;
        $options = get_option( 'widget-for-eventbrite-api-settings' );
        if ( !isset( $options['plugin-css'] ) || $options['plugin-css'] ) {
            // need to check not set as older version didn't have this option
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/frontend.css',
                array(),
                $this->version,
                'all'
            );
        }
    }
    
    public function jetpack_photon_skip_for_url( $skip, $image_url )
    {
        $banned_host_patterns = array( '/^img\\.evbuc\\.com$/' );
        $host = wp_parse_url( $image_url, PHP_URL_HOST );
        foreach ( $banned_host_patterns as $banned_host_pattern ) {
            if ( 1 === preg_match( $banned_host_pattern, $host ) ) {
                return true;
            }
        }
        return $skip;
    }
    
    public function register_image_size()
    {
        add_image_size(
            'eaw-thumbnail',
            45,
            45,
            true
        );
    }
    
    /**
     * Build social meta for single pages
     * @return void
     */
    public function wfea_generate_meta_for_social_media()
    {
        $shortcode = $this->utilities->is_single_with_wfea_shortcode();
        // checks and returns the limit is a shortcode on a post
        if ( false === $shortcode ) {
            return;
        }
        // remove [ ] from $shortcode
        $shortcode = substr( $shortcode, 1, -1 );
        $atts = shortcode_parse_atts( $shortcode );
        if ( !isset( $atts['limit'] ) ) {
            $atts['limit'] = 5;
        }
        list( $atts, $query_args ) = $this->build_query_args_from_atts( $atts );
        
        if ( !isset( $query_args['ID'] ) && $atts['limit'] > 1 ) {
            return;
            // no id and the shortcode has a limit of more than 1 so not a single event
        }
        
        $this->events = new Eventbrite_Query( $query_args );
        
        if ( !empty($this->events->api_results->events[0]) ) {
            $link = get_the_permalink();
            if ( isset( $query_args['ID'] ) ) {
                $link .= trailingslashit( $link ) . 'e/' . $query_args['ID'] . '/';
            }
            echo  '<link ref="canonical" href="' . esc_url( $link ) . '" />' ;
            echo  '<meta property="og:url" content="' . esc_url( uniqid() ) . '" />' ;
            // some reason we have to break this for facebook to work
            echo  '<meta property="og:title" content="' . esc_attr( $this->events->post->post_title ) . '" />' ;
            echo  '<meta property="og:description" content="' . esc_attr( $this->events->post->post_excerpt ) . '" />' ;
            echo  '<meta property="og:image" content="' . esc_url( $this->events->post->logo_url ) . '" />' ;
            /*
             * Twitter works on singlepage but not wit a specified Event ID
             */
            echo  '<meta name="twitter:card" content="summary_large_image">' ;
            // display og:site_name
            $site_name = get_bloginfo( 'name' );
            if ( !empty($site_name) ) {
                echo  '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '" />' ;
            }
            echo  '<meta name="twitter:title" content="' . esc_attr( $this->events->post->post_title ) . '" />' ;
            echo  '<meta name="twitter:description" content="' . esc_attr( $this->events->post->post_excerpt ) . '" />' ;
            echo  '<meta name="twitter:image" content="' . esc_url( $this->events->post->logo_url ) . '" />' ;
        }
    
    }
    
    public function wfea_the_content( $content )
    {
        $strip = apply_filters( 'wfea_strip_eb_inline_style', true );
        if ( true === $strip ) {
            $content = preg_replace( '/(<[^>]+) style=".*?"/i', '$1', $content );
        }
        $class = ( apply_filters( 'wfea_strip_eb_inline_style', true ) ? 'local_style' : '' );
        // add a container class to any iframe
        $content = preg_replace( '/<iframe/i', '<div class="wfea_eb_content_iframe_container"><iframe', $content );
        $content = preg_replace( '/<\\/iframe>/i', '</iframe></div>', $content );
        return '<div class="wfea_eb_content ' . $class . '">' . $content . '</div>';
    }
    
    private function shortcode_bool( $att )
    {
        
        if ( 'true' === $att ) {
            $att = true;
        } else {
            $att = false;
        }
        
        return (bool) $att;
    }
    
    private function get_debug_output()
    {
        return '<h2>' . esc_html__( '--- DEBUG OUTPUT ---', 'widget-for-eventbrite-api' ) . '</h2><pre>' . print_r( $this->events->api_results, true ) . '</pre>';
    }
    
    private function check_valid_att( $atts )
    {
        if ( !is_array( $atts ) ) {
            return;
        }
        $defaults = self::default_args();
        foreach ( $atts as $att => $value ) {
            
            if ( !isset( $defaults[$att] ) ) {
                $message = esc_html__( '[Display Eventbrite Plugin] Selected attribute: [', 'widget-for-eventbrite-api' ) . esc_attr( $att ) . esc_html__( '] is not valid - maybe a typo or maybe not included in your plan, refer to documentation', 'widget-for-eventbrite-api' );
                
                if ( isset( $atts['debug'] ) && $atts['debug'] ) {
                    echo  '<div class="error">' . wp_kses_post( $message ) . '</div>' ;
                    trigger_error( esc_html( $message ), E_USER_NOTICE );
                }
            
            }
        
        }
    }

}